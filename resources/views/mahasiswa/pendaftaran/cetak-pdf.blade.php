<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Bukti Pendaftaran</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            color: #1f2937;
            background: #fff;
        }

        /* ── Kartu Wrapper ── */
        .kartu {
            border: 2px solid #4338ca;
            border-radius: 10px;
            overflow: hidden;
            margin: 24px auto;
            max-width: 680px;
        }

        /* ── Header ── */
        .header {
            background-color: #4338ca;
            padding: 16px 24px;
            color: #fff;
        }
        .header-inner {
            display: table;
            width: 100%;
        }
        .header-logo-cell {
            display: table-cell;
            vertical-align: middle;
            width: 60px;
        }
        .header-logo {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #fff;
            display: table-cell;
            vertical-align: middle;
            text-align: center;
        }
        .header-text-cell {
            display: table-cell;
            vertical-align: middle;
            padding-left: 14px;
        }
        .header-title {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        .header-subtitle {
            font-size: 10px;
            color: #c7d2fe;
            margin-top: 3px;
        }
        .header-no {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 10px;
            color: #c7d2fe;
            white-space: nowrap;
        }

        /* ── Body ── */
        .body {
            padding: 20px 24px;
        }

        /* ── Foto + Identitas ── */
        .profil-table {
            display: table;
            width: 100%;
            margin-bottom: 18px;
        }
        .profil-foto-cell {
            display: table-cell;
            vertical-align: top;
            width: 110px;
        }
        .foto-box {
            width: 100px;
            height: 120px;
            border: 2px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
            background: #f3f4f6;
            text-align: center;
            line-height: 120px;
            color: #9ca3af;
            font-size: 10px;
        }
        .foto-box img {
            width: 100px;
            height: 120px;
            object-fit: cover;
        }
        .profil-data-cell {
            display: table-cell;
            vertical-align: top;
            padding-left: 16px;
        }
        .nama {
            font-size: 15px;
            font-weight: bold;
            color: #1e1b4b;
            margin-bottom: 6px;
        }
        .badge {
            display: inline-block;
            background: #e0e7ff;
            color: #3730a3;
            font-size: 9px;
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 20px;
            margin-bottom: 10px;
            letter-spacing: 0.5px;
        }
        .info-grid {
            display: table;
            width: 100%;
        }
        .info-row {
            display: table-row;
        }
        .info-label {
            display: table-cell;
            color: #6b7280;
            font-size: 10px;
            padding: 2px 0;
            width: 120px;
            vertical-align: top;
        }
        .info-sep {
            display: table-cell;
            color: #9ca3af;
            padding: 2px 6px;
            vertical-align: top;
        }
        .info-value {
            display: table-cell;
            color: #111827;
            font-weight: bold;
            font-size: 10px;
            padding: 2px 0;
            vertical-align: top;
        }

        /* ── Section ── */
        .section {
            margin-bottom: 14px;
        }
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #4338ca;
            text-transform: uppercase;
            letter-spacing: 1px;
            border-bottom: 1px solid #e0e7ff;
            padding-bottom: 4px;
            margin-bottom: 8px;
        }
        .data-grid {
            display: table;
            width: 100%;
            border-collapse: collapse;
        }
        .data-row {
            display: table-row;
        }
        .data-cell {
            display: table-cell;
            width: 50%;
            padding: 4px 0;
            vertical-align: top;
        }
        .data-cell-label {
            font-size: 9px;
            color: #6b7280;
        }
        .data-cell-value {
            font-size: 10px;
            font-weight: bold;
            color: #111827;
        }

        /* ── Divider ── */
        .divider {
            border: none;
            border-top: 1px dashed #e0e7ff;
            margin: 14px 0;
        }

        /* ── Media Foto kecil ── */
        .media-section {
            margin-top: 4px;
        }

        /* ── Footer ── */
        .footer {
            background: #f5f3ff;
            border-top: 1px solid #e0e7ff;
            padding: 10px 24px;
            display: table;
            width: 100%;
        }
        .footer-left {
            display: table-cell;
            vertical-align: middle;
            font-size: 9px;
            color: #6b7280;
        }
        .footer-right {
            display: table-cell;
            vertical-align: middle;
            text-align: right;
            font-size: 9px;
            color: #6b7280;
        }
        .footer-stamp {
            display: inline-block;
            border: 2px solid #4338ca;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            text-align: center;
            line-height: 14px;
            color: #4338ca;
            font-size: 8px;
            font-weight: bold;
            padding-top: 12px;
        }
        .watermark {
            text-align: center;
            font-size: 9px;
            color: #a5b4fc;
            margin-top: 6px;
            letter-spacing: 2px;
        }
    </style>
</head>
<body>

<div class="kartu">

    {{-- ── HEADER ── --}}
    <div class="header">
        <div class="header-inner">
            <div class="header-text-cell">
                <div class="header-title">BUKTI PENDAFTARAN MAHASISWA</div>
                <div class="header-subtitle">Dokumen resmi pendaftaran — harap disimpan</div>
            </div>
            <div class="header-no">
                No. Ref: <strong>#{{ str_pad($pendaftaran->id, 6, '0', STR_PAD_LEFT) }}</strong><br>
                Terdaftar: {{ $pendaftaran->created_at->format('d/m/Y') }}
            </div>
        </div>
    </div>

    {{-- ── BODY ── --}}
    <div class="body">

        {{-- Foto + Identitas Utama --}}
        <div class="profil-table">
            <div class="profil-foto-cell">
                <div class="foto-box">
                    @if($fotoBase64)
                        <img src="{{ $fotoBase64 }}" alt="Foto">
                    @else
                        Tidak ada foto
                    @endif
                </div>
            </div>
            <div class="profil-data-cell">
                <div class="nama">{{ $pendaftaran->nama_lengkap }}</div>
                <div class="badge">PENDAFTAR</div>
                <div class="info-grid">
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-sep">:</div>
                        <div class="info-value">{{ $pendaftaran->email }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">No. HP</div>
                        <div class="info-sep">:</div>
                        <div class="info-value">{{ $pendaftaran->no_hp }}</div>
                    </div>
                    @if($pendaftaran->no_telepon)
                    <div class="info-row">
                        <div class="info-label">No. Telepon</div>
                        <div class="info-sep">:</div>
                        <div class="info-value">{{ $pendaftaran->no_telepon }}</div>
                    </div>
                    @endif
                    <div class="info-row">
                        <div class="info-label">Kewarganegaraan</div>
                        <div class="info-sep">:</div>
                        <div class="info-value">{{ $pendaftaran->kewarganegaraan }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Agama</div>
                        <div class="info-sep">:</div>
                        <div class="info-value">{{ $pendaftaran->agama?->nama ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <hr class="divider">

        {{-- Data Pribadi --}}
        <div class="section">
            <div class="section-title">Data Pribadi</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-cell-label">Jenis Kelamin</div>
                        <div class="data-cell-value">{{ $pendaftaran->jenis_kelamin }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-cell-label">Status Menikah</div>
                        <div class="data-cell-value">{{ $pendaftaran->status_menikah }}</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Data Kelahiran --}}
        <div class="section">
            <div class="section-title">Data Kelahiran</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-cell-label">Tempat Lahir</div>
                        <div class="data-cell-value">{{ $pendaftaran->tempat_lahir }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-cell-label">Tanggal Lahir</div>
                        <div class="data-cell-value">{{ $pendaftaran->tanggal_lahir?->format('d F Y') }}</div>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-cell-label">
                            {{ $pendaftaran->lahir_luar_negeri ? 'Negara Lahir' : 'Provinsi Lahir' }}
                        </div>
                        <div class="data-cell-value">
                            {{ $pendaftaran->lahir_luar_negeri
                                ? ($pendaftaran->negara_lahir ?? '-')
                                : ($pendaftaran->provinceLahir?->name ?? '-') }}
                        </div>
                    </div>
                    @if(!$pendaftaran->lahir_luar_negeri)
                    <div class="data-cell">
                        <div class="data-cell-label">Kota/Kab. Lahir</div>
                        <div class="data-cell-value">{{ $pendaftaran->cityLahir?->name ?? '-' }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Alamat KTP --}}
        <div class="section">
            <div class="section-title">Alamat KTP</div>
            <div class="data-grid">
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-cell-label">Alamat</div>
                        <div class="data-cell-value">{{ $pendaftaran->alamat_ktp }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-cell-label">Provinsi</div>
                        <div class="data-cell-value">{{ $pendaftaran->province?->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-cell-label">Kota/Kabupaten</div>
                        <div class="data-cell-value">{{ $pendaftaran->city?->name ?? '-' }}</div>
                    </div>
                    <div class="data-cell">
                        <div class="data-cell-label">Kecamatan</div>
                        <div class="data-cell-value">{{ $pendaftaran->district?->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="data-row">
                    <div class="data-cell">
                        <div class="data-cell-label">Kelurahan/Desa</div>
                        <div class="data-cell-value">{{ $pendaftaran->village?->name ?? '-' }}</div>
                    </div>
                    <div class="data-cell"></div>
                </div>
            </div>
        </div>

        {{-- Alamat Sekarang --}}
        <div class="section">
            <div class="section-title">Alamat Saat Ini</div>
            @if($pendaftaran->same_as_ktp)
                <div style="font-size:10px; color:#4338ca; font-style:italic;">
                    Sama dengan Alamat KTP
                </div>
            @else
                <div class="data-grid">
                    <div class="data-row">
                        <div class="data-cell">
                            <div class="data-cell-label">Alamat</div>
                            <div class="data-cell-value">{{ $pendaftaran->alamat_sekarang ?? '-' }}</div>
                        </div>
                        <div class="data-cell">
                            <div class="data-cell-label">Provinsi</div>
                            <div class="data-cell-value">{{ $pendaftaran->provinceSekarang?->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="data-row">
                        <div class="data-cell">
                            <div class="data-cell-label">Kota/Kabupaten</div>
                            <div class="data-cell-value">{{ $pendaftaran->citySekarang?->name ?? '-' }}</div>
                        </div>
                        <div class="data-cell">
                            <div class="data-cell-label">Kecamatan</div>
                            <div class="data-cell-value">{{ $pendaftaran->districtSekarang?->name ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="data-row">
                        <div class="data-cell">
                            <div class="data-cell-label">Kelurahan/Desa</div>
                            <div class="data-cell-value">{{ $pendaftaran->villageSekarang?->name ?? '-' }}</div>
                        </div>
                        <div class="data-cell"></div>
                    </div>
                </div>
            @endif
        </div>

    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
        <div class="footer-left">
            <div>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</div>
            <div style="margin-top:3px; color:#9ca3af;">
                Dokumen ini dicetak secara otomatis dan sah tanpa tanda tangan basah.
            </div>
        </div>
        <div class="footer-right">
            <div class="footer-stamp">
                TERDAFTAR<br>
                {{ now()->format('Y') }}
            </div>
        </div>
    </div>

</div>

<div class="watermark">* * * DOKUMEN RESMI PENDAFTARAN MAHASISWA * * *</div>

</body>
</html>