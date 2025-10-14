<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DatabaseSwitchController extends Controller
{
    // public function switchDatabase(Request $request)
    // {
    //     $database = $request->input('db');
    //     if (empty($database)) {
    //         return redirect()->back()->with('danger', 'No database selected!');
    //     }

    //     // $config = Config::set('database.connections.mysql.database', $database);
    //     $config = Config::set('database.connections.mysql.database', session('selected_db', 'main_db'));

    //     dd($config);

    //     if (is_null($config)) {
    //         return redirect()->back()->with('danger', 'No database found.!!');
    //         // return view('welcome')->with('danger','Database not change.!!');
    //     }
    //     DB::purge('mysql');
    //     DB::reconnect('mysql');
    //     DB::select('SHOW TABLES');
    //     return redirect()->back()->with('success', 'Database changed.!!');
    //     // return view('welcome')->with('success','Database changed.!!');
    // }

    public function switchDatabase(Request $request)
    {
        $database = $request->input('db'); // example: "hindi_iffi_goa"

        if (empty($database)) {
            return redirect()->back()->with('danger', 'No database selected!');
        }

        // 🔹 Dynamically set the DB name
        Config::set('database.connections.mysql.database', $database);

        // 🔹 Reconnect the DB to apply the new settings
        DB::purge('mysql');
        DB::reconnect('mysql');

        // 🔹 (Optional) Save to session so it persists
        session(['selected_db' => $database]);

        // 🔹 Test the connection
        try {
            $tables = DB::select('SHOW TABLES');
            return redirect()
                ->back()
                ->with('success', "Database switched to {$database}");
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('danger', 'Failed to connect: ' . $e->getMessage());
        }
    }
}
