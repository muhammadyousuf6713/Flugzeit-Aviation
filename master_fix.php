<?php
// 1. Redesign Inquiry Edit Page (we will copy create.blade.php to edit.blade.php and inject the values)
$create = file_get_contents('resources/views/inquiry/create.blade.php');
$edit = str_replace('Add Inquiry', 'Edit Inquiry', $create);
$edit = str_replace('Create a New Inquiry', 'Edit Inquiry', $edit);
$edit = str_replace('action="{{ url(\'inquiry\') }}"', 'action="{{ url(\'inquiry\', $inquiry->id_inquiry) }}"', $edit);
$edit = str_replace('@csrf', '@csrf @method(\'PUT\')', $edit);

$replacements = [
    '/name="customer_name"/' => 'name="customer_name" value="{{ $inquiry->customer->customer_name ?? \'\' }}"',
    '/name="customer_cell"/' => 'name="customer_cell" value="{{ $inquiry->customer->customer_cell ?? \'\' }}"',
    '/name="customer_email"/' => 'name="customer_email" value="{{ $inquiry->customer->customer_email ?? \'\' }}"',
    '/name="customer_whatsapp"/' => 'name="customer_whatsapp" value="{{ $inquiry->customer->whatsapp_number ?? \'\' }}"',
    '/name="customer_phone_2"/' => 'name="customer_phone_2" value="{{ $inquiry->customer->customer_phone2 ?? \'\' }}"',
    '/name="customer_address"/' => 'name="customer_address" value="{{ $inquiry->customer->customer_address ?? \'\' }}"',
    '/name="customer_reference"/' => 'name="customer_reference" value="{{ $inquiry->customer->customer_reference ?? \'\' }}"',
    '/name="customer_remarks"/' => 'name="customer_remarks" value="{{ $inquiry->customer->customer_remarks ?? \'\' }}"',
    '/value="{{ \$salesPerson->id }}"/' => 'value="{{ $salesPerson->id }}" {{ $inquiry->saleperson == $salesPerson->id ? \'selected\' : \'\' }}',
    '/value="{{ \$inquiryType->type_id }}"/' => 'value="{{ $inquiryType->type_id }}" {{ $inquiry->inquiry_type == $inquiryType->type_id ? \'selected\' : \'\' }}',
    '/value="{{ \$salesReference->type_id }}"/' => 'value="{{ $salesReference->type_id }}" {{ $inquiry->sales_reference == $salesReference->type_id ? \'selected\' : \'\' }}',
    '/value="{{ \$service->id_other_services }}"/' => 'value="{{ $service->id_other_services }}" {{ in_array($service->id_other_services, explode(\',\', $inquiry->services)) ? \'selected\' : \'\' }}',
    '/name="travel_date"/' => 'name="travel_date" value="{{ $inquiry->travel_date ? \Carbon\Carbon::parse($inquiry->travel_date)->format(\'Y-m-d\') : \'\' }}"',
    '/name="followup_date"/' => 'name="followup_date" value="{{ $inquiry->followup_date ? \Carbon\Carbon::parse($inquiry->followup_date)->format(\'Y-m-d\') : \'\' }}"',
    '/name="amount"/' => 'name="amount" value="{{ $inquiry->amount }}"',
    '/name="adult"/' => 'name="adult" value="{{ $inquiry->adult }}"',
    '/name="children"/' => 'name="children" value="{{ $inquiry->children }}"',
    '/name="infant"/' => 'name="infant" value="{{ $inquiry->infant }}"',
    '/name="remarks"/' => 'name="remarks" value="{{ $inquiry->remarks }}"',
];
$edit = preg_replace(array_keys($replacements), array_values($replacements), $edit);
$edit = str_replace('<select name="customer_city" id="customer_city" class="form-control ajax-city-select2" data-placeholder="Select City">
                                            </select>', '<select name="customer_city" id="customer_city" class="form-control ajax-city-select2" data-placeholder="Select City">
                                                @if($inquiry->customer && $inquiry->customer->city_id)
                                                    <option value="{{ $inquiry->customer->city_id }}" selected>{{ $inquiry->customer->city_id }}</option>
                                                @endif
                                            </select>', $edit);
file_put_contents('resources/views/inquiry/edit.blade.php', $edit);

// 2. Fix User Edit Functionality
$userController = file_get_contents('app/Http/Controllers/UsersController.php');
// The user controller currently doesn't show success messages on update. Wait, I don't know the exact method name.
// Let's assume there's an update() method.
