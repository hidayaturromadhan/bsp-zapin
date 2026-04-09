@extends('layouts.app')

@section('title', $locale === 'id' ? 'Syarat & Ketentuan' : 'Terms & Conditions')

@section('content')

<style>
    .legal-page {
        display: flex;
        flex-direction: column;
        gap: 30px;
    }

    .legal-hero {
        position: relative;
        overflow: hidden;
        border-radius: 28px;
        padding: 42px 42px 38px;
        background:
            radial-gradient(circle at top right, rgba(255,255,255,.18), transparent 28%),
            linear-gradient(135deg, #173f08 0%, #21560e 45%, #2f7d32 100%);
        color: #fff;
        box-shadow: 0 18px 40px rgba(23, 63, 8, .18);
    }

    .legal-hero::before {
        content: '';
        position: absolute;
        right: -40px;
        top: -40px;
        width: 180px;
        height: 180px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }

    .legal-hero::after {
        content: '';
        position: absolute;
        left: -26px;
        bottom: -26px;
        width: 110px;
        height: 110px;
        border-radius: 50%;
        background: rgba(255,255,255,.06);
    }

    .legal-hero-inner {
        position: relative;
        z-index: 1;
        max-width: 860px;
    }

    .legal-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .14em;
        text-transform: uppercase;
        opacity: .92;
        margin-bottom: 14px;
    }

    .legal-eyebrow::before {
        content: '';
        width: 30px;
        height: 2px;
        border-radius: 999px;
        background: rgba(255,255,255,.7);
    }

    .legal-title {
        margin: 0 0 14px;
        font-size: clamp(30px, 5vw, 44px);
        line-height: 1.1;
        font-weight: 800;
        letter-spacing: -.03em;
        color: #fff;
    }

    .legal-desc {
        margin: 0;
        max-width: 760px;
        font-size: 15px;
        line-height: 1.85;
        color: rgba(255,255,255,.9);
    }

    .legal-meta {
        margin-top: 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .legal-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 14px;
        border-radius: 999px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        font-size: 12px;
        font-weight: 600;
        color: rgba(255,255,255,.94);
    }

    .legal-badge-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #d4f5d2;
        flex-shrink: 0;
    }

    .legal-layout {
        display: grid;
        grid-template-columns: 280px minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    .legal-sidebar {
        position: sticky;
        top: 92px;
    }

    .legal-nav-card,
    .legal-contact-card,
    .legal-content {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 22px;
        box-shadow: 0 10px 30px rgba(15, 23, 42, .05);
    }

    .legal-nav-card {
        padding: 18px;
        margin-bottom: 16px;
    }

    .legal-side-title {
        margin: 0 0 12px;
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #173f08;
    }

    .legal-nav {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .legal-nav a {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border-radius: 12px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        transition: background .15s ease, color .15s ease, transform .15s ease;
    }

    .legal-nav a::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #c8d2c8;
        flex-shrink: 0;
        transition: background .15s ease;
    }

    .legal-nav a:hover {
        background: #f4f9f2;
        color: #173f08;
        transform: translateX(2px);
    }

    .legal-nav a:hover::before {
        background: #2f7d32;
    }

    .legal-contact-card {
        padding: 18px;
    }

    .legal-contact-text {
        margin: 0;
        font-size: 13.5px;
        line-height: 1.8;
        color: #6b7280;
    }

    .legal-contact-text strong {
        color: #111827;
    }

    .legal-content {
        padding: 30px 30px 10px;
    }

    .legal-section {
        margin-bottom: 28px;
        scroll-margin-top: 92px;
    }

    .legal-section h2 {
        margin: 0 0 12px;
        font-size: 22px;
        line-height: 1.3;
        font-weight: 800;
        color: #111827;
        letter-spacing: -.02em;
    }

    .legal-section h3 {
        margin: 18px 0 8px;
        font-size: 17px;
        line-height: 1.4;
        font-weight: 700;
        color: #173f08;
    }

    .legal-section p {
        margin: 0 0 14px;
        font-size: 14.5px;
        line-height: 1.95;
        color: #4b5563;
    }

    .legal-section ul {
        margin: 0 0 14px 0;
        padding-left: 18px;
        color: #4b5563;
    }

    .legal-section li {
        margin-bottom: 8px;
        font-size: 14.5px;
        line-height: 1.85;
    }

    .legal-highlight {
        padding: 16px 18px;
        border-radius: 16px;
        background: linear-gradient(180deg, #f8fbf7 0%, #eef5eb 100%);
        border: 1px solid #dce9d7;
        margin: 16px 0 18px;
    }

    .legal-highlight p {
        margin: 0;
        color: #21560e;
        font-weight: 600;
    }

    .legal-divider {
        height: 1px;
        background: #edf1ee;
        margin: 0 0 28px;
    }

    @media (max-width: 980px) {
        .legal-layout {
            grid-template-columns: 1fr;
        }

        .legal-sidebar {
            position: static;
        }
    }

    @media (max-width: 680px) {
        .legal-hero {
            padding: 28px 22px 26px;
            border-radius: 22px;
        }

        .legal-content {
            padding: 22px 18px 4px;
            border-radius: 18px;
        }

        .legal-nav-card,
        .legal-contact-card {
            border-radius: 18px;
        }

        .legal-title {
            font-size: 30px;
        }

        .legal-desc,
        .legal-section p,
        .legal-section li {
            font-size: 14px;
        }
    }
</style>

<div class="legal-page">

    <section class="legal-hero">
        <div class="legal-hero-inner">
            <div class="legal-eyebrow">
                {{ $locale === 'id' ? 'Ketentuan Layanan' : 'Service Terms' }}
            </div>

            <h1 class="legal-title">
                {{ $locale === 'id' ? 'Syarat & Ketentuan' : 'Terms & Conditions' }}
            </h1>

            <p class="legal-desc">
                {{ $locale === 'id'
                    ? 'Syarat dan Ketentuan ini mengatur penggunaan situs web, informasi, serta layanan digital PT Bumi Siak Pusako Zapin. Dengan mengakses dan menggunakan situs ini, pengguna dianggap telah memahami dan menyetujui ketentuan yang berlaku.'
                    : 'These Terms & Conditions govern the use of the website, information, and digital services of PT Bumi Siak Pusako Zapin. By accessing and using this website, users are deemed to have understood and agreed to the applicable terms.' }}
            </p>

            <div class="legal-meta">
                <span class="legal-badge">
                    <span class="legal-badge-dot"></span>
                    {{ $locale === 'id' ? 'Akses Resmi' : 'Official Access' }}
                </span>
                <span class="legal-badge">
                    <span class="legal-badge-dot"></span>
                    {{ $locale === 'id' ? 'Penggunaan Wajar' : 'Fair Use' }}
                </span>
                <span class="legal-badge">
                    <span class="legal-badge-dot"></span>
                    {{ $locale === 'id' ? 'Hak Kekayaan Intelektual' : 'Intellectual Property' }}
                </span>
            </div>
        </div>
    </section>

    <div class="legal-layout">
        <aside class="legal-sidebar">
            <div class="legal-nav-card">
                <h2 class="legal-side-title">
                    {{ $locale === 'id' ? 'Isi Ketentuan' : 'Contents' }}
                </h2>

                <nav class="legal-nav">
                    <a href="#pendahuluan">{{ $locale === 'id' ? 'Pendahuluan' : 'Introduction' }}</a>
                    <a href="#penggunaan">{{ $locale === 'id' ? 'Penggunaan Website' : 'Website Usage' }}</a>
                    <a href="#hak-cipta">{{ $locale === 'id' ? 'Hak Cipta' : 'Copyright' }}</a>
                    <a href="#tanggung-jawab">{{ $locale === 'id' ? 'Tanggung Jawab' : 'Responsibility' }}</a>
                    <a href="#perubahan">{{ $locale === 'id' ? 'Perubahan Ketentuan' : 'Changes to Terms' }}</a>
                    <a href="#kontak">{{ $locale === 'id' ? 'Kontak' : 'Contact' }}</a>
                </nav>
            </div>

            <div class="legal-contact-card">
                <h2 class="legal-side-title">
                    {{ $locale === 'id' ? 'Informasi Tambahan' : 'Additional Information' }}
                </h2>
                <p class="legal-contact-text">
                    {{ $locale === 'id'
                        ? 'Apabila Anda memerlukan klarifikasi mengenai syarat penggunaan situs ini, silakan menghubungi perusahaan melalui saluran komunikasi resmi.'
                        : 'If you require clarification regarding the terms of use of this website, please contact the company through official communication channels.' }}
                </p>
            </div>
        </aside>

        <section class="legal-content">
            <div class="legal-section" id="pendahuluan">
                <h2>{{ $locale === 'id' ? 'Pendahuluan' : 'Introduction' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Situs ini disediakan sebagai media informasi resmi perusahaan. Semua konten, data, dan materi yang tersedia ditujukan untuk memberikan informasi yang relevan kepada masyarakat, mitra, dan para pemangku kepentingan.'
                        : 'This website is provided as an official company information platform. All content, data, and materials available are intended to provide relevant information to the public, partners, and stakeholders.' }}
                </p>

                <div class="legal-highlight">
                    <p>
                        {{ $locale === 'id'
                            ? 'Dengan menggunakan website ini, Anda menyetujui bahwa akses dan penggunaan dilakukan sesuai hukum, norma, dan ketentuan internal perusahaan yang berlaku.'
                            : 'By using this website, you agree that access and use shall be carried out in accordance with applicable laws, norms, and the company’s internal policies.' }}
                    </p>
                </div>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="penggunaan">
                <h2>{{ $locale === 'id' ? 'Penggunaan Website' : 'Website Usage' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Pengguna wajib menggunakan website ini secara sah, bertanggung jawab, dan tidak melakukan tindakan yang dapat merugikan perusahaan, pengguna lain, maupun integritas sistem digital yang tersedia.'
                        : 'Users must use this website lawfully and responsibly, and must not engage in actions that may harm the company, other users, or the integrity of the digital systems provided.' }}
                </p>

                <h3>{{ $locale === 'id' ? 'Penggunaan yang diperbolehkan' : 'Permitted use' }}</h3>
                <ul>
                    <li>{{ $locale === 'id' ? 'Mengakses informasi perusahaan untuk tujuan yang sah dan wajar.' : 'Accessing company information for lawful and reasonable purposes.' }}</li>
                    <li>{{ $locale === 'id' ? 'Menggunakan layanan digital sesuai fungsi yang telah disediakan.' : 'Using digital services according to their intended functions.' }}</li>
                    <li>{{ $locale === 'id' ? 'Tidak menyalahgunakan, mengganggu, atau merusak sistem.' : 'Not misusing, disrupting, or damaging the system.' }}</li>
                </ul>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="hak-cipta">
                <h2>{{ $locale === 'id' ? 'Hak Cipta' : 'Copyright' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Seluruh konten yang terdapat pada website ini, termasuk namun tidak terbatas pada teks, gambar, desain, logo, dokumen, dan materi publikasi, dilindungi oleh ketentuan hak kekayaan intelektual yang berlaku.'
                        : 'All content available on this website, including but not limited to text, images, designs, logos, documents, and publication materials, is protected by applicable intellectual property regulations.' }}
                </p>
                <p>
                    {{ $locale === 'id'
                        ? 'Penggunaan kembali, distribusi, reproduksi, atau modifikasi konten tanpa izin tertulis dari pihak perusahaan tidak diperkenankan, kecuali dinyatakan lain secara tegas.'
                        : 'Reuse, distribution, reproduction, or modification of content without written permission from the company is not permitted unless explicitly stated otherwise.' }}
                </p>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="tanggung-jawab">
                <h2>{{ $locale === 'id' ? 'Tanggung Jawab' : 'Responsibility' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Perusahaan berupaya memastikan bahwa informasi yang disajikan pada website akurat dan mutakhir. Namun demikian, perusahaan tidak menjamin bahwa seluruh informasi akan selalu lengkap, bebas kesalahan, atau sesuai untuk kebutuhan tertentu pengguna.'
                        : 'The company strives to ensure that the information presented on the website is accurate and up to date. However, the company does not guarantee that all information will always be complete, error-free, or suitable for the user’s specific needs.' }}
                </p>
                <p>
                    {{ $locale === 'id'
                        ? 'Penggunaan informasi dari website ini sepenuhnya menjadi tanggung jawab pengguna.'
                        : 'The use of information from this website is entirely at the user’s own responsibility.' }}
                </p>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="perubahan">
                <h2>{{ $locale === 'id' ? 'Perubahan Ketentuan' : 'Changes to Terms' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Perusahaan berhak memperbarui, mengubah, atau menyesuaikan Syarat & Ketentuan ini sewaktu-waktu sesuai kebutuhan operasional, hukum, maupun kebijakan internal. Versi terbaru yang ditampilkan pada halaman ini menjadi acuan yang berlaku.'
                        : 'The company reserves the right to update, modify, or adjust these Terms & Conditions at any time in accordance with operational, legal, or internal policy needs. The latest version displayed on this page shall be the applicable reference.' }}
                </p>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="kontak">
                <h2>{{ $locale === 'id' ? 'Kontak' : 'Contact' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Untuk pertanyaan lebih lanjut mengenai Syarat & Ketentuan ini, Anda dapat menghubungi perusahaan melalui halaman kontak atau kanal resmi yang tersedia.'
                        : 'For further questions regarding these Terms & Conditions, you may contact the company through the contact page or available official channels.' }}
                </p>
            </div>
        </section>
    </div>
</div>

@endsection