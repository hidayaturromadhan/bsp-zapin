<?php

namespace App\Exports;

use App\Models\FlowGasDailyRecord;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithTitle;

class FlowGasMonthlyExport implements FromArray, ShouldAutoSize, WithTitle
{
    protected int $month;
    protected int $year;

    public function __construct(int $month, int $year)
    {
        $this->month = $month;
        $this->year = $year;
    }

    public function array(): array
    {
        $records = FlowGasDailyRecord::query()
            ->with('category')
            ->where('type', 'gas')
            ->whereYear('record_date', $this->year)
            ->whereMonth('record_date', $this->month)
            ->orderBy('record_date')
            ->orderBy('flow_gas_category_id')
            ->get();

        $dates = $records->pluck('record_date')
            ->map(fn ($date) => Carbon::parse($date)->format('Y-m-d'))
            ->unique()
            ->values();

        $categories = $records->pluck('category')
            ->filter()
            ->unique('id')
            ->sortBy('sort_order')
            ->values();

        $rows = [];

        $rows[] = ['FLOW GAS REPORT'];
        $rows[] = ['Period', Carbon::create($this->year, $this->month, 1)->translatedFormat('F Y')];
        $rows[] = [];

        $headerTop = ['Tanggal'];
        $headerBottom = [''];

        foreach ($categories as $category) {
            $headerTop[] = $category->name . ' - MSCF';
            $headerTop[] = $category->name . ' - MMBTU';
            $headerTop[] = $category->name . ' - FIX';

            $headerBottom[] = 'MSCF';
            $headerBottom[] = 'MMBTU';
            $headerBottom[] = 'FIX';
        }

        $headerTop[] = 'TOTAL MSCF';
        $headerTop[] = 'TOTAL MMBTU';
        $headerTop[] = 'TOTAL FIX';

        $headerBottom[] = '';
        $headerBottom[] = '';
        $headerBottom[] = '';

        $rows[] = $headerTop;
        $rows[] = $headerBottom;

        foreach ($dates as $date) {
            $line = [$date];
            $dayTotalMscf = 0;
            $dayTotalMmbtu = 0;
            $dayTotalFix = 0;

            foreach ($categories as $category) {
                $record = $records->first(function ($item) use ($date, $category) {
                    return Carbon::parse($item->record_date)->format('Y-m-d') === $date
                        && (int) $item->flow_gas_category_id === (int) $category->id;
                });

                $mscf = (float) ($record->mscf ?? 0);
                $mmbtu = (float) ($record->mmbtu ?? 0);
                $fix = (float) ($record->fix ?? 0);

                $line[] = $mscf;
                $line[] = $mmbtu;
                $line[] = $fix;

                $dayTotalMscf += $mscf;
                $dayTotalMmbtu += $mmbtu;
                $dayTotalFix += $fix;
            }

            $line[] = $dayTotalMscf;
            $line[] = $dayTotalMmbtu;
            $line[] = $dayTotalFix;

            $rows[] = $line;
        }

        $rows[] = [];

        $summary = ['TOTAL BULANAN'];
        foreach ($categories as $category) {
            $categoryRecords = $records->where('flow_gas_category_id', $category->id);
            $summary[] = (float) $categoryRecords->sum('mscf');
            $summary[] = (float) $categoryRecords->sum('mmbtu');
            $summary[] = (float) $categoryRecords->sum('fix');
        }

        $summary[] = (float) $records->sum('mscf');
        $summary[] = (float) $records->sum('mmbtu');
        $summary[] = (float) $records->sum('fix');

        $rows[] = $summary;

        return $rows;
    }

    public function title(): string
    {
        return 'FLOW GAS ' . Carbon::create($this->year, $this->month, 1)->format('M Y');
    }
}
