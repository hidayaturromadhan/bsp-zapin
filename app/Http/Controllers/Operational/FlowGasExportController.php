<?php

namespace App\Http\Controllers\Operational;

use App\Exports\FlowGasMonthlyExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class FlowGasExportController extends Controller
{
    public function monthly(Request $request)
    {
        $validated = $request->validate([
            'month' => ['required', 'integer', 'between:1,12'],
            'year' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $fileName = 'flow-gas-' . $validated['year'] . '-' . str_pad((string) $validated['month'], 2, '0', STR_PAD_LEFT) . '.xlsx';

        return Excel::download(
            new FlowGasMonthlyExport(
                (int) $validated['month'],
                (int) $validated['year']
            ),
            $fileName
        );
    }
}
