<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - GokuWallet</title>
    <style>
        :root { --primary: #667eea; --secondary: #764ba2; }
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
            width: 100%; padding: 12px; background: var(--success, #28a745);
            color: white; border: none; border-radius: 8px;
            font-weight: 600; cursor: pointer; transition: 0.3s;
        }
        button:hover { opacity: 0.9; }
        p { margin-top: 20px; font-size: 14px; color: #636e72; }
        a { color: var(--primary); text-decoration: none; font-weight: bold; }
        
        /* Styling untuk list error */
        .error-container {
            color: #dc3545; 
            background: #ffeaea; 
            padding: 10px; 
            border-radius: 8px; 
            margin-bottom: 15px; 
            font-size: 13px; 
            text-align: left;
        }
        .error-container ul { margin: 0; padding-left: 20px; }
    </style>
</head>
<body>

<div class="auth-card">
    <form action="/register" method="POST">
        @csrf
        <h2>🚀 Daftar Akun</h2>
        <p style="margin-bottom: 20px;">Mulai kelola keuanganmu sekarang!</p>
        
        @if ($errors->any())
            <div class="error-container">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
        <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
        <input type="password" name="password" placeholder="Buat Password" required>
        
        <button type="submit" style="background: #28a745;">Buat Akun</button>
        
        <p>Sudah punya akun? <a href="/login">Login di sini</a></p>
    </form>
</div>

</body>
</html>