"""
OpenAI service with LangChain integration
"""
from langchain_openai import ChatOpenAI, OpenAIEmbeddings
from langchain.schema import HumanMessage, SystemMessage, AIMessage
from langchain.memory import ConversationBufferMemory
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain.vectorstores import Chroma
from langchain.chains import ConversationalRetrievalChain
from langchain.prompts import PromptTemplate
from typing import Optional, Dict, Any, List
from app.config import settings
import logging
import tiktoken

logger = logging.getLogger(__name__)

class OpenAIService:
    """OpenAI service with LangChain"""
    
    def __init__(self):
        """Initialize OpenAI service"""
        if not settings.OPENAI_API_KEY or settings.OPENAI_API_KEY.startswith("sk-YOUR"):
            logger.warning("OpenAI API key is not configured. Service will not work until configured.")
            self.llm = None
            self.embeddings = None
            self.text_splitter = None
            self.encoder = None
            self.vector_stores = {}
            return
        
        # Initialize LLM
        self.llm = ChatOpenAI(
            openai_api_key=settings.OPENAI_API_KEY,
            model_name=settings.OPENAI_MODEL,
            temperature=settings.OPENAI_TEMPERATURE,
            max_tokens=settings.OPENAI_MAX_TOKENS
        )
        
        # Initialize embeddings
        self.embeddings = OpenAIEmbeddings(
            openai_api_key=settings.OPENAI_API_KEY,
            model=settings.EMBEDDING_MODEL
        )
        
        # Initialize text splitter
        self.text_splitter = RecursiveCharacterTextSplitter(
            chunk_size=settings.CHUNK_SIZE,
            chunk_overlap=settings.CHUNK_OVERLAP,
            separators=["\n\n", "\n", ".", "!", "?", ",", " ", ""]
        )
        
        # Token encoder for counting
        self.encoder = tiktoken.encoding_for_model(settings.OPENAI_MODEL)
        
        # Store for vector databases per site
        self.vector_stores = {}
        
        logger.info("OpenAI service initialized successfully")
    
    def create_or_get_vector_store(self, site_id: int, content: str) -> Chroma:
        """
        Create or get vector store for a site
        """
        if not self.embeddings:
            raise ValueError("OpenAI service is not properly configured")
        
        if site_id not in self.vector_stores:
            # Split content into chunks
            chunks = self.text_splitter.split_text(content)
            
            # Create vector store
            self.vector_stores[site_id] = Chroma.from_texts(
                texts=chunks,
                embedding=self.embeddings,
                persist_directory=f"/tmp/chroma_db_{site_id}"
            )
            logger.info(f"Created vector store for site {site_id} with {len(chunks)} chunks")
        
        return self.vector_stores[site_id]
    
    def generate_response(
        self, 
        message: str, 
        site_info: str,
        site_name: str,
        site_url: str,
        conversation_history: Optional[List[Dict]] = None
    ) -> Dict[str, Any]:
        """
        Generate response using RAG with LangChain
        """
        if not self.llm:
            return {
                "response": "Il servizio di intelligenza artificiale non è configurato. Contatta l'amministratore.",
                "confidence": 0,
                "sources": [],
                "tokens_used": {"prompt": 0, "completion": 0, "total": 0},
                "cost_estimate": 0,
                "model": "not_configured"
            }
        
        try:
            # Create system prompt with safe string formatting
            import html
            safe_site_name = html.escape(str(site_name)[:100])  # Limit length and escape
            safe_site_url = html.escape(str(site_url)[:200])    # Limit length and escape
            safe_site_info = str(site_info)[:5000]              # Limit length
            
            system_prompt = f"""Sei l'assistente virtuale di {safe_site_name}.
            Usa ESCLUSIVAMENTE le seguenti informazioni aziendali per rispondere alle domande:
            
            {safe_site_info}
            
            Regole importanti:
            1. Rispondi SOLO basandoti sulle informazioni fornite sopra
            2. Se una domanda non può essere risposta con le informazioni disponibili, indica gentilmente che non hai queste informazioni
            3. Sii professionale, cortese e conciso
            4. Rispondi sempre in italiano
            5. Non inventare informazioni non presenti nel contesto
            6. Non fornire consigli medici, legali o finanziari
            7. Non rispondere a domande offensive o inappropriate
            8. Se appropriato, suggerisci di visitare {safe_site_url} per maggiori informazioni
            """
            
            # Create messages
            messages = [SystemMessage(content=system_prompt)]
            
            # Add conversation history if available
            if conversation_history:
                for msg in conversation_history[-5:]:  # Last 5 messages for context
                    if msg.get("role") == "user":
                        messages.append(HumanMessage(content=msg["content"]))
                    elif msg.get("role") == "assistant":
                        messages.append(AIMessage(content=msg["content"]))
            
            # Add current message
            messages.append(HumanMessage(content=message))
            
            # Generate response
            response = self.llm.invoke(messages)
            
            # Count tokens
            prompt_tokens = self.count_tokens(system_prompt + message)
            completion_tokens = self.count_tokens(response.content)
            total_tokens = prompt_tokens + completion_tokens
            
            # Estimate cost (GPT-3.5-turbo pricing)
            cost_estimate = (prompt_tokens * 0.0015 + completion_tokens * 0.002) / 1000
            
            return {
                "response": response.content,
                "confidence": 0.95,  # Can be calculated based on relevance
                "sources": ["business_info"],
                "tokens_used": {
                    "prompt": prompt_tokens,
                    "completion": completion_tokens,
                    "total": total_tokens
                },
                "cost_estimate": cost_estimate,
                "model": settings.OPENAI_MODEL
            }
            
        except Exception as e:
            logger.error(f"Error generating response: {str(e)}")
            raise
    
    def generate_response_with_rag(
        self,
        message: str,
        site_id: int,
        site_info: str,
        site_name: str,
        site_url: str
    ) -> Dict[str, Any]:
        """
        Generate response using RAG with vector store
        """
        if not self.llm:
            return {
                "response": "Il servizio di intelligenza artificiale non è configurato. Contatta l'amministratore.",
                "confidence": 0,
                "sources": [],
                "tokens_used": {"prompt": 0, "completion": 0, "total": 0},
                "cost_estimate": 0,
                "model": "not_configured"
            }
        
        try:
            # Create or get vector store
            vector_store = self.create_or_get_vector_store(site_id, site_info)
            
            # Create custom prompt
            prompt_template = """Sei l'assistente virtuale di {site_name}.
            
            Usa il seguente contesto per rispondere alla domanda. Se non puoi rispondere 
            basandoti sul contesto, dì che non hai questa informazione.
            
            Contesto: {context}
            
            Domanda: {question}
            
            Risposta professionale in italiano:"""
            
            PROMPT = PromptTemplate(
                template=prompt_template,
                input_variables=["context", "question"],
                partial_variables={"site_name": site_name}
            )
            
            # Create retrieval chain
            qa_chain = ConversationalRetrievalChain.from_llm(
                llm=self.llm,
                retriever=vector_store.as_retriever(search_kwargs={"k": 3}),
                return_source_documents=True,
                combine_docs_chain_kwargs={"prompt": PROMPT}
            )
            
            # Get response
            result = qa_chain({"question": message, "chat_history": []})
            
            # Extract relevant chunks
            sources = [doc.page_content[:100] + "..." for doc in result.get("source_documents", [])]
            
            # Count tokens
            response_text = result["answer"]
            prompt_tokens = self.count_tokens(message + site_info[:1000])
            completion_tokens = self.count_tokens(response_text)
            total_tokens = prompt_tokens + completion_tokens
            
            # Estimate cost
            cost_estimate = (prompt_tokens * 0.0015 + completion_tokens * 0.002) / 1000
            
            return {
                "response": response_text,
                "confidence": 0.95,
                "sources": sources,
                "tokens_used": {
                    "prompt": prompt_tokens,
                    "completion": completion_tokens,
                    "total": total_tokens
                },
                "cost_estimate": cost_estimate,
                "model": settings.OPENAI_MODEL
            }
            
        except Exception as e:
            logger.error(f"Error in RAG response: {str(e)}")
            # Fallback to simple generation
            return self.generate_response(message, site_info, site_name, site_url)
    
    def count_tokens(self, text: str) -> int:
        """Count tokens in text"""
        if not self.encoder:
            # Rough estimate if encoder not available
            return len(text) // 4
        try:
            return len(self.encoder.encode(text))
        except:
            # Rough estimate if encoding fails
            return len(text) // 4
