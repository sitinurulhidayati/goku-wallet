<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GokuWallet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; --light: #f4f7f6; }
        body { 
            margin: 0; padding: 0; font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .auth-card {
            background: white; padding: 40px; border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2); width: 100%; max-width: 400px;
            text-align: center;
        }
        h2 { color: #2d3436; margin-bottom: 25px; }
        input {
            width: 100%; padding: 12px; margin-bottom: 15px;
            border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box;
        }
        button {
            width: 100%; padding: 12px; background: var(--primary);
            color: white; border: none; border-radius: 8px;
            font-weight: 600; cursor: pointer; transition: 0.3s;
        }
        button:hover { background: var(--secondary); }
        p { margin-top: 20px; font-size: 14px; color: #636e72; }
        a { color: var(--primary); text-decoration: none; font-weight: bold; }
        .error-msg { 
            color: #dc3545; 
            background: #ffeaea;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px; 
            margin-bottom: 15px; 
        }
        .success-msg {
            color: #28a745;
            background: #eaffeb;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="auth-card">
    <form action="/login" method="POST">
        @csrf
        <h2>💰 GokuWallet Login</h2>
        
        @if(session('error'))
            <div class="error-msg">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="success-msg">{{ session('success') }}</div>
        @endif

        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        
        <input type="password" name="password" placeholder="Password" required>
        
        <button type="submit">Masuk Sekarang</button>
        
        <p>Belum punya akun? <a href="/register">Daftar Gratis</a></p>
    </form>
</div>

</body>
</html>