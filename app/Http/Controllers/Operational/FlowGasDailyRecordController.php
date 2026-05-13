<?php

namespace App\Http\Controllers\Operational;

use App\Http\Controllers\Controller;
use App\Models\FlowGasCategory;
use App\Models\FlowGasDailyRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FlowGasDailyRecordController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'date' => $request->input('date'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'search' => $request->input('search'),
        ];

        $categories = FlowGasCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $query = FlowGasDailyRecord::query()
            ->with('category')
            ->where('type', 'gas');

        $this->applyFilters($query, $filters, true);

        $records = $query
            ->orderByDesc('record_date')
            ->orderBy('flow_gas_category_id')
            ->paginate(10)
            ->withQueryString();

        $summaryQuery = FlowGasDailyRecord::query()
            ->where('type', 'gas');

        $this->applyFilters($summaryQuery, $filters, false);

        $summary = [
            'count' => (clone $summaryQuery)->count(),
            'total_mscf' => (float) (clone $summaryQuery)->sum('mscf'),
            'total_mmbtu' => (float) (clone $summaryQuery)->sum('mmbtu'),
            'total_fix' => (float) (clone $summaryQuery)->sum('fix'),
        ];

        $yearOptions = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year')
            ->filter()
            ->values();

        if ($yearOptions->isEmpty()) {
            $yearOptions = collect([now()->year]);
        }

        return view('operational.flow-gas.index', [
            'records' => $records,
            'categories' => $categories,
            'filters' => $filters,
            'summary' => $summary,
            'yearOptions' => $yearOptions,
            'monthOptions' => $this->monthOptions(),
        ]);
    }

    public function create(Request $request)
    {
        $categories = FlowGasCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('operational.flow-gas.create', [
            'categories' => $categories,
            'defaultDate' => $request->input('date', now()->toDateString()),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'flow_gas_category_id' => ['required', 'exists:flow_gas_categories,id'],
            'record_date' => ['required', 'date'],
            'mscf' => ['nullable', 'numeric'],
            'mmbtu' => ['nullable', 'numeric'],
            'fix' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->where('flow_gas_category_id', $validated['flow_gas_category_id'])
            ->whereDate('record_date', $validated['record_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'record_date' => 'Data untuk kategori dan tanggal tersebut sudah ada.',
                ])
                ->withInput();
        }

        $validated['type'] = 'gas';

        FlowGasDailyRecord::create($validated);

        return redirect()
            ->route('operational.flow-gas.index', [
                'month' => date('m', strtotime($validated['record_date'])),
                'year' => date('Y', strtotime($validated['record_date'])),
            ])
            ->with('success', 'Data flow gas harian berhasil ditambahkan.');
    }

    public function edit(FlowGasDailyRecord $flowGas)
    {
        abort_if($flowGas->type !== 'gas', 404);

        $categories = FlowGasCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('operational.flow-gas.edit', [
            'record' => $flowGas->load('category'),
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, FlowGasDailyRecord $flowGas)
    {
        abort_if($flowGas->type !== 'gas', 404);

        $validated = $request->validate([
            'flow_gas_category_id' => ['required', 'exists:flow_gas_categories,id'],
            'record_date' => ['required', 'date'],
            'mscf' => ['nullable', 'numeric'],
            'mmbtu' => ['nullable', 'numeric'],
            'fix' => ['nullable', 'numeric'],
            'notes' => ['nullable', 'string'],
        ]);

        $exists = FlowGasDailyRecord::query()
            ->where('id', '!=', $flowGas->id)
            ->where('type', 'gas')
            ->where('flow_gas_category_id', $validated['flow_gas_category_id'])
            ->whereDate('record_date', $validated['record_date'])
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'record_date' => 'Data untuk kategori dan tanggal tersebut sudah ada.',
                ])
                ->withInput();
        }

        $validated['type'] = 'gas';

        $flowGas->update($validated);

        return redirect()
            ->route('operational.flow-gas.index', [
                'month' => date('m', strtotime($validated['record_date'])),
                'year' => date('Y', strtotime($validated['record_date'])),
            ])
            ->with('success', 'Data flow gas harian berhasil diperbarui.');
    }

    public function destroy(FlowGasDailyRecord $flowGas)
    {
        abort_if($flowGas->type !== 'gas', 404);

        $month = optional($flowGas->record_date)->format('m');
        $year = optional($flowGas->record_date)->format('Y');

        $flowGas->delete();

        return redirect()
            ->route('operational.flow-gas.index', [
                'month' => $month,
                'year' => $year,
            ])
            ->with('success', 'Data flow gas harian berhasil dihapus.');
    }

    public function exportExcel(Request $request): StreamedResponse
    {
        $filters = [
            'category_id' => $request->input('category_id'),
            'date' => $request->input('date'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'search' => $request->input('search'),
        ];

        $spreadsheet = new Spreadsheet();

        $spreadsheet->getProperties()
            ->setCreator('BSP Zapin')
            ->setLastModifiedBy('BSP Zapin')
            ->setTitle('Export Data Flow Gas')
            ->setSubject('Export Data Flow Gas')
            ->setDescription('Export data Flow Gas berdasarkan filter operasional.');

        $selectedDate = ! empty($filters['date'])
            ? Carbon::parse($filters['date'])
            : null;

        $selectedMonth = ! empty($filters['month'])
            ? (int) $filters['month']
            : null;

        $selectedYear = ! empty($filters['year'])
            ? (int) $filters['year']
            : null;

        if ($selectedDate) {
            $year = (int) $selectedDate->year;
            $month = (int) $selectedDate->month;

            $sheet = $spreadsheet->getActiveSheet();
            $this->buildFlowGasSheet($sheet, $filters, $year, $month);

            $fileName = 'Flow Gas ' . $selectedDate->format('d-m-Y') . '.xlsx';
        } elseif ($selectedMonth && $selectedYear) {
            $sheet = $spreadsheet->getActiveSheet();
            $this->buildFlowGasSheet($sheet, $filters, $selectedYear, $selectedMonth);

            $fileName = 'Flow Gas ' . $this->monthOptions()[$selectedMonth] . ' ' . $selectedYear . '.xlsx';
        } else {
            $year = $selectedYear ?: $this->getLatestFlowGasYear();

            $spreadsheet->removeSheetByIndex(0);

            foreach (range(1, 12) as $month) {
                $sheet = $spreadsheet->createSheet();
                $this->buildFlowGasSheet($sheet, $filters, $year, $month);
            }

            $spreadsheet->setActiveSheetIndex(0);

            $fileName = 'Flow Gas Januari - Desember ' . $year . '.xlsx';
        }

        $writer = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function buildFlowGasSheet($sheet, array $filters, int $year, int $month): void
    {
        $monthName = $this->monthOptions()[$month] ?? 'Bulan';

        $sheet->setTitle($monthName);

        $recordsQuery = FlowGasDailyRecord::query()
            ->with('category')
            ->where('type', 'gas')
            ->whereYear('record_date', $year)
            ->whereMonth('record_date', $month);

        $monthFilters = $filters;
        $monthFilters['year'] = $year;
        $monthFilters['month'] = $month;

        $this->applyFilters($recordsQuery, $monthFilters, true);

        $records = $recordsQuery
            ->orderBy('record_date')
            ->orderBy('flow_gas_category_id')
            ->get();

        $flowcompARecords = $records
            ->filter(fn ($record) => $this->isFlowcompA($record))
            ->keyBy(fn ($record) => optional($record->record_date)->format('Y-m-d'));

        $flowcompBRecords = $records
            ->filter(fn ($record) => $this->isFlowcompB($record))
            ->keyBy(fn ($record) => optional($record->record_date)->format('Y-m-d'));

        $daysInMonth = Carbon::create($year, $month, 1)->daysInMonth;

        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'DATA FLOW GAS ' . strtoupper($monthName) . ' ' . $year);

        $sheet->mergeCells('A2:D2');
        $sheet->setCellValue('A2', 'FLOWCOMP A');

        $sheet->mergeCells('E2:H2');
        $sheet->setCellValue('E2', 'FLOWCOMP B');

        $sheet->mergeCells('J2:J3');
        $sheet->setCellValue('J2', 'FIX');

        $sheet->setCellValue('A3', 'NO');
        $sheet->setCellValue('B3', 'TANGGAL');
        $sheet->setCellValue('C3', 'MSCF');
        $sheet->setCellValue('D3', 'MMBTU');

        $sheet->setCellValue('E3', 'NO');
        $sheet->setCellValue('F3', 'TANGGAL');
        $sheet->setCellValue('G3', 'MSCF');
        $sheet->setCellValue('H3', 'MMBTU');

        $startRow = 4;

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::create($year, $month, $day);
            $key = $date->format('Y-m-d');
            $row = $startRow + ($day - 1);

            $flowA = $flowcompARecords->get($key);
            $flowB = $flowcompBRecords->get($key);

            $sheet->setCellValue('A' . $row, $day);
            $sheet->setCellValueExplicit('B' . $row, $date->translatedFormat('d F Y'), DataType::TYPE_STRING);

            $sheet->setCellValue('E' . $row, $day);
            $sheet->setCellValueExplicit('F' . $row, $date->translatedFormat('d F Y'), DataType::TYPE_STRING);

            if ($flowA) {
                $sheet->setCellValue('C' . $row, (float) ($flowA->mscf ?? 0));
                $sheet->setCellValue('D' . $row, (float) ($flowA->mmbtu ?? 0));
            }

            if ($flowB) {
                $sheet->setCellValue('G' . $row, (float) ($flowB->mscf ?? 0));
                $sheet->setCellValue('H' . $row, (float) ($flowB->mmbtu ?? 0));
            }

            $fixTotal = (float) ($flowA->fix ?? 0) + (float) ($flowB->fix ?? 0);

            if ($fixTotal > 0) {
                $sheet->setCellValue('J' . $row, $fixTotal);
            }
        }

        $totalRow = $startRow + $daysInMonth;
        $averageRow = $totalRow + 1;

        $sheet->setCellValue('B' . $totalRow, 'TOTAL');
        $sheet->setCellValue('C' . $totalRow, '=SUM(C' . $startRow . ':C' . ($totalRow - 1) . ')');
        $sheet->setCellValue('D' . $totalRow, '=SUM(D' . $startRow . ':D' . ($totalRow - 1) . ')');

        $sheet->setCellValue('F' . $totalRow, 'TOTAL');
        $sheet->setCellValue('G' . $totalRow, '=SUM(G' . $startRow . ':G' . ($totalRow - 1) . ')');
        $sheet->setCellValue('H' . $totalRow, '=SUM(H' . $startRow . ':H' . ($totalRow - 1) . ')');

        $sheet->setCellValue('J' . $totalRow, '=SUM(J' . $startRow . ':J' . ($totalRow - 1) . ')');

        $sheet->setCellValue('B' . $averageRow, 'RATA-RATA');
        $sheet->setCellValue('C' . $averageRow, '=IF(COUNT(C' . $startRow . ':C' . ($totalRow - 1) . ')=0,0,AVERAGE(C' . $startRow . ':C' . ($totalRow - 1) . '))');
        $sheet->setCellValue('D' . $averageRow, '=IF(COUNT(D' . $startRow . ':D' . ($totalRow - 1) . ')=0,0,AVERAGE(D' . $startRow . ':D' . ($totalRow - 1) . '))');

        $sheet->setCellValue('F' . $averageRow, 'RATA-RATA');
        $sheet->setCellValue('G' . $averageRow, '=IF(COUNT(G' . $startRow . ':G' . ($totalRow - 1) . ')=0,0,AVERAGE(G' . $startRow . ':G' . ($totalRow - 1) . '))');
        $sheet->setCellValue('H' . $averageRow, '=IF(COUNT(H' . $startRow . ':H' . ($totalRow - 1) . ')=0,0,AVERAGE(H' . $startRow . ':H' . ($totalRow - 1) . '))');

        $sheet->setCellValue('I' . $averageRow, 'TOTAL FIX');
        $sheet->setCellValue('J' . $averageRow, '=J' . $totalRow);

        $this->styleFlowGasSheet($sheet, $startRow, $totalRow, $averageRow);
    }

    private function styleFlowGasSheet($sheet, int $startRow, int $totalRow, int $averageRow): void
    {
        $darkGreen = '173F08';
        $mediumGreen = '2F6B1F';
        $softGreen = 'EAF4E5';
        $verySoftGreen = 'F8FBF7';
        $white = 'FFFFFF';
        $black = '111827';
        $borderColor = '9CA3AF';
        $yellow = 'FFF2CC';
        $blueSoft = 'EAF2FF';
        $graySoft = 'F3F4F6';

        $sheet->getStyle('A1:J' . $averageRow)->applyFromArray([
            'font' => [
                'name' => 'Calibri',
                'size' => 10,
                'color' => ['rgb' => $black],
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
        ]);

        $sheet->getStyle('A1:J1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => ['rgb' => $white],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $darkGreen],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A2:D2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => $white],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $mediumGreen],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('E2:H2')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => $white],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $mediumGreen],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('J2:J3')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => $white],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $darkGreen],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A3:H3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => $white],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $darkGreen],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        $sheet->getStyle('A1:J' . $averageRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => $borderColor],
                ],
            ],
        ]);

        $sheet->getStyle('A' . $startRow . ':J' . ($totalRow - 1))->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $verySoftGreen],
            ],
        ]);

        for ($row = $startRow; $row <= $totalRow - 1; $row++) {
            if ($row % 2 === 0) {
                $sheet->getStyle('A' . $row . ':J' . $row)->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setRGB($white);
            }
        }

        $sheet->getStyle('B' . $totalRow . ':J' . $totalRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $yellow],
            ],
        ]);

        $sheet->getStyle('B' . $averageRow . ':J' . $averageRow)->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['rgb' => $softGreen],
            ],
        ]);

        $sheet->getStyle('A' . $startRow . ':A' . ($totalRow - 1))->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($graySoft);

        $sheet->getStyle('E' . $startRow . ':E' . ($totalRow - 1))->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($graySoft);

        $sheet->getStyle('J' . $startRow . ':J' . ($totalRow - 1))->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB($blueSoft);

        $sheet->getStyle('A1:J' . $averageRow)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $sheet->getStyle('A' . $startRow . ':A' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('E' . $startRow . ':E' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('B' . $startRow . ':B' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('F' . $startRow . ':F' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->getStyle('C' . $startRow . ':D' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('G' . $startRow . ':H' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
        $sheet->getStyle('J' . $startRow . ':J' . $averageRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getStyle('C' . $startRow . ':D' . $averageRow)->getNumberFormat()->setFormatCode('#,##0.0000');
        $sheet->getStyle('G' . $startRow . ':H' . $averageRow)->getNumberFormat()->setFormatCode('#,##0.0000');
        $sheet->getStyle('J' . $startRow . ':J' . $averageRow)->getNumberFormat()->setFormatCode('#,##0.0000');

        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(16);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(7);
        $sheet->getColumnDimension('F')->setWidth(18);
        $sheet->getColumnDimension('G')->setWidth(16);
        $sheet->getColumnDimension('H')->setWidth(16);
        $sheet->getColumnDimension('I')->setWidth(13);
        $sheet->getColumnDimension('J')->setWidth(16);

        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(24);
        $sheet->getRowDimension(3)->setRowHeight(24);

        for ($row = $startRow; $row <= $averageRow; $row++) {
            $sheet->getRowDimension($row)->setRowHeight(22);
        }

        $sheet->freezePane('A4');
        $sheet->setAutoFilter('A3:J' . $totalRow);
    }

    private function applyFilters($query, array $filters, bool $withSearch): void
    {
        if (! empty($filters['category_id'])) {
            $query->where('flow_gas_category_id', $filters['category_id']);
        }

        if (! empty($filters['date'])) {
            $query->whereDate('record_date', $filters['date']);
        }

        if (! empty($filters['month'])) {
            $query->whereMonth('record_date', (int) $filters['month']);
        }

        if (! empty($filters['year'])) {
            $query->whereYear('record_date', (int) $filters['year']);
        }

        if ($withSearch && ! empty($filters['search'])) {
            $search = trim((string) $filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('notes', 'like', '%' . $search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('code', 'like', '%' . $search . '%');
                    });
            });
        }
    }

    private function isFlowcompA(FlowGasDailyRecord $record): bool
    {
        $categoryName = strtolower((string) ($record->category->name ?? ''));
        $categoryCode = strtolower((string) ($record->category->code ?? ''));

        return str_contains($categoryName, 'flowcomp a')
            || str_contains($categoryName, 'flow comp a')
            || str_contains($categoryCode, 'flowcomp_a')
            || str_contains($categoryCode, 'flow_comp_a')
            || $categoryCode === 'a';
    }

    private function isFlowcompB(FlowGasDailyRecord $record): bool
    {
        $categoryName = strtolower((string) ($record->category->name ?? ''));
        $categoryCode = strtolower((string) ($record->category->code ?? ''));

        return str_contains($categoryName, 'flowcomp b')
            || str_contains($categoryName, 'flow comp b')
            || str_contains($categoryCode, 'flowcomp_b')
            || str_contains($categoryCode, 'flow_comp_b')
            || $categoryCode === 'b';
    }

    private function getLatestFlowGasYear(): int
    {
        $latestYear = FlowGasDailyRecord::query()
            ->where('type', 'gas')
            ->selectRaw('YEAR(record_date) as year')
            ->orderByDesc('year')
            ->value('year');

        return $latestYear ? (int) $latestYear : (int) now()->year;
    }

    private function monthOptions(): array
    {
        return [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
    }
}