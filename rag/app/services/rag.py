"""
RAG (Retrieval-Augmented Generation) service
"""
from app.services.openai_service import OpenAIService
from app.services.auth import AuthService
from app.models.schemas import ChatRequest, ChatResponse, ErrorResponse
from typing import Dict, Any, Optional
import logging
import redis
import json
from datetime import datetime, timedelta

logger = logging.getLogger(__name__)

class RAGService:
    """Main RAG service orchestrator"""
    
    def __init__(self):
        """Initialize RAG service"""
        self.openai_service = OpenAIService()
        self.auth_service = AuthService()
        
        # Initialize Redis for caching and rate limiting
        try:
            from app.config import settings
            self.redis_client = redis.from_url(settings.REDIS_URL)
            self.redis_client.ping()
            logger.info("Redis connection established")
        except Exception as e:
            logger.warning(f"Redis not available: {str(e)}")
            self.redis_client = None
    
    def process_message(self, request: ChatRequest, api_key: str) -> Dict[str, Any]:
        """
        Process incoming message and generate response
        """
        try:
            # Validate API key
            is_valid, site_data, error = self.auth_service.validate_api_key(api_key)
            
            if not is_valid:
                return {
                    "success": False,
                    "error": error or "Invalid API key"
                }
            
            # Check if site has business information
            if error:  # This means site is valid but has no info
                return {
                    "success": False,
                    "error": error
                }
            
            # Check rate limiting
            if self.is_rate_limited(api_key):
                return {
                    "success": False,
                    "error": "Rate limit exceeded. Please try again later."
                }
            
            # Extract site information
            site_id = site_data['id']
            site_name = site_data['name']
            site_url = site_data['url']
            site_info_content = site_data['site_info']['markdown_content']
            
            # Check cache for similar questions
            cached_response = self.get_cached_response(site_id, request.message)
            if cached_response:
                logger.info(f"Returning cached response for site {site_id}")
                return {
                    "success": True,
                    "message": "Response generated successfully (cached)",
                    "data": cached_response
                }
            
            # Generate response using RAG
            response_data = self.openai_service.generate_response_with_rag(
                message=request.message,
                site_id=site_id,
                site_info=site_info_content,
                site_name=site_name,
                site_url=site_url
            )
            
            # Cache the response
            self.cache_response(site_id, request.message, response_data)
            
            # Track usage
            self.track_usage(api_key, response_data.get("tokens_used", {}))
            
            # Log conversation
            self.log_conversation(
                site_id=site_id,
                message=request.message,
                response=response_data["response"],
                tokens=response_data.get("tokens_used", {})
            )
            
            # Save analytics to database
            self.save_analytics_to_db(site_id, request.message)
            
            return {
                "success": True,
                "message": "Response generated successfully",
                "data": response_data
            }
            
        except Exception as e:
            logger.error(f"Error processing message: {str(e)}")
            return {
                "success": False,
                "error": f"Error processing message: {str(e)}"
            }
    
    def is_rate_limited(self, api_key: str) -> bool:
        """
        Check if API key has exceeded rate limits
        """
        if not self.redis_client:
            return False
        
        try:
            # Check per-minute limit
            minute_key = f"rate_limit:minute:{api_key}"
            minute_count = self.redis_client.incr(minute_key)
            if minute_count == 1:
                self.redis_client.expire(minute_key, 60)
            
            # Check per-hour limit
            hour_key = f"rate_limit:hour:{api_key}"
            hour_count = self.redis_client.incr(hour_key)
            if hour_count == 1:
                self.redis_client.expire(hour_key, 3600)
            
            from app.config import settings
            if minute_count > settings.RATE_LIMIT_PER_MINUTE:
                logger.warning(f"Rate limit exceeded for {api_key[:10]}... (minute)")
                return True
            
            if hour_count > settings.RATE_LIMIT_PER_HOUR:
                logger.warning(f"Rate limit exceeded for {api_key[:10]}... (hour)")
                return True
            
            return False
            
        except Exception as e:
            logger.error(f"Error checking rate limit: {str(e)}")
            return False
    
    def get_cached_response(self, site_id: int, message: str) -> Optional[Dict]:
        """
        Get cached response for similar question
        """
        if not self.redis_client:
            return None
        
        try:
            # Create cache key based on site and message hash
            import hashlib
            message_hash = hashlib.md5(message.lower().encode()).hexdigest()
            cache_key = f"response_cache:{site_id}:{message_hash}"
            
            cached = self.redis_client.get(cache_key)
            if cached:
                return json.loads(cached)
            
            return None
            
        except Exception as e:
            logger.error(f"Error getting cached response: {str(e)}")
            return None
    
    def cache_response(self, site_id: int, message: str, response_data: Dict):
        """
        Cache response for future use
        """
        if not self.redis_client:
            return
        
        try:
            import hashlib
            message_hash = hashlib.md5(message.lower().encode()).hexdigest()
            cache_key = f"response_cache:{site_id}:{message_hash}"
            
            # Cache for 1 hour
            self.redis_client.setex(
                cache_key,
                3600,
                json.dumps(response_data)
            )
            
        except Exception as e:
            logger.error(f"Error caching response: {str(e)}")
    
    def track_usage(self, api_key: str, tokens_used: Dict):
        """
        Track token usage for billing/analytics
        """
        if not self.redis_client:
            return
        
        try:
            # Track daily usage
            today = datetime.now().strftime("%Y-%m-%d")
            usage_key = f"usage:{api_key}:{today}"
            
            self.redis_client.hincrby(usage_key, "prompt_tokens", tokens_used.get("prompt", 0))
            self.redis_client.hincrby(usage_key, "completion_tokens", tokens_used.get("completion", 0))
            self.redis_client.hincrby(usage_key, "total_tokens", tokens_used.get("total", 0))
            self.redis_client.hincrby(usage_key, "requests", 1)
            
            # Expire after 30 days
            self.redis_client.expire(usage_key, 30 * 24 * 3600)
            
        except Exception as e:
            logger.error(f"Error tracking usage: {str(e)}")
    
    def log_conversation(self, site_id: int, message: str, response: str, tokens: Dict):
        """
        Log conversation for analytics (optional - can be saved to DB)
        """
        try:
            logger.info(f"Conversation for site {site_id}: Q: {message[:50]}... A: {response[:50]}...")
            # TODO: Save to database if needed for analytics
        except Exception as e:
            logger.error(f"Error logging conversation: {str(e)}")
    
    def save_analytics_to_db(self, site_id: int, message: str):
        """
        Save analytics data to Laravel database
        """
        try:
            from app.models.database import save_analytics
            save_analytics(site_id, message)
        except Exception as e:
            logger.error(f"Error saving analytics to database: {str(e)}")
