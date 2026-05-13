@php
    $reportData = $report ?? null;
@endphp

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<style>
    .wbs-report-form {
        display: grid;
        gap: 22px;
    }

    .wbs-report-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px;
    }

    .wbs-report-field {
        min-width: 0;
    }

    .wbs-report-field.full {
        grid-column: 1 / -1;
    }

    .wbs-report-label {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-bottom: 8px;
        font-size: 12.5px;
        font-weight: 800;
        color: var(--text-secondary);
        letter-spacing: .01em;
    }

    .wbs-required {
        color: var(--danger);
        font-weight: 900;
    }

    .wbs-help-text {
        margin-top: 7px;
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.6;
    }

    .wbs-input,
    .wbs-textarea,
    .wbs-select-native {
        width: 100%;
        border: 1.5px solid var(--border-strong);
        border-radius: var(--r-sm);
        background: var(--input-bg);
        color: var(--text-primary);
        outline: none;
        font-size: 14px;
        transition:
            border-color var(--dur) var(--ease),
            box-shadow var(--dur) var(--ease),
            background var(--dur) var(--ease);
    }

    .wbs-input {
        min-height: 46px;
        padding: 11px 14px;
    }

    .wbs-textarea {
        min-height: 132px;
        resize: vertical;
        padding: 13px 14px;
        line-height: 1.75;
    }

    .wbs-input::placeholder,
    .wbs-textarea::placeholder {
        color: var(--text-muted);
        opacity: .85;
    }

    .wbs-input:hover,
    .wbs-textarea:hover,
    .wbs-select-native:hover {
        background: var(--surface-hover);
        border-color: var(--brand);
    }

    .wbs-input:focus,
    .wbs-textarea:focus,
    .wbs-select-native:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3.5px var(--brand-glow);
    }

    .wbs-date-wrap {
        position: relative;
    }

    .wbs-date-wrap .wbs-input {
        padding-right: 54px;
        cursor: pointer;
    }

    .wbs-date-btn {
        position: absolute;
        top: 50%;
        right: 8px;
        width: 38px;
        height: 38px;
        transform: translateY(-50%);
        border: 1px solid var(--border);
        border-radius: 12px;
        background: var(--brand-light);
        color: var(--brand);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition:
            background var(--dur) var(--ease),
            border-color var(--dur) var(--ease),
            color var(--dur) var(--ease),
            transform var(--dur) var(--ease);
    }

    .wbs-date-btn:hover {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        transform: translateY(-50%) scale(1.02);
    }

    .wbs-date-btn svg {
        width: 18px;
        height: 18px;
        stroke: currentColor;
    }

    .wbs-custom-select {
        position: relative;
        width: 100%;
    }

    .wbs-custom-select select {
        width: 100%;
        min-height: 46px;
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        border: 1.5px solid var(--border-strong);
        border-radius: var(--r-sm);
        background: var(--input-bg);
        color: var(--text-primary);
        padding: 11px 44px 11px 14px;
        font-size: 14px;
        cursor: pointer;
        outline: none;
        transition:
            border-color var(--dur) var(--ease),
            box-shadow var(--dur) var(--ease),
            background var(--dur) var(--ease);
    }

    .wbs-custom-select select:hover {
        background: var(--surface-hover);
        border-color: var(--brand);
    }

    .wbs-custom-select select:focus {
        border-color: var(--brand);
        box-shadow: 0 0 0 3.5px var(--brand-glow);
    }

    .wbs-custom-select select option {
        background: var(--surface);
        color: var(--text-primary);
        font-size: 14px;
    }

    .wbs-select-chevron {
        position: absolute;
        right: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: color var(--dur) var(--ease), transform var(--dur) var(--ease);
    }

    .wbs-select-chevron svg {
        width: 17px;
        height: 17px;
        stroke-width: 2.2;
    }

    .wbs-custom-select:focus-within .wbs-select-chevron {
        color: var(--brand);
        transform: translateY(-50%) rotate(180deg);
    }

    .wbs-check-panel {
        grid-column: 1 / -1;
        border: 1px solid var(--border);
        border-radius: var(--r-lg);
        background: var(--surface-alt);
        padding: 16px;
    }

    .wbs-check-title {
        margin: 0 0 12px;
        font-size: 14px;
        font-weight: 800;
        color: var(--text-primary);
    }

    .wbs-check-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
    }

    .wbs-check-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        min-height: 48px;
        padding: 12px;
        border: 1px solid var(--border);
        border-radius: var(--r-md);
        background: var(--surface);
        color: var(--text-secondary);
        font-size: 13px;
        line-height: 1.55;
        cursor: pointer;
        transition:
            border-color var(--dur) var(--ease),
            background var(--dur) var(--ease),
            color var(--dur) var(--ease);
    }

    .wbs-check-item:hover {
        border-color: var(--brand);
        background: var(--surface-hover);
        color: var(--text-primary);
    }

    .wbs-check-item input[type="checkbox"] {
        width: 17px;
        height: 17px;
        margin-top: 2px;
        accent-color: var(--brand);
        flex-shrink: 0;
    }

    .wbs-attachment-current {
        display: grid;
        gap: 12px;
    }

    .wbs-attachment-card {
        padding: 14px;
        border: 1px solid var(--border);
        border-radius: var(--r-md);
        background: var(--surface);
        display: flex;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        align-items: center;
        transition:
            border-color var(--dur) var(--ease),
            background var(--dur) var(--ease),
            box-shadow var(--dur) var(--ease);
    }

    .wbs-attachment-card:hover {
        border-color: var(--brand-border);
        background: var(--surface-hover);
        box-shadow: var(--shadow-xs);
    }

    .wbs-attachment-name {
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1.45;
        word-break: break-word;
    }

    .wbs-attachment-meta {
        margin-top: 3px;
        font-size: 12px;
        color: var(--text-muted);
    }

    .wbs-attachment-actions {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        align-items: center;
    }

    .wbs-delete-attachment {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-height: 38px;
        padding: 0 12px;
        border-radius: var(--r-sm);
        border: 1px solid var(--danger-alert-border);
        background: var(--danger-alert-bg);
        color: var(--danger-alert-text);
        font-size: 12.5px;
        font-weight: 800;
        cursor: pointer;
    }

    .wbs-delete-attachment input {
        width: 15px;
        height: 15px;
        accent-color: var(--danger);
    }

    .wbs-file-box {
        position: relative;
        border: 1.5px dashed var(--border-strong);
        border-radius: 22px;
        background:
            linear-gradient(180deg, rgba(255,255,255,.42), rgba(255,255,255,0)),
            var(--surface-alt);
        padding: 20px;
        overflow: hidden;
        transition:
            border-color var(--dur) var(--ease),
            background var(--dur) var(--ease),
            box-shadow var(--dur) var(--ease),
            transform var(--dur) var(--ease);
    }

    .wbs-file-box::before {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        background:
            radial-gradient(circle at 12% 18%, rgba(37,99,235,.10), transparent 30%),
            radial-gradient(circle at 88% 78%, rgba(37,99,235,.08), transparent 34%);
        opacity: .9;
    }

    .wbs-file-box:hover,
    .wbs-file-box:focus-within {
        border-color: var(--brand);
        background: var(--surface-hover);
        box-shadow: 0 16px 38px rgba(37, 99, 235, .10);
        transform: translateY(-1px);
    }

    .wbs-file-input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border: 0;
    }

    .wbs-file-inner {
        position: relative;
        z-index: 1;
        display: grid;
        grid-template-columns: 56px minmax(0, 1fr) max-content;
        align-items: center;
        gap: 16px;
        min-width: 0;
    }

    .wbs-file-icon {
        width: 56px;
        height: 56px;
        border-radius: 18px;
        background: linear-gradient(135deg, var(--brand-light), rgba(37,99,235,.08));
        color: var(--brand);
        border: 1px solid var(--brand-border);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 8px 20px rgba(37, 99, 235, .10);
    }

    .wbs-file-icon svg {
        width: 25px;
        height: 25px;
    }

    .wbs-file-copy {
        min-width: 0;
    }

    .wbs-file-title {
        font-size: 14px;
        font-weight: 900;
        color: var(--text-primary);
        line-height: 1.35;
        letter-spacing: -.01em;
    }

    .wbs-file-desc {
        margin-top: 5px;
        font-size: 12px;
        color: var(--text-muted);
        line-height: 1.55;
        max-width: 620px;
    }

    .wbs-file-trigger {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        align-self: center;
        min-width: 116px;
        height: 52px;
        min-height: 52px;
        padding: 0 22px;
        border-radius: 18px;
        border: 1px solid var(--brand);
        background: linear-gradient(135deg, var(--brand), var(--brand-dark));
        color: #ffffff !important;
        font-size: 13px;
        font-weight: 900;
        line-height: 1;
        text-align: center;
        cursor: pointer;
        white-space: nowrap;
        box-shadow: 0 12px 26px var(--brand-glow);
        user-select: none;
        -webkit-user-select: none;
        transition:
            background var(--dur) var(--ease),
            transform var(--dur) var(--ease),
            box-shadow var(--dur) var(--ease),
            border-color var(--dur) var(--ease);
    }

    .wbs-file-trigger span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        line-height: 1;
        margin: 0;
        padding: 0;
        transform: translateY(0);
    }

    .wbs-file-trigger:hover {
        background: linear-gradient(135deg, var(--brand-dark), var(--brand));
        border-color: var(--brand-dark);
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 16px 34px var(--brand-glow);
    }

    .wbs-file-trigger:active {
        transform: translateY(0);
        box-shadow: 0 8px 18px var(--brand-glow);
    }

    .wbs-file-name {
        position: relative;
        z-index: 1;
        margin-top: 12px;
        padding: 11px 13px;
        border-radius: 14px;
        background: var(--surface);
        border: 1px solid var(--border);
        color: var(--text-secondary);
        font-size: 12.5px;
        line-height: 1.55;
        display: none;
        word-break: break-word;
    }

    .wbs-file-name.show {
        display: block;
    }

    .wbs-action-row {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        flex-wrap: wrap;
        padding-top: 2px;
    }

    .wbs-action-row .wbs-btn {
        min-height: 44px;
        padding: 0 18px;
        font-weight: 800;
    }

    .wbs-btn-primary {
        background: var(--brand);
        border-color: var(--brand);
        color: #fff;
        box-shadow: 0 8px 22px var(--brand-glow);
    }

    .wbs-btn-primary:hover {
        background: var(--brand-dark);
        border-color: var(--brand-dark);
        transform: translateY(-1px);
    }

    .wbs-btn-light {
        background: var(--icon-btn-bg);
        border-color: var(--border);
        color: var(--text-secondary);
    }

    .wbs-btn-light:hover {
        background: var(--surface-hover);
        color: var(--text-primary);
        transform: translateY(-1px);
    }

    .flatpickr-calendar {
        width: 340px !important;
        border: 1px solid var(--border) !important;
        border-radius: 22px !important;
        box-shadow: var(--shadow-lg) !important;
        overflow: hidden !important;
        font-family: inherit !important;
        background: var(--surface) !important;
        color: var(--text-primary) !important;
        padding: 10px !important;
    }

    .flatpickr-calendar.arrowTop::before,
    .flatpickr-calendar.arrowTop::after,
    .flatpickr-calendar.arrowBottom::before,
    .flatpickr-calendar.arrowBottom::after {
        display: none !important;
    }

    .flatpickr-months {
        background: transparent !important;
        padding: 2px 4px 8px !important;
        align-items: center !important;
    }

    .flatpickr-month {
        height: 44px !important;
        color: var(--text-primary) !important;
    }

    .flatpickr-current-month {
        left: 44px !important;
        right: 44px !important;
        width: auto !important;
        height: 44px !important;
        padding: 0 !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        gap: 6px !important;
        color: var(--text-primary) !important;
        font-size: 15px !important;
        font-weight: 900 !important;
    }

    .flatpickr-current-month .cur-month {
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        min-height: 34px !important;
        padding: 0 12px !important;
        border-radius: 999px !important;
        background: var(--surface-alt) !important;
        color: var(--text-primary) !important;
        font-size: 15px !important;
        font-weight: 900 !important;
        line-height: 1 !important;
    }

    .flatpickr-current-month .numInputWrapper {
        width: 76px !important;
        height: 34px !important;
        border-radius: 999px !important;
        background: var(--surface-alt) !important;
        overflow: hidden !important;
    }

    .flatpickr-current-month input.cur-year {
        height: 34px !important;
        padding: 0 8px !important;
        border: 0 !important;
        background: transparent !important;
        color: var(--text-primary) !important;
        font-size: 15px !important;
        font-weight: 900 !important;
        text-align: center !important;
        box-shadow: none !important;
    }

    .flatpickr-current-month .numInputWrapper span {
        display: none !important;
    }

    .flatpickr-prev-month,
    .flatpickr-next-month {
        top: 12px !important;
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        border-radius: 14px !important;
        color: var(--text-secondary) !important;
        fill: var(--text-secondary) !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        transition:
            background var(--dur) var(--ease),
            color var(--dur) var(--ease),
            fill var(--dur) var(--ease),
            transform var(--dur) var(--ease);
    }

    .flatpickr-prev-month {
        left: 12px !important;
    }

    .flatpickr-next-month {
        right: 12px !important;
    }

    .flatpickr-prev-month:hover,
    .flatpickr-next-month:hover {
        background: var(--brand-light) !important;
        color: var(--brand) !important;
        fill: var(--brand) !important;
        transform: translateY(-1px) !important;
    }

    .flatpickr-prev-month svg,
    .flatpickr-next-month svg {
        width: 15px !important;
        height: 15px !important;
    }

    .flatpickr-weekdays {
        background: transparent !important;
        padding: 4px 0 2px !important;
    }

    .flatpickr-weekdaycontainer {
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        width: 100% !important;
    }

    span.flatpickr-weekday {
        color: var(--text-muted) !important;
        font-size: 12px !important;
        font-weight: 900 !important;
        text-transform: capitalize !important;
    }

    .flatpickr-days {
        width: 100% !important;
        background: transparent !important;
    }

    .flatpickr-rContainer {
        width: 100% !important;
    }

    .dayContainer {
        width: 100% !important;
        min-width: 100% !important;
        max-width: 100% !important;
        padding: 8px 0 4px !important;
        display: grid !important;
        grid-template-columns: repeat(7, 1fr) !important;
        gap: 4px !important;
    }

    .flatpickr-day {
        width: 40px !important;
        max-width: 40px !important;
        height: 38px !important;
        line-height: 36px !important;
        margin: 0 auto !important;
        border-radius: 13px !important;
        color: var(--text-primary) !important;
        font-size: 13px !important;
        font-weight: 800 !important;
        border: 1px solid transparent !important;
        transition:
            background var(--dur) var(--ease),
            border-color var(--dur) var(--ease),
            color var(--dur) var(--ease),
            box-shadow var(--dur) var(--ease),
            transform var(--dur) var(--ease);
    }

    .flatpickr-day:hover {
        background: var(--brand-light) !important;
        border-color: var(--brand-border) !important;
        color: var(--brand) !important;
        transform: translateY(-1px) !important;
    }

    .flatpickr-day.today {
        border-color: var(--brand) !important;
        color: var(--brand) !important;
        background: transparent !important;
    }

    .flatpickr-day.today:hover {
        background: var(--brand-light) !important;
        color: var(--brand) !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange {
        background: var(--brand) !important;
        border-color: var(--brand) !important;
        color: #fff !important;
        box-shadow: 0 8px 18px var(--brand-glow) !important;
    }

    .flatpickr-day.prevMonthDay,
    .flatpickr-day.nextMonthDay {
        color: var(--text-muted) !important;
        opacity: .45 !important;
    }

    .flatpickr-day.flatpickr-disabled,
    .flatpickr-day.flatpickr-disabled:hover {
        color: var(--text-muted) !important;
        opacity: .32 !important;
        background: transparent !important;
        cursor: not-allowed !important;
    }

    .flatpickr-footer {
        display: none;
    }

    [data-theme="dark"] .flatpickr-calendar,
    [data-theme="dark"] .flatpickr-months,
    [data-theme="dark"] .flatpickr-weekdays,
    [data-theme="dark"] .flatpickr-days {
        background: var(--surface) !important;
        border-color: var(--border-strong) !important;
    }

    [data-theme="dark"] .flatpickr-current-month .cur-month,
    [data-theme="dark"] .flatpickr-current-month .numInputWrapper {
        background: var(--surface-alt) !important;
    }

    @media (max-width: 900px) {
        .wbs-report-grid {
            grid-template-columns: 1fr;
        }

        .wbs-check-grid {
            grid-template-columns: 1fr;
        }

        .wbs-file-inner {
            grid-template-columns: 52px minmax(0, 1fr);
        }

        .wbs-file-trigger {
            grid-column: 1 / -1;
            width: 100%;
        }
    }

    @media (max-width: 640px) {
        .wbs-file-box {
            padding: 16px;
            border-radius: 20px;
        }

        .wbs-file-inner {
            display: grid;
            grid-template-columns: 48px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
        }

        .wbs-file-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
        }

        .wbs-file-icon svg {
            width: 23px;
            height: 23px;
        }

        .wbs-file-trigger {
            grid-column: 1 / -1;
            width: 100%;
            height: 48px;
            min-height: 48px;
            border-radius: 16px;
        }

        .wbs-attachment-card {
            align-items: stretch;
        }

        .wbs-attachment-actions,
        .wbs-attachment-actions .wbs-btn,
        .wbs-delete-attachment {
            width: 100%;
            justify-content: center;
        }

        .wbs-action-row {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .wbs-action-row .wbs-btn {
            width: 100%;
        }

        .flatpickr-calendar {
            width: calc(100vw - 32px) !important;
            max-width: 340px !important;
            padding: 8px !important;
        }

        .flatpickr-day {
            width: 36px !important;
            max-width: 36px !important;
            height: 36px !important;
            line-height: 34px !important;
            border-radius: 12px !important;
        }

        .flatpickr-current-month {
            left: 42px !important;
            right: 42px !important;
        }

        .flatpickr-current-month .cur-month {
            padding: 0 9px !important;
            font-size: 14px !important;
        }

        .flatpickr-current-month .numInputWrapper {
            width: 68px !important;
        }

        .flatpickr-current-month input.cur-year {
            font-size: 14px !important;
        }
    }
</style>

<div class="wbs-report-form">
    <div class="wbs-report-grid">
        <div class="form-group wbs-report-field">
            <label for="category" class="wbs-report-label">
                <span>Kategori <span class="wbs-required">*</span></span>
            </label>

            <div class="wbs-custom-select">
                <select name="category" id="category" class="wbs-select-native" required>
                    <option value="">Pilih kategori laporan</option>
                    @foreach($categoryOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('category', $reportData->category ?? '') === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>

                <span class="wbs-select-chevron" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                        <path d="M6 9l6 6 6-6"></path>
                    </svg>
                </span>
            </div>

            <div class="wbs-help-text">
                Pilih jenis pelanggaran yang paling sesuai dengan laporan Anda.
            </div>
        </div>

        <div class="form-group wbs-report-field">
            <label for="incident_date" class="wbs-report-label">
                <span>Tanggal Kejadian</span>
            </label>

            <div class="wbs-date-wrap">
                <input
                    type="text"
                    name="incident_date"
                    id="incident_date"
                    class="wbs-input js-wbs-date"
                    value="{{ old('incident_date', optional($reportData?->incident_date)->format('Y-m-d')) }}"
                    placeholder="Pilih tanggal kejadian"
                    autocomplete="off"
                >

                <button type="button" class="wbs-date-btn" data-date-trigger="incident_date" aria-label="Pilih tanggal kejadian">
                    <svg viewBox="0 0 24 24" fill="none" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M8 2v4"></path>
                        <path d="M16 2v4"></path>
                        <path d="M3 10h18"></path>
                        <path d="M5 5h14a2 2 0 0 1 2 2v14H3V7a2 2 0 0 1 2-2Z"></path>
                    </svg>
                </button>
            </div>

            <div class="wbs-help-text">
                Isi jika tanggal kejadian diketahui. Boleh dikosongkan jika belum pasti.
            </div>
        </div>

        <div class="form-group wbs-report-field full">
            <label for="title" class="wbs-report-label">
                <span>Judul Laporan <span class="wbs-required">*</span></span>
            </label>

            <input
                type="text"
                name="title"
                id="title"
                class="wbs-input"
                value="{{ old('title', $reportData->title ?? '') }}"
                placeholder="Contoh: Dugaan penyalahgunaan aset perusahaan"
                required
            >

            <div class="wbs-help-text">
                Buat judul singkat agar laporan mudah dikenali oleh admin WBS.
            </div>
        </div>

        <div class="form-group wbs-report-field full">
            <label for="description" class="wbs-report-label">
                <span>Pokok Masalah <span class="wbs-required">*</span></span>
            </label>

            <textarea
                name="description"
                id="description"
                class="wbs-textarea"
                placeholder="Jelaskan inti permasalahan, apa yang terjadi, dan mengapa hal tersebut perlu dilaporkan."
                required
            >{{ old('description', $reportData->description ?? '') }}</textarea>

            <div class="wbs-help-text">
                Tuliskan masalah utama secara jelas. Hindari informasi yang tidak relevan.
            </div>
        </div>

        <div class="form-group wbs-report-field full">
            <label for="involved_parties" class="wbs-report-label">
                <span>Pihak yang Terlibat</span>
            </label>

            <textarea
                name="involved_parties"
                id="involved_parties"
                class="wbs-textarea"
                placeholder="Contoh: nama pihak, jabatan, unit kerja, vendor, atau pihak lain yang diduga terlibat. Jika tidak tahu, tuliskan belum diketahui."
            >{{ old('involved_parties', $reportData->involved_parties ?? '') }}</textarea>

            <div class="wbs-help-text">
                Informasi ini membantu admin WBS memahami pihak terkait dalam laporan.
            </div>
        </div>

        <div class="form-group wbs-report-field">
            <label for="location" class="wbs-report-label">
                <span>Lokasi</span>
            </label>

            <input
                type="text"
                name="location"
                id="location"
                class="wbs-input"
                value="{{ old('location', $reportData->location ?? '') }}"
                placeholder="Contoh: Kantor pusat, area operasional, gudang, lokasi proyek"
            >

            <div class="wbs-help-text">
                Isi lokasi kejadian jika diketahui.
            </div>
        </div>

        <div class="form-group wbs-report-field">
            <label for="estimated_loss" class="wbs-report-label">
                <span>Estimasi Kerugian</span>
            </label>

            <input
                type="text"
                name="estimated_loss"
                id="estimated_loss"
                class="wbs-input"
                value="{{ old('estimated_loss', $reportData->estimated_loss ?? '') }}"
                placeholder="Contoh: sekitar Rp 50 juta / belum dapat dipastikan"
            >

            <div class="wbs-help-text">
                Boleh diisi perkiraan nilai kerugian, atau tuliskan belum dapat dipastikan.
            </div>
        </div>

        <div class="form-group wbs-report-field full">
            <label for="chronology" class="wbs-report-label">
                <span>Kronologi</span>
            </label>

            <textarea
                name="chronology"
                id="chronology"
                class="wbs-textarea"
                placeholder="Ceritakan urutan kejadian: kapan diketahui, siapa yang melihat, bagaimana prosesnya, dan bukti awal yang tersedia."
            >{{ old('chronology', $reportData->chronology ?? '') }}</textarea>

            <div class="wbs-help-text">
                Semakin runtut kronologi yang diberikan, semakin mudah laporan ditindaklanjuti.
            </div>
        </div>

        <div class="wbs-check-panel">
            <h3 class="wbs-check-title">Informasi Tambahan</h3>

            <div class="wbs-check-grid">
                <label class="wbs-check-item">
                    <input type="hidden" name="has_evidence" value="0">
                    <input type="checkbox" name="has_evidence" value="1" {{ old('has_evidence', $reportData->has_evidence ?? false) ? 'checked' : '' }}>
                    <span>Ada bukti pendukung seperti dokumen, foto, rekaman, atau bukti lainnya.</span>
                </label>

                <label class="wbs-check-item">
                    <input type="hidden" name="reported_before" value="0">
                    <input type="checkbox" name="reported_before" value="1" {{ old('reported_before', $reportData->reported_before ?? false) ? 'checked' : '' }}>
                    <span>Pernah dilaporkan sebelumnya melalui kanal lain atau kepada pihak internal.</span>
                </label>

                <label class="wbs-check-item">
                    <input type="hidden" name="reported_to_other_party" value="0">
                    <input type="checkbox" name="reported_to_other_party" value="1" {{ old('reported_to_other_party', $reportData->reported_to_other_party ?? false) ? 'checked' : '' }}>
                    <span>Juga dilaporkan kepada pihak lain di luar sistem WBS ini.</span>
                </label>
            </div>
        </div>

        @if($reportData && $reportData->attachments->count())
            <div class="form-group wbs-report-field full">
                <label class="wbs-report-label">
                    <span>Lampiran Saat Ini</span>
                </label>

                <div class="wbs-attachment-current">
                    @foreach($reportData->attachments as $attachment)
                        <div class="wbs-attachment-card">
                            <div>
                                <div class="wbs-attachment-name">{{ $attachment->original_name }}</div>
                                <div class="wbs-attachment-meta">{{ $attachment->file_size_label }}</div>
                            </div>

                            <div class="wbs-attachment-actions">
                                <a href="{{ $attachment->file_url }}" target="_blank" rel="noopener noreferrer" class="wbs-btn wbs-btn-light">
                                    Lihat File
                                </a>

                                <label class="wbs-delete-attachment">
                                    <input type="checkbox" name="delete_attachment_ids[]" value="{{ $attachment->id }}">
                                    <span>Hapus lampiran</span>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="wbs-help-text">
                    Centang “Hapus lampiran” hanya jika file tersebut ingin dihapus saat laporan disimpan.
                </div>
            </div>
        @endif

        <div class="form-group wbs-report-field full">
            <label for="attachments" class="wbs-report-label">
                <span>Lampiran Baru</span>
            </label>

            <div class="wbs-file-box">
                <input
                    type="file"
                    name="attachments[]"
                    id="attachments"
                    class="wbs-file-input"
                    multiple
                    accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"
                >

                <div class="wbs-file-inner">
                    <div class="wbs-file-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8Z"></path>
                            <path d="M14 2v6h6"></path>
                            <path d="M12 18v-6"></path>
                            <path d="M9 15l3 3 3-3"></path>
                        </svg>
                    </div>

                    <div class="wbs-file-copy">
                        <div class="wbs-file-title">Upload lampiran pendukung</div>
                        <div class="wbs-file-desc">
                            Maksimal 5 file. Format: pdf, jpg, jpeg, png, doc, docx, xls, xlsx. Maksimal 5 MB per file.
                        </div>
                    </div>

                    <label for="attachments" class="wbs-file-trigger">
                        <span>Pilih File</span>
                    </label>
                </div>

                <div id="attachmentsFileName" class="wbs-file-name"></div>
            </div>

            <div class="wbs-help-text">
                Lampiran bersifat opsional, tetapi sangat membantu proses verifikasi laporan.
            </div>
        </div>
    </div>

    <div class="wbs-action-row">
        <a href="{{ route('wbs.pelapor.reports.index') }}" class="wbs-btn wbs-btn-light">
            Kembali
        </a>

        <button type="submit" class="wbs-btn wbs-btn-primary">
            {{ $submitLabel }}
        </button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/id.js"></script>

<script>
(function () {
    const dateInput = document.getElementById('incident_date');

    if (dateInput && typeof flatpickr !== 'undefined') {
        flatpickr(dateInput, {
            locale: 'id',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
            disableMobile: true,
            monthSelectorType: 'static',
            prevArrow: `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 18l-6-6 6-6"></path>
                </svg>
            `,
            nextArrow: `
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"></path>
                </svg>
            `
        });
    }

    document.querySelectorAll('[data-date-trigger]').forEach(function (button) {
        button.addEventListener('click', function () {
            const targetId = button.getAttribute('data-date-trigger');
            const input = document.getElementById(targetId);

            if (!input) {
                return;
            }

            if (input._flatpickr) {
                input._flatpickr.open();
            } else {
                input.focus();
            }
        });
    });

    const attachmentsInput = document.getElementById('attachments');
    const attachmentsFileName = document.getElementById('attachmentsFileName');

    if (!attachmentsInput || !attachmentsFileName) {
        return;
    }

    attachmentsInput.addEventListener('change', function () {
        const files = Array.from(this.files || []);

        if (!files.length) {
            attachmentsFileName.classList.remove('show');
            attachmentsFileName.textContent = '';
            return;
        }

        if (files.length === 1) {
            attachmentsFileName.textContent = 'File dipilih: ' + files[0].name;
        } else {
            attachmentsFileName.textContent = files.length + ' file dipilih: ' + files.map(function (file) {
                return file.name;
            }).join(', ');
        }

        attachmentsFileName.classList.add('show');
    });
})();
</script>