<?php

use SumeetGhimire\LaravelExcelCsv\LaravelExcelCSV;
use Illuminate\Http\Request;

class ImportTestController extends Controller
{
    public function import(Request $request)
    {
        // Assuming the file input name is 'file'
        $file = $request->file('file');
        $tableName = 'your_actual_table_name';
        try {
            LaravelExcelCSV::import($file, $tableName);
            return response()->json(['message' => 'Data imported successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
