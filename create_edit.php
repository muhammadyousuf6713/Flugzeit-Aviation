<?php
$create = file_get_contents('resources/views/inquiry/create.blade.php');
$edit = str_replace('Add Inquiry', 'Edit Inquiry', $create);
$edit = str_replace('Create a New Inquiry', 'Edit Inquiry', $edit);
$edit = str_replace('action="{{ url(\'inquiry\') }}"', 'action="{{ url(\'inquiry\', $inquiry->id_inquiry) }}"', $edit);
$edit = str_replace('@csrf', '@csrf @method(\'PUT\')', $edit);

$edit = preg_replace('/name="customer_name"/', 'name="customer_name" value="{{ $inquiry->customer->customer_name ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_cell"/', 'name="customer_cell" value="{{ $inquiry->customer->customer_cell ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_email"/', 'name="customer_email" value="{{ $inquiry->customer->customer_email ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_whatsapp"/', 'name="customer_whatsapp" value="{{ $inquiry->customer->whatsapp_number ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_phone_2"/', 'name="customer_phone_2" value="{{ $inquiry->customer->customer_phone2 ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_address"/', 'name="customer_address" value="{{ $inquiry->customer->customer_address ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_reference"/', 'name="customer_reference" value="{{ $inquiry->customer->customer_reference ?? \'\' }}"', $edit);
$edit = preg_replace('/name="customer_remarks"/', 'name="customer_remarks" value="{{ $inquiry->customer->customer_remarks ?? \'\' }}"', $edit);

file_put_contents('resources/views/inquiry/edit2.blade.php', $edit);
echo "Done";
