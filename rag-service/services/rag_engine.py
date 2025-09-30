"""
RAG Engine using LangChain and OpenAI
"""

from typing import List, Dict, Any, Optional
from langchain_openai import OpenAIEmbeddings, ChatOpenAI
from langchain.text_splitter import RecursiveCharacterTextSplitter
from langchain_community.vectorstores.pgvector import PGVector
from langchain.chains import ConversationalRetrievalChain
from langchain.memory import ConversationBufferMemory
from langchain.prompts import PromptTemplate
from langchain_community.document_loaders import PyPDFLoader, UnstructuredMarkdownLoader
import os

from config import get_settings

settings = get_settings()


class RAGEngine:
    """RAG Engine for document processing and question answering"""
    
    def __init__(self):
        self.embeddings = OpenAIEmbeddings(
            model=settings.OPENAI_EMBEDDING_MODEL,
            openai_api_key=settings.OPENAI_API_KEY
        )
        
        self.llm = ChatOpenAI(
            model=settings.OPENAI_MODEL,
            temperature=settings.OPENAI_TEMPERATURE,
            max_tokens=settings.OPENAI_MAX_TOKENS,
            openai_api_key=settings.OPENAI_API_KEY
        )
        
        self.text_splitter = RecursiveCharacterTextSplitter(
            chunk_size=settings.CHUNK_SIZE,
            chunk_overlap=settings.CHUNK_OVERLAP,
            length_function=len,
        )
        
        # Prompt template in Italian
        self.prompt_template = PromptTemplate(
            template="""Sei un assistente AI per il customer care di un'azienda. 
Rispondi alle domande dei clienti in modo professionale, cortese e accurato basandoti esclusivamente sulle informazioni fornite.

Contesto:
{context}

Storico conversazione:
{chat_history}

Domanda del cliente: {question}

Istruzioni:
- Rispondi SOLO se hai informazioni rilevanti nel contesto fornito
- Se non hai informazioni sufficienti, dillo chiaramente e cortesemente
- Sii conciso ma completo
- Usa un tono professionale ma amichevole
- Formatta la risposta in modo chiaro
- Se ci sono passaggi o liste, usa elenchi puntati

Risposta:""",
            input_variables=["context", "chat_history", "question"]
        )
    
    def _get_vector_store(self, company_id: int) -> PGVector:
        """
        Get or create vector store for a company
        
        Args:
            company_id: Company ID
        
        Returns:
            PGVector store instance
        """
        collection_name = f"company_{company_id}"
        
        vector_store = PGVector(
            collection_name=collection_name,
            connection_string=settings.DATABASE_URL,
            embedding_function=self.embeddings,
        )
        
        return vector_store
    
    async def index_documents(
        self,
        company_id: int,
        documents: List[Dict[str, Any]]
    ) -> int:
        """
        Index documents for a company
        
        Args:
            company_id: Company ID
            documents: List of documents to process
        
        Returns:
            Number of documents processed
        """
        from services.database import DatabaseService
        db_service = DatabaseService()
        
        processed_count = 0
        vector_store = self._get_vector_store(company_id)
        
        for doc in documents:
            try:
                # Load document based on type
                file_path = doc['file_path']
                
                if doc['file_type'] == 'pdf':
                    loader = PyPDFLoader(file_path)
                elif doc['file_type'] == 'md':
                    loader = UnstructuredMarkdownLoader(file_path)
                else:
                    print(f"Unsupported file type: {doc['file_type']}")
                    continue
                
                # Load and split document
                pages = loader.load()
                chunks = self.text_splitter.split_documents(pages)
                
                # Add metadata
                for chunk in chunks:
                    chunk.metadata.update({
                        "company_id": company_id,
                        "document_id": doc['id'],
                        "filename": doc['filename']
                    })
                
                # Add to vector store
                vector_store.add_documents(chunks)
                
                # Mark as processed
                await db_service.mark_document_processed(doc['id'])
                
                processed_count += 1
                print(f"Successfully indexed: {doc['filename']}")
                
            except Exception as e:
                print(f"Error processing document {doc['filename']}: {str(e)}")
                continue
        
        return processed_count
    
    async def generate_response(
        self,
        question: str,
        company_id: int,
        knowledge_base: List[Dict[str, Any]],
        conversation_id: Optional[str] = None
    ) -> Dict[str, Any]:
        """
        Generate response using RAG
        
        Args:
            question: User question
            company_id: Company ID
            knowledge_base: Company knowledge base
            conversation_id: Optional conversation ID for context
        
        Returns:
            Dict with answer and sources
        """
        try:
            # Get vector store
            vector_store = self._get_vector_store(company_id)
            
            # Create retriever
            retriever = vector_store.as_retriever(
                search_kwargs={
                    "k": settings.TOP_K_RESULTS,
                    "filter": {"company_id": company_id}
                }
            )
            
            # Create conversation memory
            memory = ConversationBufferMemory(
                memory_key="chat_history",
                return_messages=True,
                output_key="answer"
            )
            
            # Create conversational chain
            qa_chain = ConversationalRetrievalChain.from_llm(
                llm=self.llm,
                retriever=retriever,
                memory=memory,
                return_source_documents=True,
                combine_docs_chain_kwargs={"prompt": self.prompt_template}
            )
            
            # Generate response
            result = qa_chain({"question": question})
            
            # Extract sources
            sources = []
            if result.get("source_documents"):
                seen_sources = set()
                for doc in result["source_documents"]:
                    source = doc.metadata.get("filename", "Unknown")
                    if source not in seen_sources:
                        sources.append(source)
                        seen_sources.add(source)
            
            return {
                "answer": result["answer"],
                "sources": sources
            }
            
        except Exception as e:
            print(f"Error generating response: {str(e)}")
            return {
                "answer": "Mi dispiace, si è verificato un errore nel processare la tua richiesta. Riprova più tardi.",
                "sources": []
            }
