<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aqua Heart</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://unpkg.com/@dotlottie/player-component@latest/dist/dotlottie-player.mjs" type="module"></script>

    <style>
        :root {
            --bg: #edf6fb;
            --bg-accent: #dff1f7;
            --panel: #ffffff;
            --panel-soft: rgba(255, 255, 255, 0.72);
            --primary: #0f172a;
            --secondary: #334155;
            --muted: #64748b;
            --line: #dbe7ef;
            --teal: #0f766e;
            --teal-soft: rgba(15, 118, 110, 0.12);
            --sky-soft: rgba(56, 189, 248, 0.12);
            --danger-bg: #fff1f2;
            --danger-text: #be123c;
            --danger-line: #fecdd3;
            --shadow-lg: 0 30px 80px rgba(15, 23, 42, 0.12);
            --shadow-md: 0 18px 40px rgba(15, 23, 42, 0.08);
            --radius-xl: 32px;
            --radius-lg: 24px;
            --radius-md: 18px;
            --transition: 220ms ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html,
        body {
            height: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--primary);
            background:
                radial-gradient(circle at top left, rgba(56, 189, 248, 0.18), transparent 30%),
                radial-gradient(circle at bottom right, rgba(15, 118, 110, 0.12), transparent 28%),
                linear-gradient(180deg, var(--bg) 0%, var(--bg-accent) 100%);
            overflow: hidden;
        }

        .page {
            height: 100vh;
            display: grid;
            grid-template-columns: 1.08fr 0.92fr;
            gap: 18px;
            padding: 18px;
        }

        .visual-panel,
        .form-panel {
            border-radius: var(--radius-xl);
            overflow: hidden;
        }

        .visual-panel {
            position: relative;
            background:
                linear-gradient(155deg, #0f172a 0%, #123250 55%, #0f766e 100%);
            box-shadow: var(--shadow-lg);
            padding: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 0;
        }

        .visual-panel::before {
            content: '';
            position: absolute;
            inset: auto auto -80px -60px;
            width: 260px;
            height: 260px;
            border-radius: 999px;
            background: rgba(125, 211, 252, 0.16);
            filter: blur(8px);
        }

        .visual-panel::after {
            content: '';
            position: absolute;
            inset: -100px -60px auto auto;
            width: 320px;
            height: 320px;
            border-radius: 999px;
            background: rgba(45, 212, 191, 0.16);
            filter: blur(12px);
        }

        .visual-top,
        .visual-main,
        .visual-bottom {
            position: relative;
            z-index: 1;
        }

        .brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
        }

        .brand-icon {
            width: 52px;
            height: 52px;
            border-radius: 18px;
            display: grid;
            place-items: center;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.16);
            color: #ffffff;
        }

        .brand-text small {
            display: block;
            color: rgba(226, 232, 240, 0.7);
            text-transform: uppercase;
            letter-spacing: 0.16em;
            font-size: 0.72rem;
            margin-bottom: 4px;
        }

        .brand-text strong {
            color: #ffffff;
            font-size: 1.08rem;
            font-weight: 800;
            letter-spacing: -0.03em;
        }

        .visual-main {
            display: grid;
            align-content: center;
            justify-items: center;
            gap: 12px;
            padding: 6px 0;
            text-align: center;
        }

        .visual-kicker {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 12px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.14);
            color: rgba(240, 249, 255, 0.9);
            font-size: 0.76rem;
            font-weight: 700;
        }

        .visual-main h1 {
            max-width: 11ch;
            color: #ffffff;
            font-size: clamp(2.1rem, 4vw, 3.6rem);
            line-height: 0.94;
            letter-spacing: -0.07em;
        }

        .visual-main p {
            color: rgba(226, 232, 240, 0.8);
            font-size: 0.9rem;
            line-height: 1.5;
            max-width: 34ch;
        }

        .visual-lottie {
            width: min(100%, 450px);
            padding: 10px;
            border-radius: 22px;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0.05));
            border: 1px solid rgba(255, 255, 255, 0.14);
        }

        .visual-lottie dotlottie-player {
            width: 100%;
            height: 150px;
        }

        .visual-bottom {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 16px;
            color: rgba(226, 232, 240, 0.72);
            font-size: 0.8rem;
        }

        .visual-bottom span {
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .form-panel {
            background: var(--panel-soft);
            border: 1px solid rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(18px);
            box-shadow: var(--shadow-md);
            padding: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-card {
            width: 100%;
            max-width: 430px;
        }

        .form-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 18px;
        }

        .form-badge {
            padding: 8px 12px;
            border-radius: 999px;
            background: var(--teal-soft);
            color: var(--teal);
            font-size: 0.75rem;
            font-weight: 700;
        }

        .title h2 {
            font-size: 2rem;
            line-height: 1;
            letter-spacing: -0.06em;
            margin-bottom: 8px;
        }

        .title p {
            color: var(--secondary);
            font-size: 0.9rem;
            line-height: 1.5;
            margin-bottom: 16px;
        }

        .error-alert {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 12px 14px;
            margin-bottom: 14px;
            border-radius: var(--radius-md);
            background: var(--danger-bg);
            color: var(--danger-text);
            border: 1px solid var(--danger-line);
        }

        .error-alert span {
            font-size: 0.86rem;
            line-height: 1.4;
            font-weight: 600;
        }

        .login-form {
            display: grid;
            gap: 12px;
        }

        .field {
            display: grid;
            gap: 7px;
        }

        .field label {
            font-size: 0.82rem;
            font-weight: 800;
            color: var(--primary);
        }

        .input-shell {
            position: relative;
        }

        .input-shell svg {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted);
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 1px solid var(--line);
            border-radius: 18px;
            background: #ffffff;
            color: var(--primary);
            font: inherit;
            transition: border-color var(--transition), box-shadow var(--transition), transform var(--transition);
        }

        .form-control::placeholder {
            color: #94a3b8;
        }

        .form-control:focus {
            outline: none;
            border-color: rgba(15, 118, 110, 0.4);
            box-shadow: 0 0 0 5px rgba(15, 118, 110, 0.12);
            transform: translateY(-1px);
        }

        .form-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 2px;
        }

        .remember {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            color: var(--secondary);
            font-size: 0.92rem;
            font-weight: 600;
        }

        .remember input {
            width: 16px;
            height: 16px;
            accent-color: var(--teal);
        }

        .helper {
            color: var(--muted);
            font-size: 0.86rem;
        }

        .submit-btn {
            margin-top: 0;
            width: 100%;
            padding: 14px 18px;
            border: none;
            border-radius: 20px;
            background: linear-gradient(135deg, #0f172a 0%, #115e59 100%);
            color: #ffffff;
            font: inherit;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            box-shadow: 0 18px 34px rgba(15, 23, 42, 0.14);
            transition: transform var(--transition), box-shadow var(--transition);
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 22px 42px rgba(15, 23, 42, 0.18);
        }

        .form-footer {
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px solid var(--line);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            color: var(--muted);
            font-size: 0.78rem;
        }

        .form-footer span {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        @media (max-width: 1200px) {
            body {
                height: auto;
                overflow: auto;
                background:
                    linear-gradient(180deg, #f4fbff 0%, #edf6fb 100%);
            }

            .page {
                height: auto;
                grid-template-columns: 1fr;
                max-width: 560px;
                margin: 0 auto;
                gap: 0;
                padding: 24px 18px;
            }

            .visual-panel {
                display: none;
            }

            .form-panel {
                min-height: calc(100vh - 48px);
                padding: 28px 24px;
            }
        }

        @media (max-width: 768px) {
            html,
            body {
                height: auto;
            }

            body {
                overflow: auto;
                background:
                    linear-gradient(180deg, #f7fbfe 0%, #edf6fb 100%);
            }

            .page {
                min-height: 100vh;
                max-width: 100%;
                gap: 0;
                padding: 0;
            }

            .form-panel {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 24px 18px 28px;
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
                border: none;
                background: transparent;
            }

            .form-card {
                max-width: 100%;
                width: 100%;
                padding-top: 0;
            }

            .form-header {
                align-items: center;
                justify-content: flex-start;
                gap: 12px;
                margin-bottom: 24px;
            }

            .form-badge {
                display: none;
            }

            .brand {
                gap: 12px;
            }

            .brand-icon {
                width: 48px;
                height: 48px;
                border-radius: 16px;
            }

            .brand-text small {
                font-size: 0.68rem;
                letter-spacing: 0.14em;
            }

            .brand-text strong {
                font-size: 1.3rem;
                line-height: 1.1;
            }

            .title h2 {
                font-size: 2rem;
                margin-bottom: 10px;
            }

            .title p {
                margin-bottom: 18px;
                font-size: 0.96rem;
                line-height: 1.6;
            }

            .error-alert {
                margin-bottom: 16px;
            }

            .login-form {
                gap: 14px;
            }

            .field {
                gap: 8px;
            }

            .field label {
                font-size: 0.9rem;
            }

            .form-control {
                padding: 15px 16px 15px 48px;
                border-radius: 16px;
                font-size: 0.98rem;
            }

            .form-meta {
                align-items: flex-start;
                justify-content: flex-start;
                gap: 10px;
                margin-top: 4px;
            }

            .remember {
                font-size: 0.96rem;
            }

            .helper {
                font-size: 0.92rem;
            }

            .submit-btn {
                margin-top: 6px;
                padding: 16px 18px;
                border-radius: 18px;
                font-size: 1rem;
            }

            .form-footer {
                margin-top: 20px;
                padding-top: 16px;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="visual-panel">
            <div class="visual-top">
                <div class="brand">
                    <div class="brand-icon">
                        <img src="{{ asset('logo.png') }}" alt="AquaHeart Logo" style="height: 40px; width: auto; opacity: 0.8;">
                    </div>
                    <div class="brand-text">
                        <small>Aqua Heart</small>
                        <strong>Station Management System</strong>
                    </div>
                </div>
            </div>

            <div class="visual-main">
                <div class="visual-kicker">
                    <i data-lucide="shield-check" size="16"></i>
                    <span>Staff access</span>
                </div>

                <h1>Clean login for a clean system.</h1>
                <p>Sign in and continue to your dashboard.</p>

                <div class="visual-lottie">
                    <dotlottie-player
                        src="{{ asset('lottie/DATA SECURITY.lottie') }}"
                        background="transparent"
                        speed="1"
                        style="width: 100%; height: 100%;"
                        loop
                        autoplay>
                    </dotlottie-player>
                </div>
            </div>

            <div class="visual-bottom">
                <img src="{{ asset('logo.png') }}" alt="AquaHeart Logo" style="height: 24px; width: auto; opacity: 0.8;">
                <span><i data-lucide="shield" size="16"></i> Secure sign in</span>
            </div>
        </section>

        <section class="form-panel">
            <div class="form-card">
                <div class="form-header">
                    <div class="brand">
                        <img src="{{ asset('logo.png') }}" alt="AquaHeart Logo" style="height: 48px; width: auto; object-fit: contain;">
                        <div class="brand-text" style="color: var(--primary);">
                            <small style="color: var(--muted);">Internal Access</small>
                            <strong style="color: var(--primary);">AquaHeart Login</strong>
                        </div>
                    </div>

                    <div class="form-badge">Sign in</div>
                </div>

                <div class="title">
                    <h2>Welcome back</h2>
                    <p>Use your account to continue.</p>
                </div>

                @if ($errors->any() || session('error'))
                    <div class="error-alert">
                        <i data-lucide="alert-circle" size="20"></i>
                        <span>
                            @if(session('error'))
                                {{ session('error') }}
                            @else
                                We could not sign you in. Please check your email and password.
                            @endif
                        </span>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="login-form">
                    @csrf

                    <div class="field">
                        <label for="email">Email address</label>
                        <div class="input-shell">
                            <i data-lucide="mail" size="18"></i>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                placeholder="name@aquaheart.com">
                        </div>
                    </div>

                    <div class="field">
                        <label for="password">Password</label>
                        <div class="input-shell">
                            <i data-lucide="lock-keyhole" size="18"></i>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                                placeholder="Enter your password">
                        </div>
                    </div>

                    <div class="form-meta">
                        <label class="remember" for="remember">
                            <input type="checkbox" id="remember" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                            <span>Keep me signed in</span>
                        </label>

                        <span class="helper">Staff only</span>
                    </div>

                    <button type="submit" class="submit-btn">
                        <span>Sign in</span>
                        <i data-lucide="arrow-right" size="18"></i>
                    </button>
                </form>

                <div class="form-footer">
                    <span><i data-lucide="clock-3" size="15"></i> Secure session {{ date('Y') }}</span>
                    <span><i data-lucide="shield-check" size="15"></i> Aqua Heart</span>
                </div>
            </div>
        </section>
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
