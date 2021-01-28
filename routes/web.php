<?php

use App\Http\Controllers\HandlingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['auth']], function() {
	Route::get('/', function () {
        return redirect()->route('dashboard.index');
    });
	Route::get('/home', function () {
        return redirect()->route('dashboard.index');
    });
    Route::resource('dashboard', HomeController::class);

	Route::prefix('table')->group(function () {
		Route::get('/menu', [MenuController::class, 'dataTable'])->name('menu.table');
		Route::get('/permission', [PermissionController::class, 'dataTable'])->name('permission.table');
		Route::get('/role', [RoleController::class, 'dataTable'])->name('role.table');
		Route::get('/user', [UserController::class, 'dataTable'])->name('user.table');
    });

    Route::get('/menu/parent-url/{id}', [MenuController::class, 'parentUrl'])->name('menu.parentUrl');
    Route::get('/menu/parent-menu/{id}', [MenuController::class, 'parentsMenu'])->name('menu.parentsMenu');
    Route::resource('menu', MenuController::class)->only(['index', 'store', 'update', 'destroy']);

	Route::prefix('user-management')->group(function () {
	    Route::resource('permission', PermissionController::class)->only(['index', 'store', 'update', 'destroy']);
		//===========================================================================================================================
        Route::get('/role/permissions-role', [RoleController::class, 'permissionsRole'])->name('role.permissionsRole');
		Route::resource('role', RoleController::class)->only(['index', 'store', 'update', 'destroy']);
		//===========================================================================================================================
        Route::get('/user/roles-user', [UserController::class, 'rolesUser'])->name('user.rolesUser');
		Route::post('/user/email/{email}', [UserController::class, 'checkEmail'])->name('user.checkEmail');
		Route::put('user/update-password/{id}', [UserController::class, 'updatePassword'])->name('user.updatePassword');
		Route::resource('user', UserController::class)->only(['index', 'store', 'update', 'destroy']);
	});

	Route::prefix('error')->group(function () {
	    Route::get('/403', [HandlingController::class, 'error403'])->name('error.403');
		Route::get('/404', [HandlingController::class, 'error404'])->name('error.404');
		Route::get('/500', [HandlingController::class, 'error500'])->name('error.500');
		Route::get('/503', [HandlingController::class, 'error503'])->name('error.503');
	});
});
