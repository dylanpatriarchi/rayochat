"""
Configuration management for RAG service
"""

from pydantic_settings import BaseSettings
from functools import lru_cache


class Settings(BaseSettings):
    """Application settings"""
    
    # OpenAI Configuration
    OPENAI_API_KEY: str
    OPENAI_MODEL: str = "gpt-4-turbo-preview"
    OPENAI_EMBEDDING_MODEL: str = "text-embedding-3-small"
    OPENAI_TEMPERATURE: float = 0.7
    OPENAI_MAX_TOKENS: int = 1000
    
    # Database Configuration
    DATABASE_URL: str
    
    # Redis Configuration
    REDIS_URL: str = "redis://redis:6379"
    
    # RAG Configuration
    CHUNK_SIZE: int = 1000
    CHUNK_OVERLAP: int = 200
    TOP_K_RESULTS: int = 5
    SIMILARITY_THRESHOLD: float = 0.7
    
    # Vector Store Configuration
    VECTOR_DIMENSION: int = 1536  # OpenAI text-embedding-3-small dimension
    
    class Config:
        env_file = ".env"
        case_sensitive = True


@lru_cache()
def get_settings() -> Settings:
    """Get cached settings instance"""
    return Settings()
