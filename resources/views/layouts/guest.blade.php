<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'PMB Universitas XYZ') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --navy:  #0d1f3c;
            --navy2: #152a50;
            --gold:  #c9a84c;
            --gold2: #e8c96b;
            --cream: #f8f4ed;
            --white: #ffffff;
            --muted: #6b7a99;
            --err:   #c0392b;
            --serif: 'Playfair Display', Georgia, serif;
            --sans:  'DM Sans', sans-serif;
        }
        body {
            font-family: var(--sans);
            min-height: 100vh;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: var(--navy);
        }

        /* ── LEFT PANEL ── */
        .auth-left {
            background: var(--navy);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 2.5rem 3.5rem;
            position: relative; overflow: hidden;
        }
        .auth-left::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none'%3E%3Cg fill='%23c9a84c' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .auth-left::after {
            content: '';
            position: absolute; bottom: -120px; right: -80px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(201,168,76,.12) 0%, transparent 70%);
        }
        .auth-brand { display: flex; align-items: center; gap: .75rem; position: relative; z-index: 2; }
        .auth-logo {
            width: 40px; height: 40px; border-radius: 8px;
            background: var(--gold);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--serif); font-weight: 900; font-size: 1.2rem;
            color: var(--navy);
        }
        .auth-brand-name {
            font-family: var(--serif); font-size: 1rem; font-weight: 700;
            color: var(--white); line-height: 1.2;
        }
        .auth-brand-name span {
            display: block; font-family: var(--sans);
            font-size: .62rem; font-weight: 400;
            color: var(--gold); letter-spacing: .12em; text-transform: uppercase;
        }

        .auth-left-body { position: relative; z-index: 2; }
        .auth-left-eyebrow {
            font-size: .68rem; font-weight: 600; letter-spacing: .14em;
            text-transform: uppercase; color: var(--gold);
            display: flex; align-items: center; gap: .5rem;
            margin-bottom: 1rem;
        }
        .auth-left-eyebrow::before { content: ''; width: 20px; height: 1px; background: var(--gold); }
        .auth-left-h2 {
            font-family: var(--serif);
            font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 900; line-height: 1.15;
            color: var(--white); margin-bottom: 1rem;
        }
        .auth-left-h2 em { font-style: normal; color: var(--gold); display: block; }
        .auth-left-p { font-size: .85rem; line-height: 1.7; color: rgba(255,255,255,.5); max-width: 340px; }

        .auth-info-cards { margin-top: 2.5rem; display: flex; flex-direction: column; gap: .75rem; }
        .auth-info-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(201,168,76,.15);
            border-radius: 10px; padding: .9rem 1.1rem;
            display: flex; align-items: center; gap: .85rem;
        }
        .aic-icon {
            width: 32px; height: 32px; flex-shrink: 0;
            background: rgba(201,168,76,.12); border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
        }
        .aic-icon svg { color: var(--gold); }
        .aic-text { font-size: .78rem; color: rgba(255,255,255,.65); line-height: 1.4; }
        .aic-text b { color: var(--white); font-weight: 600; display: block; font-size: .8rem; }

        .auth-left-footer { font-size: .72rem; color: rgba(255,255,255,.3); position: relative; z-index: 2; }
        .auth-left-footer a { color: var(--gold); text-decoration: none; }

        /* ── RIGHT PANEL ── */
        .auth-right {
            background: var(--cream);
            display: flex; align-items: center; justify-content: center;
            padding: 3rem 2rem;
        }
        .auth-box {
            width: 100%; max-width: 420px;
        }
        .auth-box-title {
            font-family: var(--serif); font-size: 1.75rem; font-weight: 700;
            color: var(--navy); margin-bottom: .4rem;
        }
        .auth-box-sub { font-size: .85rem; color: var(--muted); margin-bottom: 2rem; }
        .auth-box-sub a { color: var(--navy); font-weight: 600; text-decoration: underline; }

        /* Form elements */
        .form-group { margin-bottom: 1.1rem; }
        .form-label {
            display: block; font-size: .78rem; font-weight: 600;
            color: var(--navy); margin-bottom: .4rem; letter-spacing: .02em;
        }
        .form-input {
            width: 100%;
            padding: .7rem 1rem;
            border: 1.5px solid #ddd6cc;
            border-radius: 7px;
            font-family: var(--sans); font-size: .88rem;
            color: var(--navy);
            background: var(--white);
            transition: border-color .2s, box-shadow .2s;
            outline: none;
        }
        .form-input:focus {
            border-color: var(--navy2);
            box-shadow: 0 0 0 3px rgba(13,31,60,.08);
        }
        .form-input::placeholder { color: #b0a898; }
        .form-error {
            font-size: .74rem; color: var(--err); margin-top: .35rem;
        }

        /* Status */
        .form-status {
            background: rgba(13,31,60,.06); border: 1px solid rgba(13,31,60,.15);
            border-radius: 6px; padding: .65rem 1rem;
            font-size: .8rem; color: var(--navy2); margin-bottom: 1.25rem;
        }

        /* Checkbox row */
        .checkbox-row {
            display: flex; align-items: center; justify-content: space-between;
            margin: 1rem 0 1.5rem;
        }
        .checkbox-label { display: flex; align-items: center; gap: .5rem; cursor: pointer; }
        .checkbox-label input {
            width: 15px; height: 15px; accent-color: var(--navy);
            cursor: pointer;
        }
        .checkbox-label span { font-size: .8rem; color: var(--muted); }
        .link-forgot {
            font-size: .78rem; color: var(--muted); text-decoration: none;
            transition: color .2s;
        }
        .link-forgot:hover { color: var(--navy); }

        .btn-submit {
            width: 100%; padding: .85rem;
            background: var(--navy); color: var(--white);
            border: none; border-radius: 7px;
            font-family: var(--sans); font-size: .9rem; font-weight: 600;
            cursor: pointer; letter-spacing: .03em;
            transition: all .2s;
            box-shadow: 0 4px 16px rgba(13,31,60,.25);
        }
        .btn-submit:hover {
            background: var(--navy2);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(13,31,60,.3);
        }
        .btn-submit:active { transform: translateY(0); }

        .auth-divider {
            text-align: center; margin: 1.25rem 0;
            font-size: .75rem; color: #b0a898; position: relative;
        }
        .auth-divider::before, .auth-divider::after {
            content: ''; position: absolute; top: 50%;
            width: 42%; height: 1px; background: #e5e0d8;
        }
        .auth-divider::before { left: 0; }
        .auth-divider::after { right: 0; }

        .auth-switch {
            text-align: center; font-size: .82rem; color: var(--muted);
            margin-top: 1.25rem;
        }
        .auth-switch a {
            color: var(--navy); font-weight: 700; text-decoration: none;
            border-bottom: 1px solid var(--gold);
        }

        @media (max-width: 820px) {
            body { grid-template-columns: 1fr; }
            .auth-left { display: none; }
            .auth-right { padding: 2rem 1.25rem; min-height: 100vh; }
        }
    </style>
</head>
<body>
    {{-- LEFT PANEL --}}
    <div class="auth-left">
        <div class="auth-brand">
            <div class="auth-logo">X</div>
            <div class="auth-brand-name">
                Universitas XYZ
                <span>Portal PMB</span>
            </div>
        </div>

        <div class="auth-left-body">
            <div class="auth-left-eyebrow">PMB {{ date('Y') }}/{{ date('Y') + 1 }}</div>
            <h2 class="auth-left-h2">
                Satu Langkah
                <em>Menuju Impian</em>
            </h2>
            <p class="auth-left-p">
                Daftarkan dirimu di Universitas XYZ dan mulai perjalanan akademik yang akan mengubah masa depanmu.
            </p>
            <div class="auth-info-cards">
                <div class="auth-info-card">
                    <div class="aic-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="aic-text">
                        <b>Pendaftaran Online 24 Jam</b>
                        Proses mudah, cepat, dan dapat dilakukan kapan saja
                    </div>
                </div>
                <div class="auth-info-card">
                    <div class="aic-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <div class="aic-text">
                        <b>Data Aman & Terlindungi</b>
                        Enkripsi penuh untuk keamanan data pribadimu
                    </div>
                </div>
                <div class="auth-info-card">
                    <div class="aic-icon">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <div class="aic-text">
                        <b>Bantuan Teknis</b>
                        Tim helpdesk siap membantu: pmb@xyz.ac.id
                    </div>
                </div>
            </div>
        </div>

        <div class="auth-left-footer">
            © {{ date('Y') }} Universitas XYZ &mdash; <a href="#">Kebijakan Privasi</a>
        </div>
    </div>

    {{-- RIGHT PANEL --}}
    <div class="auth-right">
        {{ $slot }}
    </div>
</body>
</html>