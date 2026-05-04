<!doctype html>
<html lang="id" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Writer Panel') — {{ config('app.name', 'BSP Zapin') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        :root{
            --bg:#f4f7f6;
            --surface:#ffffff;
            --surface-soft:#f8fbf9;
            --text:#0f172a;
            --text-soft:#64748b;
            --line:#e2e8f0;
            --line-soft:#edf2f7;
            --primary:#173f08;
            --primary-2:#21560e;
            --primary-soft:#eef6eb;
            --danger:#dc2626;
            --danger-soft:#fef2f2;
            --warning:#d97706;
            --warning-soft:#fffbeb;
            --success:#15803d;
            --success-soft:#f0fdf4;
            --info:#2563eb;
            --info-soft:#eff6ff;
            --white:#ffffff;
            --shadow:0 10px 30px rgba(15, 23, 42, .08);
            --radius:20px;
            --radius-sm:12px;
            --sidebar-w:280px;
            --topbar-h:74px;
            --sidebar-bg:
                radial-gradient(circle at top left, rgba(255,255,255,.08), transparent 30%),
                linear-gradient(180deg,#173f08 0%,#102d06 100%);
            --topbar-bg:
                radial-gradient(circle at left center, rgba(255,255,255,.08), transparent 26%),
                linear-gradient(90deg,#102d06 0%,#21560e 100%);
        }

        *{box-sizing:border-box}

        html{
            scroll-behavior:smooth;
            color-scheme:light;
        }

        body{
            margin:0;
            font-family:'Plus Jakarta Sans',sans-serif;
            background:var(--bg);
            color:var(--text);
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        button,
        input,
        textarea,
        select{
            font:inherit;
        }

        img{
            max-width:100%;
        }

        .w-shell{
            min-height:100vh;
            display:grid;
            grid-template-columns:var(--sidebar-w) minmax(0, 1fr);
        }

        .w-sidebar{
            position:sticky;
            top:0;
            height:100vh;
            overflow-y:auto;
            background:var(--sidebar-bg);
            color:var(--white);
            padding:22px 18px 20px;
            border-right:1px solid rgba(255,255,255,.08);
            z-index:50;
        }

        .w-sidebar::-webkit-scrollbar{
            width:8px;
        }

        .w-sidebar::-webkit-scrollbar-thumb{
            background:rgba(255,255,255,.16);
            border-radius:999px;
        }

        .w-brand{
            display:flex;
            align-items:center;
            gap:12px;
            margin-bottom:22px;
            padding:8px 6px 2px;
        }

        .w-brand-logo{
            width:44px;
            height:44px;
            border-radius:14px;
            background:rgba(255,255,255,.08);
            display:flex;
            align-items:center;
            justify-content:center;
            overflow:hidden;
            flex-shrink:0;
            border:1px solid rgba(255,255,255,.08);
        }

        .w-brand-logo img{
            width:34px;
            height:34px;
            object-fit:contain;
        }

        .w-brand-title{
            font-size:22px;
            font-weight:900;
            letter-spacing:-.03em;
            line-height:1.1;
            margin:0 0 3px;
        }

        .w-brand-subtitle{
            font-size:13px;
            color:rgba(255,255,255,.72);
            margin:0;
        }

        .w-role-badge{
            display:inline-flex;
            align-items:center;
            gap:8px;
            min-height:40px;
            padding:0 14px;
            border-radius:999px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.10);
            color:#f8fafc;
            font-weight:800;
            margin:8px 0 18px;
        }

        .w-role-dot{
            width:8px;
            height:8px;
            border-radius:999px;
            background:#bbf7d0;
            box-shadow:0 0 0 4px rgba(187,247,208,.14);
        }

        .w-nav-group{
            margin-top:16px;
        }

        .w-nav-label{
            display:block;
            font-size:11px;
            font-weight:800;
            letter-spacing:.12em;
            text-transform:uppercase;
            color:rgba(255,255,255,.52);
            margin:0 10px 10px;
        }

        .w-nav{
            display:grid;
            gap:6px;
        }

        .w-nav-item{
            display:flex;
            align-items:center;
            gap:12px;
            min-height:48px;
            padding:0 14px;
            border-radius:14px;
            color:rgba(255,255,255,.88);
            transition:background .18s ease,color .18s ease,transform .18s ease;
        }

        .w-nav-item:hover{
            background:rgba(255,255,255,.08);
            color:var(--white);
            transform:translateX(2px);
        }

        .w-nav-item.active{
            background:#eef6eb;
            color:#173f08;
            box-shadow:inset 0 0 0 1px rgba(23,63,8,.06);
        }

        .w-nav-icon{
            width:19px;
            height:19px;
            flex-shrink:0;
            display:inline-flex;
            align-items:center;
            justify-content:center;
        }

        .w-nav-icon svg{
            width:19px;
            height:19px;
            stroke:currentColor;
        }

        .w-nav-text{
            font-size:15px;
            font-weight:800;
            line-height:1.2;
        }

        .w-main{
            min-width:0;
            display:flex;
            flex-direction:column;
        }

        .w-topbar{
            position:sticky;
            top:0;
            z-index:30;
            height:var(--topbar-h);
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:16px;
            padding:14px 28px;
            background:var(--topbar-bg);
            color:var(--white);
            border-bottom:1px solid rgba(255,255,255,.08);
        }

        .w-topbar-left{
            display:flex;
            align-items:center;
            gap:14px;
            min-width:0;
        }

        .w-mobile-menu{
            display:none;
            width:42px;
            height:42px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background:rgba(255,255,255,.08);
            color:var(--white);
            align-items:center;
            justify-content:center;
            cursor:pointer;
            flex-shrink:0;
        }

        .w-topbar-title{
            min-width:0;
        }

        .w-topbar-title strong{
            display:block;
            font-size:17px;
            font-weight:900;
            line-height:1.2;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
        }

        .w-topbar-title span{
            display:block;
            font-size:12px;
            color:rgba(255,255,255,.72);
            margin-top:3px;
        }

        .w-topbar-right{
            display:flex;
            align-items:center;
            gap:12px;
            flex-shrink:0;
        }

        .w-user{
            display:flex;
            align-items:center;
            gap:10px;
            padding:7px 10px;
            border-radius:999px;
            background:rgba(255,255,255,.08);
            border:1px solid rgba(255,255,255,.10);
        }

        .w-user-avatar{
            width:34px;
            height:34px;
            border-radius:999px;
            background:#eef6eb;
            color:#173f08;
            display:flex;
            align-items:center;
            justify-content:center;
            font-size:13px;
            font-weight:900;
            flex-shrink:0;
        }

        .w-user-copy{
            line-height:1.2;
            min-width:0;
        }

        .w-user-name{
            font-size:13px;
            font-weight:900;
            white-space:nowrap;
            overflow:hidden;
            text-overflow:ellipsis;
            max-width:150px;
        }

        .w-user-role{
            font-size:11px;
            color:rgba(255,255,255,.68);
            margin-top:2px;
            text-transform:capitalize;
        }

        .w-logout-btn{
            min-height:42px;
            padding:0 14px;
            border-radius:12px;
            border:1px solid rgba(255,255,255,.14);
            background:rgba(255,255,255,.08);
            color:#fff;
            font-weight:800;
            cursor:pointer;
            transition:background .18s ease,transform .18s ease;
        }

        .w-logout-btn:hover{
            background:rgba(255,255,255,.14);
            transform:translateY(-1px);
        }

        .w-content{
            padding:28px;
            width:100%;
            max-width:1500px;
        }

        .a-page-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:18px;
            margin-bottom:20px;
        }

        .a-page-head-copy{
            min-width:0;
        }

        .a-breadcrumb{
            display:flex;
            align-items:center;
            gap:8px;
            font-size:12px;
            font-weight:800;
            color:var(--text-soft);
            margin-bottom:8px;
            flex-wrap:wrap;
        }

        .a-breadcrumb a{
            color:var(--primary);
        }

        .a-breadcrumb-sep{
            color:#94a3b8;
        }

        .a-page-title{
            margin:0;
            font-size:28px;
            line-height:1.15;
            letter-spacing:-.04em;
            font-weight:900;
            color:var(--text);
        }

        .a-page-desc{
            margin:8px 0 0;
            color:var(--text-soft);
            font-size:14px;
            line-height:1.7;
        }

        .a-card{
            background:var(--surface);
            border:1px solid var(--line);
            border-radius:var(--radius);
            padding:18px;
            box-shadow:var(--shadow);
        }

        .a-card-head{
            display:flex;
            align-items:flex-start;
            justify-content:space-between;
            gap:14px;
            margin-bottom:16px;
        }

        .a-card-title{
            font-size:16px;
            font-weight:900;
            color:var(--text);
        }

        .a-card-desc{
            margin-top:4px;
            font-size:13px;
            color:var(--text-soft);
            line-height:1.5;
        }

        .a-label{
            display:block;
            font-size:13px;
            font-weight:800;
            color:var(--text);
            margin-bottom:7px;
        }

        .a-input{
            width:100%;
            min-height:44px;
            border:1px solid var(--line);
            border-radius:12px;
            background:var(--surface);
            color:var(--text);
            padding:10px 12px;
            outline:none;
            transition:border-color .16s ease,box-shadow .16s ease,background .16s ease;
        }

        textarea.a-input{
            min-height:110px;
            resize:vertical;
            line-height:1.7;
        }

        .a-input:focus{
            border-color:var(--primary);
            box-shadow:0 0 0 4px rgba(23,63,8,.10);
        }

        .a-btn{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            gap:8px;
            min-height:44px;
            padding:0 16px;
            border-radius:12px;
            border:1px solid transparent;
            font-size:14px;
            font-weight:900;
            cursor:pointer;
            transition:transform .16s ease,background .16s ease,border-color .16s ease,color .16s ease;
            white-space:nowrap;
        }

        .a-btn:hover{
            transform:translateY(-1px);
        }

        .a-btn--primary{
            background:#173f08;
            color:#fff;
            border-color:#173f08;
        }

        .a-btn--primary:hover{
            background:#21560e;
            border-color:#21560e;
        }

        .a-btn--secondary{
            background:var(--surface);
            color:var(--text);
            border-color:var(--line);
        }

        .a-btn--light{
            background:var(--surface-soft);
            color:var(--text);
            border-color:var(--line);
        }

        .a-btn--danger{
            background:#dc2626;
            color:#fff;
            border-color:#dc2626;
        }

        .a-btn--danger:hover{
            background:#b91c1c;
            border-color:#b91c1c;
        }

        .a-btn--sm{
            min-height:34px;
            padding:0 11px;
            border-radius:10px;
            font-size:12px;
        }

        .a-alert{
            padding:13px 15px;
            border-radius:14px;
            margin-bottom:16px;
            font-size:14px;
            font-weight:700;
            line-height:1.6;
            border:1px solid transparent;
        }

        .a-alert--success{
            background:var(--success-soft);
            color:var(--success);
            border-color:rgba(21,128,61,.20);
        }

        .a-alert--danger{
            background:var(--danger-soft);
            color:var(--danger);
            border-color:rgba(220,38,38,.20);
        }

        .a-alert--info{
            background:var(--info-soft);
            color:var(--info);
            border-color:rgba(37,99,235,.20);
        }

        .a-alert--warning{
            background:var(--warning-soft);
            color:var(--warning);
            border-color:rgba(217,119,6,.20);
        }

        .a-table-wrap{
            width:100%;
            overflow-x:auto;
            border:1px solid var(--line-soft);
            border-radius:16px;
            background:var(--surface);
        }

        .a-table{
            width:100%;
            border-collapse:collapse;
            min-width:900px;
            background:var(--surface);
        }

        .a-table th{
            background:var(--surface-soft);
            color:var(--text-soft);
            font-size:12px;
            font-weight:900;
            text-transform:uppercase;
            letter-spacing:.04em;
            padding:13px 14px;
            text-align:left;
            border-bottom:1px solid var(--line);
        }

        .a-table td{
            padding:14px;
            border-bottom:1px solid var(--line-soft);
            vertical-align:top;
            font-size:14px;
            color:var(--text);
        }

        .a-table tbody tr:hover{
            background:var(--surface-soft);
        }

        .a-table tbody tr:last-child td{
            border-bottom:none;
        }

        .a-badge{
            display:inline-flex;
            align-items:center;
            justify-content:center;
            min-height:28px;
            padding:0 10px;
            border-radius:999px;
            font-size:12px;
            font-weight:900;
            border:1px solid transparent;
            white-space:nowrap;
        }

        .a-badge--gray{
            background:var(--surface-soft);
            color:var(--text-soft);
            border-color:var(--line);
        }

        .a-badge--blue{
            background:var(--info-soft);
            color:var(--info);
            border-color:rgba(37,99,235,.18);
        }

        .a-badge--orange{
            background:var(--warning-soft);
            color:var(--warning);
            border-color:rgba(217,119,6,.18);
        }

        .a-badge--green{
            background:var(--success-soft);
            color:var(--success);
            border-color:rgba(21,128,61,.18);
        }

        .a-badge--red{
            background:var(--danger-soft);
            color:var(--danger);
            border-color:rgba(220,38,38,.18);
        }

        .a-empty{
            padding:40px 18px;
            text-align:center;
            color:var(--text-soft);
        }

        .a-empty-title{
            font-size:18px;
            font-weight:900;
            color:var(--text);
            margin-bottom:6px;
        }

        .a-empty-desc{
            font-size:14px;
            margin-bottom:16px;
        }

        .w-overlay{
            display:none;
            position:fixed;
            inset:0;
            background:rgba(15,23,42,.48);
            z-index:40;
        }

        /* ============================================================
           GLOBAL FORM STYLE - NEWS & TJSL
        ============================================================ */
        .w-content input[type="text"],
        .w-content input[type="email"],
        .w-content input[type="password"],
        .w-content input[type="number"],
        .w-content input[type="date"],
        .w-content input[type="datetime-local"],
        .w-content input[type="time"],
        .w-content input[type="search"],
        .w-content input[type="url"],
        .w-content input[type="file"],
        .w-content select,
        .w-content textarea{
            background:var(--surface) !important;
            color:var(--text) !important;
            border-color:var(--line) !important;
        }

        .w-content input::placeholder,
        .w-content textarea::placeholder{
            color:var(--text-soft) !important;
            opacity:.8;
        }

        .w-content input:focus,
        .w-content select:focus,
        .w-content textarea:focus{
            border-color:var(--primary) !important;
            box-shadow:0 0 0 4px rgba(23,63,8,.10) !important;
            outline:none !important;
        }

        .w-content input[disabled],
        .w-content select[disabled],
        .w-content textarea[disabled],
        .w-content input[readonly],
        .w-content textarea[readonly]{
            background:var(--surface-soft) !important;
            color:var(--text-soft) !important;
            cursor:not-allowed;
        }

        .w-content label,
        .w-content .a-label,
        .w-content .wn-label,
        .w-content .wt-label,
        .w-content .form-label{
            color:var(--text) !important;
        }

        .w-content .a-card-desc,
        .w-content .a-page-desc,
        .w-content .wn-help,
        .w-content .wn-gallery-note,
        .w-content .wn-subtitle,
        .w-content .wt-help,
        .w-content .form-help,
        .w-content .help-text,
        .w-content small{
            color:var(--text-soft) !important;
        }

        .w-content .a-card,
        .w-content .wn-card,
        .w-content .wt-card,
        .w-content .form-card,
        .w-content .panel-card{
            background:var(--surface) !important;
            border-color:var(--line) !important;
            color:var(--text) !important;
            box-shadow:var(--shadow) !important;
        }

        .w-content .a-card-title,
        .w-content .wn-title,
        .w-content .wn-builder-title,
        .w-content .wn-side-title,
        .w-content .wt-title,
        .w-content .wt-builder-title,
        .w-content .wt-side-title,
        .w-content .form-title,
        .w-content .panel-title{
            color:var(--text) !important;
        }

        .w-content .a-btn--secondary,
        .w-content .a-btn--light,
        .w-content .wn-btn,
        .w-content .wt-btn,
        .w-content .btn-light,
        .w-content .btn-secondary{
            background:var(--surface) !important;
            color:var(--text) !important;
            border-color:var(--line) !important;
        }

        .w-content .a-btn--secondary:hover,
        .w-content .a-btn--light:hover,
        .w-content .wn-btn:hover,
        .w-content .wt-btn:hover,
        .w-content .btn-light:hover,
        .w-content .btn-secondary:hover{
            background:var(--surface-soft) !important;
            color:var(--primary) !important;
            border-color:var(--primary) !important;
        }

        .w-content .a-btn--primary,
        .w-content .wn-btn--primary,
        .w-content .wt-btn--primary,
        .w-content .btn-primary{
            background:#173f08 !important;
            color:#ffffff !important;
            border-color:#173f08 !important;
        }

        .w-content .a-btn--primary:hover,
        .w-content .wn-btn--primary:hover,
        .w-content .wt-btn--primary:hover,
        .w-content .btn-primary:hover{
            background:#21560e !important;
            border-color:#21560e !important;
        }

        .w-content .a-btn--danger,
        .w-content .wn-btn--danger,
        .w-content .wt-btn--danger,
        .w-content .btn-danger{
            background:#dc2626 !important;
            color:#ffffff !important;
            border-color:#dc2626 !important;
        }

        .w-content .a-btn--danger:hover,
        .w-content .wn-btn--danger:hover,
        .w-content .wt-btn--danger:hover,
        .w-content .btn-danger:hover{
            background:#b91c1c !important;
            border-color:#b91c1c !important;
        }

        /* ============================================================
           SELECT DROPDOWN STYLE - NEWS & TJSL
        ============================================================ */
        .w-content select,
        .w-content .wn-select-custom,
        .w-content .wt-select-custom,
        .w-content .select-custom{
            width:100%;
            min-height:52px;
            border-radius:14px !important;
            background-color:var(--surface) !important;
            color:var(--text) !important;
            border:1px solid var(--line) !important;
            padding:0 46px 0 16px !important;
            font-size:14px;
            font-weight:700;
            line-height:1.2;
            appearance:none;
            -webkit-appearance:none;
            -moz-appearance:none;
            cursor:pointer;
            background-image:
                linear-gradient(45deg, transparent 50%, #64748b 50%),
                linear-gradient(135deg, #64748b 50%, transparent 50%);
            background-position:
                calc(100% - 22px) calc(50% - 3px),
                calc(100% - 16px) calc(50% - 3px);
            background-size:
                6px 6px,
                6px 6px;
            background-repeat:no-repeat;
            transition:border-color .18s ease, box-shadow .18s ease, background-color .18s ease, color .18s ease;
        }

        .w-content select:hover,
        .w-content .wn-select-custom:hover,
        .w-content .wt-select-custom:hover,
        .w-content .select-custom:hover{
            border-color:rgba(23,63,8,.45) !important;
            background-color:#fbfdfb !important;
        }

        .w-content select:focus,
        .w-content .wn-select-custom:focus,
        .w-content .wt-select-custom:focus,
        .w-content .select-custom:focus{
            border-color:var(--primary) !important;
            box-shadow:0 0 0 4px rgba(23,63,8,.10) !important;
            background-color:#ffffff !important;
            outline:none !important;
        }

        .w-content select option,
        .w-content .wn-select-custom option,
        .w-content .wt-select-custom option,
        .w-content .select-custom option{
            background:#ffffff;
            color:#0f172a;
            font-size:14px;
            font-weight:600;
            padding:10px 14px;
        }

        .w-content select option:checked,
        .w-content .wn-select-custom option:checked,
        .w-content .wt-select-custom option:checked,
        .w-content .select-custom option:checked{
            background:#173f08;
            color:#ffffff;
        }

        .w-content .wn-select-wrap,
        .w-content .wt-select-wrap,
        .w-content .select-wrap{
            position:relative;
            width:100%;
            border:0 !important;
            background:transparent !important;
            min-height:52px;
        }

        .w-content .wn-select-wrap::after,
        .w-content .wt-select-wrap::after,
        .w-content .select-wrap::after{
            display:none !important;
        }

        .w-content .wn-file-upload,
        .w-content .wt-file-upload,
        .w-content .file-upload{
            background:var(--surface) !important;
            border-color:var(--line) !important;
            color:var(--text) !important;
        }

        .w-content .wn-file-upload:hover,
        .w-content .wt-file-upload:hover,
        .w-content .file-upload:hover{
            background:var(--surface-soft) !important;
            border-color:var(--primary) !important;
        }

        .w-content .wn-file-name,
        .w-content .wt-file-name,
        .w-content .file-name{
            color:var(--text-soft) !important;
        }

        .w-content .wn-file-trigger,
        .w-content .wt-file-trigger,
        .w-content .file-trigger{
            background:#173f08 !important;
            color:#ffffff !important;
        }

        .w-content .wn-block,
        .w-content .wt-block,
        .w-content .content-block{
            background:var(--surface-soft) !important;
            border-color:var(--line) !important;
            color:var(--text) !important;
        }

        .w-content .wn-block:hover,
        .w-content .wt-block:hover,
        .w-content .content-block:hover{
            border-color:var(--primary) !important;
        }

        .w-content .wn-block-badge,
        .w-content .wt-block-badge,
        .w-content .block-badge{
            background:var(--primary-soft) !important;
            color:var(--primary) !important;
            border:1px solid rgba(23, 63, 8, .08);
        }

        .w-content .wn-drag-handle,
        .w-content .wt-drag-handle,
        .w-content .drag-handle{
            background:var(--surface) !important;
            border-color:var(--line) !important;
            color:var(--text-soft) !important;
        }

        .w-content .wn-drag-handle:hover,
        .w-content .wt-drag-handle:hover,
        .w-content .drag-handle:hover{
            color:var(--primary) !important;
            border-color:var(--primary) !important;
        }

        .w-content .wn-featured-preview,
        .w-content .wt-featured-preview,
        .w-content .featured-preview,
        .w-content .image-preview{
            background:var(--surface-soft) !important;
            border-color:var(--line) !important;
            color:var(--text) !important;
        }

        .w-content .wn-featured-preview-label,
        .w-content .wt-featured-preview-label,
        .w-content .featured-preview-label{
            color:var(--text-soft) !important;
        }

        .w-content .wn-thumb,
        .w-content .wt-thumb,
        .w-content .preview-thumb{
            border-color:var(--line) !important;
            background:var(--surface-soft) !important;
        }

        .w-content .wn-side-row,
        .w-content .wt-side-row,
        .w-content .side-row{
            border-color:var(--line-soft) !important;
        }

        .w-content .wn-side-label,
        .w-content .wt-side-label,
        .w-content .side-label{
            color:var(--text-soft) !important;
        }

        .w-content .wn-side-value,
        .w-content .wt-side-value,
        .w-content .side-value{
            color:var(--text) !important;
        }

        .w-content .a-table-wrap,
        .w-content .table-wrap{
            border-color:var(--line) !important;
            background:var(--surface) !important;
        }

        .w-content .a-table,
        .w-content table{
            background:var(--surface) !important;
            color:var(--text) !important;
        }

        .w-content .a-table th,
        .w-content table th{
            background:var(--surface-soft) !important;
            color:var(--text-soft) !important;
            border-color:var(--line) !important;
        }

        .w-content .a-table td,
        .w-content table td{
            background:var(--surface) !important;
            color:var(--text) !important;
            border-color:var(--line-soft) !important;
        }

        .w-content .a-table tbody tr:hover td,
        .w-content table tbody tr:hover td{
            background:var(--surface-soft) !important;
        }

        .w-content .a-empty,
        .w-content .empty-state{
            background:var(--surface) !important;
            color:var(--text-soft) !important;
            border-color:var(--line) !important;
        }

        .w-content .a-empty-title,
        .w-content .empty-title{
            color:var(--text) !important;
        }

        .w-content .filter-card,
        .w-content .search-card,
        .w-content .toolbar-card{
            background:var(--surface) !important;
            border-color:var(--line) !important;
            color:var(--text) !important;
        }

        .w-content .filter-row,
        .w-content .search-row,
        .w-content .toolbar-row{
            color:var(--text) !important;
        }

        /* ============================================================
           PAGINATION
        ============================================================ */
        .w-pagination {
            margin-top:18px;
            display:flex;
            justify-content:flex-end;
        }

        .w-pagination nav[role="navigation"] {
            width:100%;
        }

        .w-pagination nav[role="navigation"] > div {
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:14px;
            flex-wrap:wrap;
        }

        .w-pagination nav[role="navigation"] > div:first-child {
            display:none;
        }

        .w-pagination nav[role="navigation"] p {
            margin:0;
            font-size:13px;
            font-weight:700;
            color:var(--text-soft);
        }

        .w-pagination nav[role="navigation"] span[aria-current="page"] span,
        .w-pagination nav[role="navigation"] a,
        .w-pagination nav[role="navigation"] span[aria-disabled="true"] span {
            min-width:38px;
            height:38px;
            padding:0 13px;
            border-radius:12px;
            border:1px solid var(--line);
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:var(--surface);
            color:var(--text);
            font-size:13px;
            font-weight:900;
            line-height:1;
            text-decoration:none;
            transition:transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease, box-shadow .16s ease;
        }

        .w-pagination nav[role="navigation"] a:hover {
            transform:translateY(-1px);
            background:var(--primary-soft);
            border-color:rgba(23,63,8,.25);
            color:var(--primary);
            box-shadow:0 8px 18px rgba(15,23,42,.06);
        }

        .w-pagination nav[role="navigation"] span[aria-current="page"] span {
            background:#173f08;
            border-color:#173f08;
            color:#fff;
            box-shadow:0 8px 20px rgba(23,63,8,.20);
        }

        .w-pagination nav[role="navigation"] span[aria-disabled="true"] span {
            background:var(--surface-soft);
            color:var(--text-soft);
            cursor:not-allowed;
        }

        .w-pagination nav[role="navigation"] svg {
            width:16px;
            height:16px;
        }

        .w-pagination .pagination {
            display:flex;
            align-items:center;
            justify-content:flex-end;
            gap:6px;
            flex-wrap:wrap;
            list-style:none;
            padding:0;
            margin:0;
        }

        .w-pagination .page-item {
            margin:0;
        }

        .w-pagination .page-link {
            min-width:38px;
            height:38px;
            padding:0 13px;
            border-radius:12px;
            border:1px solid var(--line);
            display:inline-flex;
            align-items:center;
            justify-content:center;
            background:var(--surface);
            color:var(--text);
            font-size:13px;
            font-weight:900;
            text-decoration:none;
            transition:transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease, box-shadow .16s ease;
        }

        .w-pagination .page-link:hover {
            transform:translateY(-1px);
            background:var(--primary-soft);
            border-color:rgba(23,63,8,.25);
            color:var(--primary);
            box-shadow:0 8px 18px rgba(15,23,42,.06);
        }

        .w-pagination .page-item.active .page-link {
            background:#173f08;
            border-color:#173f08;
            color:#fff;
            box-shadow:0 8px 20px rgba(23,63,8,.20);
        }

        .w-pagination .page-item.disabled .page-link {
            background:var(--surface-soft);
            color:var(--text-soft);
            cursor:not-allowed;
            box-shadow:none;
            transform:none;
        }

        /* ============================================================
           SWEETALERT CUSTOM - WRITER
        ============================================================ */
        .swal2-popup.swal2-bspz-popup {
            width:min(92vw, 520px) !important;
            border-radius:24px !important;
            padding:30px 30px 28px !important;
            font-family:'Plus Jakarta Sans', sans-serif !important;
            box-shadow:0 30px 90px rgba(15, 23, 42, .26) !important;
        }

        .swal2-title.swal2-bspz-title {
            font-size:24px !important;
            font-weight:900 !important;
            color:#0f172a !important;
            letter-spacing:-.04em !important;
            line-height:1.2 !important;
            padding:0 !important;
        }

        .swal2-html-container.swal2-bspz-html {
            margin:12px 0 0 !important;
            font-size:15px !important;
            color:#64748b !important;
            line-height:1.7 !important;
        }

        .swal2-actions {
            gap:10px !important;
            margin-top:26px !important;
        }

        .swal2-styled {
            box-shadow:none !important;
        }

        .swal2-confirm.swal2-bspz-confirm,
        .swal2-cancel.swal2-bspz-cancel {
            min-height:44px !important;
            padding:0 20px !important;
            border-radius:14px !important;
            font-size:14px !important;
            font-weight:900 !important;
            line-height:1 !important;
            border:1px solid transparent !important;
            transition:transform .16s ease, background .16s ease, border-color .16s ease, color .16s ease, box-shadow .16s ease !important;
        }

        .swal2-confirm.swal2-bspz-confirm:hover,
        .swal2-cancel.swal2-bspz-cancel:hover {
            transform:translateY(-1px) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--primary {
            background:#173f08 !important;
            border-color:#173f08 !important;
            color:#ffffff !important;
            box-shadow:0 12px 24px rgba(23, 63, 8, .20) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--primary:hover {
            background:#21560e !important;
            border-color:#21560e !important;
        }

        .swal2-confirm.swal2-bspz-confirm--danger {
            background:#dc2626 !important;
            border-color:#dc2626 !important;
            color:#ffffff !important;
            box-shadow:0 12px 24px rgba(220, 38, 38, .22) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--danger:hover {
            background:#b91c1c !important;
            border-color:#b91c1c !important;
        }

        .swal2-confirm.swal2-bspz-confirm--logout {
            background:#991b1b !important;
            border-color:#991b1b !important;
            color:#ffffff !important;
            box-shadow:0 12px 24px rgba(153, 27, 27, .22) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--logout:hover {
            background:#7f1d1d !important;
            border-color:#7f1d1d !important;
        }

        .swal2-confirm.swal2-bspz-confirm--success {
            background:#15803d !important;
            border-color:#15803d !important;
            color:#ffffff !important;
            box-shadow:0 12px 24px rgba(21, 128, 61, .22) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--success:hover {
            background:#166534 !important;
            border-color:#166534 !important;
        }

        .swal2-confirm.swal2-bspz-confirm--warning {
            background:#d97706 !important;
            border-color:#d97706 !important;
            color:#ffffff !important;
            box-shadow:0 12px 24px rgba(217, 119, 6, .22) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--warning:hover {
            background:#b45309 !important;
            border-color:#b45309 !important;
        }

        .swal2-confirm.swal2-bspz-confirm--info {
            background:#2563eb !important;
            border-color:#2563eb !important;
            color:#ffffff !important;
            box-shadow:0 12px 24px rgba(37, 99, 235, .22) !important;
        }

        .swal2-confirm.swal2-bspz-confirm--info:hover {
            background:#1d4ed8 !important;
            border-color:#1d4ed8 !important;
        }

        .swal2-cancel.swal2-bspz-cancel {
            background:#ffffff !important;
            color:#334155 !important;
            border-color:#dbe3ea !important;
        }

        .swal2-cancel.swal2-bspz-cancel:hover {
            background:#f8fafc !important;
            color:#0f172a !important;
            border-color:#cbd5e1 !important;
        }

        .swal2-icon.swal2-warning {
            border-color:#f59e0b !important;
            color:#f59e0b !important;
        }

        .swal2-icon.swal2-error {
            border-color:#ef4444 !important;
            color:#ef4444 !important;
        }

        .swal2-icon.swal2-success {
            border-color:#22c55e !important;
            color:#22c55e !important;
        }

        .swal2-icon.swal2-question {
            border-color:#173f08 !important;
            color:#173f08 !important;
        }

        /* ============================================================
           RESPONSIVE
        ============================================================ */
        @media (min-width:1501px){
            .w-content{
                margin-inline:auto;
            }
        }

        @media (max-width:1100px){
            .w-shell{
                grid-template-columns:1fr;
            }

            .w-sidebar{
                position:fixed;
                left:0;
                top:0;
                transform:translateX(-100%);
                transition:transform .22s ease;
                width:var(--sidebar-w);
                max-width:calc(100vw - 48px);
            }

            body.sidebar-open .w-sidebar{
                transform:translateX(0);
            }

            body.sidebar-open .w-overlay{
                display:block;
            }

            .w-mobile-menu{
                display:inline-flex;
            }

            .w-content{
                padding:22px;
                max-width:none;
            }
        }

        @media (max-width:760px){
            :root{
                --topbar-h:auto;
            }

            .w-topbar{
                min-height:68px;
                height:auto;
                padding:12px 16px;
                align-items:center;
            }

            .w-topbar-title strong{
                font-size:15px;
            }

            .w-topbar-title span{
                display:none;
            }

            .w-user{
                padding:6px;
            }

            .w-user-copy{
                display:none;
            }

            .w-logout-btn{
                min-height:38px;
                padding:0 11px;
                border-radius:10px;
                font-size:12px;
            }

            .w-content{
                padding:16px;
            }

            .a-page-head{
                display:grid;
                gap:14px;
            }

            .a-page-title{
                font-size:24px;
            }

            .a-card{
                padding:15px;
                border-radius:16px;
            }

            .w-content input[type="text"],
            .w-content input[type="email"],
            .w-content input[type="password"],
            .w-content input[type="number"],
            .w-content input[type="date"],
            .w-content input[type="datetime-local"],
            .w-content input[type="time"],
            .w-content input[type="search"],
            .w-content select,
            .w-content textarea{
                font-size:14px;
            }

            .w-content select,
            .w-content .wn-select-custom,
            .w-content .wt-select-custom,
            .w-content .select-custom{
                min-height:50px;
                border-radius:13px !important;
                padding-left:14px !important;
                padding-right:44px !important;
                font-size:14px;
                background-position:
                    calc(100% - 21px) calc(50% - 3px),
                    calc(100% - 15px) calc(50% - 3px);
            }

            .w-content .wn-file-upload,
            .w-content .wt-file-upload,
            .w-content .file-upload{
                flex-direction:column;
                align-items:stretch;
            }

            .w-content .wn-file-trigger,
            .w-content .wt-file-trigger,
            .w-content .file-trigger{
                width:100%;
            }

            .w-pagination {
                justify-content:center;
            }

            .w-pagination nav[role="navigation"] > div {
                justify-content:center;
            }

            .w-pagination nav[role="navigation"] p {
                width:100%;
                text-align:center;
            }

            .w-pagination nav[role="navigation"] a,
            .w-pagination nav[role="navigation"] span[aria-current="page"] span,
            .w-pagination nav[role="navigation"] span[aria-disabled="true"] span,
            .w-pagination .page-link {
                min-width:36px;
                height:36px;
                padding:0 11px;
                font-size:12px;
                border-radius:11px;
            }

            .swal2-popup.swal2-bspz-popup {
                padding:26px 20px 24px !important;
                border-radius:20px !important;
            }

            .swal2-title.swal2-bspz-title {
                font-size:21px !important;
            }

            .swal2-html-container.swal2-bspz-html {
                font-size:14px !important;
            }

            .swal2-actions {
                width:100% !important;
            }

            .swal2-confirm.swal2-bspz-confirm,
            .swal2-cancel.swal2-bspz-cancel {
                flex:1 1 auto !important;
                min-width:120px !important;
            }
        }

        @media (max-width:480px){
            .w-topbar{
                gap:10px;
            }

            .w-topbar-left{
                gap:10px;
            }

            .w-mobile-menu{
                width:38px;
                height:38px;
                border-radius:10px;
            }

            .w-user-avatar{
                width:32px;
                height:32px;
            }

            .w-logout-btn{
                font-size:0;
                width:38px;
                padding:0;
                position:relative;
            }

            .w-logout-btn::before{
                content:'⎋';
                font-size:18px;
                line-height:1;
            }

            .a-page-title{
                font-size:22px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
@php
    $user = Auth::user();
    $userName = $user?->name ?? session('user_name', 'Writer');
    $userRole = $user?->role ?? session('user_role', 'writer');

    $initials = collect(explode(' ', trim($userName)))
        ->filter()
        ->take(2)
        ->map(fn ($part) => mb_substr($part, 0, 1))
        ->implode('');

    $initials = $initials ?: 'W';

    $isDashboardActive = request()->routeIs('writer.dashboard');
    $isNewsActive = request()->routeIs('writer.news.*');
    $isTjslActive = request()->routeIs('writer.tjsl.*');
@endphp

<div class="w-overlay" id="writerOverlay"></div>

<div class="w-shell">
    <aside class="w-sidebar" id="writerSidebar">
        <div class="w-brand">
            <div class="w-brand-logo">
                <img src="{{ asset('images/logo.png') }}" alt="BSP Zapin">
            </div>
            <div class="w-brand-copy">
                <p class="w-brand-title">Writer</p>
                <p class="w-brand-subtitle">BSP Zapin Panel</p>
            </div>
        </div>

        <div class="w-role-badge">
            <span class="w-role-dot"></span>
            <span>Writer Area</span>
        </div>

        <div class="w-nav-group">
            <span class="w-nav-label">Menu Utama</span>

            <nav class="w-nav">
                <a href="{{ route('writer.dashboard') }}" class="w-nav-item {{ $isDashboardActive ? 'active' : '' }}">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 13h8V3H3v10Z"/>
                            <path d="M13 21h8V11h-8v10Z"/>
                            <path d="M13 3h8v6h-8V3Z"/>
                            <path d="M3 21h8v-6H3v6Z"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">Dashboard</span>
                </a>

                <a href="{{ route('writer.news.index') }}" class="w-nav-item {{ $isNewsActive ? 'active' : '' }}">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 19.5A2.5 2.5 0 0 0 6.5 22H20"/>
                            <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2Z"/>
                            <path d="M8 7h8"/>
                            <path d="M8 11h8"/>
                            <path d="M8 15h5"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">News</span>
                </a>

                <a href="{{ route('writer.tjsl.index') }}" class="w-nav-item {{ $isTjslActive ? 'active' : '' }}">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-11V5l-8-3-8 3v6c0 7 8 11 8 11Z"/>
                            <path d="M9 12l2 2 4-5"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">TJSL</span>
                </a>
            </nav>
        </div>

        <div class="w-nav-group">
            <span class="w-nav-label">Website</span>

            <nav class="w-nav">
                <a href="{{ route('web.home', ['locale' => 'id']) }}" target="_blank" class="w-nav-item">
                    <span class="w-nav-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/>
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>
                        </svg>
                    </span>
                    <span class="w-nav-text">Lihat Website</span>
                </a>
            </nav>
        </div>
    </aside>

    <main class="w-main">
        <header class="w-topbar">
            <div class="w-topbar-left">
                <button type="button" class="w-mobile-menu" id="writerMenuBtn" aria-label="Buka Menu">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round">
                        <path d="M4 6h16"/>
                        <path d="M4 12h16"/>
                        <path d="M4 18h16"/>
                    </svg>
                </button>

                <div class="w-topbar-title">
                    <strong>@yield('title', 'Writer Panel')</strong>
                    <span>Kelola konten website publik BSP Zapin</span>
                </div>
            </div>

            <div class="w-topbar-right">
                <div class="w-user">
                    <div class="w-user-avatar">{{ strtoupper($initials) }}</div>
                    <div class="w-user-copy">
                        <div class="w-user-name">{{ $userName }}</div>
                        <div class="w-user-role">{{ $userRole }}</div>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="js-logout-form">
                    @csrf
                    <button type="submit" class="w-logout-btn">Logout</button>
                </form>
            </div>
        </header>

        <section class="w-content">
            @yield('content')
        </section>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const writerMenuBtn = document.getElementById('writerMenuBtn');
    const writerOverlay = document.getElementById('writerOverlay');

    if (writerMenuBtn) {
        writerMenuBtn.addEventListener('click', function () {
            document.body.classList.add('sidebar-open');
        });
    }

    if (writerOverlay) {
        writerOverlay.addEventListener('click', function () {
            document.body.classList.remove('sidebar-open');
        });
    }
</script>

<script>
    window.BSPZSwal = Swal.mixin({
        customClass: {
            popup: 'swal2-bspz-popup',
            title: 'swal2-bspz-title',
            htmlContainer: 'swal2-bspz-html',
            confirmButton: 'swal2-bspz-confirm swal2-bspz-confirm--primary',
            cancelButton: 'swal2-bspz-cancel'
        },
        buttonsStyling: false,
        confirmButtonText: 'OK',
        cancelButtonText: 'Batal'
    });

    document.addEventListener('DOMContentLoaded', function () {
        function confirmClass(type) {
            const map = {
                primary: 'swal2-bspz-confirm swal2-bspz-confirm--primary',
                submit: 'swal2-bspz-confirm swal2-bspz-confirm--primary',
                save: 'swal2-bspz-confirm swal2-bspz-confirm--primary',
                edit: 'swal2-bspz-confirm swal2-bspz-confirm--primary',

                delete: 'swal2-bspz-confirm swal2-bspz-confirm--danger',
                danger: 'swal2-bspz-confirm swal2-bspz-confirm--danger',
                destroy: 'swal2-bspz-confirm swal2-bspz-confirm--danger',

                logout: 'swal2-bspz-confirm swal2-bspz-confirm--logout',

                publish: 'swal2-bspz-confirm swal2-bspz-confirm--success',
                approve: 'swal2-bspz-confirm swal2-bspz-confirm--success',
                success: 'swal2-bspz-confirm swal2-bspz-confirm--success',

                unpublish: 'swal2-bspz-confirm swal2-bspz-confirm--warning',
                warning: 'swal2-bspz-confirm swal2-bspz-confirm--warning',
                reject: 'swal2-bspz-confirm swal2-bspz-confirm--warning',

                info: 'swal2-bspz-confirm swal2-bspz-confirm--info',
                preview: 'swal2-bspz-confirm swal2-bspz-confirm--info'
            };

            return map[type] || map.primary;
        }

        function submitFormDirectly(form) {
            form.dataset.confirmed = '1';
            HTMLFormElement.prototype.submit.call(form);
        }

        @if(session('success'))
            BSPZSwal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: @json(session('success')),
                timer: 2400,
                showConfirmButton: false,
                customClass: {
                    popup: 'swal2-bspz-popup',
                    title: 'swal2-bspz-title',
                    htmlContainer: 'swal2-bspz-html',
                    confirmButton: confirmClass('success'),
                    cancelButton: 'swal2-bspz-cancel'
                }
            });
        @endif

        @if(session('error'))
            BSPZSwal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: @json(session('error')),
                customClass: {
                    popup: 'swal2-bspz-popup',
                    title: 'swal2-bspz-title',
                    htmlContainer: 'swal2-bspz-html',
                    confirmButton: confirmClass('danger'),
                    cancelButton: 'swal2-bspz-cancel'
                }
            });
        @endif

        @if(session('warning'))
            BSPZSwal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: @json(session('warning')),
                customClass: {
                    popup: 'swal2-bspz-popup',
                    title: 'swal2-bspz-title',
                    htmlContainer: 'swal2-bspz-html',
                    confirmButton: confirmClass('warning'),
                    cancelButton: 'swal2-bspz-cancel'
                }
            });
        @endif

        @if(session('info'))
            BSPZSwal.fire({
                icon: 'info',
                title: 'Informasi',
                text: @json(session('info')),
                customClass: {
                    popup: 'swal2-bspz-popup',
                    title: 'swal2-bspz-title',
                    htmlContainer: 'swal2-bspz-html',
                    confirmButton: confirmClass('info'),
                    cancelButton: 'swal2-bspz-cancel'
                }
            });
        @endif

        @if($errors->any())
            BSPZSwal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                html: `{!! implode('<br>', $errors->all()) !!}`,
                customClass: {
                    popup: 'swal2-bspz-popup',
                    title: 'swal2-bspz-title',
                    htmlContainer: 'swal2-bspz-html',
                    confirmButton: confirmClass('danger'),
                    cancelButton: 'swal2-bspz-cancel'
                }
            });
        @endif

        document.querySelectorAll('.js-logout-form').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.confirmed === '1') {
                    return;
                }

                event.preventDefault();

                BSPZSwal.fire({
                    icon: 'question',
                    title: 'Logout dari Writer Panel?',
                    text: 'Anda akan keluar dari sesi saat ini.',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Logout',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-bspz-popup',
                        title: 'swal2-bspz-title',
                        htmlContainer: 'swal2-bspz-html',
                        confirmButton: confirmClass('logout'),
                        cancelButton: 'swal2-bspz-cancel'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        submitFormDirectly(form);
                    }
                });
            });
        });

        document.querySelectorAll('.js-delete-form, .js-confirm-delete').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.confirmed === '1') {
                    return;
                }

                event.preventDefault();

                const title = form.getAttribute('data-title') || 'Hapus data ini?';
                const text = form.getAttribute('data-text') || 'Data yang dihapus tidak dapat dikembalikan.';
                const confirmText = form.getAttribute('data-confirm') || 'Ya, Hapus';

                BSPZSwal.fire({
                    icon: 'warning',
                    title: title,
                    text: text,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-bspz-popup',
                        title: 'swal2-bspz-title',
                        htmlContainer: 'swal2-bspz-html',
                        confirmButton: confirmClass('delete'),
                        cancelButton: 'swal2-bspz-cancel'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        submitFormDirectly(form);
                    }
                });
            });
        });

        document.querySelectorAll('.js-confirm-submit').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.confirmed === '1') {
                    return;
                }

                event.preventDefault();

                const title = form.getAttribute('data-title') || 'Lanjutkan proses?';
                const text = form.getAttribute('data-text') || 'Pastikan data yang Anda masukkan sudah benar.';
                const confirmText = form.getAttribute('data-confirm') || 'Ya, Lanjutkan';
                const type = form.getAttribute('data-type') || form.getAttribute('data-action-type') || 'submit';
                const icon = form.getAttribute('data-icon') || 'question';

                BSPZSwal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-bspz-popup',
                        title: 'swal2-bspz-title',
                        htmlContainer: 'swal2-bspz-html',
                        confirmButton: confirmClass(type),
                        cancelButton: 'swal2-bspz-cancel'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        submitFormDirectly(form);
                    }
                });
            });
        });

        document.querySelectorAll('[data-swal-confirm]').forEach(function (button) {
            button.addEventListener('click', function (event) {
                const formSelector = button.getAttribute('data-form');
                const targetForm = formSelector ? document.querySelector(formSelector) : button.closest('form');

                if (!targetForm) {
                    return;
                }

                event.preventDefault();

                const title = button.getAttribute('data-title') || 'Lanjutkan proses?';
                const text = button.getAttribute('data-text') || 'Pastikan tindakan ini sudah benar.';
                const confirmText = button.getAttribute('data-confirm') || 'Ya, Lanjutkan';
                const type = button.getAttribute('data-type') || button.getAttribute('data-action-type') || 'primary';
                const icon = button.getAttribute('data-icon') || 'question';

                BSPZSwal.fire({
                    icon: icon,
                    title: title,
                    text: text,
                    showCancelButton: true,
                    confirmButtonText: confirmText,
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'swal2-bspz-popup',
                        title: 'swal2-bspz-title',
                        htmlContainer: 'swal2-bspz-html',
                        confirmButton: confirmClass(type),
                        cancelButton: 'swal2-bspz-cancel'
                    }
                }).then(function (result) {
                    if (result.isConfirmed) {
                        submitFormDirectly(targetForm);
                    }
                });
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>