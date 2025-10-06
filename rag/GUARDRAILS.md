# RAG Guardrails System

## Overview

The RAG Guardrails system provides comprehensive input and output validation for the RayoChat RAG service. It acts as a security and quality control layer that ensures safe, appropriate, and business-relevant interactions.

## Features

### Input Guardrails
- **Length Validation**: Enforces minimum and maximum input lengths
- **Encoding Validation**: Ensures proper UTF-8 encoding and reasonable special character ratios
- **Dangerous Pattern Detection**: Blocks prompt injection, jailbreak attempts, and system manipulation
- **Inappropriate Content Filtering**: Filters profanity, adult content, and violent language
- **Business Relevance Checking**: Ensures queries are relevant to business inquiries
- **Rate Limiting**: Prevents spam and repeated similar requests

### Output Guardrails
- **Length Validation**: Ensures responses are within acceptable length limits
- **Forbidden Pattern Detection**: Prevents leakage of sensitive information or system details
- **Quality Checks**: Validates response coherence and prevents excessive repetition
- **Business Appropriateness**: Ensures professional tone and business-appropriate content
- **Information Leakage Prevention**: Blocks system prompts and internal information
- **Relevance Validation**: Ensures responses are relevant to the original query

## Configuration

### Environment Variables

```bash
# Enable/disable guardrails
GUARDRAILS_ENABLED=true
GUARDRAILS_STRICT_MODE=false

# Input validation limits
MAX_INPUT_LENGTH=2000
MIN_INPUT_LENGTH=3

# Output validation limits
MAX_OUTPUT_LENGTH=1500
MIN_OUTPUT_LENGTH=10

# Sensitivity levels (low, medium, high)
INPUT_GUARDRAILS_SENSITIVITY=medium
OUTPUT_GUARDRAILS_SENSITIVITY=medium

# Content filtering options
BLOCK_INAPPROPRIATE_CONTENT=true
BLOCK_DANGEROUS_PATTERNS=true
REQUIRE_BUSINESS_RELEVANCE=false
```

### Sensitivity Levels

- **Low**: Basic validation, minimal filtering
- **Medium**: Standard validation with reasonable filtering (default)
- **High**: Strict validation with aggressive filtering

## API Endpoints

### Main Chat Endpoint
```
POST /ask
```
Input and output guardrails are automatically applied to all chat requests.

### Guardrails Statistics
```
GET /guardrails/stats?days=7
```
Returns violation statistics for monitoring and debugging.

## Violation Types

### Input Violations
- `length_too_short` / `length_too_long`: Message length issues
- `encoding_error`: Invalid character encoding
- `excessive_special_chars`: Too many special characters
- `dangerous_pattern`: Prompt injection or system manipulation attempts
- `inappropriate_content`: Profanity or inappropriate language
- `business_irrelevant`: Query not relevant to business context

### Output Violations
- `output_too_short` / `output_too_long`: Response length issues
- `forbidden_pattern`: Sensitive information or system details
- `high_repetition`: Excessive repetitive content
- `too_generic`: Too many generic AI phrases
- `unprofessional_tone`: Inappropriate language for business context
- `system_leakage`: Potential system information disclosure
- `incoherent_response`: Empty or meaningless responses
- `low_relevance`: Response not relevant to the query

## Fallback Responses

When output guardrails detect violations, the system generates contextual fallback responses based on the original query:

- **Contact requests**: Directs users to contact information
- **Hours/availability**: Refers to business hours and scheduling
- **Services/products**: General information about offerings
- **Pricing**: Directs to direct contact for quotes
- **General questions**: Professional acknowledgment with contact direction

## Monitoring and Logging

### Violation Logging
- All violations are logged with detailed metadata
- Redis storage for real-time monitoring (7-day retention)
- Application logs for permanent record

### Metrics Tracked
- Violation counts by type and severity
- Site-specific violation patterns
- Temporal violation trends
- Fallback response usage

## Integration

### RAG Service Integration
```python
from app.services.guardrails import GuardrailsService

# Initialize
guardrails = GuardrailsService()

# Validate input
is_valid, error, metadata = guardrails.validate_input(message, site_id)

# Validate output
is_valid, error, metadata = guardrails.validate_output(response, site_id, original_message)
```

### Custom Patterns
You can extend the guardrails by modifying the pattern lists in `app/services/guardrails.py`:

- `dangerous_patterns`: Add new dangerous input patterns
- `inappropriate_patterns`: Add new inappropriate content patterns
- `forbidden_output_patterns`: Add new forbidden output patterns

## Security Considerations

1. **Pattern Evasion**: Regularly update patterns to handle new evasion techniques
2. **False Positives**: Monitor violation logs to identify and fix false positives
3. **Performance**: Guardrails add processing time; monitor performance impact
4. **Bypass Attempts**: Log and analyze patterns that might indicate bypass attempts

## Troubleshooting

### Common Issues

1. **High False Positive Rate**
   - Lower sensitivity level
   - Review and adjust pattern matching
   - Check business relevance requirements

2. **Performance Issues**
   - Consider disabling less critical checks
   - Optimize regex patterns
   - Use caching for repeated validations

3. **Legitimate Content Blocked**
   - Review violation logs
   - Adjust patterns or sensitivity
   - Add business-specific exceptions

### Debug Mode
Enable debug mode to see detailed violation information:
```bash
DEBUG=true
```

This will include guardrail metadata in API responses for troubleshooting.

## Best Practices

1. **Regular Monitoring**: Check violation statistics regularly
2. **Pattern Updates**: Keep dangerous patterns updated with new threats
3. **Business Context**: Customize business relevance patterns for your domain
4. **Gradual Rollout**: Start with lower sensitivity and gradually increase
5. **User Feedback**: Monitor user complaints about blocked content
6. **Performance Testing**: Test guardrails impact on response times

## Future Enhancements

- Machine learning-based content classification
- Dynamic pattern learning from violations
- Integration with external threat intelligence
- Advanced semantic analysis for context understanding
- Custom business domain vocabulary learning
