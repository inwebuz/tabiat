<?php

namespace App\Http\Controllers\Voyager;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class StatusController extends VoyagerBaseController
{
    public function activate(Request $request)
    {
        $this->authorize('browse_admin');
        $table = $request->input('table', '');
        $id = $request->input('id', 0);
        return $this->changeStatus($table, $id, 1);
    }

    public function deactivate(Request $request)
    {
        $this->authorize('browse_admin');
        $table = $request->input('table', '');
        $id = $request->input('id', 0);
        return $this->changeStatus($table, $id, 0);
    }

    private function changeStatus($table, $id, $status)
    {
        if ( !$table || !$id ) {
            abort(400);
        }

        try {
            DB::table($table)->where('id', $id)->update(['status' => $status]);
        } catch (Exception $e) {
            abort(400);
        }

        return [
            'status' => $status,
        ];
    }
}
