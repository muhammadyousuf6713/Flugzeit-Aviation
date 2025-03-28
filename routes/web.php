<?php

use App\Http\Controllers\AboutDetailController;
use App\Http\Controllers\AboutHeaderController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\AdmissionController;
use App\Http\Controllers\CampLifeController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', [HomeController::class, 'home']);
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('billing', function () {
        return view('billing');
    })->name('billing');

    Route::get('profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('rtl', function () {
        return view('rtl');
    })->name('rtl');

    Route::get('user-management', function () {
        return view('laravel-examples/user-management');
    })->name('user-management');

    Route::get('tables', function () {
        return view('tables');
    })->name('tables');

    Route::get('virtual-reality', function () {
        return view('virtual-reality');
    })->name('virtual-reality');

    Route::get('static-sign-in', function () {
        return view('static-sign-in');
    })->name('sign-in');

    Route::get('static-sign-up', function () {
        return view('static-sign-up');
    })->name('sign-up');

    Route::get('/logout', [SessionsController::class, 'destroy']);
    Route::get('/user-profile', [InfoUserController::class, 'create']);
    Route::post('/user-profile', [InfoUserController::class, 'store']);
    Route::get('/login', function () {
        return view('dashboard');
    })->name('sign-up');

    Route::group(['prefix' => 'academic-program', 'middleware' => ['auth']], function () {
        Route::get('list', [AcademicController::class, 'index'])->middleware('permission:Academic Programmes list')->name('academic-program.list');
        Route::get('create', [AcademicController::class, 'create'])->name('academic-program.create');
        Route::post('store', [AcademicController::class, 'store'])->name('academic-program.store');
        Route::get('edit/{id}', [AcademicController::class, 'edit'])->name('academic-program.edit');
        Route::put('update/{id}', [AcademicController::class, 'update'])->name('academic-program.update');
        Route::get('destroy/{id}', [AcademicController::class, 'destroy'])->name('academic-program.destroy');
    });
    Route::group(['prefix' => 'academic-program-category', 'middleware' => ['auth']], function () {
        Route::get('create', [AcademicController::class, 'cat_create'])->name('academic-program-category.create');
        Route::post('store', [AcademicController::class, 'cat_store'])->name('academic-program-category.store');
    });
    Route::group(['prefix' => 'admission', 'middleware' => ['auth']], function () {
        Route::get('list', [AdmissionController::class, 'index'])->middleware('permission:Admissions list')->name('admission.list');
        Route::get('create', [AdmissionController::class, 'create'])->name('admission.create');
        Route::post('store', [AdmissionController::class, 'store'])->name('admission.store');
        Route::get('edit/{id}', [AdmissionController::class, 'edit'])->name('admission.edit');
        Route::put('update/{id}', [AdmissionController::class, 'update'])->name('admission.update');
        Route::get('destroy/{id}', [AdmissionController::class, 'destroy'])->name('admission.destroy');
    });
    Route::group(['prefix' => 'admission-category', 'middleware' => ['auth']], function () {
        Route::get('create', [AdmissionController::class, 'cat_create'])->name('admission-category.create');
        Route::post('store', [AdmissionController::class, 'cat_store'])->name('admission-category.store');
    });

    Route::prefix('administration')->group(function () {
        // Main Index Route
        Route::get('/', [AdministrationController::class, 'index'])->name('administration.index');

        // Routes for creating each individual part
        Route::get('/create-header', [AdministrationController::class, 'createHeader'])->name('administration.create-header');
        Route::get('/create-detail', [AdministrationController::class, 'createDetail'])->name('administration.create-detail');
        Route::get('/create-contact', [AdministrationController::class, 'createContact'])->name('administration.create-contact');
        Route::get('/create-author', [AdministrationController::class, 'createAuthor'])->name('administration.create-author');

        // Store Routes for creating each part
        Route::post('/store-header', [AdministrationController::class, 'storeHeader'])->name('administration.store-header');
        Route::post('/store-detail', [AdministrationController::class, 'storeDetail'])->name('administration.store-detail');
        Route::post('/store-contact', [AdministrationController::class, 'storeContact'])->name('administration.store-contact');
        Route::post('/store-author', [AdministrationController::class, 'storeAuthor'])->name('administration.store-author');

        // Edit Route
        Route::get('/edit/{id}', [AdministrationController::class, 'edit'])->name('administration.edit');
        Route::get('/edit-author/{id}', [AdministrationController::class, 'edit_author'])->name('administration.edit-author');
        Route::get('/edit-detail/{id}', [AdministrationController::class, 'edit_detail'])->name('administration.edit-detail');
        Route::get('/edit-contact/{id}', [AdministrationController::class, 'edit_contact'])->name('administration.edit-contact');

        // Update Route
        Route::post('/update/{id}', [AdministrationController::class, 'update'])->name('administration.update');
        Route::post('/update-author/{id}', [AdministrationController::class, 'update_author'])->name('administration.update-author');
        Route::post('/update-detail/{id}', [AdministrationController::class, 'update_detail'])->name('administration.update-detail');
        Route::post('/update-contact/{id}', [AdministrationController::class, 'update_contact'])->name('administration.update-contact');

        // Delete Route
        Route::post('/delete/{id}', [AdministrationController::class, 'destroy'])->name('administration.delete');
        Route::post('/delete-author/{id}', [AdministrationController::class, 'destroy_author'])->name('administration.delete-author');
        Route::post('/delete-detail/{id}', [AdministrationController::class, 'destroy_detail'])->name('administration.delete-detail');
        Route::post('/delete-contact/{id}', [AdministrationController::class, 'destroy_contact'])->name('administration.delete-contact');
    });
    Route::group(['prefix' => 'campus-life', 'middleware' => ['auth']], function () {
        Route::get('list', [CampLifeController::class, 'index'])->middleware('permission:Campus Life list')->name('campus-life.list');
        Route::get('create', [CampLifeController::class, 'create'])->middleware('permission:Campus Life add')->name('campus-life.create');
        Route::post('store', [CampLifeController::class, 'store'])->middleware('permission:Campus Life add')->name('campus-life.store');
        Route::get('edit/{id}', [CampLifeController::class, 'edit'])->middleware('permission:Campus Life edit')->name('campus-life.edit');
        Route::put('update/{id}', [CampLifeController::class, 'update'])->middleware('permission:Campus Life edit')->name('campus-life.update');
        Route::get('destroy/{id}', [CampLifeController::class, 'destroy'])->middleware('permission:Campus Life delete')->name('campus-life.destroy');
    });
    Route::group(['prefix' => 'campus-life-detail', 'middleware' => ['auth']], function () {
        Route::get('list', [CampLifeController::class, 'index_detail'])->name('campus-life-detail.list');
        Route::get('create', [CampLifeController::class, 'create_detail'])->name('campus-life-detail.create');
        Route::post('store', [CampLifeController::class, 'store_detail'])->name('campus-life-detail.store');
        Route::get('edit/{id}', [CampLifeController::class, 'edit_detail'])->name('campus-life-detail.edit');
        Route::put('update/{id}', [CampLifeController::class, 'update_detail'])->name('campus-life-detail.update');
        Route::delete('destroy/{id}', [CampLifeController::class, 'destroy_detail'])->name('campus-life-detail.destroy');
    });

    Route::get('user-management', [UsersController::class, 'index']);
    Route::get('users/create', [UsersController::class, 'create']);
    Route::post('users/store', [UsersController::class, 'store']);
    Route::get('users/edit/{id}', [UsersController::class, 'edit']);
    Route::post('users/update/{id}', [UsersController::class, 'update']);

    //Roles
    Route::group(['prefix' => 'roles', 'middleware' => ['auth']], function () {
        Route::get('', [RoleController::class, 'index']);
        Route::get('/add', [RoleController::class, 'create']);
        Route::post('/store/', [RoleController::class, 'store']);
        Route::get('/edit/{id}', [RoleController::class, 'edit']);
        Route::post('/edit/{id}', [RoleController::class, 'update']);
        Route::post('/delete', [RoleController::class, 'destroy']);
    });

    // create permission
    Route::prefix('permission')->middleware(['permission:Super-Admin'])->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index');         // List permission
        Route::get('/create', [PermissionController::class, 'create'])->name('permission.create'); // Create form
        Route::get('/get-sub-modules', [PermissionController::class, 'getSubModules'])->name('get.sub.modules');
        Route::post('/', [PermissionController::class, 'store'])->name('permission.store');                   // Store permission
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permission.edit');     // Edit form
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permission.update');      // Update permission
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permission.destroy'); // Delete permission
    });

    //asign permission to role
    Route::get('roles/permission/{id?}', [PermissionController::class, 'permission_index']);
    Route::post('roles/permission/{role_id}', [PermissionController::class, 'assignPermissions']);

    // Route::group(['prefix' => 'user-management', 'middleware' => ['auth']], function () {
    //     Route::get('/', [UsersController::class, 'index'])->middleware('permission:User Management List');
    //     Route::get('create', [UsersController::class, 'create']);
    //     Route::post('store', [UsersController::class, 'store']);
    //     Route::get('edit/{id}', [UsersController::class, 'edit']);
    //     Route::post('update/{id}', [UsersController::class, 'update']);
    // });

    // Route::prefix('admin')->middleware('auth')->group(function () {
    //     Route::get('role/{roleId}/permissions', [AdminController::class, 'showRolePermissionsForm'])->name('role.permissions.form');
    //     Route::post('role/{roleId}/permissions', [AdminController::class, 'storeRolePermissions'])->name('role.permissions.store');
    // });

    // // For Permission
    // Route::resource('permissions', PermissionController::class)->middleware('auth');

    // // For Role
    // Route::resource('roles', RoleController::class);

    Route::prefix('about')->group(function () {
        Route::resource('header', AboutHeaderController::class);
        Route::resource('{id}/detail', AboutDetailController::class)->parameters([
            'detail' => 'detail',
        ]);

    });

    Route::resource('about_pages', AboutUsController::class);

});

Route::get('/checkrole/{roleId}', [AdminController::class, 'checkrole']);
// Route::get('/create-table', [AdminController::class, 'createtable']);

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [RegisterController::class, 'create']);
    Route::post('/register', [RegisterController::class, 'store']);
    Route::get('/login', [SessionsController::class, 'create']);
    Route::post('/session', [SessionsController::class, 'store']);
    Route::get('/login/forgot-password', [ResetController::class, 'create']);
    Route::post('/forgot-password', [ResetController::class, 'sendEmail']);
    Route::get('/reset-password/{token}', [ResetController::class, 'resetPass'])->name('password.reset');
    Route::post('/reset-password', [ChangePasswordController::class, 'changePassword'])->name('password.update');
});

Route::get('/login', function () {
    return view('session/login-session');
})->name('login');
