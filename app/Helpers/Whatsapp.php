<?php

if (! function_exists('wa_link')) {
    function wa_link(?string $phone, string $message): ?string
    {
        if (! $phone) {
            return null;
        }

        // normalisasi nomor
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        if (str_starts_with($phone, '8')) {
            $phone = '62' . $phone;
        }

        $encoded = urlencode($message);

        return "https://wa.me/{$phone}?text={$encoded}";
    }
}