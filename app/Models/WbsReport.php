<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WbsReport extends Model
{
    protected $table = 'wbs_reports';

    protected $fillable = [
        'report_number',
        'user_id',
        'category',
        'title',
        'description',
        'involved_parties',
        'location',
        'incident_date',
        'chronology',
        'estimated_loss',
        'has_evidence',
        'reported_before',
        'reported_to_other_party',
        'status',
        'admin_notes',
        'follow_up_result',
        'pdf_path',
        'submitted_at',
        'processed_at',
        'closed_at',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'has_evidence' => 'boolean',
        'reported_before' => 'boolean',
        'reported_to_other_party' => 'boolean',
        'submitted_at' => 'datetime',
        'processed_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public const STATUS_LAPORAN_MASUK = 'laporan_masuk';
    public const STATUS_DITELAAH = 'ditelaah';
    public const STATUS_PERLU_KLARIFIKASI = 'perlu_klarifikasi';
    public const STATUS_DALAM_PROSES = 'dalam_proses';
    public const STATUS_DALAM_INVESTIGASI = 'dalam_investigasi';
    public const STATUS_SELESAI = 'selesai';
    public const STATUS_DITUTUP = 'ditutup';
    public const STATUS_DI_LUAR_RUANG_LINGKUP = 'di_luar_ruang_lingkup';

    public static function statusOptions(): array
    {
        return [
            self::STATUS_LAPORAN_MASUK => 'Laporan Masuk',
            self::STATUS_DITELAAH => 'Ditelaah',
            self::STATUS_PERLU_KLARIFIKASI => 'Perlu Klarifikasi',
            self::STATUS_DALAM_PROSES => 'Dalam Proses',
            self::STATUS_DALAM_INVESTIGASI => 'Dalam Investigasi',
            self::STATUS_SELESAI => 'Selesai',
            self::STATUS_DITUTUP => 'Ditutup',
            self::STATUS_DI_LUAR_RUANG_LINGKUP => 'Di Luar Ruang Lingkup',
        ];
    }

    public static function categoryOptions(): array
    {
        return [
            'fraud_keuangan' => 'Fraud & Keuangan',
            'penyuapan_korupsi' => 'Penyuapan & Korupsi',
            'pelanggaran_pengadaan_tender' => 'Pelanggaran Pengadaan & Tender',
            'pencurian_penyalahgunaan_aset' => 'Pencurian & Penyalahgunaan Aset',
            'pelanggaran_etika_perilaku' => 'Pelanggaran Etika & Perilaku',
            'pelanggaran_hukum_regulasi' => 'Pelanggaran Hukum & Regulasi',
            'k3ll' => 'K3LL',
            'keamanan_informasi_data' => 'Keamanan Informasi Data',
        ];
    }

    public static function editableByPelaporStatuses(): array
    {
        return [
            self::STATUS_LAPORAN_MASUK,
            self::STATUS_PERLU_KLARIFIKASI,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(WbsReportAttachment::class, 'wbs_report_id')->latest('id');
    }

    public function scopeFilterStatus(Builder $query, ?string $status): Builder
    {
        if (! filled($status)) {
            return $query;
        }

        return $query->where('status', $status);
    }

    public function scopeFilterCategory(Builder $query, ?string $category): Builder
    {
        if (! filled($category)) {
            return $query;
        }

        return $query->where('category', $category);
    }

    public function scopeSearch(Builder $query, ?string $search): Builder
    {
        $search = trim((string) $search);

        if ($search === '') {
            return $query;
        }

        return $query->where(function (Builder $sub) use ($search) {
            $sub->where('report_number', 'like', '%' . $search . '%')
                ->orWhere('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('user', function (Builder $userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('email', 'like', '%' . $search . '%');
                });
        });
    }

    public function getStatusLabelAttribute(): string
    {
        return static::statusOptions()[$this->status] ?? ucfirst(str_replace('_', ' ', $this->status));
    }

    public function getCategoryLabelAttribute(): string
    {
        return static::categoryOptions()[$this->category] ?? ucfirst(str_replace('_', ' ', $this->category));
    }

    public function getPdfUrlAttribute(): ?string
    {
        if (! $this->pdf_path) {
            return null;
        }

        return asset($this->pdf_path);
    }

    public function canBeEditedByPelapor(): bool
    {
        return in_array($this->status, static::editableByPelaporStatuses(), true);
    }

    public function isAlreadyHandledByAdmin(): bool
    {
        return ! $this->canBeEditedByPelapor();
    }
}