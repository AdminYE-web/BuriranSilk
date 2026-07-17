<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f6f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 380px;
            background: #fff;
            border-radius: 14px;
            padding: 32px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .login-card h1 {
            margin: 0 0 24px;
            font-size: 26px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 14px;
            font-weight: 600;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            height: 42px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 0 12px;
            box-sizing: border-box;
        }

        .remember-row {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .btn-login {
            width: 100%;
            height: 44px;
            border: 0;
            border-radius: 8px;
            background: #2563eb;
            color: #fff;
            font-weight: 700;
            cursor: pointer;
        }

        .error-box {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-card">
    <h1>Admin Login</h1>

    @if($errors->any())
        <div class="error-box">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('admin.login.submit') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Email</label>
            <input 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required
                autofocus
            >
        </div>

        <div class="form-group">
            <label>Password</label>
            <input 
                type="password" 
                name="password" 
                required
            >
        </div>

        {{-- <label class="remember-row">
            <input type="checkbox" name="remember" value="1">
            Remember me
        </label> --}}

        <button type="submit" class="btn-login">
            Login
        </button>
    </form>
</div>

</body>
</html>