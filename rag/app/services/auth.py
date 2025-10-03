"""
Authentication service for API key validation
"""
from typing import Optional, Tuple, Dict, Any
from app.models.database import get_site_with_info
import logging
import hashlib
import hmac

logger = logging.getLogger(__name__)

class AuthService:
    """Authentication service"""
    
    @staticmethod
    def validate_api_key(api_key: str) -> Tuple[bool, Optional[Dict[str, Any]], Optional[str]]:
        """
        Validate API key and return site information
        
        Returns:
            Tuple of (is_valid, site_data, error_message)
        """
        try:
            # Check if API key is provided
            if not api_key:
                return False, None, "API key is required"
            
            # Validate API key format (should start with 'rc_s_')
            if not api_key.startswith('rc_s_'):
                return False, None, "Invalid API key format"
            
            # Get site from database
            site_data = get_site_with_info(api_key)
            
            if not site_data:
                logger.warning(f"Invalid API key attempted: {api_key[:10]}...")
                return False, None, "Invalid API key"
            
            # Check if site has info
            if not site_data.get('site_info') or not site_data['site_info'].get('markdown_content'):
                logger.info(f"Site {site_data['id']} has no business information")
                return True, site_data, "No business information available for this site"
            
            logger.info(f"API key validated for site: {site_data['id']}")
            return True, site_data, None
            
        except Exception as e:
            logger.error(f"Error validating API key: {str(e)}")
            return False, None, f"Authentication error: {str(e)}"
    
    @staticmethod
    def is_rate_limited(api_key: str) -> bool:
        """
        Check if API key is rate limited
        This will be implemented with Redis
        """
        # TODO: Implement rate limiting with Redis
        return False
