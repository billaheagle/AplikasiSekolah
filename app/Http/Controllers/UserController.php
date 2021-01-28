<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        if(! auth()->user()
            ->can('user-access'))
            return redirect()->route('error.403');

        return view('admin.users.index');
    }

    public function store(Request $request)
    {
        if(! auth()->user()
            ->can('user-access', 'user-create'))
            return redirect()->route('error.403');

        $user = new User();
        $user->fill($request->all());
        $user->password = bcrypt($request->password);
        $user->maker = auth()->user()->id;
        $user->modifier = auth()->user()->id;

        try {
            $user->save();
            $user->assignRole($request->role);
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => 'Email ' . $request->email . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeAddSuccessfully')));
    }

    public function update(Request $request, $id)
    {
        if(! auth()->user()
            ->can('user-access', 'user-update'))
            return redirect()->route('error.403');

        $user = User::with('roles')->findOrFail($id);
        $user->fill($request->all());
        $user->modifier = auth()->user()->id;

        try {
            $user->save();
            $user->roles()->detach();
            $user->assignRole($request->role);
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => 'Email ' . $request->email . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeUpdateSuccessfully')));
    }

    public function updatePassword(Request $request, $id)
    {
        if(! auth()->user()
            ->can('user-access', 'user-update'))
            return redirect()->route('error.403');

        $user = User::findOrFail($id);
        $user->password = bcrypt($request->password);
        $user->modifier = auth()->user()->id;

        try {
            $user->save();
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => 'Password tidak dapat dirubah'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeUpdateSuccessfully')));
    }

    public function destroy($id)
    {
        if(! auth()->user()
            ->can('user-access', 'user-delete'))
            return redirect()->route('error.403');

        try {
            $user = User::with('roles')->findOrFail($id)->delete();
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => trans('global.messages.deleteUnsuccessfull')));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.deleteSuccessfully')));
    }

    public function dataTable()
    {
        if(! auth()->user()
            ->hasRole(['administrator', 'headmaster', 'administrative-staff']))
            return redirect()->route('error.403');

        if (auth()->user()->hasRole('administrator')) {
            $users = User::with('roles')->orderBy('id', 'asc');
        } else if (auth()->user()->hasRole('headmaster')) {
            $users = User::with('roles')->orderBy('id', 'asc')->whereDoesntHave('roles', function ($role) {
                $role->where('name', 'administrator');
            });
        } else if (auth()->user()->hasRole('administrative-staff')) {
            $users = User::with('roles')->orderBy('id', 'asc')->whereDoesntHave('roles', function ($role) {
                $role->where('name', 'administrator')->orWhere('name', 'headmaster');
            });
        }

        return DataTables::of($users)
            ->addColumn('action', function ($data) {
                return view('components.action', [
                    'data' => $data,
                    'url_destroy' => route('user.destroy', $data->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function rolesUser() {
        return Role::orderBy('id', 'asc')->get();
    }

    public function checkEmail($email) {
        $user = User::where('email', $email)->first();

        if(! $user) return response()->json(array('success' => true));
    }
}
