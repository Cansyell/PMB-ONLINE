<x-guest-layout>
    <div class="auth-box">

        <h1 class="auth-box-title">Selamat Datang</h1>
        <p class="auth-box-sub">
            Masuk ke portal pendaftaran. Belum punya akun?
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Daftar di sini</a>
            @endif
        </p>

        {{-- Session Status --}}
        @if (session('status'))
            <div class="form-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email</label>
                <input id="email"
                       class="form-input"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@email.com"
                       required autofocus autocomplete="username">
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label class="form-label" for="password">Kata Sandi</label>
                <input id="password"
                       class="form-input"
                       type="password"
                       name="password"
                       placeholder="••••••••"
                       required autocomplete="current-password">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Remember + Forgot --}}
            <div class="checkbox-row">
                <label class="checkbox-label">
                    <input type="checkbox" name="remember" id="remember_me">
                    <span>Ingat saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="link-forgot" href="{{ route('password.request') }}">
                        Lupa kata sandi?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-submit">Masuk ke Portal</button>
        </form>

        <div class="auth-divider">atau</div>

        <div class="auth-switch">
            Belum terdaftar?
            @if (Route::has('register'))
                <a href="{{ route('register') }}">Buat Akun Sekarang</a>
            @endif
        </div>

    </div>
</x-guest-layout>