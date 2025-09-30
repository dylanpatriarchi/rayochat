import React, { useState, useEffect, useRef } from 'react';
import './styles.css';

interface Message {
  id: string;
  text: string;
  sender: 'user' | 'bot';
  timestamp: Date;
  sources?: string[];
}

interface RayoChatWidgetProps {
  apiKey: string;
  apiUrl?: string;
  position?: 'bottom-right' | 'bottom-left';
  primaryColor?: string;
}

const RayoChatWidget: React.FC<RayoChatWidgetProps> = ({
  apiKey,
  apiUrl = 'https://yourdomain.com/api/widget',
  position = 'bottom-right',
  primaryColor = '#FF6B35',
}) => {
  const [isOpen, setIsOpen] = useState(false);
  const [messages, setMessages] = useState<Message[]>([]);
  const [inputValue, setInputValue] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [conversationId, setConversationId] = useState<string | null>(null);
  const [showRating, setShowRating] = useState(false);
  const [hasRated, setHasRated] = useState(false);
  const messagesEndRef = useRef<HTMLDivElement>(null);

  useEffect(() => {
    if (isOpen && messages.length === 0) {
      // Welcome message
      setMessages([
        {
          id: '1',
          text: 'Ciao! 👋 Come posso aiutarti oggi?',
          sender: 'bot',
          timestamp: new Date(),
        },
      ]);
    }
  }, [isOpen]);

  useEffect(() => {
    scrollToBottom();
  }, [messages]);

  const scrollToBottom = () => {
    messagesEndRef.current?.scrollIntoView({ behavior: 'smooth' });
  };

  const sendMessage = async () => {
    if (!inputValue.trim() || isLoading) return;

    const userMessage: Message = {
      id: Date.now().toString(),
      text: inputValue,
      sender: 'user',
      timestamp: new Date(),
    };

    setMessages((prev) => [...prev, userMessage]);
    setInputValue('');
    setIsLoading(true);

    try {
      const response = await fetch(`${apiUrl}/chat`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          api_key: apiKey,
          question: inputValue,
          conversation_id: conversationId,
        }),
      });

      if (!response.ok) {
        throw new Error('Failed to send message');
      }

      const data = await response.json();

      const botMessage: Message = {
        id: (Date.now() + 1).toString(),
        text: data.answer,
        sender: 'bot',
        timestamp: new Date(),
        sources: data.sources,
      };

      setMessages((prev) => [...prev, botMessage]);
      setConversationId(data.conversation_id);
      
      // Show rating after 2 messages
      if (messages.length >= 3 && !hasRated) {
        setShowRating(true);
      }
    } catch (error) {
      console.error('Error sending message:', error);
      const errorMessage: Message = {
        id: (Date.now() + 1).toString(),
        text: 'Mi dispiace, si è verificato un errore. Riprova più tardi.',
        sender: 'bot',
        timestamp: new Date(),
      };
      setMessages((prev) => [...prev, errorMessage]);
    } finally {
      setIsLoading(false);
    }
  };

  const handleKeyPress = (e: React.KeyboardEvent) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMessage();
    }
  };

  const submitRating = async (rating: number) => {
    if (!conversationId) return;

    try {
      await fetch(`${apiUrl}/rate`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          conversation_id: conversationId,
          rating,
        }),
      });

      setShowRating(false);
      setHasRated(true);
      
      // Add thank you message
      const thankYouMessage: Message = {
        id: Date.now().toString(),
        text: 'Grazie per la tua valutazione! 🙏',
        sender: 'bot',
        timestamp: new Date(),
      };
      setMessages((prev) => [...prev, thankYouMessage]);
    } catch (error) {
      console.error('Error submitting rating:', error);
    }
  };

  return (
    <>
      <style>{`
        :root {
          --rayochat-primary: ${primaryColor};
        }
      `}</style>
      
      <div className={`rayochat-widget ${position}`}>
        {/* Chat Button */}
        {!isOpen && (
          <button
            className="rayochat-button"
            onClick={() => setIsOpen(true)}
            aria-label="Apri chat"
          >
            <svg
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
              stroke="currentColor"
              strokeWidth="2"
            >
              <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
            </svg>
          </button>
        )}

        {/* Chat Window */}
        {isOpen && (
          <div className="rayochat-window">
            {/* Header */}
            <div className="rayochat-header">
              <div className="rayochat-header-content">
                <div className="rayochat-header-title">
                  <strong>RayoChat</strong>
                  <span className="rayochat-status">Online</span>
                </div>
              </div>
              <button
                className="rayochat-close"
                onClick={() => setIsOpen(false)}
                aria-label="Chiudi chat"
              >
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                  <path
                    d="M15 5L5 15M5 5l10 10"
                    stroke="currentColor"
                    strokeWidth="2"
                    strokeLinecap="round"
                  />
                </svg>
              </button>
            </div>

            {/* Messages */}
            <div className="rayochat-messages">
              {messages.map((message) => (
                <div
                  key={message.id}
                  className={`rayochat-message ${message.sender}`}
                >
                  <div className="rayochat-message-content">
                    {message.text}
                    {message.sources && message.sources.length > 0 && (
                      <div className="rayochat-sources">
                        <small>📄 Fonti: {message.sources.join(', ')}</small>
                      </div>
                    )}
                  </div>
                </div>
              ))}

              {isLoading && (
                <div className="rayochat-message bot">
                  <div className="rayochat-message-content">
                    <div className="rayochat-typing">
                      <span></span>
                      <span></span>
                      <span></span>
                    </div>
                  </div>
                </div>
              )}

              {showRating && (
                <div className="rayochat-rating">
                  <p>Come valuti questa conversazione?</p>
                  <div className="rayochat-rating-stars">
                    {[1, 2, 3, 4, 5].map((star) => (
                      <button
                        key={star}
                        onClick={() => submitRating(star)}
                        className="rayochat-star"
                      >
                        ⭐
                      </button>
                    ))}
                  </div>
                </div>
              )}

              <div ref={messagesEndRef} />
            </div>

            {/* Input */}
            <div className="rayochat-input-container">
              <input
                type="text"
                className="rayochat-input"
                placeholder="Scrivi un messaggio..."
                value={inputValue}
                onChange={(e) => setInputValue(e.target.value)}
                onKeyPress={handleKeyPress}
                disabled={isLoading}
              />
              <button
                className="rayochat-send"
                onClick={sendMessage}
                disabled={isLoading || !inputValue.trim()}
                aria-label="Invia messaggio"
              >
                <svg
                  width="20"
                  height="20"
                  viewBox="0 0 20 20"
                  fill="currentColor"
                >
                  <path d="M2 3l18 7-18 7V3zm2 11.5V10h10v-2H4V3.5L16.5 10 4 14.5z" />
                </svg>
              </button>
            </div>

            {/* Footer */}
            <div className="rayochat-footer">
              <span>Powered by <strong>RayoChat</strong></span>
            </div>
          </div>
        )}
      </div>
    </>
  );
};

export default RayoChatWidget;
