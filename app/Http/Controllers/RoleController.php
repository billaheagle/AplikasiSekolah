<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Database\QueryException;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class RoleController extends Controller
{
    public function index()
    {
        if(! auth()->user()
            ->can('role-access'))
            return redirect()->route('error.403');

        return view('admin.roles.index');
    }

    public function store(Request $request)
    {
        if(! auth()->user()
            ->can('role-access', 'role-create'))
            return redirect()->route('error.403');

        try {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name
            ]);
            $role->givePermissionTo($request->permission);
        } catch(RoleAlreadyExists $exception) {
            return response()->json(array('success' => false, 'messages' => 'Role ' . $request->role . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeAddSuccessfully')));
    }

    public function update(Request $request, $id)
    {
        if(! auth()->user()
            ->can('role-access', 'role-update'))
            return redirect()->route('error.403');

        $role = Role::with('permissions')->findOrFail($id);
        $role->name = $request->name;
        $role->guard_name = $request->guard_name;

        try { // Masih error, bisa sama role name dan guard
            $role->save();
            $role->permissions()->detach();
            $role->givePermissionTo($request->permission);
        } catch(RoleAlreadyExists $exception) {
            return response()->json(array('success' => false, 'messages' => 'Role ' . $request->role . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeUpdateSuccessfully')));
    }

    public function destroy($id)
    {
        if(! auth()->user()
            ->can('role-access', 'role-delete'))
            return redirect()->route('error.403');

        try {
            $role = Role::with('permissions')->findOrFail($id)->delete();
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => trans('global.messages.deleteUnsuccessfull')));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.deleteSuccessfully')));
    }

    public function dataTable()
    {
        $role = Role::query()->with('permissions')->orderBy('id', 'asc');
        return DataTables::of($role)
            ->addColumn('action', function ($data) {
                return view('components.action', [
                    'data' => $data,
                    'url_destroy' => route('role.destroy', $data->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function permissionsRole() {
        return Permission::orderBy('id', 'asc')->get();
    }
}
