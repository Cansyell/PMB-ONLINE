<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PMB Universitas XYZ — Penerimaan Mahasiswa Baru {{ date('Y') }}/{{ date('Y') + 1 }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --navy:   #0d1f3c;
            --navy2:  #152a50;
            --gold:   #c9a84c;
            --gold2:  #e8c96b;
            --cream:  #f8f4ed;
            --white:  #ffffff;
            --muted:  #6b7a99;
            --serif:  'Playfair Display', Georgia, serif;
            --sans:   'DM Sans', sans-serif;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: var(--sans);
            background: var(--cream);
            color: var(--navy);
            overflow-x: hidden;
        }

        /* ── NAV ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 50;
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.1rem 3rem;
            background: rgba(13,31,60,.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(201,168,76,.2);
        }
        .nav-brand {
            display: flex; align-items: center; gap: .75rem;
        }
        .nav-logo {
            width: 38px; height: 38px;
            background: var(--gold);
            border-radius: 6px;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--serif); font-weight: 900; font-size: 1.1rem;
            color: var(--navy); flex-shrink: 0;
        }
        .nav-title {
            font-family: var(--serif); font-size: .95rem; font-weight: 700;
            color: var(--white); line-height: 1.2;
        }
        .nav-title span { display: block; font-size: .65rem; font-weight: 400; color: var(--gold); letter-spacing: .1em; text-transform: uppercase; font-family: var(--sans); }
        .nav-links { display: flex; align-items: center; gap: 1.5rem; }
        .nav-links a {
            font-size: .82rem; font-weight: 500; color: rgba(255,255,255,.7);
            text-decoration: none; letter-spacing: .02em;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--gold2); }
        .nav-btn {
            background: var(--gold); color: var(--navy) !important;
            padding: .45rem 1.2rem; border-radius: 4px;
            font-weight: 600 !important; font-size: .8rem !important;
            transition: background .2s !important;
        }
        .nav-btn:hover { background: var(--gold2) !important; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            background: var(--navy);
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            overflow: hidden;
        }
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23c9a84c' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .hero-left {
            display: flex; flex-direction: column; justify-content: center;
            padding: 8rem 4rem 4rem 5rem;
            position: relative; z-index: 2;
        }
        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: .5rem;
            font-size: .72rem; font-weight: 500; letter-spacing: .14em;
            text-transform: uppercase; color: var(--gold);
            margin-bottom: 1.5rem;
        }
        .hero-eyebrow::before {
            content: ''; width: 28px; height: 1px; background: var(--gold);
        }
        .hero-h1 {
            font-family: var(--serif);
            font-size: clamp(2.6rem, 5vw, 4rem);
            font-weight: 900; line-height: 1.1;
            color: var(--white); margin-bottom: 1.5rem;
        }
        .hero-h1 em {
            font-style: normal; color: var(--gold);
            display: block;
        }
        .hero-sub {
            font-size: 1rem; line-height: 1.7; color: rgba(255,255,255,.6);
            max-width: 440px; margin-bottom: 2.5rem;
        }
        .hero-actions { display: flex; gap: 1rem; align-items: center; flex-wrap: wrap; }
        .btn-primary {
            background: var(--gold); color: var(--navy);
            padding: .85rem 2.2rem; border-radius: 4px;
            font-weight: 600; font-size: .9rem; text-decoration: none;
            letter-spacing: .03em; transition: all .2s;
            box-shadow: 0 4px 20px rgba(201,168,76,.3);
        }
        .btn-primary:hover { background: var(--gold2); transform: translateY(-1px); }
        .btn-outline {
            color: rgba(255,255,255,.8); text-decoration: none;
            font-size: .85rem; font-weight: 500;
            display: flex; align-items: center; gap: .4rem;
            transition: color .2s;
        }
        .btn-outline:hover { color: var(--gold); }
        .btn-outline svg { transition: transform .2s; }
        .btn-outline:hover svg { transform: translateX(3px); }

        .hero-stats {
            display: flex; gap: 2rem; margin-top: 3.5rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,.1);
        }
        .stat-item { }
        .stat-num {
            font-family: var(--serif); font-size: 1.8rem; font-weight: 700;
            color: var(--gold); line-height: 1;
        }
        .stat-lbl { font-size: .72rem; color: rgba(255,255,255,.5); margin-top: .25rem; letter-spacing: .05em; }

        /* ── HERO RIGHT ── */
        .hero-right {
            position: relative; z-index: 2;
            display: flex; align-items: flex-end; justify-content: center;
            padding: 2rem 3rem 0;
        }
        .hero-visual {
            width: 100%; max-width: 480px;
            background: linear-gradient(160deg, var(--navy2) 0%, rgba(201,168,76,.08) 100%);
            border: 1px solid rgba(201,168,76,.2);
            border-bottom: none;
            border-radius: 16px 16px 0 0;
            padding: 2.5rem 2.5rem 0;
            position: relative;
            margin-top: 5rem;
        }
        .hero-card-badge {
            display: inline-block;
            background: rgba(201,168,76,.15); border: 1px solid rgba(201,168,76,.3);
            color: var(--gold); font-size: .68rem; letter-spacing: .1em;
            text-transform: uppercase; padding: .3rem .75rem; border-radius: 20px;
            margin-bottom: 1.25rem; font-weight: 500;
        }
        .hero-card-title {
            font-family: var(--serif); font-size: 1.4rem; font-weight: 700;
            color: var(--white); line-height: 1.3; margin-bottom: 1.5rem;
        }
        .timeline {
            display: flex; flex-direction: column; gap: 0;
            padding-bottom: 2rem;
        }
        .tl-item {
            display: flex; gap: 1rem; position: relative;
        }
        .tl-item:not(:last-child)::before {
            content: ''; position: absolute;
            left: 11px; top: 24px; bottom: -4px; width: 1px;
            background: rgba(201,168,76,.2);
        }
        .tl-dot {
            width: 24px; height: 24px; border-radius: 50%;
            border: 2px solid rgba(201,168,76,.4);
            background: var(--navy2);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; margin-top: 2px;
        }
        .tl-dot.active { border-color: var(--gold); background: rgba(201,168,76,.15); }
        .tl-dot.active::after {
            content: ''; width: 6px; height: 6px; border-radius: 50%;
            background: var(--gold);
        }
        .tl-body { padding-bottom: 1.25rem; }
        .tl-date { font-size: .65rem; color: var(--gold); font-weight: 600; letter-spacing: .08em; text-transform: uppercase; }
        .tl-event { font-size: .82rem; color: rgba(255,255,255,.8); margin-top: .15rem; font-weight: 500; }

        /* ── JALUR ── */
        .section-jalur {
            background: var(--cream);
            padding: 6rem 5rem;
        }
        .section-label {
            font-size: .7rem; font-weight: 600; letter-spacing: .14em;
            text-transform: uppercase; color: var(--gold);
            display: flex; align-items: center; gap: .5rem;
            margin-bottom: .75rem;
        }
        .section-label::before { content: ''; width: 24px; height: 1px; background: var(--gold); }
        .section-h2 {
            font-family: var(--serif); font-size: clamp(1.8rem, 3vw, 2.6rem);
            font-weight: 700; color: var(--navy); line-height: 1.2;
            max-width: 500px; margin-bottom: 3rem;
        }
        .jalur-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;
        }
        .jalur-card {
            background: var(--white);
            border: 1px solid #e5e0d8;
            border-radius: 12px; padding: 2rem;
            position: relative; overflow: hidden;
            transition: all .25s; cursor: pointer;
        }
        .jalur-card::before {
            content: ''; position: absolute;
            top: 0; left: 0; right: 0; height: 3px;
            background: var(--gold); opacity: 0; transition: opacity .25s;
        }
        .jalur-card:hover { border-color: var(--gold); transform: translateY(-3px); box-shadow: 0 12px 36px rgba(13,31,60,.1); }
        .jalur-card:hover::before { opacity: 1; }
        .jalur-icon {
            width: 44px; height: 44px; border-radius: 10px;
            background: rgba(13,31,60,.06);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 1.25rem;
        }
        .jalur-icon svg { color: var(--navy); }
        .jalur-name {
            font-family: var(--serif); font-size: 1.1rem; font-weight: 700;
            color: var(--navy); margin-bottom: .5rem;
        }
        .jalur-desc { font-size: .82rem; color: var(--muted); line-height: 1.6; margin-bottom: 1.25rem; }
        .jalur-badge {
            display: inline-block; font-size: .65rem; font-weight: 600;
            letter-spacing: .06em; text-transform: uppercase;
            padding: .25rem .65rem; border-radius: 20px;
            background: rgba(13,31,60,.06); color: var(--navy);
        }

        /* ── FOOTER CTA ── */
        .section-cta {
            background: var(--navy);
            padding: 5rem;
            text-align: center;
            position: relative; overflow: hidden;
        }
        .section-cta::before {
            content: '';
            position: absolute; inset: 0;
            background: radial-gradient(ellipse at center, rgba(201,168,76,.08) 0%, transparent 70%);
        }
        .cta-h2 {
            font-family: var(--serif); font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 700; color: var(--white); margin-bottom: 1rem;
            position: relative;
        }
        .cta-sub { color: rgba(255,255,255,.55); font-size: .9rem; margin-bottom: 2rem; position: relative; }
        .cta-actions { display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; position: relative; }
        .btn-ghost {
            color: rgba(255,255,255,.7); text-decoration: none;
            padding: .8rem 1.8rem; border-radius: 4px;
            border: 1px solid rgba(255,255,255,.2);
            font-size: .85rem; font-weight: 500; transition: all .2s;
        }
        .btn-ghost:hover { border-color: var(--gold); color: var(--gold); }

        footer {
            background: #08152a;
            padding: 1.5rem 5rem;
            display: flex; align-items: center; justify-content: space-between;
            border-top: 1px solid rgba(201,168,76,.15);
        }
        footer p { font-size: .75rem; color: rgba(255,255,255,.35); }
        footer a { color: var(--gold); text-decoration: none; font-size: .75rem; }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .hero-right { display: none; }
            .hero-left { padding: 7rem 2rem 4rem; }
            .section-jalur { padding: 4rem 2rem; }
            .jalur-grid { grid-template-columns: 1fr; }
            nav { padding: 1rem 1.5rem; }
            .nav-links a:not(.nav-btn) { display: none; }
            footer { padding: 1.5rem 2rem; flex-direction: column; gap: .5rem; text-align: center; }
            .section-cta { padding: 4rem 2rem; }
        }
    </style>
</head>
<body>

    {{-- NAV --}}
    <nav>
        <div class="nav-brand">
            <div class="nav-logo">X</div>
            <div class="nav-title">
                Universitas XYZ
                <span>Berdiri 1965</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="#jalur">Jalur Masuk</a>
            <a href="#jadwal">Jadwal</a>
            {{-- <a href="#faq">FAQ</a> --}}
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-btn">Dashboard</a>
                @else
                    <a href="{{ route('login') }}">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-btn">Daftar Sekarang</a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-left">
            <div class="hero-eyebrow">PMB {{ date('Y') }}/{{ date('Y') + 1 }}</div>
            <h1 class="hero-h1">
                Mulai Perjalanan
                <em>Akademikmu</em>
                Bersama Kami
            </h1>
            <p class="hero-sub">
                Universitas XYZ membuka pendaftaran mahasiswa baru untuk tahun akademik
                {{ date('Y') }}/{{ date('Y') + 1 }}. Raih masa depan terbaik bersama
                lebih dari 50 program studi terakreditasi.
            </p>
            <div class="hero-actions">
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="btn-primary">Daftar Sekarang</a>
                @endif
                <a href="#jalur" class="btn-outline">
                    Lihat Jalur Masuk
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <div class="hero-stats">
                <div class="stat-item">
                    <div class="stat-num">50+</div>
                    <div class="stat-lbl">Program Studi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">A</div>
                    <div class="stat-lbl">Akreditasi BAN-PT</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">30K+</div>
                    <div class="stat-lbl">Alumni Aktif</div>
                </div>
            </div>
        </div>

        <div class="hero-right" id="jadwal">
            <div class="hero-visual">
                <div class="hero-card-badge">Jadwal Pendaftaran</div>
                <div class="hero-card-title">Tahun Akademik {{ date('Y') }}/{{ date('Y') + 1 }}</div>
                <div class="timeline">
                    <div class="tl-item">
                        <div class="tl-dot active"></div>
                        <div class="tl-body">
                            <div class="tl-date">1 Jan – 28 Feb {{ date('Y') }}</div>
                            <div class="tl-event">Pendaftaran Jalur Prestasi</div>
                        </div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot"></div>
                        <div class="tl-body">
                            <div class="tl-date">1 Mar – 30 Apr {{ date('Y') }}</div>
                            <div class="tl-event">Pendaftaran Jalur Ujian Tulis</div>
                        </div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot"></div>
                        <div class="tl-body">
                            <div class="tl-date">15 Mei {{ date('Y') }}</div>
                            <div class="tl-event">Pengumuman Hasil Seleksi</div>
                        </div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot"></div>
                        <div class="tl-body">
                            <div class="tl-date">1 – 15 Jun {{ date('Y') }}</div>
                            <div class="tl-event">Daftar Ulang & Registrasi</div>
                        </div>
                    </div>
                    <div class="tl-item">
                        <div class="tl-dot"></div>
                        <div class="tl-body">
                            <div class="tl-date">1 Agustus {{ date('Y') }}</div>
                            <div class="tl-event">Awal Tahun Akademik Baru</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- JALUR MASUK --}}
    <section class="section-jalur" id="jalur">
        <div class="section-label">Jalur Penerimaan</div>
        <h2 class="section-h2">Pilih Jalur Masuk yang Sesuai Untukmu</h2>
        <div class="jalur-grid">
            <div class="jalur-card">
                <div class="jalur-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
                <div class="jalur-name">Jalur Prestasi</div>
                <p class="jalur-desc">Berdasarkan nilai rapor, prestasi akademik, dan non-akademik. Tanpa ujian tulis.</p>
                <span class="jalur-badge">Tanpa Ujian Tulis</span>
            </div>
            <div class="jalur-card">
                <div class="jalur-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </div>
                <div class="jalur-name">Ujian Tulis</div>
                <p class="jalur-desc">Seleksi melalui ujian tertulis meliputi tes kemampuan akademik dan bidang ilmu.</p>
                <span class="jalur-badge">Ujian Online/Offline</span>
            </div>
            <div class="jalur-card">
                <div class="jalur-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="jalur-name">Jalur Internasional</div>
                <p class="jalur-desc">Khusus untuk calon mahasiswa dari luar negeri dengan nilai IELTS/TOEFL memadai.</p>
                <span class="jalur-badge">Mahasiswa Asing</span>
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section-cta">
        <h2 class="cta-h2">Siap Memulai Pendaftaran?</h2>
        <p class="cta-sub">Buat akun sekarang dan ikuti langkah-langkah pendaftaran dengan mudah.</p>
        <div class="cta-actions">
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="btn-primary">Buat Akun Pendaftar</a>
            @endif
            @if (Route::has('login'))
                <a href="{{ route('login') }}" class="btn-ghost">Sudah Punya Akun</a>
            @endif
        </div>
    </section>

    <footer>
        <p>© {{ date('Y') }} Universitas XYZ. Hak cipta dilindungi.</p>
        <a href="mailto:pmb@xyz.ac.id">pmb@xyz.ac.id</a>
    </footer>

</body>
</html>