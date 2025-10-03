# RayoChat RAG Service

## 🎯 Overview

The RAG (Retrieval-Augmented Generation) service is the AI brain of the RayoChat platform. It provides intelligent, context-aware responses to customer inquiries by combining OpenAI's language models with business-specific information stored in a vector database.

## 🏗️ Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    RAG Service (FastAPI)                   │
├─────────────────────────────────────────────────────────────┤
│  API Layer                                                 │
│  ├── /ask                          # Main chat endpoint     │
│  ├── /webhook                      # Alternative endpoint   │
│  └── /health                       # Health check           │
├─────────────────────────────────────────────────────────────┤
│  Services                                                   │
│  ├── AuthService                   # API key validation     │
│  ├── OpenAIService                 # LLM integration        │
│  └── RAGService                    # Main orchestrator      │
├─────────────────────────────────────────────────────────────┤
│  Data Layer                                                 │
│  ├── PostgreSQL                    # Site & business info   │
│  ├── ChromaDB                      # Vector embeddings      │
│  └── Redis                         # Caching & rate limits  │
└─────────────────────────────────────────────────────────────┘
```

## ✨ Features

### AI-Powered Responses
- **OpenAI Integration** - GPT-3.5-turbo for natural language generation
- **Context Awareness** - Responses based on business-specific information
- **Multi-language Support** - Primarily Italian with English fallback
- **Conversation Memory** - Context retention across interactions

### Vector Search & RAG
- **ChromaDB Integration** - Efficient vector storage and retrieval
- **Semantic Search** - Find relevant information using embeddings
- **Text Chunking** - Intelligent document splitting for better retrieval
- **Source Attribution** - Track which information was used in responses

### Security & Performance
- **API Key Authentication** - Secure access control per site
- **Rate Limiting** - 30 requests/minute, 500/hour per API key
- **Input Sanitization** - XSS and injection prevention
- **Response Caching** - Redis-based caching for similar queries
- **Token Usage Tracking** - Monitor OpenAI API consumption

## 🚀 Quick Start

### 1. Environment Configuration
```bash
# Copy and edit environment file
cp .env.example .env
# Add your OpenAI API key
OPENAI_API_KEY=sk-your-actual-openai-key-here
```

### 2. Start the Service
```bash
# From project root
docker compose up -d rag
```

### 3. Verify Installation
```bash
# Health check
curl http://localhost:8002/health

# Test chat endpoint
curl -X POST http://localhost:8002/ask \
  -H "Content-Type: application/json" \
  -d '{
    "message": "Hello, how are you?",
    "api_key": "rc_s_your_site_api_key"
  }'
```

## 📡 API Endpoints

### POST /ask
Primary endpoint for processing chat messages.

**Request:**
```json
{
    "message": "What services do you offer?",
    "api_key": "rc_s_xxxxxxxxxxxxxxxxxxxxx",
    "conversation_id": "optional-conversation-id"
}
```

**Response:**
```json
{
    "success": true,
    "message": "Response generated successfully",
    "data": {
        "response": "Based on our business information, we offer...",
        "confidence": 0.95,
        "sources": [
            "## Our Services\n\nWe provide comprehensive...",
            "### Consulting Services\n\nOur team..."
        ],
        "tokens_used": {
            "prompt": 277,
            "completion": 28,
            "total": 305
        },
        "cost_estimate": 0.0004715,
        "model": "gpt-3.5-turbo"
    },
    "timestamp": "2025-10-03T23:11:12.205055"
}
```

### POST /webhook
Alternative endpoint for webhook integrations.

**Request:**
```json
{
    "message": "Tell me about your company",
    "api_key": "rc_s_xxxxxxxxxxxxxxxxxxxxx"
}
```

### GET /health
Service health check endpoint.

**Response:**
```json
{
    "status": "healthy",
    "services": {
        "database": "healthy",
        "redis": "healthy", 
        "openai": "configured"
    },
    "timestamp": "2025-10-03T23:13:01.750689"
}
```

## 🔧 Configuration

### Environment Variables

```env
# Database Connection
DATABASE_URL=postgresql://rayochat:rayochat_password@db:5432/rayochat

# OpenAI Configuration
OPENAI_API_KEY=sk-your-openai-key-here
OPENAI_MODEL=gpt-3.5-turbo
OPENAI_TEMPERATURE=0.7
OPENAI_MAX_TOKENS=500

# Redis Configuration
REDIS_URL=redis://redis:6379/1

# Application Settings
APP_ENV=development
DEBUG=True
LOG_LEVEL=INFO

# Rate Limiting
RATE_LIMIT_PER_MINUTE=30
RATE_LIMIT_PER_HOUR=500

# LangChain Settings
EMBEDDING_MODEL=text-embedding-ada-002
CHUNK_SIZE=1000
CHUNK_OVERLAP=200
```

### Security Configuration

- **CORS Origins** - Configured for Laravel integration
- **Input Validation** - Pydantic schemas with sanitization
- **API Key Format** - Must match `rc_s_[32 alphanumeric chars]`
- **Rate Limiting** - Per-IP and per-API-key limits
- **Security Headers** - XSS, clickjacking protection

## 🏗️ Technical Architecture

### Data Flow
```
1. Client Request → FastAPI Endpoint
2. Input Validation → Pydantic Schemas
3. API Key Auth → PostgreSQL Lookup
4. Rate Limiting → Redis Check
5. Cache Check → Redis Lookup
6. RAG Processing → LangChain + OpenAI
7. Response Cache → Redis Storage
8. Response → Client
```

### Components

**FastAPI Application:**
- Async request handling
- Automatic API documentation
- Built-in validation and serialization

**LangChain Integration:**
- Document chunking and embedding
- Vector store management
- Conversational retrieval chains
- Prompt template management

**OpenAI Services:**
- GPT-3.5-turbo for text generation
- text-embedding-ada-002 for embeddings
- Token counting and cost estimation

**ChromaDB Vector Store:**
- Persistent vector storage
- Semantic similarity search
- Automatic embedding generation
- Per-site vector collections

## 🛡️ Security Features

### Input Security
- **Message Sanitization** - Remove dangerous characters
- **XSS Prevention** - HTML entity escaping
- **Injection Protection** - Pattern detection
- **Length Limits** - Prevent oversized inputs

### API Security
- **Authentication** - API key validation
- **Authorization** - Site-specific access control
- **Rate Limiting** - DDoS protection
- **CORS Policy** - Cross-origin restrictions

### Data Security
- **SQL Injection Prevention** - SQLAlchemy ORM
- **Connection Security** - SSL/TLS support
- **Session Management** - Secure Redis sessions
- **Error Handling** - No sensitive data exposure

## 📊 Monitoring & Analytics

### Health Monitoring
```bash
# Service health
curl http://localhost:8002/health

# Detailed logs
docker logs rayochat_rag -f

# Redis monitoring
docker exec rayochat_redis redis-cli monitor
```

### Usage Analytics
- **Token Consumption** - Track OpenAI API usage
- **Response Times** - Monitor performance
- **Cache Hit Rates** - Optimize caching
- **Error Rates** - Identify issues

### Performance Metrics
- **Request Latency** - Average response time
- **Throughput** - Requests per second
- **Memory Usage** - Vector store size
- **Database Connections** - Connection pool status

## 🚀 Development

### Local Development
```bash
# Install dependencies
cd rag
pip install -r requirements.txt

# Run development server
uvicorn app.main:app --reload --host 0.0.0.0 --port 8000

# Run with debug logging
LOG_LEVEL=DEBUG uvicorn app.main:app --reload
```

### Testing
```bash
# Unit tests
python -m pytest tests/

# Integration tests
python -m pytest tests/integration/

# Load testing
python -m pytest tests/load/
```

### Code Quality
```bash
# Format code
black app/

# Lint code
flake8 app/

# Type checking
mypy app/
```

## 🔧 Troubleshooting

### Common Issues

**Service Won't Start:**
```bash
# Check logs
docker logs rayochat_rag

# Verify environment
docker exec rayochat_rag env | grep OPENAI

# Restart service
docker compose restart rag
```

**OpenAI API Errors:**
- Verify API key is valid and has credits
- Check rate limits on OpenAI dashboard
- Monitor token usage and costs

**Database Connection Issues:**
```bash
# Test connection
docker exec rayochat_rag python -c "
from app.models.database import engine
with engine.connect() as conn:
    print('Database connected successfully')
"
```

**Redis Connection Issues:**
```bash
# Test Redis
docker exec rayochat_redis redis-cli ping

# Clear cache
docker exec rayochat_redis redis-cli FLUSHDB
```

**Performance Issues:**
- Monitor vector store size
- Check embedding generation time
- Optimize chunk size and overlap
- Review caching strategy

### Debug Mode
Enable debug mode for detailed logging:
```env
DEBUG=True
LOG_LEVEL=DEBUG
```

## 📈 Scaling Considerations

### Horizontal Scaling
- Multiple RAG service instances
- Load balancer configuration
- Shared Redis and PostgreSQL
- Vector store synchronization

### Performance Optimization
- **Caching Strategy** - Aggressive response caching
- **Connection Pooling** - Database connection limits
- **Async Processing** - Non-blocking operations
- **Memory Management** - Vector store optimization

## 📄 License

This RAG service is part of the proprietary RayoChat platform. See LICENSE.md for details.
