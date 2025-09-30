"""
Database service for interacting with PostgreSQL
"""

from typing import Optional, List, Dict, Any
from sqlalchemy import create_engine, text
from sqlalchemy.pool import QueuePool
from datetime import datetime
import uuid

from config import get_settings

settings = get_settings()


class DatabaseService:
    """Service for database operations"""
    
    def __init__(self):
        self.engine = create_engine(
            settings.DATABASE_URL,
            poolclass=QueuePool,
            pool_size=5,
            max_overflow=10,
            pool_pre_ping=True
        )
    
    async def check_connection(self) -> bool:
        """Check database connection health"""
        try:
            with self.engine.connect() as conn:
                conn.execute(text("SELECT 1"))
            return True
        except Exception as e:
            print(f"Database connection error: {str(e)}")
            return False
    
    async def get_company_by_hash(self, company_hash: str) -> Optional[Dict[str, Any]]:
        """
        Get company data by hash
        
        Args:
            company_hash: Unique company hash
        
        Returns:
            Company data or None
        """
        try:
            with self.engine.connect() as conn:
                result = conn.execute(
                    text("""
                        SELECT id, name, api_key, is_active
                        FROM companies
                        WHERE hash = :hash AND is_active = true
                    """),
                    {"hash": company_hash}
                )
                row = result.fetchone()
                
                if row:
                    return {
                        "id": row[0],
                        "name": row[1],
                        "api_key": row[2],
                        "is_active": row[3]
                    }
                return None
        except Exception as e:
            print(f"Error fetching company: {str(e)}")
            return None
    
    async def get_company_knowledge(self, company_id: int) -> Optional[List[Dict[str, Any]]]:
        """
        Get all processed documents for a company
        
        Args:
            company_id: Company ID
        
        Returns:
            List of document data
        """
        try:
            with self.engine.connect() as conn:
                result = conn.execute(
                    text("""
                        SELECT id, filename, content, metadata
                        FROM documents
                        WHERE company_id = :company_id 
                        AND status = 'processed'
                        ORDER BY created_at DESC
                    """),
                    {"company_id": company_id}
                )
                
                documents = []
                for row in result:
                    documents.append({
                        "id": row[0],
                        "filename": row[1],
                        "content": row[2],
                        "metadata": row[3]
                    })
                
                return documents if documents else None
        except Exception as e:
            print(f"Error fetching knowledge base: {str(e)}")
            return None
    
    async def get_unprocessed_documents(self, company_id: int) -> List[Dict[str, Any]]:
        """
        Get all unprocessed documents for a company
        
        Args:
            company_id: Company ID
        
        Returns:
            List of unprocessed documents
        """
        try:
            with self.engine.connect() as conn:
                result = conn.execute(
                    text("""
                        SELECT id, filename, file_path, file_type
                        FROM documents
                        WHERE company_id = :company_id 
                        AND status = 'pending'
                        ORDER BY created_at ASC
                    """),
                    {"company_id": company_id}
                )
                
                documents = []
                for row in result:
                    documents.append({
                        "id": row[0],
                        "filename": row[1],
                        "file_path": row[2],
                        "file_type": row[3]
                    })
                
                return documents
        except Exception as e:
            print(f"Error fetching unprocessed documents: {str(e)}")
            return []
    
    async def store_conversation(
        self,
        company_id: int,
        conversation_id: Optional[str],
        question: str,
        answer: str,
        sources: List[str]
    ) -> str:
        """
        Store conversation in database
        
        Args:
            company_id: Company ID
            conversation_id: Existing conversation ID or None
            question: User question
            answer: AI answer
            sources: List of source references
        
        Returns:
            Conversation ID
        """
        try:
            if not conversation_id:
                conversation_id = str(uuid.uuid4())
            
            with self.engine.begin() as conn:
                conn.execute(
                    text("""
                        INSERT INTO conversations 
                        (id, company_id, conversation_id, question, answer, sources, created_at)
                        VALUES (:id, :company_id, :conversation_id, :question, :answer, :sources, :created_at)
                    """),
                    {
                        "id": str(uuid.uuid4()),
                        "company_id": company_id,
                        "conversation_id": conversation_id,
                        "question": question,
                        "answer": answer,
                        "sources": ",".join(sources),
                        "created_at": datetime.utcnow()
                    }
                )
            
            return conversation_id
        except Exception as e:
            print(f"Error storing conversation: {str(e)}")
            return conversation_id or str(uuid.uuid4())
    
    async def store_rating(self, conversation_id: str, rating: int):
        """
        Store conversation rating
        
        Args:
            conversation_id: Conversation ID
            rating: Rating value (1-5)
        """
        try:
            with self.engine.begin() as conn:
                conn.execute(
                    text("""
                        UPDATE conversations
                        SET rating = :rating, rated_at = :rated_at
                        WHERE conversation_id = :conversation_id
                    """),
                    {
                        "rating": rating,
                        "rated_at": datetime.utcnow(),
                        "conversation_id": conversation_id
                    }
                )
        except Exception as e:
            print(f"Error storing rating: {str(e)}")
            raise
    
    async def mark_document_processed(self, document_id: int):
        """
        Mark document as processed
        
        Args:
            document_id: Document ID
        """
        try:
            with self.engine.begin() as conn:
                conn.execute(
                    text("""
                        UPDATE documents
                        SET status = 'processed', processed_at = :processed_at
                        WHERE id = :document_id
                    """),
                    {
                        "document_id": document_id,
                        "processed_at": datetime.utcnow()
                    }
                )
        except Exception as e:
            print(f"Error marking document as processed: {str(e)}")
