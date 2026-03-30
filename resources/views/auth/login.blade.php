<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Aqua Heart</title>
    
    <!-- Modern Professional Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --primary: #0f172a;
            --accent: #3b82f6;
            --bg: #f8fafc;
            --border: #e2e8f0;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg);
            background-image: radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.05) 0, transparent 50%), 
                              radial-gradient(at 50% 0%, rgba(14, 165, 233, 0.05) 0, transparent 50%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: white;
            padding: 48px;
            border-radius: 24px;
            border: 1px solid var(--border);
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
            margin-bottom: 40px;
        }

        .brand-icon {
            width: 48px;
            height: 48px;
            background: var(--primary);
            color: white;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand h1 {
            font-size: 1.5rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--primary);
        }

        .brand p {
            color: var(--text-muted);
            font-size: 0.9rem;
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: var(--bg);
            border: 1px solid var(--border);
            border-radius: 12px;
            font-family: inherit;
            font-size: 0.95rem;
            color: var(--primary);
            transition: all 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--accent);
            background: white;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.08);
        }

        .alert {
            padding: 16px;
            background: #fef2f2;
            color: #b91c1c;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 30px;
            border: 1px solid #fee2e2;
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .remember-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 24px;
            cursor: pointer;
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 600;
        }

        .remember-wrap input {
            width: 18px;
            height: 18px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="brand">
            <div class="brand-icon">
                <i data-lucide="droplet"></i>
            </div>
            <h1>AquaHeart</h1>
            <p>Administrative Access</p>
        </div>

        @if ($errors->any() || session('error'))
            <div class="alert">
                @if(session('error'))
                    {{ session('error') }}
                @else
                    Please check your credentials.
                @endif
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    class="form-control"
                    value="{{ old('email') }}"
                    required 
                    placeholder="admin@aquaheart.com"
                >
            </div>

            <div class="form-group">
                <label for="password">Security Password</label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="form-control"
                    required 
                    placeholder="••••••••"
                >
            </div>

            <label class="remember-wrap">
                <input type="checkbox" name="remember" value="1"> 
                Keep me signed in
            </label>

            <button type="submit" class="btn-submit">
                <span>Authenticate</span>
                <i data-lucide="arrow-right" size="18"></i>
            </button>
        </form>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
