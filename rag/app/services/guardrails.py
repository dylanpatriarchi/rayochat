"""
Guardrails system for RAG service - Input and Output validation and safety checks
"""
import re
import logging
from typing import Dict, Any, List, Tuple, Optional
from datetime import datetime
import hashlib
import json
from app.config import settings

logger = logging.getLogger(__name__)

class GuardrailViolation(Exception):
    """Exception raised when a guardrail is violated"""
    def __init__(self, message: str, violation_type: str, severity: str = "high"):
        self.message = message
        self.violation_type = violation_type
        self.severity = severity
        super().__init__(self.message)

class InputGuardrails:
    """Input validation and safety checks"""
    
    def __init__(self):
        self.max_input_length = getattr(settings, 'MAX_INPUT_LENGTH', 2000)
        self.min_input_length = getattr(settings, 'MIN_INPUT_LENGTH', 3)
        
        # Dangerous patterns that could indicate prompt injection or malicious intent
        self.dangerous_patterns = [
            # Prompt injection attempts
            r'ignore\s+(?:previous|all|above)\s+(?:instructions|prompts|rules)',
            r'forget\s+(?:everything|all|previous)',
            r'you\s+are\s+now\s+(?:a|an)\s+\w+',
            r'act\s+as\s+(?:a|an)\s+\w+',
            r'pretend\s+(?:to\s+be|you\s+are)',
            r'roleplay\s+as',
            r'simulate\s+(?:a|an)\s+\w+',
            
            # System prompt manipulation
            r'system\s*:\s*',
            r'assistant\s*:\s*',
            r'human\s*:\s*',
            r'user\s*:\s*',
            r'<\|.*?\|>',
            r'\[INST\]|\[/INST\]',
            
            # Data extraction attempts
            r'show\s+me\s+(?:all|your)\s+(?:data|information|files|documents)',
            r'list\s+(?:all|your)\s+(?:data|information|files|documents)',
            r'dump\s+(?:all|your)\s+(?:data|information|database)',
            r'export\s+(?:all|your)\s+(?:data|information)',
            
            # Jailbreak attempts
            r'DAN\s+mode',
            r'developer\s+mode',
            r'jailbreak',
            r'unrestricted\s+mode',
            
            # Code injection attempts
            r'<script.*?>.*?</script>',
            r'javascript\s*:',
            r'eval\s*\(',
            r'exec\s*\(',
            r'system\s*\(',
            
            # SQL injection patterns
            r'union\s+select',
            r'drop\s+table',
            r'delete\s+from',
            r'insert\s+into',
            r'update\s+.*\s+set',
            
            # Sensitive information requests
            r'(?:api|secret|private)\s+key',
            r'password',
            r'token',
            r'credential',
            r'database\s+connection',
            r'environment\s+variable',
        ]
        
        # Inappropriate content patterns
        self.inappropriate_patterns = [
            r'\b(?:fuck|shit|damn|bitch|asshole|bastard)\b',
            r'\b(?:sex|porn|xxx|adult|nude)\b',
            r'\b(?:kill|murder|suicide|death|violence)\b',
            r'\b(?:hate|racist|nazi|terrorist)\b',
        ]
        
        # Business-related keywords that indicate legitimate queries
        self.business_keywords = [
            'business', 'company', 'service', 'product', 'price', 'cost', 'contact',
            'location', 'address', 'phone', 'email', 'hours', 'open', 'closed',
            'appointment', 'booking', 'reservation', 'order', 'purchase', 'buy',
            'information', 'about', 'help', 'support', 'question', 'how', 'what',
            'when', 'where', 'why', 'who', 'can', 'do', 'offer', 'provide'
        ]
    
    def validate_input(self, message: str, site_id: int) -> Tuple[bool, Optional[str], Dict[str, Any]]:
        """
        Comprehensive input validation
        
        Returns:
            (is_valid, error_message, metadata)
        """
        metadata = {
            'timestamp': datetime.now().isoformat(),
            'site_id': site_id,
            'input_length': len(message),
            'violations': []
        }
        
        try:
            # Basic validation
            if not self._validate_length(message, metadata):
                return False, "Message length is invalid", metadata
            
            if not self._validate_encoding(message, metadata):
                return False, "Message contains invalid characters", metadata
            
            # Security checks
            if not self._check_dangerous_patterns(message, metadata):
                return False, "Message contains potentially dangerous content", metadata
            
            # Content appropriateness
            if not self._check_inappropriate_content(message, metadata):
                return False, "Message contains inappropriate content", metadata
            
            # Business relevance check
            if not self._check_business_relevance(message, metadata):
                return False, "Message is not relevant to business inquiries", metadata
            
            # Rate limiting check (additional layer)
            if not self._check_input_rate_limiting(message, site_id, metadata):
                return False, "Too many similar requests", metadata
            
            logger.info(f"Input validation passed for site {site_id}")
            return True, None, metadata
            
        except Exception as e:
            logger.error(f"Error in input validation: {str(e)}")
            metadata['violations'].append({
                'type': 'validation_error',
                'severity': 'high',
                'message': str(e)
            })
            return False, "Input validation failed", metadata
    
    def _validate_length(self, message: str, metadata: Dict) -> bool:
        """Validate message length"""
        if len(message) < self.min_input_length:
            metadata['violations'].append({
                'type': 'length_too_short',
                'severity': 'medium',
                'message': f"Message too short: {len(message)} < {self.min_input_length}"
            })
            return False
        
        if len(message) > self.max_input_length:
            metadata['violations'].append({
                'type': 'length_too_long',
                'severity': 'high',
                'message': f"Message too long: {len(message)} > {self.max_input_length}"
            })
            return False
        
        return True
    
    def _validate_encoding(self, message: str, metadata: Dict) -> bool:
        """Validate message encoding and characters"""
        try:
            # Check for valid UTF-8
            message.encode('utf-8')
            
            # Check for excessive special characters
            special_char_ratio = len(re.findall(r'[^\w\s\.\?\!\,\;\:\-\(\)\[\]\{\}]', message)) / len(message)
            if special_char_ratio > 0.3:
                metadata['violations'].append({
                    'type': 'excessive_special_chars',
                    'severity': 'medium',
                    'message': f"Too many special characters: {special_char_ratio:.2%}"
                })
                return False
            
            return True
            
        except UnicodeEncodeError:
            metadata['violations'].append({
                'type': 'encoding_error',
                'severity': 'high',
                'message': "Invalid character encoding"
            })
            return False
    
    def _check_dangerous_patterns(self, message: str, metadata: Dict) -> bool:
        """Check for dangerous patterns that might indicate attacks"""
        message_lower = message.lower()
        
        for pattern in self.dangerous_patterns:
            if re.search(pattern, message_lower, re.IGNORECASE):
                metadata['violations'].append({
                    'type': 'dangerous_pattern',
                    'severity': 'high',
                    'message': f"Matched dangerous pattern: {pattern}",
                    'pattern': pattern
                })
                logger.warning(f"Dangerous pattern detected: {pattern}")
                return False
        
        return True
    
    def _check_inappropriate_content(self, message: str, metadata: Dict) -> bool:
        """Check for inappropriate content"""
        message_lower = message.lower()
        
        for pattern in self.inappropriate_patterns:
            if re.search(pattern, message_lower, re.IGNORECASE):
                metadata['violations'].append({
                    'type': 'inappropriate_content',
                    'severity': 'medium',
                    'message': f"Inappropriate content detected: {pattern}",
                    'pattern': pattern
                })
                logger.warning(f"Inappropriate content detected: {pattern}")
                return False
        
        return True
    
    def _check_business_relevance(self, message: str, metadata: Dict) -> bool:
        """Check if message is relevant to business inquiries"""
        message_lower = message.lower()
        
        # Check for business keywords
        business_score = 0
        for keyword in self.business_keywords:
            if keyword in message_lower:
                business_score += 1
        
        # If message is very short, be more lenient
        if len(message) < 20:
            min_score = 1
        else:
            min_score = 2
        
        # Check for question patterns
        question_patterns = [r'\?', r'\bhow\b', r'\bwhat\b', r'\bwhen\b', r'\bwhere\b', r'\bwhy\b', r'\bcan\b']
        has_question_pattern = any(re.search(pattern, message_lower) for pattern in question_patterns)
        
        if business_score >= min_score or has_question_pattern:
            metadata['business_relevance_score'] = business_score
            return True
        
        metadata['violations'].append({
            'type': 'business_irrelevant',
            'severity': 'low',
            'message': f"Low business relevance score: {business_score}",
            'business_score': business_score
        })
        
        # For now, we'll allow it but log it
        logger.info(f"Low business relevance score: {business_score} for message: {message[:50]}...")
        return True
    
    def _check_input_rate_limiting(self, message: str, site_id: int, metadata: Dict) -> bool:
        """Check for repeated similar inputs (spam detection)"""
        try:
            # Create hash of the message
            message_hash = hashlib.md5(message.lower().encode()).hexdigest()
            
            # This would typically use Redis, but for now we'll just log
            logger.debug(f"Input hash for site {site_id}: {message_hash}")
            
            # TODO: Implement Redis-based duplicate detection
            return True
            
        except Exception as e:
            logger.error(f"Error in input rate limiting check: {str(e)}")
            return True


class OutputGuardrails:
    """Output validation and safety checks"""
    
    def __init__(self):
        self.max_output_length = getattr(settings, 'MAX_OUTPUT_LENGTH', 1500)
        self.min_output_length = getattr(settings, 'MIN_OUTPUT_LENGTH', 10)
        
        # Patterns that should not appear in outputs
        self.forbidden_output_patterns = [
            # System information leakage
            r'(?:api|secret|private)\s+key\s*:\s*\w+',
            r'password\s*:\s*\w+',
            r'token\s*:\s*\w+',
            r'database\s+connection\s*:\s*\w+',
            
            # Internal system references
            r'openai\s+api',
            r'gpt-\d+',
            r'language\s+model',
            r'ai\s+assistant',
            r'artificial\s+intelligence',
            
            # Inappropriate content
            r'\b(?:fuck|shit|damn|bitch|asshole|bastard)\b',
            r'\b(?:kill|murder|suicide|death|violence)\b',
            
            # Competitor mentions (configurable)
            r'\b(?:competitor|rival|alternative)\b',
            
            # Legal disclaimers that might be too strong
            r'not\s+responsible\s+for\s+any\s+damages',
            r'use\s+at\s+your\s+own\s+risk',
        ]
        
        # Required elements for business responses
        self.required_elements = [
            'helpful', 'informative', 'professional', 'business-focused'
        ]
    
    def validate_output(self, response: str, site_id: int, original_message: str) -> Tuple[bool, Optional[str], Dict[str, Any]]:
        """
        Comprehensive output validation
        
        Returns:
            (is_valid, error_message, metadata)
        """
        metadata = {
            'timestamp': datetime.now().isoformat(),
            'site_id': site_id,
            'output_length': len(response),
            'original_message_length': len(original_message),
            'violations': []
        }
        
        try:
            # Basic validation
            if not self._validate_output_length(response, metadata):
                return False, "Response length is invalid", metadata
            
            # Content safety checks
            if not self._check_forbidden_patterns(response, metadata):
                return False, "Response contains forbidden content", metadata
            
            # Quality checks
            if not self._check_response_quality(response, original_message, metadata):
                return False, "Response quality is insufficient", metadata
            
            # Business appropriateness
            if not self._check_business_appropriateness(response, metadata):
                return False, "Response is not business-appropriate", metadata
            
            # Information leakage check
            if not self._check_information_leakage(response, metadata):
                return False, "Response may contain sensitive information", metadata
            
            # Coherence and relevance
            if not self._check_coherence_and_relevance(response, original_message, metadata):
                return False, "Response is not coherent or relevant", metadata
            
            logger.info(f"Output validation passed for site {site_id}")
            return True, None, metadata
            
        except Exception as e:
            logger.error(f"Error in output validation: {str(e)}")
            metadata['violations'].append({
                'type': 'validation_error',
                'severity': 'high',
                'message': str(e)
            })
            return False, "Output validation failed", metadata
    
    def _validate_output_length(self, response: str, metadata: Dict) -> bool:
        """Validate response length"""
        if len(response) < self.min_output_length:
            metadata['violations'].append({
                'type': 'output_too_short',
                'severity': 'medium',
                'message': f"Response too short: {len(response)} < {self.min_output_length}"
            })
            return False
        
        if len(response) > self.max_output_length:
            metadata['violations'].append({
                'type': 'output_too_long',
                'severity': 'medium',
                'message': f"Response too long: {len(response)} > {self.max_output_length}"
            })
            # Don't fail, just truncate
            return True
        
        return True
    
    def _check_forbidden_patterns(self, response: str, metadata: Dict) -> bool:
        """Check for forbidden patterns in output"""
        response_lower = response.lower()
        
        for pattern in self.forbidden_output_patterns:
            if re.search(pattern, response_lower, re.IGNORECASE):
                metadata['violations'].append({
                    'type': 'forbidden_pattern',
                    'severity': 'high',
                    'message': f"Forbidden pattern in output: {pattern}",
                    'pattern': pattern
                })
                logger.warning(f"Forbidden pattern in output: {pattern}")
                return False
        
        return True
    
    def _check_response_quality(self, response: str, original_message: str, metadata: Dict) -> bool:
        """Check response quality metrics"""
        # Check for repetitive content
        words = response.lower().split()
        if len(words) > 10:
            unique_words = set(words)
            repetition_ratio = 1 - (len(unique_words) / len(words))
            
            if repetition_ratio > 0.5:
                metadata['violations'].append({
                    'type': 'high_repetition',
                    'severity': 'medium',
                    'message': f"High repetition ratio: {repetition_ratio:.2%}",
                    'repetition_ratio': repetition_ratio
                })
                return False
        
        # Check for generic responses
        generic_phrases = [
            'i am an ai', 'as an ai', 'i cannot', 'i don\'t have access',
            'i\'m sorry but', 'unfortunately', 'i apologize'
        ]
        
        response_lower = response.lower()
        generic_count = sum(1 for phrase in generic_phrases if phrase in response_lower)
        
        if generic_count > 2:
            metadata['violations'].append({
                'type': 'too_generic',
                'severity': 'low',
                'message': f"Too many generic phrases: {generic_count}",
                'generic_count': generic_count
            })
            # Don't fail, just log
        
        return True
    
    def _check_business_appropriateness(self, response: str, metadata: Dict) -> bool:
        """Check if response is appropriate for business context"""
        # Check for professional tone
        unprofessional_patterns = [
            r'\byo\b', r'\bhey\b', r'\bwassup\b', r'\bwhatever\b',
            r'\bdude\b', r'\bawesome\b', r'\bcool\b', r'\bsweet\b'
        ]
        
        response_lower = response.lower()
        for pattern in unprofessional_patterns:
            if re.search(pattern, response_lower):
                metadata['violations'].append({
                    'type': 'unprofessional_tone',
                    'severity': 'low',
                    'message': f"Unprofessional language detected: {pattern}",
                    'pattern': pattern
                })
                # Don't fail, just log
        
        return True
    
    def _check_information_leakage(self, response: str, metadata: Dict) -> bool:
        """Check for potential information leakage"""
        # Check for system prompts or internal instructions
        system_leakage_patterns = [
            r'system\s+prompt', r'instructions\s+were', r'i\s+was\s+told',
            r'my\s+training', r'openai', r'chatgpt', r'gpt-\d+'
        ]
        
        response_lower = response.lower()
        for pattern in system_leakage_patterns:
            if re.search(pattern, response_lower):
                metadata['violations'].append({
                    'type': 'system_leakage',
                    'severity': 'high',
                    'message': f"Potential system information leakage: {pattern}",
                    'pattern': pattern
                })
                logger.warning(f"Potential system leakage detected: {pattern}")
                return False
        
        return True
    
    def _check_coherence_and_relevance(self, response: str, original_message: str, metadata: Dict) -> bool:
        """Check if response is coherent and relevant to the original message"""
        # Basic coherence check - response should not be empty or just punctuation
        if not response.strip() or len(response.strip()) < 5:
            metadata['violations'].append({
                'type': 'incoherent_response',
                'severity': 'high',
                'message': "Response is empty or too short to be meaningful"
            })
            return False
        
        # Check if response contains at least some words from the original message
        # (simple relevance check)
        original_words = set(re.findall(r'\b\w+\b', original_message.lower()))
        response_words = set(re.findall(r'\b\w+\b', response.lower()))
        
        # Remove common stop words for better matching
        stop_words = {'the', 'a', 'an', 'and', 'or', 'but', 'in', 'on', 'at', 'to', 'for', 'of', 'with', 'by', 'is', 'are', 'was', 'were', 'be', 'been', 'have', 'has', 'had', 'do', 'does', 'did', 'will', 'would', 'could', 'should', 'may', 'might', 'can', 'this', 'that', 'these', 'those'}
        
        original_content_words = original_words - stop_words
        response_content_words = response_words - stop_words
        
        if len(original_content_words) > 0:
            relevance_score = len(original_content_words.intersection(response_content_words)) / len(original_content_words)
            metadata['relevance_score'] = relevance_score
            
            if relevance_score < 0.1 and len(original_message) > 20:
                metadata['violations'].append({
                    'type': 'low_relevance',
                    'severity': 'medium',
                    'message': f"Low relevance score: {relevance_score:.2%}",
                    'relevance_score': relevance_score
                })
                # Don't fail, just log for now
                logger.info(f"Low relevance score: {relevance_score:.2%}")
        
        return True


class GuardrailsService:
    """Main guardrails service that orchestrates input and output validation"""
    
    def __init__(self):
        self.input_guardrails = InputGuardrails()
        self.output_guardrails = OutputGuardrails()
        self.redis_client = None
        
        # Initialize Redis for logging violations
        try:
            from app.config import settings
            import redis
            self.redis_client = redis.from_url(settings.REDIS_URL)
        except Exception as e:
            logger.warning(f"Redis not available for guardrails logging: {str(e)}")
    
    def validate_input(self, message: str, site_id: int) -> Tuple[bool, Optional[str], Dict[str, Any]]:
        """Validate input message"""
        is_valid, error_message, metadata = self.input_guardrails.validate_input(message, site_id)
        
        # Log violations
        if not is_valid:
            self._log_violation('input', site_id, metadata)
        
        return is_valid, error_message, metadata
    
    def validate_output(self, response: str, site_id: int, original_message: str) -> Tuple[bool, Optional[str], Dict[str, Any]]:
        """Validate output response"""
        is_valid, error_message, metadata = self.output_guardrails.validate_output(response, site_id, original_message)
        
        # Log violations
        if not is_valid:
            self._log_violation('output', site_id, metadata)
        
        return is_valid, error_message, metadata
    
    def _log_violation(self, violation_type: str, site_id: int, metadata: Dict):
        """Log guardrail violations for monitoring and analysis"""
        try:
            violation_data = {
                'type': violation_type,
                'site_id': site_id,
                'timestamp': metadata['timestamp'],
                'violations': metadata['violations']
            }
            
            # Log to application logs
            logger.warning(f"Guardrail violation ({violation_type}) for site {site_id}: {len(metadata['violations'])} violations")
            
            # Store in Redis for monitoring (if available)
            if self.redis_client:
                violation_key = f"guardrail_violations:{violation_type}:{site_id}:{datetime.now().strftime('%Y%m%d')}"
                self.redis_client.lpush(violation_key, json.dumps(violation_data))
                self.redis_client.expire(violation_key, 7 * 24 * 3600)  # Keep for 7 days
            
        except Exception as e:
            logger.error(f"Error logging guardrail violation: {str(e)}")
    
    def get_violation_stats(self, site_id: Optional[int] = None, days: int = 7) -> Dict[str, Any]:
        """Get violation statistics for monitoring"""
        if not self.redis_client:
            return {"error": "Redis not available"}
        
        try:
            stats = {
                'input_violations': 0,
                'output_violations': 0,
                'total_violations': 0,
                'violation_types': {},
                'period_days': days
            }
            
            # This would implement actual stats gathering from Redis
            # For now, return empty stats
            return stats
            
        except Exception as e:
            logger.error(f"Error getting violation stats: {str(e)}")
            return {"error": str(e)}

