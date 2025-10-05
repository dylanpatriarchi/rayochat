"""
Message Classification Service using ML
Automatically classifies messages into predefined categories
"""

import re
import logging
from typing import Dict, List, Tuple, Optional
from dataclasses import dataclass
import json

logger = logging.getLogger(__name__)

@dataclass
class ClassificationResult:
    category: str
    confidence: float
    subcategories: Dict[str, float]
    keywords: List[str]

class MessageClassifier:
    """
    Message classifier based on patterns and keywords
    Uses a hybrid approach: rule-based + ML-like scoring
    """
    
    def __init__(self):
        self.categories = {
            'supporto_tecnico': {
                'keywords': [
                    'errore', 'bug', 'problema', 'non funziona', 'crash', 'lento', 
                    'caricamento', 'login', 'password', 'accesso', 'installazione',
                    'aggiornamento', 'compatibilità', 'browser', 'mobile', 'app',
                    'error', 'issue', 'broken', 'fix', 'help', 'support'
                ],
                'patterns': [
                    r'\b(non|doesn\'t|can\'t|unable).*(funziona|work|load|access)\b',
                    r'\b(errore|error|problema|issue|bug)\b',
                    r'\b(aiuto|help|supporto|support)\b.*\b(tecnico|technical)\b'
                ],
                'weight': 1.0
            },
            'informazioni_prodotto': {
                'keywords': [
                    'prezzo', 'costo', 'quanto costa', 'tariffe', 'piano', 'abbonamento',
                    'caratteristiche', 'funzionalità', 'features', 'specifiche',
                    'disponibilità', 'quando', 'dove', 'come', 'cosa', 'perché',
                    'price', 'cost', 'pricing', 'plan', 'subscription', 'features'
                ],
                'patterns': [
                    r'\b(quanto|how much|what.*cost|price|pricing)\b',
                    r'\b(cosa|what|che cosa|which).*\b(fa|does|is|are)\b',
                    r'\b(come|how).*\b(funziona|works|use|utilizzare)\b'
                ],
                'weight': 1.0
            },
            'vendite_commerciale': {
                'keywords': [
                    'acquistare', 'comprare', 'ordine', 'pagamento', 'fattura',
                    'sconto', 'offerta', 'promozione', 'demo', 'prova', 'trial',
                    'contatto', 'vendite', 'commerciale', 'preventivo',
                    'buy', 'purchase', 'order', 'payment', 'invoice', 'discount',
                    'offer', 'promotion', 'demo', 'trial', 'sales', 'quote'
                ],
                'patterns': [
                    r'\b(voglio|want|need|vorrei).*\b(acquistare|buy|purchase)\b',
                    r'\b(demo|prova|trial|test)\b',
                    r'\b(contatto|contact|sales|vendite)\b'
                ],
                'weight': 1.0
            },
            'feedback_recensioni': {
                'keywords': [
                    'ottimo', 'fantastico', 'perfetto', 'eccellente', 'bravo',
                    'male', 'pessimo', 'terribile', 'deludente', 'insoddisfatto',
                    'recensione', 'feedback', 'opinione', 'esperienza', 'valutazione',
                    'great', 'excellent', 'perfect', 'amazing', 'awesome',
                    'bad', 'terrible', 'awful', 'disappointed', 'review', 'feedback'
                ],
                'patterns': [
                    r'\b(molto|really|extremely).*(bene|buono|good|bad|male)\b',
                    r'\b(recensione|review|feedback|opinione|opinion)\b',
                    r'\b(soddisfatto|satisfied|happy|deluso|disappointed)\b'
                ],
                'weight': 1.0
            },
            'richiesta_generale': {
                'keywords': [
                    'informazioni', 'info', 'dettagli', 'spiegazione', 'chiarimento',
                    'domanda', 'curiosità', 'interesse', 'sapere', 'conoscere',
                    'information', 'details', 'explanation', 'question', 'know',
                    'understand', 'learn', 'tell me', 'explain'
                ],
                'patterns': [
                    r'\b(potresti|could you|can you).*\b(spiegare|explain|tell)\b',
                    r'\b(vorrei sapere|want to know|need to know)\b',
                    r'\b(informazioni|information|details).*\b(su|about|regarding)\b'
                ],
                'weight': 0.8
            },
            'lamentela_problema': {
                'keywords': [
                    'lamentela', 'reclamo', 'protesta', 'insoddisfatto', 'arrabbiato',
                    'deluso', 'frustrato', 'annoiato', 'stanco', 'basta',
                    'complaint', 'complain', 'angry', 'frustrated', 'disappointed',
                    'upset', 'annoyed', 'fed up', 'enough'
                ],
                'patterns': [
                    r'\b(sono|i am).*(arrabbiato|angry|frustrated|upset)\b',
                    r'\b(non sono|not).*(soddisfatto|satisfied|happy)\b',
                    r'\b(reclamo|complaint|complain)\b'
                ],
                'weight': 1.2
            }
        }
    
    def preprocess_message(self, message: str) -> str:
        """Preprocess the message for classification"""
        # Convert to lowercase
        message = message.lower().strip()
        
        # Remove special characters but keep spaces and basic punctuation
        message = re.sub(r'[^\w\s\.\!\?\,\;\:]', ' ', message)
        
        # Remove multiple spaces
        message = re.sub(r'\s+', ' ', message)
        
        return message
    
    def calculate_keyword_score(self, message: str, category_data: Dict) -> Tuple[float, List[str]]:
        """Calculate score based on keywords"""
        found_keywords = []
        score = 0.0
        
        for keyword in category_data['keywords']:
            if keyword in message:
                found_keywords.append(keyword)
                # Higher weight for longer keywords (more specific)
                keyword_weight = len(keyword.split()) * 0.5 + 1.0
                score += keyword_weight
        
        return score, found_keywords
    
    def calculate_pattern_score(self, message: str, category_data: Dict) -> float:
        """Calculate score based on regex patterns"""
        score = 0.0
        
        for pattern in category_data.get('patterns', []):
            matches = re.findall(pattern, message, re.IGNORECASE)
            if matches:
                # Higher weight for more complex patterns
                pattern_weight = len(pattern) / 50.0 + 1.0
                score += len(matches) * pattern_weight
        
        return score
    
    def classify_message(self, message: str) -> ClassificationResult:
        """
        Classify a message and return category, confidence and details
        """
        if not message or len(message.strip()) < 3:
            return ClassificationResult(
                category='altro',
                confidence=0.0,
                subcategories={},
                keywords=[]
            )
        
        processed_message = self.preprocess_message(message)
        category_scores = {}
        all_keywords = {}
        
        # Calculate scores for each category
        for category, category_data in self.categories.items():
            keyword_score, found_keywords = self.calculate_keyword_score(processed_message, category_data)
            pattern_score = self.calculate_pattern_score(processed_message, category_data)
            
            # Combine scores with category weight
            total_score = (keyword_score + pattern_score) * category_data['weight']
            
            category_scores[category] = total_score
            all_keywords[category] = found_keywords
        
        # Find category with highest score
        if not category_scores or max(category_scores.values()) == 0:
            best_category = 'richiesta_generale'
            confidence = 0.3
        else:
            best_category = max(category_scores, key=category_scores.get)
            max_score = category_scores[best_category]
            total_score = sum(category_scores.values())
            confidence = min(max_score / (total_score + 1.0), 0.95)
        
        # Normalize scores for subcategories
        total_score = sum(category_scores.values())
        if total_score > 0:
            subcategories = {
                cat: round(score / total_score, 4) 
                for cat, score in category_scores.items()
            }
        else:
            subcategories = {}
        
        return ClassificationResult(
            category=best_category,
            confidence=round(confidence, 4),
            subcategories=subcategories,
            keywords=all_keywords.get(best_category, [])
        )
    
    def get_category_stats(self, messages: List[str]) -> Dict:
        """
        Analyze a list of messages and return statistics by category
        """
        if not messages:
            return {}
        
        category_counts = {}
        total_confidence = {}
        
        for message in messages:
            result = self.classify_message(message)
            category = result.category
            
            if category not in category_counts:
                category_counts[category] = 0
                total_confidence[category] = 0.0
            
            category_counts[category] += 1
            total_confidence[category] += result.confidence
        
        # Calculate percentages and average confidence
        total_messages = len(messages)
        stats = {}
        
        for category, count in category_counts.items():
            stats[category] = {
                'count': count,
                'percentage': round((count / total_messages) * 100, 2),
                'avg_confidence': round(total_confidence[category] / count, 4)
            }
        
        return stats

# Global classifier instance
message_classifier = MessageClassifier()

def classify_message(message: str) -> Dict:
    """
    Utility function to classify a single message
    """
    try:
        result = message_classifier.classify_message(message)
        return {
            'category': result.category,
            'confidence': float(result.confidence),
            'subcategories': result.subcategories,
            'keywords': result.keywords
        }
    except Exception as e:
        logger.error(f"Error classifying message: {str(e)}")
        return {
            'category': 'altro',
            'confidence': 0.0,
            'subcategories': {},
            'keywords': []
        }

def get_message_stats(messages: List[str]) -> Dict:
    """
    Utility function to get statistics on a list of messages
    """
    try:
        return message_classifier.get_category_stats(messages)
    except Exception as e:
        logger.error(f"Error getting message stats: {str(e)}")
        return {}
