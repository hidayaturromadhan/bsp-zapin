<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CrudeDailyRecord;
use App\Models\FlowGasDailyRecord;
use App\Models\VitolRecord;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class OperationalController extends Controller
{
    private int $cacheMinutes = 30;
    private int $maxYears = 8;

    public function index(string $locale)
    {
        $locale = in_array($locale, ['id', 'en'], true) ? $locale : 'id';
        $isEnglish = $locale === 'en';

        $data = Cache::remember(
            "web_operational_chart_data_v2_{$locale}",
            now()->addMinutes($this->cacheMinutes),
            function () {
                return [
                    'gas' => $this->getGasYearlyData(),
                    'crude' => $this->getCrudeYearlyData(),
                    'vitol' => $this->getVitolYearlyData(),
                ];
            }
        );

        $gasYearlyRaw = collect($data['gas']);
        $crudeYearlyRaw = collect($data['crude']);
        $vitolYearlyRaw = collect($data['vitol']);

        return view('web.operational', [
            'locale' => $locale,
            'pageTitle' => $isEnglish ? 'Operational' : 'Operasional',

            'opText' => $this->getOperationalText($locale),

            'gasYears' => $gasYearlyRaw
                ->pluck('year')
                ->map(fn ($year) => (string) $year)
                ->values(),

            'gasValues' => $gasYearlyRaw
                ->pluck('total_value')
                ->map(fn ($value) => round((float) $value, 4))
                ->values(),

            'crudeYears' => $crudeYearlyRaw
                ->pluck('year')
                ->map(fn ($year) => (string) $year)
                ->values(),

            'crudeValues' => $crudeYearlyRaw
                ->pluck('total_value')
                ->map(fn ($value) => round((float) $value, 4))
                ->values(),

            'vitolYears' => $vitolYearlyRaw
                ->pluck('year')
                ->map(fn ($year) => (string) $year)
                ->values(),

            'vitolValues' => $vitolYearlyRaw
                ->pluck('total_value')
                ->map(fn ($value) => round((float) $value, 4))
                ->values(),
        ]);
    }

    private function getGasYearlyData(): array
    {
        return FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->whereNotNull('record_date')
            ->selectRaw('YEAR(record_date) as year, SUM(COALESCE(mscf, 0)) as total_value')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderByDesc(DB::raw('YEAR(record_date)'))
            ->limit($this->maxYears)
            ->get()
            ->sortBy('year')
            ->values()
            ->map(fn ($row) => [
                'year' => (int) $row->year,
                'total_value' => (float) $row->total_value,
            ])
            ->all();
    }

    private function getCrudeYearlyData(): array
    {
        return CrudeDailyRecord::query()
            ->whereNotNull('record_date')
            ->selectRaw('YEAR(record_date) as year, SUM(COALESCE(production, 0)) as total_value')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderByDesc(DB::raw('YEAR(record_date)'))
            ->limit($this->maxYears)
            ->get()
            ->sortBy('year')
            ->values()
            ->map(fn ($row) => [
                'year' => (int) $row->year,
                'total_value' => (float) $row->total_value,
            ])
            ->all();
    }

    private function getVitolYearlyData(): array
    {
        return VitolRecord::query()
            ->whereNotNull('year')
            ->selectRaw('year, SUM(COALESCE(quantity, 0)) as total_value')
            ->groupBy('year')
            ->orderByDesc('year')
            ->limit($this->maxYears)
            ->get()
            ->sortBy('year')
            ->values()
            ->map(fn ($row) => [
                'year' => (int) $row->year,
                'total_value' => (float) $row->total_value,
            ])
            ->all();
    }

    private function getOperationalText(string $locale): array
    {
        if ($locale === 'en') {
            return [
                'kicker' => 'Operational Insight',
                'description' => 'The operational activities of PT Bumi Siak Pusako Zapin focus on reliable, efficient, and sustainable energy management and distribution. Supported by infrastructure and the implementation of high safety and performance standards, the Company ensures that gas distribution and other operational activities run optimally to meet energy needs in its operational areas.',

                'gasTitle' => 'Annual Gas Trend',
                'gasDesc' => 'Total annual gas distribution based on daily records.',
                'gasEmptyTitle' => 'No Gas Data Available',
                'gasEmptyDesc' => 'The chart will appear once gas data is available.',

                'crudeTitle' => 'Annual Crude Oil Trend',
                'crudeDesc' => 'Total annual crude oil production based on daily records.',
                'crudeEmptyTitle' => 'No Crude Oil Data Available',
                'crudeEmptyDesc' => 'The chart will appear once crude oil data is available.',

                'vitolTitle' => 'Annual VITOL Trend',
                'vitolDesc' => 'Total annual VITOL quantity based on monthly records.',
                'vitolEmptyTitle' => 'No VITOL Data Available',
                'vitolEmptyDesc' => 'The chart will appear once VITOL data is available.',

                'gasDataset' => 'MSCF',
                'crudeDataset' => 'Production',
                'vitolDataset' => 'Quantity',
            ];
        }

        return [
            'kicker' => 'Insight Operasional',
            'description' => 'Kegiatan operasional PT Bumi Siak Pusako Zapin berfokus pada pengelolaan dan penyaluran energi secara andal, efisien, dan berkelanjutan. Melalui dukungan infrastruktur serta penerapan standar keselamatan dan kinerja yang tinggi, Perusahaan memastikan distribusi gas dan kegiatan operasional lainnya berjalan optimal dalam memenuhi kebutuhan energi di wilayah operasional.',

            'gasTitle' => 'Tren Tahunan Gas',
            'gasDesc' => 'Total penyaluran gas per tahun berdasarkan data harian.',
            'gasEmptyTitle' => 'Belum ada data Gas',
            'gasEmptyDesc' => 'Grafik akan tampil setelah data gas tersedia.',

            'crudeTitle' => 'Tren Tahunan Crude Oil',
            'crudeDesc' => 'Total produksi crude oil per tahun berdasarkan data harian.',
            'crudeEmptyTitle' => 'Belum ada data Crude Oil',
            'crudeEmptyDesc' => 'Grafik akan tampil setelah data crude oil tersedia.',

            'vitolTitle' => 'Tren Tahunan VITOL',
            'vitolDesc' => 'Total quantity VITOL per tahun berdasarkan data bulanan.',
            'vitolEmptyTitle' => 'Belum ada data VITOL',
            'vitolEmptyDesc' => 'Grafik akan tampil setelah data VITOL tersedia.',

            'gasDataset' => 'MSCF',
            'crudeDataset' => 'Produksi',
            'vitolDataset' => 'Quantity',
        ];
    }
}