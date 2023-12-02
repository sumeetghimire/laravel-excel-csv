<?php

use SumeetGhimire\LaravelExcelCsv\LaravelExcelCSV;
use App\Models\User;

class ExportTestController extends Controller
{
    public function export()
    {
        $tableName = 'your_actual_table_name';

        try {
            // Assuming you want to export all data from the model
            $data = User::all();
            $filename = 'exported_data';

            LaravelExcelCSV::export($data, $filename);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
