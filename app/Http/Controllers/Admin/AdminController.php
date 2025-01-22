<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\User;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {

        return view('admin.dashboard');
    }

    public function createtable()
    {
        // Schema::create('brands', function (Blueprint $table) {
        //     $table->bigIncrements('id_brands');
        //     $table->string('brands_name')->nullable();
        //     $table->string('brands_abbrevation')->nullable();
        //     $table->string('brands_logo')->nullable();
        //     $table->string('brands_image')->nullable();
        //     $table->string('brands_status')->default('0');
        //     $table->timestamps();
        // });

        // Schema::create('stores', function (Blueprint $table) { // Changed to plural for consistency
        //     $table->bigIncrements('id_store');
        //     $table->string('store_name')->nullable();
        //     $table->string('store_location')->nullable();
        //     $table->string('store_manager')->nullable();
        //     $table->string('store_city')->nullable();
        //     $table->string('employee_status')->default('0');
        //     $table->timestamps();
        // });

        // Schema::create('products_category_main', function (Blueprint $table) {
        //     $table->bigIncrements('id_pro_cate_main');
        //     $table->string('products_category_name')->nullable();
        //     $table->string('products_category_name_urdu')->nullable();
        //     $table->enum('products_category_status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();
        // });

        // Schema::create('products_sub_cat_parent', function (Blueprint $table) {
        //     $table->bigIncrements('id_pro_sub_cat_parent');
        //     $table->unsignedBigInteger('pro_cate_main_id')->nullable();
        //     $table->string('name')->nullable();
        //     $table->string('name_urdu')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('pro_cate_main_id')->references('id_pro_cate_main')->on('products_category_main')->onDelete('cascade');
        // });

        // Schema::create('products_sub_cat_child', function (Blueprint $table) {
        //     $table->bigIncrements('id_pr_sub_cat_child');
        //     $table->unsignedBigInteger('pro_sub_cat_parent_id')->nullable();
        //     $table->string('name')->nullable();
        //     $table->string('name_urdu')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('pro_sub_cat_parent_id')->references('id_pro_sub_cat_parent')->on('products_sub_cat_parent')->onDelete('cascade');
        // });

        // Schema::create('products', function (Blueprint $table) {
        //     $table->bigIncrements('id_products');
        //     $table->unsignedBigInteger('brands_id')->nullable();
        //     $table->string('products_name')->nullable();
        //     $table->string('products_sale_price')->nullable();
        //     $table->string('products_purchase_price')->nullable();
        //     $table->string('products_retail_price')->nullable();
        //     $table->unsignedBigInteger('category_id')->nullable();
        //     $table->text('products_desc')->nullable();
        //     $table->unsignedBigInteger('unit_type_id')->nullable();
        //     $table->string('products_qt_per_unit')->nullable();
        //     $table->unsignedBigInteger('measurement_unit_id')->nullable();
        //     $table->string('products_threshold')->nullable();
        //     $table->string('products_status')->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->index('products_name');

        //     // Foreign key constraints
        //     $table->foreign('brands_id')->references('id_brands')->on('brands')->onDelete('cascade');
        //     $table->foreign('category_id')->references('id_products_category')->on('products_category')->onDelete('set null');
        // });

        // Schema::create('stocks', function (Blueprint $table) { // Changed to plural for consistency
        //     $table->bigIncrements('id_stock');
        //     $table->unsignedBigInteger('products_id')->nullable();
        //     $table->unsignedBigInteger('store_id')->nullable();
        //     $table->string('stock_batch_number')->nullable();
        //     $table->string('stock_damage_qty')->nullable();
        //     $table->string('stock_damage_date')->nullable();
        //     $table->string('stock_purchase_date')->nullable();
        //     $table->string('stock_expiry_date')->nullable();
        //     $table->string('stock_batch_type')->nullable();
        //     $table->string('stock_manual_add_qty')->nullable();
        //     $table->string('stock_purchase_qty')->nullable();
        //     $table->string('stock_qty_to_transfer')->nullable();
        //     $table->string('stock_purchase_price')->nullable(); // Corrected spelling
        //     $table->string('stock_remarks')->nullable();
        //     $table->string('employee_status')->default('0');
        //     $table->timestamps();

        //     // Foreign key constraints
        //     $table->foreign('products_id')->references('id_products')->on('products')->onDelete('set null');
        //     $table->foreign('store_id')->references('id_store')->on('stores')->onDelete('set null');
        // });


        // Schema::create('products_images', function (Blueprint $table) { // Changed to plural for consistency
        //     $table->bigIncrements('id_products_images');
        //     $table->unsignedBigInteger('product_id')->nullable();
        //     $table->string('products_images_name')->nullable();
        //     $table->string('products_images_status')->default('1');
        //     $table->timestamps();

        //     // Foreign key constraints
        //     $table->foreign('product_id')->references('id_products')->on('products')->onDelete('cascade');
        // });

        // Schema::table('permissions', function (Blueprint $table) {
        //     $table->unsignedBigInteger('parent_id')->default(0)->after('guard_name');
        //     $table->foreign('parent_id')->references('id')->on('permissions')->onDelete('cascade');
        // });

        // $operation = Permission::create(['name' => 'operation', 'guard_name' => 'web', 'parent_id' => 0]);

        // // Create sub-module permissions
        // $permissions = [
        //     ['name' => 'customers', 'parent_id' => $operation->id],
        //     ['name' => 'purchase', 'parent_id' => $operation->id],
        //     ['name' => 'sale', 'parent_id' => $operation->id],
        //     ['name' => 'service purchase', 'parent_id' => $operation->id]
        // ];

        // foreach ($permissions as $permissionData) {
        //     Permission::create(array_merge($permissionData, ['guard_name' => 'web']));
        // }











        // Main modules and their sub-modules with actions
        // $modules = [
        //     'Dashboard' => [
        //         'Admin Dashboard' => ['list', 'view'],
        //         'Sales Dashboard' => ['list', 'view'],
        //     ],
        //     'Operations' => [
        //         'Customers' => ['list', 'add', 'edit', 'view', 'delete'],
        //         'Inquiry' => ['list', 'add', 'edit', 'view', 'delete'],
        //     ],
        //     'Administerator' => [
        //         'Users Management' => ['list', 'add', 'edit', 'view', 'delete'],
        //         'Roles Management' => ['list', 'add', 'edit', 'view', 'delete'],
        //         'Roles Permissions' => ['list', 'add', 'edit', 'view', 'delete'],
        //     ],
        //     'Preferences' => [
        //         'Inquiry Types' => ['list', 'add', 'edit', 'view', 'delete'],
        //         'Services' => ['list', 'add', 'edit', 'view', 'delete'],
        //         'Sales References' => ['list', 'add', 'edit', 'view', 'delete'],

        //     ],
        //     'Profile' => ['list', 'add', 'edit', 'view', 'delete']
        // ];
        // try {
        //     foreach ($modules as $moduleName => $subModules) {
        //         $mainModule = Permission::firstOrCreate([
        //             'name' => $moduleName,
        //             'guard_name' => 'web'
        //         ], [
        //             'parent_id' => 0
        //         ]);

        //         foreach ($subModules as $subModuleName => $actions) {
        //             if (!is_array($actions)) {
        //                 continue;
        //             }

        //             $subModule = Permission::firstOrCreate([
        //                 'name' => $subModuleName,
        //                 'guard_name' => 'web',
        //                 'parent_id' => $mainModule->id
        //             ]);

        //             foreach ($actions as $action) {
        //                 Permission::firstOrCreate([
        //                     'name' => $subModuleName . ' ' . $action,
        //                     'guard_name' => 'web',
        //                     'parent_id' => $subModule->id
        //                 ]);
        //             }
        //         }
        //     }

        //     $roleName = 'Super-Admin';
        //     $role = Role::firstOrCreate(['name' => $roleName]);
        //     $permissions = Permission::all();
        //     $role->givePermissionTo($permissions);

        //     Log::info("Permissions successfully assigned to the role {$roleName}");
        // } catch (\Exception $e) {
        //     Log::error('Error creating permissions: ' . $e->getMessage());
        // }










        // Schema::create('suppliers', function (Blueprint $table) {
        //     $table->bigIncrements('id_supplier');
        //     $table->unsignedBigInteger('brand_id')->nullable();
        //     $table->string('supplier_name')->nullable();
        //     $table->string('supplier_name_urdu')->nullable();
        //     $table->string('supplier_email')->nullable();
        //     $table->string('supplier_address')->nullable();
        //     $table->string('supplier_address_urdu')->nullable();
        //     $table->string('contact_person')->nullable();
        //     $table->string('phone_1')->nullable();
        //     $table->string('phone_2')->nullable();
        //     $table->string('website')->nullable();
        //     $table->enum('status', ['0', '1'])->default('0');
        //     $table->decimal('opening_bal_dr', 15, 2)->nullable();
        //     $table->decimal('opening_bal_cr', 15, 2)->nullable();
        //     $table->date('opening_date')->nullable();
        //     $table->timestamps();
        // });

        // Schema::create('suppliers_account', function (Blueprint $table) {
        //     $table->bigIncrements('id_suppliers_account');
        //     $table->unsignedBigInteger('suppliers_id')->nullable();
        //     $table->string('account_name')->nullable();
        //     $table->string('account_no')->nullable();
        //     $table->decimal('debit', 15, 2)->nullable();
        //     $table->decimal('credit', 15, 2)->nullable();
        //     $table->decimal('balance', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('suppliers_id')->references('id_supplier')->on('suppliers')->onDelete('cascade');
        // });

        // Schema::create('customers_account', function (Blueprint $table) {
        //     $table->bigIncrements('id_customers_account');
        //     $table->unsignedBigInteger('customers_id')->nullable();
        //     $table->string('account_name')->nullable();
        //     $table->string('account_no')->nullable();
        //     $table->decimal('debit', 15, 2)->nullable();
        //     $table->decimal('credit', 15, 2)->nullable();
        //     $table->decimal('balance', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('customers_id')->references('id_customers')->on('customers')->onDelete('cascade');
        // });

        // Schema::create('suppliers_sale_orders', function (Blueprint $table) {
        //     $table->bigIncrements('id_suppliers_sale_order');
        //     $table->unsignedBigInteger('suppliers_id')->nullable();
        //     $table->string('order_no')->nullable();
        //     $table->date('order_date')->nullable();
        //     $table->integer('quantity')->nullable();
        //     $table->decimal('amount', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('suppliers_id')->references('id_supplier')->on('suppliers')->onDelete('cascade');
        // });

        // Schema::create('suppliers_payment_history', function (Blueprint $table) {
        //     $table->bigIncrements('id_suppliers_payment_history');
        //     $table->unsignedBigInteger('suppliers_id')->nullable();
        //     $table->string('invoice_no')->nullable();
        //     $table->date('invoice_date')->nullable();
        //     $table->decimal('amount', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('suppliers_id')->references('id_supplier')->on('suppliers')->onDelete('cascade');
        // });


        // Schema::create('vouchers_unique_number', function (Blueprint $table) {
        //     $table->bigIncrements('id_vouchers_unique_number');
        //     $table->string('voucher_number')->unique()->nullable();
        //     $table->timestamps();
        // });

        // Schema::create('vouchers', function (Blueprint $table) {
        //     $table->bigIncrements('id_vouchers');
        //     $table->unsignedBigInteger('vouchers_unique_number_id')->nullable();
        //     $table->string('account_no')->nullable();
        //     $table->string('account_title')->nullable();
        //     $table->string('payment_mode')->nullable();
        //     $table->string('instrument')->nullable();
        //     $table->decimal('amount', 15, 2)->nullable();
        //     $table->string('remarks')->nullable();
        //     $table->string('debit')->nullable();
        //     $table->string('credit')->nullable();
        //     $table->string('business_partner_type')->nullable();
        //     $table->string('partner_name')->nullable();
        //     $table->string('tax_debit')->nullable();
        //     $table->string('transaction_acc_no')->nullable();
        //     $table->string('transaction_acc_name')->nullable();
        //     $table->text('desciption')->nullable();
        //     $table->date('date')->nullable();
        //     $table->enum('status', ['0', '1'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('vouchers_unique_number_id')->references('id_vouchers_unique_number')->on('vouchers_unique_number')->onDelete('cascade');
        //     $table->index('vouchers_unique_number_id');
        // });


        // Schema::create('customers', function (Blueprint $table) {
        //     $table->bigIncrements('id_customers');
        //     $table->unsignedBigInteger('business_id')->nullable();
        //     $table->unsignedBigInteger('campaign_id')->nullable();
        //     $table->string('customer_name')->nullable();
        //     $table->string('customer_name_urdu')->nullable();
        //     $table->string('customer_email')->nullable();
        //     $table->decimal('customer_ope_bal_dr', 15, 2)->nullable();
        //     $table->decimal('customer_ope_bal_cr', 15, 2)->nullable();
        //     $table->date('customer_ope_date')->nullable();
        //     $table->string('customer_cell')->nullable();
        //     $table->boolean('whatsapp_check')->default(false);
        //     $table->string('whatsapp_number')->nullable();
        //     $table->text('customer_address')->nullable();
        //     $table->text('customer_address_urdu')->nullable();
        //     $table->string('customer_phone1')->nullable();
        //     $table->string('customer_phone2')->nullable();
        //     $table->string('customer_type')->nullable();
        //     $table->string('customer_reference')->nullable();
        //     $table->text('customer_remarks')->nullable();
        //     $table->text('customer_remarks_urdu')->nullable();
        //     $table->unsignedBigInteger('city_id')->nullable();
        //     $table->string('country')->nullable();
        //     $table->string('customer_image')->nullable();
        //     $table->string('sale_person')->nullable();
        //     $table->string('accounts_customer_rating')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();
        //     $table->softDeletes();
        // });

        // Schema::create('customers_account', function (Blueprint $table) {
        //     $table->bigIncrements('id_customer_account');
        //     $table->unsignedBigInteger('customers_id')->nullable();
        //     $table->string('account_name')->nullable();
        //     $table->string('account_no')->nullable();
        //     $table->decimal('debit', 15, 2)->nullable();
        //     $table->decimal('credit', 15, 2)->nullable();
        //     $table->decimal('balance', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('customers_id')->references('id_customers')->on('customers')->onDelete('cascade');
        // });

        // Schema::create('customers_sale_orders', function (Blueprint $table) {
        //     $table->bigIncrements('id_customers_sale_order');
        //     $table->unsignedBigInteger('customers_id')->nullable();
        //     $table->string('order_no')->nullable();
        //     $table->date('order_date')->nullable();
        //     $table->integer('quantity')->nullable();
        //     $table->decimal('amount', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('customers_id')->references('id_customers')->on('customers')->onDelete('cascade');
        // });

        // Schema::create('customers_payment_history', function (Blueprint $table) {
        //     $table->bigIncrements('id_customers_payment_history');
        //     $table->unsignedBigInteger('customers_id')->nullable();
        //     $table->string('invoice_no')->nullable();
        //     $table->date('invoice_date')->nullable();
        //     $table->decimal('amount', 15, 2)->nullable();
        //     $table->enum('status', ['0', '1', '2'])->default('0');
        //     $table->timestamps();

        //     $table->foreign('customers_id')->references('id_customers')->on('customers')->onDelete('cascade');
        // });

        // // Good Receipts Truck Table
        // Schema::create('good_receipts_truck', function (Blueprint $table) {
        //     $table->bigIncrements('id_truck');
        //     $table->string('truck_no')->nullable();
        //     $table->timestamps();
        // });

        // // Good Receipts Table
        // Schema::create('good_receipts', function (Blueprint $table) {
        //     $table->bigIncrements('id_good_rec');
        //     $table->unsignedBigInteger('business_id')->nullable();
        //     $table->unsignedBigInteger('supplier_id')->nullable();
        //     $table->unsignedBigInteger('good_receipts_truck_id')->nullable();
        //     $table->date('date')->nullable();
        //     $table->string('p_no')->nullable();
        //     $table->string('builty_no')->nullable();
        //     $table->string('rent_recieve')->nullable();
        //     $table->text('details')->nullable();
        //     $table->string('goods_entities_json')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('supplier_id')->references('id_supplier')->on('suppliers')->onDelete('set null');
        //     $table->foreign('good_receipts_truck_id')->references('id_truck')->on('good_receipts_truck')->onDelete('cascade');
        // });

        // // Good Receipts Details Table
        // Schema::create('good_receipts_details', function (Blueprint $table) {
        //     $table->bigIncrements('id_grd');
        //     $table->unsignedBigInteger('good_receipts_id')->nullable();
        //     $table->unsignedBigInteger('truck_id')->nullable();
        //     $table->string('goods_name')->nullable();
        //     $table->string('marka')->nullable();
        //     $table->string('bori')->nullable();
        //     $table->string('weight')->nullable();
        //     $table->string('rate')->nullable();
        //     $table->text('notes')->nullable();
        //     $table->timestamps();

        //     $table->foreign('good_receipts_id')->references('id_good_rec')->on('good_receipts')->onDelete('cascade');
        //     $table->foreign('truck_id')->references('id_truck')->on('good_receipts_truck')->onDelete('cascade');
        // });

        // // Sales Table
        // Schema::create('sales', function (Blueprint $table) {
        //     $table->bigIncrements('id_sales');
        //     $table->unsignedBigInteger('bussiness_id')->nullable();
        //     $table->unsignedBigInteger('good_receipts_truck_id')->nullable();
        //     $table->unsignedBigInteger('customer_id')->nullable();
        //     $table->date('date')->nullable();
        //     $table->string('s_no')->nullable();
        //     $table->string('bill_no')->nullable();
        //     $table->text('details')->nullable();
        //     $table->text('bori_details')->nullable();
        //     $table->string('balance')->nullable();
        //     $table->string('paid')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('good_receipts_truck_id')->references('id_truck')->on('good_receipts_truck')->onDelete('set null');
        //     $table->foreign('customer_id')->references('id_customers')->on('customers')->onDelete('set null');
        // });

        // // Sales Items Details Table
        // Schema::create('sales_items_details', function (Blueprint $table) {
        //     $table->bigIncrements('id_sid');
        //     $table->unsignedBigInteger('sales_id')->nullable();
        //     $table->string('items_name')->nullable();
        //     $table->string('marka')->nullable();
        //     $table->string('bori')->nullable();
        //     $table->string('weight')->nullable();
        //     $table->string('rate')->nullable();
        //     $table->text('notes')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->timestamps();

        //     $table->foreign('sales_id')->references('id_sales')->on('sales')->onDelete('cascade');
        // });

        // // Sales Per Bori Weight Table
        // Schema::create('sales_per_bw', function (Blueprint $table) {
        //     $table->bigIncrements('id_sales_per_bw');
        //     $table->unsignedBigInteger('sales_id')->nullable();
        //     // $table->unsignedBigInteger('sales_items_details_id')->nullable();
        //     $table->string('bori_number')->nullable();
        //     $table->string('per_bori_weight')->nullable();
        //     $table->timestamps();

        //     // $table->foreign('sales_items_details_id')->references('id_sid')->on('sales_items_details')->onDelete('cascade');
        //     $table->foreign('sales_id')->references('id_sales')->on('sales')->onDelete('cascade');
        // });

        // purchase_order Table

        // Schema::create('purchase_order', function (Blueprint $table) {
        //     $table->bigIncrements('id_purchase_order');
        //     $table->unsignedBigInteger('business_id')->nullable();
        //     $table->unsignedBigInteger('supplier_id')->nullable();
        //     $table->date('date')->nullable();
        //     $table->string('po_no')->nullable();
        //     $table->text('details')->nullable();
        //     $table->string('rent_recieve')->nullable();
        //     $table->string('labour')->nullable();
        //     $table->timestamps();

        //     $table->foreign('supplier_id')->references('id_supplier')->on('suppliers')->onDelete('set null');
        // });

        // Schema::create('purchase_order_details', function (Blueprint $table) {
        //     $table->bigIncrements('id_purchase_order_details');
        //     $table->unsignedBigInteger('purchase_order_id')->nullable();
        //     $table->string('items_name')->nullable();
        //     $table->text('marka')->nullable();
        //     $table->string('bori')->nullable();
        //     $table->string('weight')->nullable();
        //     $table->string('rate')->nullable();
        //     $table->string('amount')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->timestamps();

        //     $table->foreign('purchase_order_id')->references('id_purchase_order')->on('purchase_order')->onDelete('cascade');
        // });

        // B-Cheque Table
        // Schema::create('b_cheque', function (Blueprint $table) {
        //     $table->bigIncrements('id_b_cheque');
        //     $table->unsignedBigInteger('bussiness_id')->nullable();
        //     $table->unsignedBigInteger('good_receipts_truck_id')->nullable();
        //     $table->unsignedBigInteger('customer_id')->nullable();
        //     $table->unsignedBigInteger('supplier_id')->nullable();
        //     $table->date('date')->nullable();
        //     $table->string('pb_no')->nullable();
        //     $table->string('bilty_no')->nullable();
        //     $table->text('detail')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('good_receipts_truck_id')->references('id_truck')->on('good_receipts_truck')->onDelete('set null');
        //     $table->foreign('customer_id')->references('id_customers')->on('customers')->onDelete('set null');
        //     $table->foreign('supplier_id')->references('id_supplier')->on('suppliers')->onDelete('set null');
        // });

        // Schema::create('b_cheque_items', function (Blueprint $table) {
        //     $table->bigIncrements('id_b_cheque_items');
        //     $table->unsignedBigInteger('b_cheque_id')->nullable();
        //     $table->string('marka')->nullable();
        //     $table->string('item')->nullable();
        //     $table->string('bori')->nullable();
        //     $table->string('weight')->nullable();
        //     $table->string('rate')->nullable();
        //     $table->string('amount')->nullable();
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('b_cheque_id')->references('id_b_cheque')->on('b_cheque')->onDelete('cascade');
        // });

        // Schema::create('b_cheque_details', function (Blueprint $table) {
        //     $table->bigIncrements('id_b_cheque_details');
        //     $table->unsignedBigInteger('b_cheque_id')->nullable();
        //     $table->string('rent_recieve_amount')->nullable();
        //     $table->string('labour_amount')->nullable();
        //     $table->string('market_fees_amount')->nullable();
        //     $table->string('post_expense_amount')->nullable();
        //     $table->string('miscellaneous_expense_amount')->nullable();
        //     $table->string('cash_amount')->nullable();
        //     $table->string('commission_rate')->nullable();
        //     $table->string('commission_amount')->nullable();
        //     $table->text('else_more_amount')->nullable();
        //     $table->text('details')->nullable();
        //     $table->string('total_bori')->nullable();
        //     $table->string('weight')->nullable();
        //     $table->string('total_amount')->nullable();
        //     $table->string('total_expanse_amount')->nullable();
        //     $table->string('save_amount')->nullable();
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     $table->foreign('b_cheque_id')->references('id_b_cheque')->on('b_cheque')->onDelete('cascade');
        // });

        // Schema::create('supplier_brand', function (Blueprint $table) {
        //     $table->id('id_supplier_brand');
        //     $table->unsignedBigInteger('supplier_id');
        //     $table->unsignedBigInteger('brand_id');
        //     $table->timestamps();

        //     // Foreign keys
        //     $table->foreign('supplier_id')->references('id_supplier')->on('suppliers')->onDelete('cascade');
        //     $table->foreign('brand_id')->references('id_brands')->on('brands')->onDelete('cascade');
        // });

        // Schema::create('products_stock', function (Blueprint $table) {
        //     $table->bigIncrements('id_products_stock');
        //     $table->unsignedBigInteger('pro_cate_main_id')->nullable();
        //     $table->unsignedBigInteger('pro_cate_parent_id')->nullable();
        //     $table->unsignedBigInteger('pro_cate_child_id')->nullable();
        //     $table->integer('quantity')->default(0);
        //     $table->date('date')->nullable();
        //     $table->enum('status', ['1', '0'])->default('1');
        //     $table->string('created_by')->nullable();
        //     $table->timestamps();

        //     // Foreign key constraints
        //     $table->foreign('pro_cate_main_id')->references('id_pro_cate_main')->on('products_category_main')->onDelete('cascade');
        //     $table->foreign('pro_cate_parent_id')->references('id_pro_sub_cat_parent')->on('products_sub_cat_parent')->onDelete('cascade');
        //     $table->foreign('pro_cate_child_id')->references('id_pro_sub_cat_child')->on('products_sub_cat_child')->onDelete('cascade');
        // });

        echo "All Good";
    }

    public function assignSuperAdminRole()
    {
        // $user = User::find(auth()->user()->id);

        // echo $user->getRoleNames();
        // echo $user->getAllPermissions();
        // echo $user->hasPermissionTo('accounts');

        // if (auth()->user()->can('accounts')){
        //     echo "Done";
        // } else {
        //     echo "Not Done";
        // }

        // if ($user->hasRole('super-admin')) {
        //     echo 'User is a super-admin';
        // } else {
        //     echo 'User is not a super-admin';
        // }

        // if ($user) {
        //     $user->assignRole('super-admin');
        //     return response()->json(['message' => 'Role assigned successfully']);
        // } else {
        //     return response()->json(['message' => 'User not found'], 404);
        // }
    }
}
