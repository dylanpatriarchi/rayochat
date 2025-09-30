<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            font-size: 2rem;
            font-weight: bold;
        }
        .logo-rayo {
            color: #0A0A0A;
        }
        .logo-chat {
            color: #FF6B35;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }
        .otp-code {
            font-size: 2.5rem;
            font-weight: bold;
            color: #FF6B35;
            letter-spacing: 0.5rem;
            margin: 20px 0;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">
                <span class="logo-rayo">Rayo</span><span class="logo-chat">Chat</span>
            </div>
        </div>
        
        <div class="content">
            <h2>Ciao {{ $userName }}!</h2>
            <p>Il tuo codice di accesso a RayoChat è:</p>
            
            <div class="otp-code">{{ $code }}</div>
            
            <p>Questo codice è valido per 10 minuti.</p>
            <p>Se non hai richiesto questo codice, ignora questa email.</p>
        </div>
        
        <div class="footer">
            <p>© {{ date('Y') }} RayoChat. Tutti i diritti riservati.</p>
        </div>
    </div>
</body>
</html>
