/**
 * RayoChat Shopify Widget
 * Intelligent AI chat widget for Shopify stores
 * Version: 1.0.0
 */

(function() {
    'use strict';
    
    // Configuration (replaced by server)
    const SHOP = '{{SHOP}}';
    const CONFIG = {{CONFIG}};
    
    // Widget state
    let isOpen = false;
    let isLoading = false;
    let sessionId = null;
    let conversationId = null;
    
    // DOM elements
    let widget, toggle, window, messages, input, sendBtn, typing;
    
    /**
     * Initialize the widget
     */
    function initWidget() {
        // Generate session ID
        sessionId = generateSessionId();
        
        // Create widget HTML
        createWidgetHTML();
        
        // Get DOM elements
        widget = document.getElementById('rayochat-widget');
        toggle = document.getElementById('rayochat-toggle');
        window = document.getElementById('rayochat-window');
        messages = document.getElementById('rayochat-messages');
        input = document.getElementById('rayochat-input');
        sendBtn = document.getElementById('rayochat-send');
        typing = document.getElementById('rayochat-typing');
        
        // Bind events
        bindEvents();
        
        // Start conversation
        startConversation();
        
        // Track widget load
        trackEvent('widget_loaded');
        
        console.log('RayoChat Widget initialized for shop:', SHOP);
    }
    
    /**
     * Create widget HTML
     */
    function createWidgetHTML() {
        const widgetHTML = `
            <div id="rayochat-widget" class="rayochat-widget-${CONFIG.position}" style="position: fixed; z-index: 999999; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                <!-- Chat Toggle Button -->
                <div id="rayochat-toggle" class="rayochat-toggle" style="
                    width: 60px; height: 60px; border-radius: 50%; 
                    background-color: ${CONFIG.color}; cursor: pointer; 
                    display: flex; align-items: center; justify-content: center;
                    box-shadow: 0 4px 20px rgba(0,0,0,0.15); 
                    transition: all 0.3s cubic-bezier(0.25,0.46,0.45,0.94);
                    border: none; position: relative; overflow: hidden;
                ">
                    <svg class="rayochat-icon-chat" viewBox="0 0 24 24" width="24" height="24">
                        <path fill="white" d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4A2,2 0 0,0 20,2M6,9V7H18V9H6M14,11V13H6V11H14M18,15H6V17H18V15Z"/>
                    </svg>
                    <svg class="rayochat-icon-close" viewBox="0 0 24 24" width="24" height="24" style="display: none;">
                        <path fill="white" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                    </svg>
                </div>
                
                <!-- Chat Window -->
                <div id="rayochat-window" class="rayochat-window" style="
                    position: absolute; bottom: 80px; width: 380px; height: 500px;
                    background: #ffffff; border-radius: 16px; 
                    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
                    display: none; flex-direction: column; overflow: hidden;
                    transform: scale(0.8) translateY(20px); opacity: 0;
                    transition: all 0.3s cubic-bezier(0.25,0.46,0.45,0.94);
                ">
                    <!-- Chat Header -->
                    <div class="rayochat-header" style="
                        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                        color: white; padding: 16px 20px; display: flex;
                        align-items: center; justify-content: space-between;
                        border-radius: 16px 16px 0 0;
                    ">
                        <div class="rayochat-header-info" style="display: flex; align-items: center; gap: 12px;">
                            <div class="rayochat-avatar" style="
                                width: 40px; height: 40px; border-radius: 50%;
                                background: rgba(255,255,255,0.2); display: flex;
                                align-items: center; justify-content: center;
                            ">
                                <svg viewBox="0 0 24 24" width="32" height="32">
                                    <path fill="white" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11A1.5,1.5 0 0,1 10.5,9.5A1.5,1.5 0 0,1 12,8A1.5,1.5 0 0,1 13.5,9.5A1.5,1.5 0 0,1 12,11Z"/>
                                </svg>
                            </div>
                            <div class="rayochat-header-text">
                                <div class="rayochat-title" style="font-weight: 600; font-size: 16px; margin: 0;">${CONFIG.title}</div>
                                <div class="rayochat-status" style="font-size: 12px; opacity: 0.9; margin: 0;">Online</div>
                            </div>
                        </div>
                        <button id="rayochat-minimize" class="rayochat-minimize" style="
                            background: none; border: none; color: white; cursor: pointer;
                            padding: 8px; border-radius: 50%; transition: background-color 0.2s ease;
                            display: flex; align-items: center; justify-content: center;
                        ">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path fill="currentColor" d="M19,13H5V11H19V13Z"/>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Chat Messages -->
                    <div id="rayochat-messages" class="rayochat-messages" style="
                        flex: 1; padding: 20px; overflow-y: auto; background: #f8f9fa;
                        display: flex; flex-direction: column; gap: 12px; scroll-behavior: smooth;
                    ">
                        <div class="rayochat-message rayochat-message-bot" style="
                            display: flex; flex-direction: column; max-width: 85%;
                            align-self: flex-start; align-items: flex-start;
                        ">
                            <div class="rayochat-message-content" style="
                                padding: 12px 16px; border-radius: 18px; font-size: 14px;
                                line-height: 1.4; word-wrap: break-word; position: relative;
                                background: white; color: #2d3748; border: 1px solid #e2e8f0;
                                border-bottom-left-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
                            ">${CONFIG.welcomeMessage}</div>
                            <div class="rayochat-message-time" style="
                                font-size: 11px; color: #a0aec0; margin-top: 4px; padding: 0 4px;
                            ">${getCurrentTime()}</div>
                        </div>
                    </div>
                    
                    <!-- Chat Input -->
                    <div class="rayochat-input-container" style="
                        padding: 16px 20px; background: white; border-top: 1px solid #e2e8f0;
                    ">
                        <div class="rayochat-input-wrapper" style="
                            display: flex; align-items: center; gap: 12px;
                            background: #f7fafc; border-radius: 24px; padding: 8px 16px;
                            border: 1px solid #e2e8f0; transition: border-color 0.2s ease;
                        ">
                            <input type="text" id="rayochat-input" placeholder="Type your message..." maxlength="1000" style="
                                flex: 1; border: none; background: none; outline: none;
                                font-size: 14px; color: #2d3748; padding: 8px 0;
                            ">
                            <button id="rayochat-send" class="rayochat-send-btn" style="
                                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                                border: none; border-radius: 50%; width: 36px; height: 36px;
                                display: flex; align-items: center; justify-content: center;
                                cursor: pointer; transition: all 0.2s ease; color: white;
                            ">
                                <svg viewBox="0 0 24 24" width="20" height="20">
                                    <path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Typing Indicator -->
                    <div id="rayochat-typing" class="rayochat-typing" style="
                        padding: 0 20px 10px; background: #f8f9fa; display: none;
                    ">
                        <div class="rayochat-typing-content" style="
                            display: flex; align-items: center; gap: 8px;
                            padding: 8px 12px; background: white; border-radius: 18px;
                            border: 1px solid #e2e8f0; max-width: fit-content;
                        ">
                            <div class="rayochat-typing-dots" style="display: flex; gap: 3px;">
                                <span style="
                                    width: 6px; height: 6px; border-radius: 50%; background: #cbd5e0;
                                    animation: rayochatTypingDots 1.4s infinite ease-in-out;
                                    animation-delay: -0.32s;
                                "></span>
                                <span style="
                                    width: 6px; height: 6px; border-radius: 50%; background: #cbd5e0;
                                    animation: rayochatTypingDots 1.4s infinite ease-in-out;
                                    animation-delay: -0.16s;
                                "></span>
                                <span style="
                                    width: 6px; height: 6px; border-radius: 50%; background: #cbd5e0;
                                    animation: rayochatTypingDots 1.4s infinite ease-in-out;
                                "></span>
                            </div>
                            <span class="rayochat-typing-text" style="font-size: 12px; color: #718096;">
                                AI is typing...
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Add CSS animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes rayochatTypingDots {
                0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
                40% { transform: scale(1); opacity: 1; }
            }
            .rayochat-widget-bottom-right { bottom: 20px; right: 20px; }
            .rayochat-widget-bottom-left { bottom: 20px; left: 20px; }
            .rayochat-widget-bottom-left .rayochat-window { right: auto; left: 0; }
            .rayochat-window-open { transform: scale(1) translateY(0) !important; opacity: 1 !important; }
            .rayochat-toggle:hover { transform: scale(1.1); box-shadow: 0 6px 25px rgba(0,0,0,0.2); }
            .rayochat-minimize:hover { background: rgba(255,255,255,0.1) !important; }
            .rayochat-send-btn:hover { transform: scale(1.05); box-shadow: 0 2px 8px rgba(102,126,234,0.3); }
            .rayochat-send-btn:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }
            .rayochat-input-wrapper:focus-within { border-color: #667eea; box-shadow: 0 0 0 3px rgba(102,126,234,0.1); }
            .rayochat-messages::-webkit-scrollbar { width: 4px; }
            .rayochat-messages::-webkit-scrollbar-track { background: transparent; }
            .rayochat-messages::-webkit-scrollbar-thumb { background: #cbd5e0; border-radius: 2px; }
            @media (max-width: 480px) {
                .rayochat-widget-bottom-right, .rayochat-widget-bottom-left { 
                    bottom: 10px; right: 10px; left: 10px; 
                }
                .rayochat-window { 
                    width: calc(100vw - 20px); height: 70vh; max-height: 500px;
                    bottom: 80px; right: 0; left: 0; 
                }
                .rayochat-toggle { 
                    width: 56px; height: 56px; position: fixed; bottom: 20px; right: 20px; 
                }
            }
        `;
        document.head.appendChild(style);
        
        // Add widget to page
        document.body.insertAdjacentHTML('beforeend', widgetHTML);
    }
    
    /**
     * Bind event listeners
     */
    function bindEvents() {
        // Toggle chat
        toggle.addEventListener('click', toggleChat);
        document.getElementById('rayochat-minimize').addEventListener('click', closeChat);
        
        // Send message
        sendBtn.addEventListener('click', sendMessage);
        input.addEventListener('keypress', function(e) {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
        
        // Close on outside click
        document.addEventListener('click', function(e) {
            if (isOpen && !widget.contains(e.target)) {
                closeChat();
            }
        });
        
        // Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeChat();
            }
        });
        
        // Prevent window from closing when clicking inside
        window.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    /**
     * Toggle chat window
     */
    function toggleChat() {
        if (isOpen) {
            closeChat();
        } else {
            openChat();
        }
    }
    
    /**
     * Open chat window
     */
    function openChat() {
        isOpen = true;
        window.style.display = 'flex';
        
        setTimeout(() => {
            window.classList.add('rayochat-window-open');
            toggle.querySelector('.rayochat-icon-chat').style.display = 'none';
            toggle.querySelector('.rayochat-icon-close').style.display = 'block';
        }, 10);
        
        setTimeout(() => {
            input.focus();
        }, 300);
        
        scrollToBottom();
        trackEvent('widget_opened');
    }
    
    /**
     * Close chat window
     */
    function closeChat() {
        isOpen = false;
        window.classList.remove('rayochat-window-open');
        toggle.querySelector('.rayochat-icon-chat').style.display = 'block';
        toggle.querySelector('.rayochat-icon-close').style.display = 'none';
        
        setTimeout(() => {
            window.style.display = 'none';
        }, 300);
        
        trackEvent('widget_closed');
    }
    
    /**
     * Send message
     */
    function sendMessage() {
        const message = input.value.trim();
        
        if (!message || isLoading) {
            return;
        }
        
        // Add user message
        addMessage(message, 'user');
        input.value = '';
        
        // Show typing indicator
        showTyping();
        
        // Send to API
        sendToAPI(message);
    }
    
    /**
     * Add message to chat
     */
    function addMessage(content, type, timestamp) {
        const time = timestamp || getCurrentTime();
        const isUser = type === 'user';
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `rayochat-message rayochat-message-${type}`;
        messageDiv.style.cssText = `
            display: flex; flex-direction: column; max-width: 85%;
            align-self: ${isUser ? 'flex-end' : 'flex-start'};
            align-items: ${isUser ? 'flex-end' : 'flex-start'};
            animation: rayochatMessageSlideIn 0.3s ease-out;
        `;
        
        const contentDiv = document.createElement('div');
        contentDiv.className = 'rayochat-message-content';
        contentDiv.textContent = content;
        contentDiv.style.cssText = `
            padding: 12px 16px; border-radius: 18px; font-size: 14px;
            line-height: 1.4; word-wrap: break-word; position: relative;
            ${isUser ? `
                background: linear-gradient(135deg, #007AFF 0%, #5856D6 100%);
                color: white; border-bottom-right-radius: 6px;
            ` : `
                background: white; color: #2d3748; border: 1px solid #e2e8f0;
                border-bottom-left-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            `}
        `;
        
        const timeDiv = document.createElement('div');
        timeDiv.className = 'rayochat-message-time';
        timeDiv.textContent = time;
        timeDiv.style.cssText = `
            font-size: 11px; color: #a0aec0; margin-top: 4px; padding: 0 4px;
        `;
        
        messageDiv.appendChild(contentDiv);
        messageDiv.appendChild(timeDiv);
        messages.appendChild(messageDiv);
        
        scrollToBottom();
    }
    
    /**
     * Send message to API
     */
    async function sendToAPI(message) {
        isLoading = true;
        sendBtn.disabled = true;
        
        try {
            const response = await fetch(`${CONFIG.apiEndpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    message: message,
                    session_id: sessionId,
                    customer_id: getCustomerId()
                })
            });
            
            const result = await response.json();
            
            hideTyping();
            
            if (result.success && result.data) {
                addMessage(result.data.response, 'bot', result.data.timestamp);
                trackEvent('message_received', {
                    response_length: result.data.response.length,
                    response_time: result.data.response_time
                });
            } else {
                const errorMsg = result.error || 'Sorry, I encountered an error. Please try again.';
                addMessage(errorMsg, 'bot');
                trackEvent('message_error', { error: errorMsg });
            }
            
        } catch (error) {
            hideTyping();
            
            let errorMessage = 'Sorry, I\'m having trouble connecting. Please try again.';
            
            if (error.name === 'TypeError' && error.message.includes('fetch')) {
                errorMessage = 'Connection error. Please check your internet connection.';
            }
            
            addMessage(errorMessage, 'bot');
            trackEvent('api_error', { error: error.message });
            
        } finally {
            isLoading = false;
            sendBtn.disabled = false;
            input.focus();
        }
    }
    
    /**
     * Start conversation
     */
    async function startConversation() {
        try {
            const response = await fetch(`${CONFIG.apiEndpoint.replace('/chat/', '/conversation/start/')}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    session_id: sessionId,
                    customer_id: getCustomerId(),
                    page_url: window.location.href
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                conversationId = result.data.conversation_id;
            }
            
        } catch (error) {
            console.warn('Failed to start conversation:', error);
        }
    }
    
    /**
     * Show typing indicator
     */
    function showTyping() {
        typing.style.display = 'block';
        scrollToBottom();
    }
    
    /**
     * Hide typing indicator
     */
    function hideTyping() {
        typing.style.display = 'none';
    }
    
    /**
     * Scroll to bottom
     */
    function scrollToBottom() {
        setTimeout(() => {
            messages.scrollTop = messages.scrollHeight;
        }, 100);
    }
    
    /**
     * Get current time
     */
    function getCurrentTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + 
               now.getMinutes().toString().padStart(2, '0');
    }
    
    /**
     * Generate session ID
     */
    function generateSessionId() {
        return 'sess_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }
    
    /**
     * Get customer ID from Shopify
     */
    function getCustomerId() {
        // Try to get customer ID from Shopify global variables
        if (typeof window.ShopifyAnalytics !== 'undefined' && window.ShopifyAnalytics.meta) {
            return window.ShopifyAnalytics.meta.page?.customerId || null;
        }
        return null;
    }
    
    /**
     * Track events
     */
    function trackEvent(eventType, eventData = {}) {
        // Track to our analytics
        fetch(`${CONFIG.apiEndpoint.replace('/chat/', '/analytics/')}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                event_type: eventType,
                event_data: {
                    ...eventData,
                    session_id: sessionId,
                    page_url: window.location.href,
                    timestamp: new Date().toISOString()
                }
            })
        }).catch(error => {
            console.warn('Analytics tracking failed:', error);
        });
        
        // Track to Google Analytics if available
        if (typeof gtag !== 'undefined') {
            gtag('event', eventType, {
                event_category: 'RayoChat',
                event_label: SHOP,
                ...eventData
            });
        }
        
        // Track to Shopify Analytics if available
        if (typeof window.ShopifyAnalytics !== 'undefined' && window.ShopifyAnalytics.lib) {
            window.ShopifyAnalytics.lib.track('RayoChat ' + eventType, eventData);
        }
    }
    
    // Add message slide-in animation
    const animationStyle = document.createElement('style');
    animationStyle.textContent = `
        @keyframes rayochatMessageSlideIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(animationStyle);
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initWidget);
    } else {
        initWidget();
    }
    
    // Expose global API
    window.RayoChat = {
        open: openChat,
        close: closeChat,
        sendMessage: function(msg) {
            input.value = msg;
            sendMessage();
        },
        isOpen: function() { return isOpen; },
        version: '1.0.0'
    };
    
})();
