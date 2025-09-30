"""
RayoChat RAG Service
FastAPI application for handling AI-powered customer care queries
"""

from fastapi import FastAPI, HTTPException, Depends
from fastapi.middleware.cors import CORSMiddleware
from pydantic import BaseModel
from typing import Optional, List
import os
from datetime import datetime

from services.rag_engine import RAGEngine
from services.database import DatabaseService
from config import get_settings

# Initialize FastAPI app
app = FastAPI(
    title="RayoChat RAG Service",
    description="AI-powered customer care RAG system",
    version="1.0.0"
)

# CORS configuration
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Laravel backend will validate
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Initialize services
settings = get_settings()
rag_engine = RAGEngine()
db_service = DatabaseService()


# Request/Response models
class AskRequest(BaseModel):
    question: str
    conversation_id: Optional[str] = None
    

class AskResponse(BaseModel):
    answer: str
    conversation_id: str
    sources: List[str]
    timestamp: str


class HealthResponse(BaseModel):
    status: str
    timestamp: str
    services: dict


@app.get("/", response_model=dict)
async def root():
    """Root endpoint"""
    return {
        "service": "RayoChat RAG Service",
        "version": "1.0.0",
        "status": "operational"
    }


@app.get("/health", response_model=HealthResponse)
async def health_check():
    """Health check endpoint"""
    db_status = await db_service.check_connection()
    
    return HealthResponse(
        status="healthy" if db_status else "degraded",
        timestamp=datetime.utcnow().isoformat(),
        services={
            "database": "connected" if db_status else "disconnected",
            "openai": "configured" if settings.OPENAI_API_KEY else "not_configured",
            "rag_engine": "ready"
        }
    )


@app.post("/ask/{company_hash}", response_model=AskResponse)
async def ask_question(
    company_hash: str,
    request: AskRequest
):
    """
    Main endpoint for handling customer questions
    
    Args:
        company_hash: Unique hash identifier for the company
        request: Question and optional conversation_id
    
    Returns:
        AI-generated answer with sources and conversation_id
    """
    try:
        # Validate company exists and retrieve company data
        company_data = await db_service.get_company_by_hash(company_hash)
        
        if not company_data:
            raise HTTPException(
                status_code=404,
                detail="Company not found or inactive"
            )
        
        # Get company knowledge base
        knowledge_base = await db_service.get_company_knowledge(company_data['id'])
        
        if not knowledge_base:
            return AskResponse(
                answer="Mi dispiace, ma non ho ancora informazioni sufficienti per rispondere. Il proprietario del sito non ha ancora caricato la base di conoscenza.",
                conversation_id=request.conversation_id or "",
                sources=[],
                timestamp=datetime.utcnow().isoformat()
            )
        
        # Generate response using RAG
        response = await rag_engine.generate_response(
            question=request.question,
            company_id=company_data['id'],
            knowledge_base=knowledge_base,
            conversation_id=request.conversation_id
        )
        
        # Store conversation in database
        conversation_id = await db_service.store_conversation(
            company_id=company_data['id'],
            conversation_id=request.conversation_id,
            question=request.question,
            answer=response['answer'],
            sources=response['sources']
        )
        
        return AskResponse(
            answer=response['answer'],
            conversation_id=conversation_id,
            sources=response['sources'],
            timestamp=datetime.utcnow().isoformat()
        )
        
    except HTTPException:
        raise
    except Exception as e:
        print(f"Error processing question: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Errore interno del server. Riprova più tardi."
        )


@app.post("/rate/{conversation_id}")
async def rate_conversation(
    conversation_id: str,
    rating: int
):
    """
    Endpoint for rating a conversation
    
    Args:
        conversation_id: Unique conversation identifier
        rating: Rating value (1-5)
    """
    if rating < 1 or rating > 5:
        raise HTTPException(
            status_code=400,
            detail="Rating must be between 1 and 5"
        )
    
    try:
        await db_service.store_rating(conversation_id, rating)
        return {"status": "success", "message": "Rating saved"}
    except Exception as e:
        print(f"Error saving rating: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Error saving rating"
        )


@app.post("/index/{company_hash}")
async def index_documents(company_hash: str):
    """
    Trigger document indexing for a company
    Called by Laravel backend after document upload
    
    Args:
        company_hash: Unique hash identifier for the company
    """
    try:
        company_data = await db_service.get_company_by_hash(company_hash)
        
        if not company_data:
            raise HTTPException(
                status_code=404,
                detail="Company not found"
            )
        
        # Get unprocessed documents
        documents = await db_service.get_unprocessed_documents(company_data['id'])
        
        if not documents:
            return {
                "status": "success",
                "message": "No documents to process",
                "processed": 0
            }
        
        # Process documents
        processed_count = await rag_engine.index_documents(
            company_id=company_data['id'],
            documents=documents
        )
        
        return {
            "status": "success",
            "message": f"Indexed {processed_count} documents",
            "processed": processed_count
        }
        
    except HTTPException:
        raise
    except Exception as e:
        print(f"Error indexing documents: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Error indexing documents"
        )


if __name__ == "__main__":
    import uvicorn
    uvicorn.run(app, host="0.0.0.0", port=8000)
