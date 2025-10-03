"""
Database models and connection
"""
from sqlalchemy import create_engine, Column, Integer, String, Text, DateTime, ForeignKey
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, relationship, Session
from sqlalchemy.pool import StaticPool
from app.config import settings
import logging

logger = logging.getLogger(__name__)

# Create engine with connection pooling and security settings
engine = create_engine(
    settings.DATABASE_URL,
    pool_pre_ping=True,
    pool_size=5,
    max_overflow=10,
    pool_recycle=3600,  # Recycle connections every hour
    echo=False,  # Never log SQL queries in production
    connect_args={
        "sslmode": "prefer",  # Prefer SSL connections
        "connect_timeout": 10,
        "application_name": "rayochat_rag"
    }
)

SessionLocal = sessionmaker(autocommit=False, autoflush=False, bind=engine)
Base = declarative_base()

class Site(Base):
    """Site model matching Laravel's sites table"""
    __tablename__ = "sites"
    
    id = Column(Integer, primary_key=True)
    user_id = Column(Integer, ForeignKey("users.id"))
    name = Column(String(255))
    url = Column(String(255))
    api_key = Column(String(255), unique=True, index=True)
    created_at = Column(DateTime)
    updated_at = Column(DateTime)
    
    # Relationships
    user = relationship("User", back_populates="sites")
    site_info = relationship("SiteInfoMD", back_populates="site", uselist=False)

class User(Base):
    """User model matching Laravel's users table"""
    __tablename__ = "users"
    
    id = Column(Integer, primary_key=True)
    name = Column(String(255))
    email = Column(String(255), unique=True)
    max_number_sites = Column(Integer, default=3)
    created_at = Column(DateTime)
    updated_at = Column(DateTime)
    
    # Relationships
    sites = relationship("Site", back_populates="user")

class SiteInfoMD(Base):
    """Site info model matching Laravel's site_info_m_d_s table"""
    __tablename__ = "site_info_m_d_s"
    
    id = Column(Integer, primary_key=True)
    site_id = Column(Integer, ForeignKey("sites.id"), unique=True)
    markdown_content = Column(Text)
    html_content = Column(Text)
    created_at = Column(DateTime)
    updated_at = Column(DateTime)
    
    # Relationships
    site = relationship("Site", back_populates="site_info")

def get_db() -> Session:
    """Get database session"""
    db = SessionLocal()
    try:
        yield db
    finally:
        db.close()

def get_site_with_info(api_key: str):
    """Get site with info by API key"""
    db = SessionLocal()
    try:
        from sqlalchemy.orm import joinedload
        site = db.query(Site).options(
            joinedload(Site.site_info)
        ).filter(Site.api_key == api_key).first()
        
        if site:
            # Create a detached copy of the data we need
            site_data = {
                'id': site.id,
                'name': site.name,
                'url': site.url,
                'api_key': site.api_key,
                'user_id': site.user_id,
                'site_info': None
            }
            
            if site.site_info:
                site_data['site_info'] = {
                    'id': site.site_info.id,
                    'markdown_content': site.site_info.markdown_content,
                    'html_content': site.site_info.html_content
                }
            
            return site_data
        return None
    finally:
        db.close()
