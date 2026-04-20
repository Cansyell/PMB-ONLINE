<x-guest-layout>
    <div class="auth-box">

        <h1 class="auth-box-title">Buat Akun</h1>
        <p class="auth-box-sub">
            Daftarkan diri sebagai calon mahasiswa.
            Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
        </p>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            {{-- Name --}}
            <div class="form-group">
                <label class="form-label" for="name">Nama Lengkap</label>
                <input id="name"
                       class="form-input"
                       type="text"
                       name="name"
                       value="{{ old('name') }}"
                       placeholder="Sesuai kartu identitas"
                       required autofocus autocomplete="name">
                @error('name')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label class="form-label" for="email">Alamat Email Aktif</label>
                <input id="email"
                       class="form-input"
                       type="email"
                       name="email"
                       value="{{ old('email') }}"
                       placeholder="nama@email.com"
                       required autocomplete="username">
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
                       placeholder="Minimal 8 karakter"
                       required autocomplete="new-password">
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            {{-- Confirm Password --}}
            <div class="form-group" style="margin-bottom:1.5rem;">
                <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation"
                       class="form-input"
                       type="password"
                       name="password_confirmation"
                       placeholder="Ulangi kata sandi"
                       required autocomplete="new-password">
                @error('password_confirmation')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Daftar Sekarang</button>
        </form>

        <div class="auth-divider">atau</div>

        <div class="auth-switch">
            Sudah punya akun?
            <a href="{{ route('login') }}">Masuk ke Portal</a>
        </div>

    </div>
</x-guest-layout>