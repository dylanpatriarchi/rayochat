/**
 * RayoChat Widget - WordPress Integration
 * This script loads and initializes the RayoChat widget
 */

(function() {
    'use strict';
    
    // Wait for DOM to be ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWidget);
    } else {
        initWidget();
    }
    
    function initWidget() {
        // Get settings from localized script
        const settings = window.rayochatSettings || {};
        
        if (!settings.apiKey) {
            console.error('RayoChat: API key not configured');
            return;
        }
        
        // Create widget HTML
        const widgetHTML = createWidgetHTML(settings);
        
        // Insert widget into page
        const container = document.getElementById('rayochat-widget-root');
        if (container) {
            container.innerHTML = widgetHTML;
            attachEventListeners(settings);
        }
    }
    
    function createWidgetHTML(settings) {
        return `
            <style>
                :root {
                    --rayochat-primary: ${settings.primaryColor};
                }
            </style>
            <div class="rayochat-widget ${settings.position}" id="rayochat-widget">
                <!-- Chat button -->
                <button class="rayochat-button" id="rayochat-open-btn" aria-label="Apri chat">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                    </svg>
                </button>
                
                <!-- Chat window (hidden by default) -->
                <div class="rayochat-window" id="rayochat-window" style="display: none;">
                    <div class="rayochat-header">
                        <div class="rayochat-header-content">
                            <div class="rayochat-header-title">
                                <strong>RayoChat</strong>
                                <span class="rayochat-status">Online</span>
                            </div>
                        </div>
                        <button class="rayochat-close" id="rayochat-close-btn" aria-label="Chiudi chat">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <path d="M15 5L5 15M5 5l10 10" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="rayochat-messages" id="rayochat-messages">
                        <div class="rayochat-message bot">
                            <div class="rayochat-message-content">
                                Ciao! 👋 Come posso aiutarti oggi?
                            </div>
                        </div>
                    </div>
                    
                    <div class="rayochat-input-container">
                        <input type="text" 
                               class="rayochat-input" 
                               id="rayochat-input"
                               placeholder="Scrivi un messaggio..."
                               autocomplete="off">
                        <button class="rayochat-send" id="rayochat-send-btn" aria-label="Invia messaggio">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2 3l18 7-18 7V3zm2 11.5V10h10v-2H4V3.5L16.5 10 4 14.5z" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="rayochat-footer">
                        <span>Powered by <strong>RayoChat</strong></span>
                    </div>
                </div>
            </div>
        `;
    }
    
    function attachEventListeners(settings) {
        const openBtn = document.getElementById('rayochat-open-btn');
        const closeBtn = document.getElementById('rayochat-close-btn');
        const sendBtn = document.getElementById('rayochat-send-btn');
        const input = document.getElementById('rayochat-input');
        const window = document.getElementById('rayochat-window');
        
        let conversationId = null;
        let isLoading = false;
        
        // Open chat
        openBtn.addEventListener('click', () => {
            window.style.display = 'flex';
            openBtn.style.display = 'none';
            input.focus();
        });
        
        // Close chat
        closeBtn.addEventListener('click', () => {
            window.style.display = 'none';
            openBtn.style.display = 'flex';
        });
        
        // Send message on Enter
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Send message on button click
        sendBtn.addEventListener('click', sendMessage);
        
        function sendMessage() {
            const message = input.value.trim();
            if (!message || isLoading) return;
            
            // Add user message to chat
            addMessage(message, 'user');
            input.value = '';
            isLoading = true;
            sendBtn.disabled = true;
            
            // Show typing indicator
            showTypingIndicator();
            
            // Send to API
            fetch(settings.apiUrl + '/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    api_key: settings.apiKey,
                    question: message,
                    conversation_id: conversationId
                })
            })
            .then(response => response.json())
            .then(data => {
                removeTypingIndicator();
                addMessage(data.answer, 'bot', data.sources);
                conversationId = data.conversation_id;
            })
            .catch(error => {
                console.error('RayoChat Error:', error);
                removeTypingIndicator();
                addMessage('Mi dispiace, si è verificato un errore. Riprova più tardi.', 'bot');
            })
            .finally(() => {
                isLoading = false;
                sendBtn.disabled = false;
            });
        }
        
        function addMessage(text, sender, sources = []) {
            const messagesContainer = document.getElementById('rayochat-messages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `rayochat-message ${sender}`;
            
            let sourcesHTML = '';
            if (sources && sources.length > 0) {
                sourcesHTML = `<div class="rayochat-sources"><small>📄 Fonti: ${sources.join(', ')}</small></div>`;
            }
            
            messageDiv.innerHTML = `
                <div class="rayochat-message-content">
                    ${escapeHtml(text)}
                    ${sourcesHTML}
                </div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            scrollToBottom();
        }
        
        function showTypingIndicator() {
            const messagesContainer = document.getElementById('rayochat-messages');
            const typingDiv = document.createElement('div');
            typingDiv.className = 'rayochat-message bot';
            typingDiv.id = 'rayochat-typing';
            typingDiv.innerHTML = `
                <div class="rayochat-message-content">
                    <div class="rayochat-typing">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;
            messagesContainer.appendChild(typingDiv);
            scrollToBottom();
        }
        
        function removeTypingIndicator() {
            const typingDiv = document.getElementById('rayochat-typing');
            if (typingDiv) {
                typingDiv.remove();
            }
        }
        
        function scrollToBottom() {
            const messagesContainer = document.getElementById('rayochat-messages');
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    }
})();
