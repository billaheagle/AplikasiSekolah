<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Yajra\DataTables\Facades\DataTables;

class MenuController extends Controller
{
    public function index()
    {
        if(! auth()->user()
            ->can('menu-access'))
            return redirect()->route('error.403');

        return view('admin.menus.index');
    }

    public function store(Request $request, MenuService $menuService)
    {
        if(! auth()->user()
            ->can('menu-access', 'menu-create'))
            return redirect()->route('error.403');

        $model_name = str_slug($request->title);

        $menu = new Menu();
        $menu->fill($request->all());
        $menu->maker = auth()->user()->id;
        $menu->modifier = auth()->user()->id;
        $menu->model_name = $model_name;

        try {
            $menu->save();
            $menuService->integrateMenuPermission($request->parent_id, $request->single_menu, $model_name);
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => 'Menu ' . $request->menu . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeAddSuccessfully')));
    }

    public function update(Request $request, $id, MenuService $menuService)
    {
        if(! auth()->user()
            ->can('menu-access', 'menu-update'))
            return redirect()->route('error.403');

        $menu = Menu::where('id', $id)->first();
        $old_model_name = $menu->model_name;
        $model_name = str_slug($request->title);

        $menu->fill($request->all());
        $menu->modifier = auth()->user()->id;
        $menu->model_name = $model_name;

        try {
            $menu->save();
            $menuService->updateMenuPermission($menu, $old_model_name, $model_name);
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => 'Menu ' . $request->menu . ' sudah terpakai'));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.storeUpdateSuccessfully')));
    }

    public function destroy($id, MenuService $menuService)
    {
        if(! auth()->user()
            ->can('menu-access', 'menu-delete'))
            return redirect()->route('error.403');

        try {
            $menu = Menu::findOrFail($id);
            $menuService->revokeMenuPermission($menu);
            $menu->delete();
        } catch(QueryException $exception) {
            return response()->json(array('success' => false, 'messages' => trans('global.messages.deleteUnsuccessfull')));
        }

        return response()->json(array('success' => true, 'messages' => trans('global.messages.deleteSuccessfully')));
    }

    public function dataTable()
    {
        $menu = Menu::query()->with('parent')->orderBy('id', 'asc');
        return DataTables::of($menu)
            ->addColumn('action', function ($data) {
                return view('components.action', [
                    'data' => $data,
                    'url_destroy' => route('menu.destroy', $data->id)
                ]);
            })
            ->addIndexColumn()
            ->rawColumns(['action'])
            ->make(true);
    }

    public function parentUrl($id) {
        return Menu::where('id', $id)->first();
    }

    public function parentsMenu($id) {
        return Menu::where('parent_id', null)->orWhere('parent_id', '')
        ->where('model_name', '!=', 'dashboard')->where('id', '!=', $id)
        ->orderBy('id', 'asc')->get();
    }
}
