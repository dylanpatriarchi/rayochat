"""
Authentication utilities for API key validation
"""
import re
from typing import Optional
from fastapi import HTTPException, Header

def validate_api_key_format(api_key: str) -> bool:
    """
    Validate API key format
    
    Args:
        api_key: API key to validate
        
    Returns:
        bool: True if valid format
    """
    if not api_key:
        return False
    
    # Must start with rc_s_ and contain only alphanumeric characters
    return bool(re.match(r'^rc_s_[a-zA-Z0-9]{32}$', api_key))

def extract_api_key(
    authorization: Optional[str] = Header(None),
    x_api_key: Optional[str] = Header(None)
) -> str:
    """
    Extract API key from headers
    
    Args:
        authorization: Authorization header (Bearer token)
        x_api_key: X-API-Key header
        
    Returns:
        str: API key
        
    Raises:
        HTTPException: If API key not found or invalid format
    """
    api_key = None
    
    # Try Authorization header first (Bearer token)
    if authorization:
        if authorization.startswith('Bearer '):
            api_key = authorization[7:]  # Remove 'Bearer ' prefix
    
    # Fallback to X-API-Key header
    if not api_key and x_api_key:
        api_key = x_api_key
    
    # Validate presence
    if not api_key:
        raise HTTPException(
            status_code=401,
            detail="API key required. Use Authorization: Bearer <key> or X-API-Key header"
        )
    
    # Validate format
    if not validate_api_key_format(api_key):
        raise HTTPException(
            status_code=401,
            detail="Invalid API key format. Must be rc_s_ followed by 32 alphanumeric characters"
        )
    
    return api_key
