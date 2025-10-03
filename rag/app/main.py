"""
Main FastAPI application for RAG service
"""
from fastapi import FastAPI, HTTPException, Request, Depends
from fastapi.middleware.cors import CORSMiddleware
from fastapi.middleware.trustedhost import TrustedHostMiddleware
from fastapi.responses import JSONResponse
from slowapi import Limiter, _rate_limit_exceeded_handler
from slowapi.util import get_remote_address
from slowapi.errors import RateLimitExceeded
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.responses import Response
from app.config import settings
from app.models.schemas import ChatRequest, ChatResponse, ErrorResponse
from app.services.rag import RAGService
from app.utils.auth import extract_api_key
import logging
import uvicorn
from datetime import datetime

# Configure logging
logging.basicConfig(
    level=getattr(logging, settings.LOG_LEVEL),
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# Security Headers Middleware
class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request: Request, call_next):
        response = await call_next(request)
        
        # Security headers
        response.headers["X-Content-Type-Options"] = "nosniff"
        response.headers["X-Frame-Options"] = "DENY"
        response.headers["X-XSS-Protection"] = "1; mode=block"
        response.headers["Referrer-Policy"] = "strict-origin-when-cross-origin"
        response.headers["Permissions-Policy"] = "geolocation=(), microphone=(), camera=()"
        
        # Remove server header
        if "server" in response.headers:
            del response.headers["server"]
            
        return response

# Create FastAPI app
app = FastAPI(
    title="RayoChat RAG Service",
    description="Retrieval-Augmented Generation service for RayoChat",
    version="1.0.0",
    debug=settings.DEBUG,
    docs_url="/docs" if settings.DEBUG else None,  # Disable docs in production
    redoc_url="/redoc" if settings.DEBUG else None  # Disable redoc in production
)

# Add security middleware
app.add_middleware(SecurityHeadersMiddleware)

# Add trusted host middleware (only allow specific hosts)
if not settings.DEBUG:
    app.add_middleware(
        TrustedHostMiddleware, 
        allowed_hosts=["localhost", "127.0.0.1", "rag", "rayochat_rag"]
    )

# Configure CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=settings.CORS_ORIGINS,
    allow_credentials=False,  # Disable credentials for security
    allow_methods=["POST", "GET", "OPTIONS"],
    allow_headers=["Content-Type", "Accept", "User-Agent"],  # Specific headers only
)

# Configure rate limiting
limiter = Limiter(key_func=get_remote_address)
app.state.limiter = limiter
app.add_exception_handler(RateLimitExceeded, _rate_limit_exceeded_handler)

# Initialize services
rag_service = RAGService()

@app.get("/")
async def root():
    """Health check endpoint"""
    return {
        "status": "healthy",
        "service": "RayoChat RAG",
        "version": "1.0.0",
        "timestamp": datetime.now().isoformat()
    }

@app.get("/health")
async def health_check():
    """Detailed health check"""
    try:
        # Check database connection
        from app.models.database import engine
        from sqlalchemy import text
        with engine.connect() as conn:
            conn.execute(text("SELECT 1"))
        db_status = "healthy"
    except Exception as e:
        db_status = f"unhealthy: {str(e)}"
    
    # Check Redis connection
    try:
        if rag_service.redis_client:
            rag_service.redis_client.ping()
            redis_status = "healthy"
        else:
            redis_status = "not configured"
    except Exception as e:
        redis_status = f"unhealthy: {str(e)}"
    
    # Check OpenAI
    openai_status = "configured" if settings.OPENAI_API_KEY else "not configured"
    
    return {
        "status": "healthy" if db_status == "healthy" else "degraded",
        "services": {
            "database": db_status,
            "redis": redis_status,
            "openai": openai_status
        },
        "timestamp": datetime.now().isoformat()
    }

@app.post("/ask")
@limiter.limit(f"{settings.RATE_LIMIT_PER_MINUTE}/minute")
async def ask_question(
    request: Request, 
    chat_request: ChatRequest,
    api_key: str = Depends(extract_api_key)
):
    """
    Main endpoint for processing chat messages
    
    Expected Headers:
    - Authorization: Bearer rc_s_xxxxx
    - OR X-API-Key: rc_s_xxxxx
    
    Expected POST body:
    {
        "message": "User's question"
    }
    
    Returns:
    {
        "success": true,
        "message": "Response generated successfully",
        "data": {
            "response": "AI generated response",
            "confidence": 0.95,
            "sources": ["business_info"],
            "tokens_used": {...}
        }
    }
    """
    try:
        logger.info(f"Received request from {get_remote_address(request)}")
        
        # Process the message
        result = rag_service.process_message(chat_request, api_key)
        
        if not result["success"]:
            raise HTTPException(
                status_code=400 if "Invalid API key" in result.get("error", "") else 500,
                detail=result.get("error", "Unknown error")
            )
        
        return ChatResponse(
            success=True,
            message=result["message"],
            data=result["data"]
        )
        
    except HTTPException:
        raise
    except Exception as e:
        # Log error with request context but don't expose sensitive data
        logger.error(f"Unexpected error in ask endpoint: {type(e).__name__}", 
                    extra={"error_type": type(e).__name__, "endpoint": "/ask"})
        return ErrorResponse(
            success=False,
            error="Internal server error",
            detail=str(e) if settings.DEBUG else "An unexpected error occurred"
        )

@app.post("/webhook")
@limiter.limit(f"{settings.RATE_LIMIT_PER_MINUTE}/minute")
async def webhook_handler(
    request: Request,
    api_key: str = Depends(extract_api_key)
):
    """
    Alternative webhook endpoint that accepts raw JSON
    """
    try:
        body = await request.json()
        
        # Extract message from body (API key comes from header)
        message = body.get("message")
        
        if not message:
            raise HTTPException(
                status_code=400,
                detail="Missing required field: message"
            )
        
        # Create ChatRequest
        chat_request = ChatRequest(
            message=message,
            conversation_id=body.get("conversation_id")
        )
        
        # Process the message
        result = rag_service.process_message(chat_request, api_key)
        
        if not result["success"]:
            raise HTTPException(
                status_code=400 if "Invalid API key" in result.get("error", "") else 500,
                detail=result.get("error", "Unknown error")
            )
        
        return JSONResponse(
            content={
                "success": True,
                "message": result["message"],
                "data": result["data"],
                "timestamp": datetime.now().isoformat()
            }
        )
        
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Webhook error: {str(e)}")
        return JSONResponse(
            status_code=500,
            content={
                "success": False,
                "error": "Internal server error",
                "detail": str(e) if settings.DEBUG else None,
                "timestamp": datetime.now().isoformat()
            }
        )

@app.exception_handler(Exception)
async def global_exception_handler(request: Request, exc: Exception):
    """Global exception handler"""
    logger.error(f"Global exception: {str(exc)}")
    return JSONResponse(
        status_code=500,
        content={
            "success": False,
            "error": "Internal server error",
            "detail": str(exc) if settings.DEBUG else None,
            "timestamp": datetime.now().isoformat()
        }
    )

if __name__ == "__main__":
    uvicorn.run(
        "app.main:app",
        host="0.0.0.0",
        port=8000,
        reload=settings.DEBUG,
        log_level=settings.LOG_LEVEL.lower()
    )
