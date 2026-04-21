<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>CV - {{ $user->name }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
        }
        @page {
            margin: 40px 50px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #eee;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0;
            font-size: 24pt;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            font-size: 12pt;
            color: #7f8c8d;
        }
        .main-table {
            width: 100%;
            border-spacing: 0;
        }
        .main-table > tbody > tr > td {
            vertical-align: top;
        }
        .sidebar {
            width: 200px;
            padding-right: 20px;
        }
        .content {
            padding-left: 20px;
            border-left: 1px solid #eee;
        }
        .section-title {
            font-size: 14pt;
            font-weight: bold;
            color: #34495e;
            border-bottom: 1px solid #bdc3c7;
            padding-bottom: 5px;
            margin-top: 0;
            margin-bottom: 15px;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            margin: 0 auto 15px auto;
            display: block;
            object-fit: cover;
        }
        .sidebar-section {
            margin-bottom: 20px;
        }
        .sidebar-section h3 {
            font-size: 12pt;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }
        .sidebar-section p, .sidebar-section a {
            margin: 0;
            font-size: 9pt;
            color: #555;
            text-decoration: none;
            word-wrap: break-word;
        }
        .item {
            margin-bottom: 15px;
        }
        .item-header {
            margin-bottom: 3px;
        }
        .item-title {
            font-weight: bold;
            font-size: 11pt;
        }
        .item-subtitle {
            font-weight: bold;
            color: #555;
        }
        .item-date {
            float: right;
            font-size: 9pt;
            color: #7f8c8d;
        }
        .item-description {
            font-size: 10pt;
            color: #555;
            text-align: justify;
        }
        .skills-container {
            width: 100%;
        }
        .skill {
            width: 48%;
            display: inline-block;
            margin-bottom: 8px;
        }
        .skill-name {
            font-weight: bold;
        }
        .skill-level {
            font-size: 9pt;
            color: #7f8c8d;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $user->name }}</h1>
        <p>{{ $user->jabatan ?? 'Jabatan Belum Diisi' }}</p>
    </div>

    <table class="main-table">
        <tr>
            <td class="sidebar">
                @if($user->attachment_foto_profile)
                    <img src="{{ storage_path('app/public/' . $user->attachment_foto_profile) }}" alt="Foto Profil" class="profile-pic">
                @endif

                <div class="sidebar-section">
                    <h3>Kontak</h3>
                    <p>{{ $user->email }}</p>
                    @if($user->no_wa)<p>{{ $user->no_wa }}</p>@endif
                    @if($user->address)<p style="margin-top: 5px;">{{ $user->address }}</p>@endif
                </div>

                @if($user->cv && ($user->cv->linkedin_url || $user->cv->github_url || $user->cv->portfolio_url))
                <div class="sidebar-section">
                    <h3>Tautan</h3>
                    @if($user->cv->linkedin_url)<p><a href="{{ $user->cv->linkedin_url }}">LinkedIn</a></p>@endif
                    @if($user->cv->github_url)<p><a href="{{ $user->cv->github_url }}">GitHub</a></p>@endif
                    @if($user->cv->portfolio_url)<p><a href="{{ $user->cv->portfolio_url }}">Portofolio</a></p>@endif
                </div>
                @endif
            </td>
            <td class="content">
                @if($user->cv && $user->cv->ringkasan_pribadi)
                <div class="section">
                    <h2 class="section-title">Ringkasan Pribadi</h2>
                    <p class="item-description">{{ $user->cv->ringkasan_pribadi }}</p>
                </div>
                @endif

                @if($user->pengalamanKerjas->isNotEmpty())
                <div class="section" style="margin-top: 20px;">
                    <h2 class="section-title">Pengalaman Kerja</h2>
                    @foreach($user->pengalamanKerjas->sortByDesc('tanggal_mulai') as $pengalaman)
                    <div class="item">
                        <div class="item-header">
                            <span class="item-date">
                                {{ \Carbon\Carbon::parse($pengalaman->tanggal_mulai)->isoFormat('MMM YYYY') }} - 
                                {{ $pengalaman->tanggal_selesai ? \Carbon\Carbon::parse($pengalaman->tanggal_selesai)->isoFormat('MMM YYYY') : 'Sekarang' }}
                            </span>
                            <span class="item-title">{{ $pengalaman->posisi }}</span>
                        </div>
                        <div class="item-subtitle">{{ $pengalaman->perusahaan }}</div>
                        @if($pengalaman->deskripsi)
                        <p class="item-description">{{ $pengalaman->deskripsi }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                @if($user->pendidikans->isNotEmpty())
                <div class="section" style="margin-top: 20px;">
                    <h2 class="section-title">Pendidikan</h2>
                    @foreach($user->pendidikans->sortByDesc('tahun_lulus') as $pendidikan)
                    <div class="item">
                        <div class="item-header">
                            <span class="item-date">{{ $pendidikan->tahun_lulus }}</span>
                            <span class="item-title">{{ $pendidikan->institusi }}</span>
                        </div>
                        <div class="item-subtitle">{{ $pendidikan->jenjang }} - {{ $pendidikan->jurusan }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                @if($user->keahlians->isNotEmpty())
                <div class="section" style="margin-top: 20px;">
                    <h2 class="section-title">Keahlian</h2>
                    <div class="skills-container">
                        @foreach($user->keahlians as $keahlian)
                        <div class="skill">
                            <span class="skill-name">{{ $keahlian->nama_keahlian }}</span>
                            <span class="skill-level">({{ $keahlian->tingkat }})</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </td>
        </tr>
    </table>

</body>
</html>