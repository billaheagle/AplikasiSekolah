<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Exceptions\PermissionAlreadyExists;

class PermissionController extends Controller
{
    public function index()
    {
        if(! auth()->user()
            ->can('permission-access'))
            return redirect()->route('error.403');

        return view('admin.permissions.index');
    }

    public function store(Request $request)
    {
        if(! auth()->user()
            ->can('permission-access', 'permission-create'))
            return redirect()->route('error.403');

        try {
            $permission = Permission::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);
        } catch(PermissionAlreadyExists $exception) {
            return response()->json(array('success' => false, 'messages' => 'Permission ' . $request->permission . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeAddSuccessfully')));
    }

    public function update(Request $request, $id)
    {
        if(! auth()->user()
            ->can('permission-access', 'permission-update'))
            return redirect()->route('error.403');

        $permission = Permission::findOrFail($id);
        $permission->name = $request->name;
        $permission->guard_name = $request->guard_name;

        try { // Masih error, bisa sama permission name dan guard
            $permission->save();
        } catch(PermissionAlreadyExists $exception) {
            return response()->json(array('success' => false, 'messages' => 'Permission ' . $request->permission . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeUpdateSuccessfully')));
    }

    public function destroy($id)
    {
        if(! auth()->user()
            ->can('permission-access', 'permission-delete'))
            return redirect()->route('error.403');

        try {
            $permission = Permission::findOrFail($id)->delete();
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => trans('global.messages.deleteUnsuccessfull')));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.deleteSuccessfully')));
    }

    public function dataTable()
    {
        $permission = Permission::query()->orderBy('id', 'asc');
        return DataTables::of($permission)
            ->addColumn('action', function ($data) {
                return view('components.action', [
                    'data' => $data,
                    'url_destroy' => route('permission.destroy', $data->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }
}
