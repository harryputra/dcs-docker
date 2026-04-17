<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DatabaseManagerController extends Controller
{
    /**
     * Check if user is authorized for DB access
     */
    private function checkAccess()
    {
        $user = Auth::user();
        if (!$user || !$user->isRole('Administrator') || !$user->is_dev_mode) {
            abort(403, 'Unauthorized. Dev Mode is not active.');
        }
    }

    /**
     * Get all tables
     */
    private function getAllTables()
    {
        $tables = [];
        $dbTables = DB::select('SHOW TABLES');
        $dbName = env('DB_DATABASE', 'db_dcs');
        $property = "Tables_in_{$dbName}";

        foreach ($dbTables as $table) {
            if (isset($table->$property)) {
                $tables[] = $table->$property;
            } else {
                // Fallback for some environments
                $tables[] = array_values((array)$table)[0];
            }
        }
        return $tables;
    }

    public function index()
    {
        $this->checkAccess();
        $tables = $this->getAllTables();
        return view('admin.database.index', compact('tables'));
    }

    public function show($table)
    {
        $this->checkAccess();
        
        if (!Schema::hasTable($table)) {
            abort(404, "Table not found");
        }

        $columns = Schema::getColumnListing($table);
        $records = DB::table($table)->paginate(15);
        
        // Find primary key (best effort)
        $primaryKey = in_array('id', $columns) ? 'id' : null;

        return view('admin.database.show', compact('table', 'columns', 'records', 'primaryKey'));
    }

    public function create($table)
    {
        $this->checkAccess();
        
        if (!Schema::hasTable($table)) {
            abort(404, "Table not found");
        }

        $columns = Schema::getColumnListing($table);
        
        // Try to get column types using DB inspection if DBAL isn't available
        $columnMetadata = [];
        foreach($columns as $col) {
            $type = Schema::getColumnType($table, $col);
            $columnMetadata[$col] = $type;
        }

        return view('admin.database.form', compact('table', 'columns', 'columnMetadata'));
    }

    public function store(Request $request, $table)
    {
        $this->checkAccess();
        
        if (!Schema::hasTable($table)) {
            abort(404, "Table not found");
        }

        $data = $request->except(['_token']);
        
        try {
            DB::table($table)->insert($data);
            return redirect()->route('db.show', $table)->with('success', 'Record created successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating record: ' . $e->getMessage());
        }
    }

    public function edit($table, $id)
    {
        $this->checkAccess();
        
        if (!Schema::hasTable($table)) {
            abort(404, "Table not found");
        }

        $columns = Schema::getColumnListing($table);
        $primaryKey = in_array('id', $columns) ? 'id' : $columns[0]; // Guess PK
        
        $record = DB::table($table)->where($primaryKey, $id)->first();
        
        if (!$record) {
            abort(404, "Record not found");
        }

        $columnMetadata = [];
        foreach($columns as $col) {
            $type = Schema::getColumnType($table, $col);
            $columnMetadata[$col] = $type;
        }

        return view('admin.database.form', compact('table', 'columns', 'columnMetadata', 'record', 'primaryKey', 'id'));
    }

    public function update(Request $request, $table, $id)
    {
        $this->checkAccess();
        
        if (!Schema::hasTable($table)) {
            abort(404, "Table not found");
        }

        $columns = Schema::getColumnListing($table);
        $primaryKey = in_array('id', $columns) ? 'id' : $columns[0]; // Guess PK
        
        $data = $request->except(['_token', '_method']);
        
        try {
            DB::table($table)->where($primaryKey, $id)->update($data);
            return redirect()->route('db.show', $table)->with('success', 'Record updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error updating record: ' . $e->getMessage());
        }
    }

    public function destroy($table, $id)
    {
        $this->checkAccess();
        
        if (!Schema::hasTable($table)) {
            abort(404, "Table not found");
        }

        $columns = Schema::getColumnListing($table);
        $primaryKey = in_array('id', $columns) ? 'id' : $columns[0]; // Guess PK
        
        try {
            DB::table($table)->where($primaryKey, $id)->delete();
            return redirect()->route('db.show', $table)->with('success', 'Record deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting record: ' . $e->getMessage());
        }
    }
}
