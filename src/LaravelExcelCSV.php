<?php

namespace SumeetGhimire\LaravelExcelCSV;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LaravelExcelCSV
{
    // ... (previous code)

    /**
     * Import data from CSV file to the database.
     *
     * @param  string  $fileName
     * @param  string  $tableName
     * @return void
     */

     public static function import($file, $tableName)
     {
         $uploadedFile = self::storeCsvFile($file);
         if ($uploadedFile) {
             $spreadsheet = IOFactory::load($uploadedFile);
             $worksheet = $spreadsheet->getActiveSheet();
     
             $header = [];
             $data = [];
     
             foreach ($worksheet->getRowIterator() as $row) {
                 $cellIterator = $row->getCellIterator();
     
                 if (empty($header)) {
                     foreach ($cellIterator as $cell) {
                         $header[] = $cell->getValue();
                     }
                 } else {
                     $rowData = [];
                     foreach ($cellIterator as $cell) {
                         $rowData[] = $cell->getValue();
                     }
                     $data[] = array_combine($header, $rowData);
                 }
             }
     
             DB::beginTransaction();
     
             try {
                 foreach ($data as $rowData) {
                     DB::table($tableName)->insert($rowData);
                 }
     
                 DB::commit();
             } catch (\Exception $e) {
                 DB::rollBack();
                 \Log::error("Error inserting data into the database: " . $e->getMessage());
     
             }
     
             unlink(public_path('csv/imported_file.csv'));
     
             return 1;
         }
     
     }
     


     public static function export($data, $filename)
     {
         $data = $data->toArray();
 
         $spreadsheet = new Spreadsheet();
 
         $worksheet = $spreadsheet->getActiveSheet();
 
         $headerRow = array_keys($data[0]);
         $worksheet->fromArray([$headerRow], null, 'A1');
 
         $dataRows = array_map(function ($item) {
             return array_values($item);
         }, $data);
         $worksheet->fromArray($dataRows, null, 'A2');
 
         $directory = public_path('excel');
 
         if (!file_exists($directory)) {
             mkdir($directory, 0777, true);
         }
 
         $filePath = "{$directory}/{$filename}.xlsx";
         $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
         $writer->save($filePath);
         return response()->download($filePath)->deleteFileAfterSend(true);
     }
 
 
 

    /**
     * Validate CSV headers against table columns.
     *
     * @param  array   $headers
     * @param  string  $tableName
     * @return bool
     */
    public static function storeCsvFile($file)
    {
        $publicPath = public_path('csv');
        $fileName = 'imported_file.csv';

        File::makeDirectory($publicPath, 0777, true, true);

        $storedPath = $file->move($publicPath, $fileName);

        return $storedPath;
    }
    protected static function validateHeaders($headers, $tableName)
    {
        $tableColumns = Schema::getColumnListing($tableName);
        $commonHeaders = array_intersect($headers, $tableColumns);
        
        return count(array_intersect($headers, $tableColumns)) > 0;
    }
}
