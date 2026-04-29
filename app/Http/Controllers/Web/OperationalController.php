<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\FlowGasDailyRecord;
use Illuminate\Support\Facades\DB;

class OperationalController extends Controller
{
    public function index(string $locale)
    {
        $yearlyRaw = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year, SUM(COALESCE(mscf, 0)) as total_mscf')
            ->groupBy(DB::raw('YEAR(record_date)'))
            ->orderBy(DB::raw('YEAR(record_date)'))
            ->get();

        $years = $yearlyRaw->pluck('year')
            ->map(fn ($year) => (string) $year)
            ->values();

        $values = $yearlyRaw->pluck('total_mscf')
            ->map(fn ($value) => round((float) $value, 4))
            ->values();

        return view('web.operational', [
            'locale' => $locale,
            'pageTitle' => $locale === 'en' ? 'Operational' : 'Operasional',
            'years' => $years,
            'values' => $values,
        ]);
    }
}