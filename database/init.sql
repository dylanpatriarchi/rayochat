-- Enable pgvector extension
CREATE EXTENSION IF NOT EXISTS vector;
CREATE EXTENSION IF NOT EXISTS "uuid-ossp";

-- This file ensures pgvector is loaded on database initialization
SELECT 'pgvector extension enabled successfully' AS status;
