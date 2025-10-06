"""
Configuration module for RAG service
"""
import os
from pydantic_settings import BaseSettings
from typing import List

class Settings(BaseSettings):
    """Application settings"""
    
    # Database
    DATABASE_URL: str = os.getenv("DATABASE_URL")
    
    # Redis
    REDIS_URL: str = os.getenv("REDIS_URL")
    
    # OpenAI
    OPENAI_API_KEY: str = os.getenv("OPENAI_API_KEY", "")
    OPENAI_MODEL: str = os.getenv("OPENAI_MODEL", "gpt-3.5-turbo")
    OPENAI_TEMPERATURE: float = float(os.getenv("OPENAI_TEMPERATURE", "0.7"))
    OPENAI_MAX_TOKENS: int = int(os.getenv("OPENAI_MAX_TOKENS", "500"))
    
    # Application
    APP_ENV: str = os.getenv("APP_ENV", "development")
    DEBUG: bool = os.getenv("DEBUG", "True").lower() == "true"
    LOG_LEVEL: str = os.getenv("LOG_LEVEL", "INFO")
    
    # CORS - Load from environment for security
    CORS_ORIGINS: List[str] = [
        "http://localhost:8001",
        "http://rayochat_nginx", 
        "http://nginx"
    ]
    
    # Rate Limiting
    RATE_LIMIT_PER_MINUTE: int = int(os.getenv("RATE_LIMIT_PER_MINUTE", "30"))
    RATE_LIMIT_PER_HOUR: int = int(os.getenv("RATE_LIMIT_PER_HOUR", "500"))
    
    # LangChain
    EMBEDDING_MODEL: str = os.getenv("EMBEDDING_MODEL", "text-embedding-ada-002")
    CHUNK_SIZE: int = int(os.getenv("CHUNK_SIZE", "1000"))
    CHUNK_OVERLAP: int = int(os.getenv("CHUNK_OVERLAP", "200"))
    
    # Guardrails Configuration
    GUARDRAILS_ENABLED: bool = os.getenv("GUARDRAILS_ENABLED", "True").lower() == "true"
    GUARDRAILS_STRICT_MODE: bool = os.getenv("GUARDRAILS_STRICT_MODE", "False").lower() == "true"
    MAX_INPUT_LENGTH: int = int(os.getenv("MAX_INPUT_LENGTH", "2000"))
    MIN_INPUT_LENGTH: int = int(os.getenv("MIN_INPUT_LENGTH", "3"))
    MAX_OUTPUT_LENGTH: int = int(os.getenv("MAX_OUTPUT_LENGTH", "1500"))
    MIN_OUTPUT_LENGTH: int = int(os.getenv("MIN_OUTPUT_LENGTH", "10"))
    
    # Guardrails Sensitivity Levels (low, medium, high)
    INPUT_GUARDRAILS_SENSITIVITY: str = os.getenv("INPUT_GUARDRAILS_SENSITIVITY", "medium")
    OUTPUT_GUARDRAILS_SENSITIVITY: str = os.getenv("OUTPUT_GUARDRAILS_SENSITIVITY", "medium")
    
    # Content Filtering
    BLOCK_INAPPROPRIATE_CONTENT: bool = os.getenv("BLOCK_INAPPROPRIATE_CONTENT", "True").lower() == "true"
    BLOCK_DANGEROUS_PATTERNS: bool = os.getenv("BLOCK_DANGEROUS_PATTERNS", "True").lower() == "true"
    REQUIRE_BUSINESS_RELEVANCE: bool = os.getenv("REQUIRE_BUSINESS_RELEVANCE", "False").lower() == "true"
    
    class Config:
        env_file = ".env"
        case_sensitive = True

    def __post_init__(self):
        """Validate critical settings"""
        if not self.DATABASE_URL:
            raise ValueError("DATABASE_URL environment variable is required")
        if not self.REDIS_URL:
            raise ValueError("REDIS_URL environment variable is required")
        if not self.OPENAI_API_KEY:
            raise ValueError("OPENAI_API_KEY environment variable is required")

settings = Settings()
