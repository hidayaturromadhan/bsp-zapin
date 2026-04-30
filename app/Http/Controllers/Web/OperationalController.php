<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\CrudeDailyRecord;
use App\Models\FlowGasDailyRecord;
use App\Models\VitolRecord;
use App\Services\NewsAutoTranslator;
use Illuminate\Support\Facades\DB;

class OperationalController extends Controller
{
    public function index(string $locale, NewsAutoTranslator $translator)
    {
        $isEnglish = $locale === 'en';

        $t = function (string $text) use ($isEnglish, $translator) {
            return $isEnglish
                ? $translator->translateText($text, 'id', 'en')
                : $text;
        };

        $gasYearlyRaw = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year, SUM(COALESCE(mscf, 0)) as total_value')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderBy(DB::raw('YEAR(record_date)'))
            ->get();

        $crudeYearlyRaw = CrudeDailyRecord::query()
            ->selectRaw('YEAR(record_date) as year, SUM(COALESCE(production, 0)) as total_value')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderBy(DB::raw('YEAR(record_date)'))
            ->get();

        $vitolYearlyRaw = VitolRecord::query()
            ->selectRaw('year, SUM(COALESCE(quantity, 0)) as total_value')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        return view('web.operational', [
            'locale' => $locale,
            'pageTitle' => $isEnglish ? 'Operational' : 'Operasional',

            'opText' => [
                'kicker' => $t('Insight Operasional'),
                'description' => $t('Kegiatan operasional PT Bumi Siak Pusako Zapin berfokus pada pengelolaan dan penyaluran energi secara andal, efisien, dan berkelanjutan. Melalui dukungan infrastruktur serta penerapan standar keselamatan dan kinerja yang tinggi, Perusahaan memastikan distribusi gas dan kegiatan operasional lainnya berjalan optimal dalam memenuhi kebutuhan energi di wilayah operasional.'),

                'gasTitle' => $t('Tren Tahunan Gas'),
                'gasDesc' => $t('Total penyaluran gas per tahun berdasarkan data harian.'),
                'gasEmptyTitle' => $t('Belum ada data Gas'),
                'gasEmptyDesc' => $t('Grafik akan tampil setelah data gas tersedia.'),

                'crudeTitle' => $t('Tren Tahunan Crude Oil'),
                'crudeDesc' => $t('Total produksi crude oil per tahun berdasarkan data harian.'),
                'crudeEmptyTitle' => $t('Belum ada data Crude Oil'),
                'crudeEmptyDesc' => $t('Grafik akan tampil setelah data crude oil tersedia.'),

                'vitolTitle' => $t('Tren Tahunan VITOL'),
                'vitolDesc' => $t('Total quantity VITOL per tahun berdasarkan data bulanan.'),
                'vitolEmptyTitle' => $t('Belum ada data VITOL'),
                'vitolEmptyDesc' => $t('Grafik akan tampil setelah data VITOL tersedia.'),

                'gasDataset' => 'MSCF',
                'crudeDataset' => $t('Produksi'),
                'vitolDataset' => 'Quantity',
            ],

            'gasYears' => $gasYearlyRaw->pluck('year')->map(fn ($year) => (string) $year)->values(),
            'gasValues' => $gasYearlyRaw->pluck('total_value')->map(fn ($value) => round((float) $value, 4))->values(),

            'crudeYears' => $crudeYearlyRaw->pluck('year')->map(fn ($year) => (string) $year)->values(),
            'crudeValues' => $crudeYearlyRaw->pluck('total_value')->map(fn ($value) => round((float) $value, 4))->values(),

            'vitolYears' => $vitolYearlyRaw->pluck('year')->map(fn ($year) => (string) $year)->values(),
            'vitolValues' => $vitolYearlyRaw->pluck('total_value')->map(fn ($value) => round((float) $value, 4))->values(),
        ]);
    }
}