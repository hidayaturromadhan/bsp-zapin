<?php

namespace App\Console\Commands;

use App\Models\OperationalDisplayToken;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class GenerateOperationalDisplayToken extends Command
{
    protected $signature = 'operational:display-token
                            {--name=Display TV Operational : Nama token display}
                            {--days= : Masa aktif token dalam hari. Kosongkan jika tidak ingin expired}';

    protected $description = 'Generate token aman untuk akses publik Operational Display TV tanpa login.';

    public function handle(): int
    {
        $name = (string) $this->option('name');
        $days = $this->option('days');

        $token = Str::random(64);

        $expiredAt = null;

        if ($days !== null && $days !== '') {
            $expiredAt = now()->addDays((int) $days);
        }

        $displayToken = OperationalDisplayToken::create([
            'name' => $name ?: 'Display TV Operational',
            'token' => $token,
            'is_active' => true,
            'expired_at' => $expiredAt,
        ]);

        $url = url('/operational/display/' . $displayToken->token);

        $this->newLine();
        $this->info('Token Display TV berhasil dibuat.');
        $this->newLine();

        $this->line('Nama Token     : ' . $displayToken->name);
        $this->line('Token          : ' . $displayToken->token);
        $this->line('Status         : Aktif');
        $this->line('Expired        : ' . ($displayToken->expired_at ? $displayToken->expired_at->format('d-m-Y H:i:s') : 'Tidak ada expired'));
        $this->newLine();

        $this->warn('URL Display TV:');
        $this->line($url);

        $this->newLine();
        $this->comment('Simpan URL ini. Siapa pun yang punya URL ini bisa membuka Display TV tanpa login.');
        $this->comment('Jika URL bocor, nonaktifkan token dari database atau buat token baru.');

        return self::SUCCESS;
    }
}