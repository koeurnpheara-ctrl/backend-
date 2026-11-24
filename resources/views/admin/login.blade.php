<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin sign in · Form &amp; Field</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --forest: #435344; --sage: #78866f; --cream: #f5f4ef; --ink: #292d28; }
        body { min-height: 100vh; display: grid; place-items: center; padding: 24px; background: var(--cream); color: var(--ink); font-family: Inter, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .login-card { width: min(100%, 440px); border: 1px solid #e8e8e1; border-radius: 16px; background: #fff; box-shadow: 0 20px 60px rgba(37,43,35,.08); }
        .brand-mark { width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; background: var(--forest); color: #fff; font: 700 25px Georgia, serif; }
        .brand-name { font: 600 22px Georgia, serif; }
        .login-title { font: 500 32px Georgia, serif; }
        .form-control { min-height: 46px; border-color: #dedfd8; }
        .form-control:focus { border-color: var(--sage); box-shadow: 0 0 0 .2rem rgba(120,134,111,.15); }
        .btn-forest { min-height: 46px; background: var(--forest); border-color: var(--forest); color: #fff; }
        .btn-forest:hover { background: #334235; border-color: #334235; color: #fff; }
    </style>
</head>
<body>
<main class="login-card p-4 p-md-5">
    <a href="/" class="d-flex align-items-center gap-2 text-decoration-none text-dark mb-5">
        <span class="brand-mark">f.</span>
        <span class="brand-name">form &amp; field</span>
    </a>
    <div class="text-uppercase small fw-semibold mb-2" style="color:#78866f;letter-spacing:1.5px">Store administration</div>
    <h1 class="login-title mb-2">Welcome back</h1>
    <p class="text-secondary mb-4">Sign in to manage your store.</p>

    @if ($errors->any())
        <div class="alert alert-danger" role="alert">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.store') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" autocomplete="username" required autofocus>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="current-password" required>
        </div>
        <div class="form-check mb-4">
            <input id="remember" name="remember" type="checkbox" class="form-check-input" value="1">
            <label for="remember" class="form-check-label text-secondary">Keep me signed in</label>
        </div>
        <button type="submit" class="btn btn-forest w-100">Sign in to dashboard</button>
    </form>
    <div class="text-center small text-secondary mt-4">
        <a href="http://16.176.28.191/" class="text-decoration-none" style="color:#435344">Return to the storefront</a>
    </div>
</main>
</body>
</html>
