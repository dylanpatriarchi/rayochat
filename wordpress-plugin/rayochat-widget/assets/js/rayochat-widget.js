/**
 * RayoChat Widget JavaScript
 * Handles chat functionality and API communication
 */

(function($) {
    'use strict';

    // Widget state
    let isOpen = false;
    let isLoading = false;
    let conversationId = null;

    // DOM elements
    let $widget, $toggle, $window, $messages, $input, $sendBtn, $typing;

    /**
     * Initialize the chat widget
     */
    function initWidget() {
        $widget = $('#rayochat-widget');
        $toggle = $('#rayochat-toggle');
        $window = $('#rayochat-window');
        $messages = $('#rayochat-messages');
        $input = $('#rayochat-input');
        $sendBtn = $('#rayochat-send');
        $typing = $('#rayochat-typing');

        // Bind events
        bindEvents();

        // Generate conversation ID
        conversationId = generateConversationId();

        console.log('RayoChat Widget initialized');
    }

    /**
     * Bind event handlers
     */
    function bindEvents() {
        // Toggle chat window
        $toggle.on('click', toggleChat);
        $('#rayochat-minimize').on('click', closeChat);

        // Send message
        $sendBtn.on('click', sendMessage);
        $input.on('keypress', function(e) {
            if (e.which === 13 && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Input focus/blur effects
        $input.on('focus', function() {
            $widget.addClass('rayochat-focused');
        }).on('blur', function() {
            $widget.removeClass('rayochat-focused');
        });

        // Prevent chat window from closing when clicking inside
        $window.on('click', function(e) {
            e.stopPropagation();
        });

        // Close chat when clicking outside
        $(document).on('click', function(e) {
            if (isOpen && !$widget.is(e.target) && $widget.has(e.target).length === 0) {
                closeChat();
            }
        });

        // Handle escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && isOpen) {
                closeChat();
            }
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
        $window.show();
        
        // Trigger animation
        setTimeout(function() {
            $window.addClass('rayochat-window-open');
            $toggle.find('.rayochat-icon-chat').hide();
            $toggle.find('.rayochat-icon-close').show();
        }, 10);

        // Focus input
        setTimeout(function() {
            $input.focus();
        }, 300);

        // Scroll to bottom
        scrollToBottom();

        // Track event
        trackEvent('chat_opened');
    }

    /**
     * Close chat window
     */
    function closeChat() {
        isOpen = false;
        $window.removeClass('rayochat-window-open');
        $toggle.find('.rayochat-icon-chat').show();
        $toggle.find('.rayochat-icon-close').hide();

        setTimeout(function() {
            $window.hide();
        }, 300);

        // Track event
        trackEvent('chat_closed');
    }

    /**
     * Send message to the chat
     */
    function sendMessage() {
        const message = $input.val().trim();
        
        if (!message || isLoading) {
            return;
        }

        // Add user message to chat
        addMessage(message, 'user');
        
        // Clear input
        $input.val('');
        
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
        const messageClass = type === 'user' ? 'rayochat-message-user' : 'rayochat-message-bot';
        
        const messageHtml = `
            <div class="rayochat-message ${messageClass}">
                <div class="rayochat-message-content">${escapeHtml(content)}</div>
                <div class="rayochat-message-time">${time}</div>
            </div>
        `;
        
        $messages.append(messageHtml);
        scrollToBottom();

        // Track message
        trackEvent('message_sent', { type: type, length: content.length });
    }

    /**
     * Send message to RAG API
     */
    function sendToAPI(message) {
        isLoading = true;
        $sendBtn.prop('disabled', true).addClass('rayochat-loading');

        const data = {
            action: 'rayochat_send_message',
            message: message,
            conversation_id: conversationId,
            nonce: rayochat_ajax.nonce
        };

        $.ajax({
            url: rayochat_ajax.ajax_url,
            type: 'POST',
            data: data,
            timeout: 30000,
            success: function(response) {
                hideTyping();
                
                if (response.success && response.data) {
                    addMessage(response.data.message, 'bot', response.data.timestamp);
                    
                    // Show success animation
                    $widget.addClass('rayochat-success');
                    setTimeout(() => $widget.removeClass('rayochat-success'), 600);
                    
                    // Track successful response
                    trackEvent('message_received', { 
                        response_length: response.data.message.length,
                        has_sources: response.data.sources && response.data.sources.length > 0
                    });
                } else {
                    const errorMsg = response.data || rayochat_ajax.strings.error;
                    addMessage(errorMsg, 'bot');
                    showError();
                    
                    // Track error
                    trackEvent('message_error', { error: errorMsg });
                }
            },
            error: function(xhr, status, error) {
                hideTyping();
                
                let errorMessage = rayochat_ajax.strings.error;
                
                if (status === 'timeout') {
                    errorMessage = 'La richiesta ha impiegato troppo tempo. Riprova.';
                } else if (xhr.status === 0) {
                    errorMessage = 'Problema di connessione. Controlla la tua connessione internet.';
                } else if (xhr.status >= 500) {
                    errorMessage = 'Errore del server. Riprova tra qualche minuto.';
                }
                
                addMessage(errorMessage, 'bot');
                showError();
                
                // Track error
                trackEvent('api_error', { 
                    status: xhr.status, 
                    error: error,
                    message: errorMessage 
                });
                
                console.error('RayoChat API Error:', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: error,
                    response: xhr.responseText
                });
            },
            complete: function() {
                isLoading = false;
                $sendBtn.prop('disabled', false).removeClass('rayochat-loading');
                $input.focus();
            }
        });
    }

    /**
     * Show typing indicator
     */
    function showTyping() {
        $typing.show();
        scrollToBottom();
    }

    /**
     * Hide typing indicator
     */
    function hideTyping() {
        $typing.hide();
    }

    /**
     * Show error state
     */
    function showError() {
        $widget.addClass('rayochat-error');
        setTimeout(() => $widget.removeClass('rayochat-error'), 3000);
    }

    /**
     * Scroll messages to bottom
     */
    function scrollToBottom() {
        setTimeout(function() {
            $messages.scrollTop($messages[0].scrollHeight);
        }, 100);
    }

    /**
     * Get current time formatted
     */
    function getCurrentTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + 
               now.getMinutes().toString().padStart(2, '0');
    }

    /**
     * Generate unique conversation ID
     */
    function generateConversationId() {
        return 'conv_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
    }

    /**
     * Escape HTML to prevent XSS
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Track events for analytics
     */
    function trackEvent(eventName, properties = {}) {
        // Basic event tracking - can be extended with Google Analytics, etc.
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, {
                event_category: 'RayoChat',
                ...properties
            });
        }
        
        // Console log for debugging
        if (window.console && console.log) {
            console.log('RayoChat Event:', eventName, properties);
        }
    }

    /**
     * Handle widget resize for mobile
     */
    function handleResize() {
        if (window.innerWidth <= 480) {
            $widget.addClass('rayochat-mobile');
        } else {
            $widget.removeClass('rayochat-mobile');
        }
    }

    /**
     * Initialize accessibility features
     */
    function initAccessibility() {
        // Add ARIA labels
        $toggle.attr({
            'aria-label': 'Apri chat assistente',
            'role': 'button',
            'tabindex': '0'
        });

        $input.attr({
            'aria-label': 'Scrivi messaggio',
            'autocomplete': 'off'
        });

        $sendBtn.attr({
            'aria-label': 'Invia messaggio',
            'role': 'button'
        });

        // Handle keyboard navigation
        $toggle.on('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleChat();
            }
        });

        // Announce messages to screen readers
        $messages.attr('aria-live', 'polite');
    }

    /**
     * Handle connection status
     */
    function checkConnection() {
        if (navigator.onLine === false) {
            addMessage('Connessione internet non disponibile. I messaggi verranno inviati quando la connessione sarà ripristinata.', 'bot');
            $sendBtn.prop('disabled', true);
        } else {
            $sendBtn.prop('disabled', false);
        }
    }

    /**
     * Initialize offline/online handlers
     */
    function initConnectionHandlers() {
        window.addEventListener('online', function() {
            addMessage('Connessione ripristinata!', 'bot');
            $sendBtn.prop('disabled', false);
            trackEvent('connection_restored');
        });

        window.addEventListener('offline', function() {
            addMessage('Connessione persa. Riprova quando la connessione sarà disponibile.', 'bot');
            $sendBtn.prop('disabled', true);
            trackEvent('connection_lost');
        });
    }

    /**
     * Add welcome message with delay
     */
    function showWelcomeMessage() {
        setTimeout(function() {
            // Welcome message is already in HTML, just scroll to it
            scrollToBottom();
        }, 1000);
    }

    /**
     * Initialize widget when DOM is ready
     */
    $(document).ready(function() {
        // Check if widget exists
        if ($('#rayochat-widget').length === 0) {
            console.warn('RayoChat Widget not found');
            return;
        }

        // Initialize all components
        initWidget();
        initAccessibility();
        initConnectionHandlers();
        handleResize();
        checkConnection();
        showWelcomeMessage();

        // Handle window resize
        $(window).on('resize', handleResize);

        // Add loading class initially
        $widget.addClass('rayochat-loaded');

        console.log('RayoChat Widget fully loaded');
    });

    // Expose some methods globally for debugging
    window.RayoChat = {
        open: openChat,
        close: closeChat,
        sendMessage: function(msg) {
            $input.val(msg);
            sendMessage();
        },
        addMessage: addMessage,
        isOpen: function() { return isOpen; },
        version: '1.0.0'
    };

})(jQuery);
