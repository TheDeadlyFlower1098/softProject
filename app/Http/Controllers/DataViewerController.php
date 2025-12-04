<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DataViewerController extends Controller
{
    public function index()
    {
        // List every table you want displayed
        $tables = [
            'users',
            'roles',
            'patients',
            'employees',
            'groups',
            'rosters',
            'appointments',
            'prescriptions',
            'medicine_checks',
            'payments',
            'registration_requests',
            // Add more as needed
        ];

        $data = [];

        foreach ($tables as $table) {
            // Pull ALL rows from each table
            $data[$table] = DB::table($table)->get();
        }

        return view('dataviewer.index', compact('data'));
    }
}
