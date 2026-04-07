<?php

namespace App\Http\Controllers;

use App\Exports\MoviesExport;
use Maatwebsite\Excel\Facades\Excel;

class MoviesExportController extends Controller
{
    public function export()
    {
        return Excel::download(new MoviesExport, 'movies.xlsx');
    }
}
