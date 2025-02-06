<?php

use App\Http\Controllers\AjaxController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InfoUserController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\InquirytypesController;
use App\Http\Controllers\PermissionAssignmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ResetController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SalesReferenceController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SessionsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
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



    Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('customers/create', [CustomerController::class, 'create']);
    Route::post('customers/store', [CustomerController::class, 'store']);
    Route::get('get-data', [AjaxController::class, 'getData']);
    Route::get('get_customer_details', [AjaxController::class, 'get_customer_details']);
    Route::get('check_customer_number/{cell}', [AjaxController::class, 'check_customer_number']);
    Route::get('customer_list/{query?}', [AjaxController::class, 'customer_search']);
    Route::get('customers/destroy/{id}', [CustomerController::class, 'destroy']);
    // Route::get('customers/{id}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::get('customers/edit/{id}', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('customers/{id}', [CustomerController::class, 'update'])->name('customers.update');
    // Route::get('customers/update/{id}', [CustomerController::class, 'update']);
    Route::get('customers/view/{id}', [CustomerController::class, 'view']);
    Route::get('get_customer_data', 'AjaxController@getCustomerData')->name('get_customer_data');


    // Inquiry Types
    Route::get('/inquiry', [InquiryController::class, 'get_inquiry_list'])->middleware(['permission:Inquiry list']);
    Route::get('/get_followup_details/{id}', [AjaxController::class, 'get_followup_details']);
    // Route::get('/inquiry_edit/{inquiry_id}', [InquiryController::class, 'edit_inquiry_index']);
    Route::get('/inquiry_ajax_list', [InquiryController::class, 'getdata']);
    Route::get('/inquiry/create', [InquiryController::class, 'create'])->middleware(['permission:Inquiry add']);
    Route::get('/inquiry_test/create', [InquiryController::class, 'create_test']);
    Route::post('/inquiry/store', [InquiryController::class, 'store']);
    Route::post('/add_inquiry_remarks', [InquiryController::class, 'add_inquiry_remarks']);
    Route::post('/add_followup_remarks', [InquiryController::class, 'add_followup_remarks']);
    Route::post('/inquiry_edit_update', [InquiryController::class, 'inquiry_edit_update']);
    Route::get('/append_services_edit/{inq_id}', [InquiryController::class, 'append_services_edit']);
    Route::get('/edit_inquiry/{id}', [InquiryController::class, 'edit'])->name('edit_inquiry')->middleware(['permission:Inquiry edit']);
    Route::post('/update_inquiry/{id}', [InquiryController::class, 'update'])->name('update_inquiry');
    Route::get('/delete_inquiry/{id}', [InquiryController::class, 'destroy'])->name('delete_inquiry');
    Route::get('/get_sub_services/{id}', [AjaxController::class, 'get_sub_services'])->name('get_sub_services');
    Route::get('/get_sub_services_id/{id}/{inq_id}', [AjaxController::class, 'get_sub_services_id']);
    Route::get('/add_more_services/{count}', [AjaxController::class, 'add_more_services'])->name('add_more_services');
    Route::get('/add_more_services_users/{count}', [AjaxController::class, 'add_more_services_users'])->name('add_more_services_users');
    Route::get('/get_campaign_data/{id}', [AjaxController::class, 'get_campaign_data'])->name('get_campaign_data');
    Route::get('/follow_up/{id}', [InquiryController::class, 'follow_up']);

    Route::get('/create_quotation/{id}', [QuotationController::class, 'create_quotation']);
    Route::get('autocomplete_country', [AjaxController::class, 'autocomplete'])->name('autocomplete_country');
    Route::get('autocomplete_city', [AjaxController::class, 'autocomplete_city'])->name('autocomplete_city');

    // Route::get('user-management', function () {
    //     return view('laravel-examples/user-management');
    // })->name('user-management');
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
        Route::get('/', [PermissionController::class, 'index'])->name('permission.index'); // List permission
        Route::get('/create', [PermissionController::class, 'create'])->name('permission.create'); // Create form
        Route::get('/get-sub-modules', [PermissionController::class, 'getSubModules'])->name('get.sub.modules');
        Route::post('/', [PermissionController::class, 'store'])->name('permission.store'); // Store permission
        Route::get('/{permission}/edit', [PermissionController::class, 'edit'])->name('permission.edit'); // Edit form
        Route::put('/{permission}', [PermissionController::class, 'update'])->name('permission.update'); // Update permission
        Route::delete('/{permission}', [PermissionController::class, 'destroy'])->name('permission.destroy'); // Delete permission
    });



    // Aslam Work


    // Inquiry Type
    Route::get('/inquiry-type', [InquirytypesController::class, 'index']);
    Route::get('inquiry-type/create', [InquirytypesController::class, 'create']);
    Route::post('inquiry-type/store', [InquirytypesController::class, 'store']);
    Route::get('inquiry-type/edit/{id}', [InquirytypesController::class, 'edit']);
    Route::post('inquiry-type/update/{id}', [InquirytypesController::class, 'update']);
    // Route::post('inquiry-type/delete/{id}', [InquirytypesController::class, 'destroy']);
    Route::delete('/inquiry-type/delete/{id}', [InquirytypesController::class, 'destroy'])->name('inquiry-type.destroy');


    // SalesReference
    Route::get('/sales-reference', [SalesReferenceController::class, 'index']);
    Route::get('sales-reference/create', [SalesReferenceController::class, 'create']);
    Route::post('sales-reference/store', [SalesReferenceController::class, 'store']);
    Route::get('sales-reference/edit/{id}', [SalesReferenceController::class, 'edit']);
    Route::post('sales-reference/update/{id}', [SalesReferenceController::class, 'update']);
    // Route::post('sales-reference/delete/{id}', [SalesReferenceController::class, 'destroy']);
    Route::delete('/sales-reference/delete/{id}', [SalesReferenceController::class, 'destroy'])->name('sales-reference.destroy');

    // Services
    Route::get('/services', [ServiceController::class, 'index']);
    Route::get('services/create', [ServiceController::class, 'create']);
    Route::post('services/store', [ServiceController::class, 'store']);
    Route::get('services/edit/{id}', [ServiceController::class, 'edit']);
    Route::post('sercices/update/{id}', [ServiceController::class, 'update']);
    Route::delete('services/delete/{id}', [ServiceController::class, 'destroy'])->name('services.destroy');


    //asign permission to role
    Route::get('roles/permission/{id?}', [PermissionController::class, 'permission_index']);
    Route::post('roles/permission/{role_id}', [PermissionController::class, 'assignPermissions']);



















    // Route::get('/create-table', [App\Http\Controllers\Admin\AdminController::class, 'createtable']);
});



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
