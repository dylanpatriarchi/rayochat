"""
Pydantic schemas for request/response validation
"""
from pydantic import BaseModel, Field, validator
from typing import Optional, Dict, Any, List
from datetime import datetime

class ChatRequest(BaseModel):
    """Chat request schema"""
    message: str = Field(..., min_length=1, max_length=1000, description="User message")
    conversation_id: Optional[str] = Field(None, description="Conversation ID for context")
    
    @validator('message')
    def validate_message(cls, v):
        """Validate and sanitize message"""
        import re
        v = v.strip()
        if not v:
            raise ValueError("Message cannot be empty")
        
        # Remove potentially dangerous characters
        v = re.sub(r'[<>"\'\\\x00-\x1f\x7f-\x9f]', '', v)
        
        # Limit consecutive whitespace
        v = re.sub(r'\s+', ' ', v)
        
        # Check for suspicious patterns
        suspicious_patterns = [
            r'<script',
            r'javascript:',
            r'data:',
            r'vbscript:',
            r'on\w+\s*=',
            r'eval\s*\(',
            r'expression\s*\(',
        ]
        
        for pattern in suspicious_patterns:
            if re.search(pattern, v, re.IGNORECASE):
                raise ValueError("Message contains potentially unsafe content")
        
        return v

class ChatResponse(BaseModel):
    """Chat response schema"""
    success: bool
    message: str
    data: Dict[str, Any]
    timestamp: datetime = Field(default_factory=datetime.now)

class ErrorResponse(BaseModel):
    """Error response schema"""
    success: bool = False
    error: str
    detail: Optional[str] = None
    timestamp: datetime = Field(default_factory=datetime.now)

class SiteInfo(BaseModel):
    """Site information schema"""
    id: int
    name: str
    url: str
    has_info: bool
    
class TokenUsage(BaseModel):
    """Token usage tracking"""
    prompt_tokens: int
    completion_tokens: int
    total_tokens: int
    cost_estimate: float
