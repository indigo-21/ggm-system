<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class Controller
{
    /**
     * Generic duplicate check for masterfile tables.
     * Accepts 'name' and 'table' parameters, returns JSON { exists: bool }.
     */
    public static function checkMasterfileDuplicate(Request $request)
    {
        $name = trim($request->input('name', ''));
        $table = $request->input('table', '');

        // Whitelist of allowed tables for security
        $allowedTables = [
            'materials',
            'colours',
            'based_ledgers',
            'letter_types',
            'accessories',
        ];

        if (!in_array($table, $allowedTables) || empty($name)) {
            return response()->json(['exists' => false]);
        }

        // Tables that use soft deletes
        $softDeleteTables = ['materials', 'based_ledgers', 'letter_types', 'accessories'];

        $query = DB::table($table)->whereRaw('LOWER(TRIM(name)) = ?', [strtolower($name)]);

        if (in_array($table, $softDeleteTables)) {
            $query->whereNull('deleted_at');
        }

        $exists = $query->exists();

        return response()->json(['exists' => $exists]);
    }
}
