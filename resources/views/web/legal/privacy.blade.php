@extends('layouts.app')

@section('title', $locale === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy')

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
                {{ $locale === 'id' ? 'Ketentuan Informasi' : 'Information Policy' }}
            </div>

            <h1 class="legal-title">
                {{ $locale === 'id' ? 'Kebijakan Privasi' : 'Privacy Policy' }}
            </h1>

            <p class="legal-desc">
                {{ $locale === 'id'
                    ? 'PT Bumi Siak Pusako Zapin berkomitmen menjaga kerahasiaan, keamanan, dan integritas data pribadi pengguna serta memastikan pemrosesan informasi dilakukan secara bertanggung jawab, transparan, dan sesuai ketentuan yang berlaku.'
                    : 'PT Bumi Siak Pusako Zapin is committed to maintaining the confidentiality, security, and integrity of users’ personal data, while ensuring that information is processed responsibly, transparently, and in accordance with applicable requirements.' }}
            </p>

            <div class="legal-meta">
                <span class="legal-badge">
                    <span class="legal-badge-dot"></span>
                    {{ $locale === 'id' ? 'Perlindungan Data' : 'Data Protection' }}
                </span>
                <span class="legal-badge">
                    <span class="legal-badge-dot"></span>
                    {{ $locale === 'id' ? 'Transparansi Informasi' : 'Information Transparency' }}
                </span>
                <span class="legal-badge">
                    <span class="legal-badge-dot"></span>
                    {{ $locale === 'id' ? 'Keamanan Sistem' : 'System Security' }}
                </span>
            </div>
        </div>
    </section>

    <div class="legal-layout">
        <aside class="legal-sidebar">
            <div class="legal-nav-card">
                <h2 class="legal-side-title">
                    {{ $locale === 'id' ? 'Isi Kebijakan' : 'Policy Contents' }}
                </h2>

                <nav class="legal-nav">
                    <a href="#pendahuluan">{{ $locale === 'id' ? 'Pendahuluan' : 'Introduction' }}</a>
                    <a href="#data">{{ $locale === 'id' ? 'Data yang Dikumpulkan' : 'Data Collected' }}</a>
                    <a href="#penggunaan">{{ $locale === 'id' ? 'Penggunaan Data' : 'Use of Data' }}</a>
                    <a href="#keamanan">{{ $locale === 'id' ? 'Keamanan Informasi' : 'Information Security' }}</a>
                    <a href="#hak">{{ $locale === 'id' ? 'Hak Pengguna' : 'User Rights' }}</a>
                    <a href="#kontak">{{ $locale === 'id' ? 'Kontak' : 'Contact' }}</a>
                </nav>
            </div>

            <div class="legal-contact-card">
                <h2 class="legal-side-title">
                    {{ $locale === 'id' ? 'Butuh Bantuan?' : 'Need Assistance?' }}
                </h2>
                <p class="legal-contact-text">
                    {{ $locale === 'id'
                        ? 'Untuk pertanyaan terkait privasi data atau permintaan informasi lebih lanjut, silakan hubungi tim kami melalui kanal komunikasi resmi perusahaan.'
                        : 'For questions related to data privacy or requests for further information, please contact our team through the company’s official communication channels.' }}
                </p>
            </div>
        </aside>

        <section class="legal-content">
            <div class="legal-section" id="pendahuluan">
                <h2>{{ $locale === 'id' ? 'Pendahuluan' : 'Introduction' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Kami menghargai kepercayaan setiap pengguna yang mengakses layanan, situs, dan informasi perusahaan. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, menyimpan, dan melindungi data yang diberikan oleh pengguna.'
                        : 'We value the trust of every user who accesses our services, website, and company information. This Privacy Policy explains how we collect, use, store, and protect data provided by users.' }}
                </p>

                <div class="legal-highlight">
                    <p>
                        {{ $locale === 'id'
                            ? 'Dengan menggunakan layanan kami, pengguna dianggap telah memahami bahwa informasi tertentu dapat diproses untuk tujuan operasional, komunikasi, dan peningkatan layanan.'
                            : 'By using our services, users are deemed to understand that certain information may be processed for operational purposes, communication, and service improvement.' }}
                    </p>
                </div>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="data">
                <h2>{{ $locale === 'id' ? 'Data yang Dikumpulkan' : 'Data Collected' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Kami dapat mengumpulkan data yang secara langsung diberikan oleh pengguna maupun data yang dihasilkan dari penggunaan layanan digital perusahaan.'
                        : 'We may collect data directly provided by users as well as data generated through the use of the company’s digital services.' }}
                </p>

                <h3>{{ $locale === 'id' ? 'Contoh data yang dapat dikumpulkan' : 'Examples of data that may be collected' }}</h3>
                <ul>
                    <li>{{ $locale === 'id' ? 'Nama lengkap dan informasi identitas dasar.' : 'Full name and basic identity information.' }}</li>
                    <li>{{ $locale === 'id' ? 'Alamat email, nomor telepon, atau informasi kontak lainnya.' : 'Email address, phone number, or other contact information.' }}</li>
                    <li>{{ $locale === 'id' ? 'Informasi teknis seperti perangkat, browser, dan aktivitas penggunaan situs.' : 'Technical information such as device, browser, and website usage activity.' }}</li>
                </ul>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="penggunaan">
                <h2>{{ $locale === 'id' ? 'Penggunaan Data' : 'Use of Data' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Data yang dikumpulkan digunakan secara terbatas dan proporsional untuk mendukung operasional, peningkatan pengalaman pengguna, penyampaian informasi resmi, serta kebutuhan komunikasi perusahaan.'
                        : 'Collected data is used in a limited and proportionate manner to support operations, improve user experience, deliver official information, and meet the company’s communication needs.' }}
                </p>

                <h3>{{ $locale === 'id' ? 'Tujuan penggunaan data' : 'Purposes of data use' }}</h3>
                <ul>
                    <li>{{ $locale === 'id' ? 'Menyediakan layanan dan informasi yang relevan.' : 'Providing relevant services and information.' }}</li>
                    <li>{{ $locale === 'id' ? 'Menanggapi pertanyaan, permintaan, atau korespondensi pengguna.' : 'Responding to user inquiries, requests, or correspondence.' }}</li>
                    <li>{{ $locale === 'id' ? 'Meningkatkan kualitas layanan, keamanan, dan keandalan sistem.' : 'Improving service quality, system security, and reliability.' }}</li>
                </ul>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="keamanan">
                <h2>{{ $locale === 'id' ? 'Keamanan Informasi' : 'Information Security' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Kami menerapkan langkah-langkah pengamanan yang wajar secara administratif, teknis, dan operasional untuk melindungi data dari akses yang tidak sah, penyalahgunaan, pengungkapan, maupun perubahan yang tidak sesuai.'
                        : 'We implement reasonable administrative, technical, and operational safeguards to protect data from unauthorized access, misuse, disclosure, or improper alteration.' }}
                </p>
                <p>
                    {{ $locale === 'id'
                        ? 'Meskipun demikian, tidak ada sistem digital yang sepenuhnya bebas risiko. Oleh karena itu, pengguna juga diharapkan menjaga kerahasiaan data dan informasi akses mereka.'
                        : 'However, no digital system is entirely risk-free. Therefore, users are also expected to maintain the confidentiality of their data and access information.' }}
                </p>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="hak">
                <h2>{{ $locale === 'id' ? 'Hak Pengguna' : 'User Rights' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Pengguna berhak meminta klarifikasi mengenai data yang diberikan, mengajukan pembaruan informasi, dan menyampaikan pertanyaan mengenai pemrosesan data pribadi sesuai kebijakan yang berlaku.'
                        : 'Users have the right to request clarification regarding submitted data, request information updates, and raise questions regarding the processing of personal data in accordance with applicable policy.' }}
                </p>
            </div>

            <div class="legal-divider"></div>

            <div class="legal-section" id="kontak">
                <h2>{{ $locale === 'id' ? 'Kontak' : 'Contact' }}</h2>
                <p>
                    {{ $locale === 'id'
                        ? 'Apabila Anda memiliki pertanyaan lebih lanjut mengenai Kebijakan Privasi ini, silakan menghubungi kami melalui saluran resmi perusahaan yang tersedia pada halaman kontak.'
                        : 'If you have further questions regarding this Privacy Policy, please contact us through the company’s official communication channels available on the contact page.' }}
                </p>
            </div>
        </section>
    </div>
</div>

@endsection