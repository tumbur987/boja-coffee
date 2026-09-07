<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Masuk &mdash; {{ config('app.name', 'SiBoja Coffee') }}</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style>
            :root {
                --dark: #1a0e0a;
                --coffee: #4a2c2a;
                --coffee-light: #6b4226;
                --cream: #d4a574;
                --cream-light: #f5e6d3;
                --cream-lighter: #faf3eb;
                --bg: #fdfbf9;
                --white: #ffffff;
                --text: #2c1810;
                --text-light: #8b7355;
                --error: #dc2626;
            }
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
                background: linear-gradient(135deg, var(--dark) 0%, #2c1810 40%, var(--coffee) 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px;
                position: relative;
                overflow: hidden;
            }
            body::before {
                content: '';
                position: absolute;
                top: -50%;
                right: -20%;
                width: 800px;
                height: 800px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(212,165,116,0.08) 0%, transparent 70%);
            }
            body::after {
                content: '';
                position: absolute;
                bottom: -30%;
                left: -10%;
                width: 600px;
                height: 600px;
                border-radius: 50%;
                background: radial-gradient(circle, rgba(212,165,116,0.05) 0%, transparent 70%);
            }
            .login-wrapper {
                width: 100%;
                max-width: 420px;
                position: relative;
                z-index: 2;
            }
            .login-brand {
                text-align: center;
                margin-bottom: 32px;
            }
            .login-brand .logo-icon {
                width: 64px;
                height: 64px;
                background: linear-gradient(135deg, var(--cream), var(--cream-light));
                border-radius: 20px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                font-size: 32px;
                margin-bottom: 16px;
                box-shadow: 0 8px 32px rgba(212,165,116,0.3);
            }
            .login-brand h1 {
                font-family: 'Playfair Display', serif;
                font-size: 28px;
                font-weight: 800;
                color: white;
                letter-spacing: -0.5px;
            }
            .login-brand p {
                color: rgba(255,255,255,0.5);
                font-size: 14px;
                margin-top: 4px;
            }
            .login-card {
                background: rgba(255,255,255,0.95);
                backdrop-filter: blur(20px);
                border-radius: 24px;
                padding: 40px 36px;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
                border: 1px solid rgba(255,255,255,0.1);
            }
            .login-card h2 {
                font-family: 'Playfair Display', serif;
                font-size: 22px;
                font-weight: 700;
                color: var(--dark);
                margin-bottom: 4px;
            }
            .login-card .subtitle {
                color: var(--text-light);
                font-size: 14px;
                margin-bottom: 28px;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                display: block;
                font-size: 13px;
                font-weight: 600;
                color: var(--text);
                margin-bottom: 8px;
            }
            .form-group input[type="email"],
            .form-group input[type="password"] {
                width: 100%;
                padding: 14px 16px;
                border: 2px solid var(--cream-light);
                border-radius: 12px;
                font-size: 15px;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: var(--text);
                background: var(--cream-lighter);
                transition: all 0.2s;
                outline: none;
            }
            .form-group input:focus {
                border-color: var(--cream);
                background: var(--white);
                box-shadow: 0 0 0 4px rgba(212,165,116,0.15);
            }
            .form-group input::placeholder {
                color: var(--text-light);
                opacity: 0.6;
            }
            .form-group .error-text {
                color: var(--error);
                font-size: 13px;
                margin-top: 6px;
                display: block;
            }
            .remember-row {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 24px;
            }
            .remember-row label {
                display: flex;
                align-items: center;
                gap: 8px;
                font-size: 14px;
                color: var(--text-light);
                cursor: pointer;
            }
            .remember-row input[type="checkbox"] {
                width: 18px;
                height: 18px;
                border-radius: 6px;
                border: 2px solid var(--cream-light);
                accent-color: var(--coffee);
                cursor: pointer;
            }
            .forgot-link {
                font-size: 14px;
                color: var(--coffee);
                text-decoration: none;
                font-weight: 600;
                transition: color 0.2s;
            }
            .forgot-link:hover {
                color: var(--coffee-light);
            }
            .btn-login {
                width: 100%;
                padding: 16px;
                background: linear-gradient(135deg, var(--coffee), var(--coffee-light));
                color: white;
                border: none;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 700;
                font-family: 'Plus Jakarta Sans', sans-serif;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 8px;
            }
            .btn-login:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 24px rgba(74,44,42,0.4);
            }
            .btn-login:active {
                transform: translateY(0);
            }
            .status-message {
                background: rgba(34,197,94,0.1);
                border: 1px solid rgba(34,197,94,0.3);
                color: #16a34a;
                padding: 12px 16px;
                border-radius: 12px;
                font-size: 14px;
                margin-bottom: 20px;
                display: flex;
                align-items: center;
                gap: 8px;
            }
            .back-home {
                text-align: center;
                margin-top: 24px;
            }
            .back-home a {
                color: rgba(255,255,255,0.5);
                text-decoration: none;
                font-size: 13px;
                transition: color 0.2s;
                display: inline-flex;
                align-items: center;
                gap: 6px;
            }
            .back-home a:hover {
                color: var(--cream);
            }
            .footer-text {
                text-align: center;
                margin-top: 16px;
                color: rgba(255,255,255,0.3);
                font-size: 12px;
            }
        </style>
    </head>
    <body>
        <div class="login-wrapper">
            <div class="login-brand">
                <div class="logo-icon">&#9749;</div>
                <h1>SiBoja Coffee</h1>
                <p>Sistem Manajemen Coffee Shop</p>
            </div>
            <div class="login-card">
                {{ $slot }}
            </div>
            <div class="back-home">
                <a href="/">&larr; Kembali ke Beranda</a>
            </div>
            <div class="footer-text">&copy; {{ date('Y') }} SiBoja Coffee</div>
        </div>
    </body>
</html>
