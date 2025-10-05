<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'RayoChat')</title>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #374151;
            background-color: #f9fafb;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .logo {
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 8px;
            text-decoration: none;
        }

        .tagline {
            color: #fed7aa;
            font-size: 14px;
            font-weight: 500;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
        }

        .greeting {
            font-size: 24px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }

        .message {
            font-size: 16px;
            line-height: 1.7;
            color: #374151;
            margin-bottom: 30px;
        }

        .message p {
            margin-bottom: 16px;
        }

        .message p:last-child {
            margin-bottom: 0;
        }

        /* Button */
        .btn-container {
            text-align: center;
            margin: 30px 0;
        }

        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px -1px rgba(249, 115, 22, 0.3);
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(249, 115, 22, 0.4);
        }

        /* Info box */
        .info-box {
            background-color: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 8px 8px 0;
        }

        .info-box h4 {
            color: #ea580c;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .info-box p {
            color: #9a3412;
            font-size: 14px;
            margin: 0;
        }

        /* Code/OTP display */
        .code-display {
            background-color: #f3f4f6;
            border: 2px dashed #d1d5db;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin: 20px 0;
        }

        .code {
            font-family: 'Courier New', monospace;
            font-size: 32px;
            font-weight: bold;
            color: #ea580c;
            letter-spacing: 4px;
            margin: 10px 0;
        }

        .code-label {
            font-size: 14px;
            color: #6b7280;
            font-weight: 500;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .footer-text {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 15px;
        }

        .footer-links {
            margin-bottom: 20px;
        }

        .footer-links a {
            color: #f97316;
            text-decoration: none;
            font-size: 14px;
            margin: 0 15px;
            font-weight: 500;
        }

        .footer-links a:hover {
            color: #ea580c;
            text-decoration: underline;
        }

        .copyright {
            font-size: 12px;
            color: #9ca3af;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                box-shadow: none;
            }

            .email-header,
            .email-content,
            .email-footer {
                padding: 25px 20px;
            }

            .greeting {
                font-size: 20px;
            }

            .message {
                font-size: 15px;
            }

            .btn {
                padding: 12px 24px;
                font-size: 15px;
            }

            .code {
                font-size: 28px;
                letter-spacing: 2px;
            }

            .footer-links a {
                display: block;
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">RayoChat</div>
            <div class="tagline">La tua piattaforma di chat intelligente</div>
        </div>

        <!-- Content -->
        <div class="email-content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-text">
                Grazie per aver scelto RayoChat per le tue esigenze di comunicazione.
            </div>
            
            <div class="footer-links">
                <a href="https://rayochat.com">Sito Web</a>
                <a href="https://rayochat.com/supporto">Supporto</a>
                <a href="https://rayochat.com/privacy">Privacy</a>
            </div>
            
            <div class="copyright">
                © {{ date('Y') }} RayoChat. Tutti i diritti riservati.
            </div>
        </div>
    </div>
</body>
</html>
