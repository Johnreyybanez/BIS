<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign In &mdash; Portal</title>
    <link rel="icon" type="image/png" href="{{ asset('images/end.jpg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap" rel="stylesheet">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #0c0e14;
            --panel:    #13161f;
            --border:   #1f2330;
            --input-bg: #191c27;
            --accent:   #e8b84b;
            --accent-dk:#c49730;
            --text:     #e8eaf0;
            --muted:    #6b7080;
            --danger:   #e05c5c;
        }

        html, body {
            min-height: 100vh;
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image:
                linear-gradient(var(--border) 1px, transparent 1px),
                linear-gradient(90deg, var(--border) 1px, transparent 1px);
            background-size: 48px 48px;
            opacity: .4;
            pointer-events: none;
        }

        body::after {
            content: '';
            position: fixed;
            top: -160px;
            left: 50%;
            translate: -50% 0;
            width: 640px;
            height: 640px;
            border-radius: 50%;
            background: radial-gradient(circle, #e8b84b20 0%, transparent 70%);
            pointer-events: none;
        }

        .page {
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px;
            position: relative;
            z-index: 1;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 40px 40px;
            box-shadow: 0 32px 80px rgba(0,0,0,.6);
            animation: rise .55s cubic-bezier(.16,1,.3,1) both;
        }

        @keyframes rise {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .badge {
            width: 52px; height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dk));
            display: grid;
            place-items: center;
            margin-bottom: 28px;
            box-shadow: 0 8px 24px #e8b84b28;
        }
        .badge svg {
            width: 26px; height: 26px;
            fill: none; stroke: #0c0e14;
            stroke-width: 2; stroke-linecap: round; stroke-linejoin: round;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 2rem;
            font-weight: 400;
            letter-spacing: -.02em;
            margin-bottom: 6px;
        }
        h1 em { font-style: italic; color: var(--accent); }

        .sub {
            font-size: .875rem;
            color: var(--muted);
            margin-bottom: 36px;
        }

        .field { margin-bottom: 18px; }

        label {
            display: block;
            font-size: .75rem;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .input-wrap { position: relative; }

        .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            translate: 0 -50%;
            width: 16px; height: 16px;
            stroke: var(--muted);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
            pointer-events: none;
            transition: stroke .2s;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px 42px 12px 42px;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--text);
            font-family: inherit;
            font-size: .9375rem;
            outline: none;
            transition: border-color .2s, box-shadow .2s;
        }
        input::placeholder { color: var(--muted); }
        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 3px #e8b84b18;
        }
        .input-wrap:focus-within .input-icon { stroke: var(--accent); }

        .eye-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            translate: 0 -50%;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: grid;
            place-items: center;
            color: var(--muted);
            transition: color .2s;
        }
        .eye-btn:hover { color: var(--accent); }
        .eye-btn svg {
            width: 17px; height: 17px;
            stroke: currentColor; fill: none;
            stroke-width: 1.8; stroke-linecap: round; stroke-linejoin: round;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
            font-size: .85rem;
            color: var(--muted);
            cursor: pointer;
            user-select: none;
        }
        .remember input[type="checkbox"] {
            width: 16px; height: 16px;
            padding: 0;
            accent-color: var(--accent);
            cursor: pointer;
        }

        .field-error {
            font-size: .78rem;
            color: var(--danger);
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn {
            width: 100%;
            padding: 13px;
            background: linear-gradient(135deg, var(--accent), var(--accent-dk));
            color: #0c0e14;
            border: none;
            border-radius: 10px;
            font-family: inherit;
            font-size: .9375rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 4px 20px #e8b84b28;
            transition: filter .2s, transform .15s;
            position: relative;
            overflow: hidden;
        }
        .btn:hover  { filter: brightness(1.1); }
        .btn:active { transform: scale(.98); }
        .btn.loading { pointer-events: none; }
        .btn.loading .btn-text { opacity: 0; }
        .btn.loading .spinner  { display: block; }

        .spinner {
            display: none;
            position: absolute;
            width: 20px; height: 20px;
            border: 2.5px solid rgba(12,14,20,.3);
            border-top-color: #0c0e14;
            border-radius: 50%;
            animation: spin .7s linear infinite;
        }
        @keyframes spin { to { rotate: 360deg; } }

        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 28px 0 0;
            color: var(--muted);
            font-size: .78rem;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        .footer-note {
            text-align: center;
            font-size: .8rem;
            color: var(--muted);
            margin-top: 18px;
        }
        .footer-note a { color: var(--accent); text-decoration: none; font-weight: 500; }
        .footer-note a:hover { text-decoration: underline; }

        /* SweetAlert2 dark theme overrides */
        .swal2-popup {
            background: var(--panel) !important;
            border: 1px solid var(--border) !important;
            border-radius: 16px !important;
            color: var(--text) !important;
            font-family: 'DM Sans', sans-serif !important;
        }
        .swal2-title {
            color: var(--text) !important;
            font-family: 'DM Serif Display', serif !important;
            font-weight: 400 !important;
        }
        .swal2-html-container { color: var(--muted) !important; }
        .swal2-confirm {
            background: linear-gradient(135deg, var(--accent), var(--accent-dk)) !important;
            color: #0c0e14 !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            box-shadow: none !important;
        }
        .swal2-cancel, .swal2-deny {
            background: var(--input-bg) !important;
            color: var(--text) !important;
            border-radius: 8px !important;
        }
        .swal2-icon.swal2-error   { border-color: var(--danger) !important; color: var(--danger) !important; }
        .swal2-icon.swal2-warning { border-color: var(--accent) !important; color: var(--accent) !important; }
        .swal2-icon.swal2-success { border-color: var(--accent) !important; }
        .swal2-icon.swal2-success [class^='swal2-success-line'] { background: var(--accent) !important; }
        .swal2-icon.swal2-success .swal2-success-ring { border-color: #e8b84b44 !important; }
        .swal2-timer-progress-bar { background: var(--accent) !important; }
    </style>
</head>
<body>
<div class="page">
    <div class="card">

        <div class="badge">
            <svg viewBox="0 0 24 24">
                <rect x="3" y="11" width="18" height="11" rx="2"/>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
        </div>

        <h1>Welcome <em>back</em></h1>
        <p class="sub">Sign in to continue to your dashboard.</p>

        <form id="loginForm" action="{{ route('login.submit') }}" method="POST">
            @csrf

            {{-- Username --}}
            <div class="field">
                <label for="username">Username</label>
                <div class="input-wrap">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        value="{{ old('username') }}"
                        placeholder="Enter your username"
                        autocomplete="username"
                        autofocus
                    >
                    <svg class="input-icon" viewBox="0 0 24 24">
                        <circle cx="12" cy="8" r="4"/>
                        <path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/>
                    </svg>
                </div>
                @error('username')
                    <div class="field-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Password --}}
            <div class="field">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="••••••••"
                        autocomplete="current-password"
                    >
                    <svg class="input-icon" viewBox="0 0 24 24">
                        <rect x="3" y="11" width="18" height="11" rx="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    <button type="button" class="eye-btn" id="eyeBtn" aria-label="Toggle password visibility">
                        <svg id="eyeIcon" viewBox="0 0 24 24">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <div class="field-error">
                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <line x1="12" y1="8" x2="12" y2="12"/>
                            <line x1="12" y1="16" x2="12.01" y2="16"/>
                        </svg>
                        {{ $message }}
                    </div>
                @enderror
            </div>

            {{-- Remember Me --}}
            <label class="remember">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                Keep me signed in
            </label>

            <button type="submit" class="btn" id="loginBtn">
                <span class="btn-text">Sign In</span>
                <div class="spinner"></div>
            </button>
        </form>

        <div class="divider">secured access</div>

        <p class="footer-note">
            Need access? <a href="#">Contact your administrator</a>
        </p>
    </div>
</div>

<script>
// ── Password visibility toggle ───────────────────────────────────────────────
const eyeBtn  = document.getElementById('eyeBtn');
const pwInput = document.getElementById('password');
const eyeIcon = document.getElementById('eyeIcon');

const eyeOpen   = `<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>`;
const eyeClosed = `<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>`;

eyeBtn.addEventListener('click', () => {
    const isHidden    = pwInput.type === 'password';
    pwInput.type      = isHidden ? 'text' : 'password';
    eyeIcon.innerHTML = isHidden ? eyeClosed : eyeOpen;
});

// ── Submit: client-side validation + loading state ───────────────────────────
const form     = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');

form.addEventListener('submit', function (e) {
    const username = document.getElementById('username').value.trim();
    const password = pwInput.value.trim();

    if (!username || !password) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Fields Required',
            text: 'Please enter both your username and password.',
            confirmButtonText: 'Got it',
        });
        return;
    }

    loginBtn.classList.add('loading');
});

// ── Server flash messages ────────────────────────────────────────────────────
@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Authentication Failed',
        text: @json(session('error')),
        confirmButtonText: 'Try Again',
    });
@endif

@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Done!',
        text: @json(session('success')),
        confirmButtonText: 'Continue',
        timer: 2500,
        timerProgressBar: true,
    });
@endif

@if($errors->any() && !$errors->has('username') && !$errors->has('password'))
    Swal.fire({
        icon: 'error',
        title: 'Oops!',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonText: 'Dismiss',
    });
@endif
</script>
</body>
</html>