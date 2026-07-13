<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$permissions = [
    'Campus Life', 'Campus Life list', 'Campus Life add', 'Campus Life edit', 'Campus Life view', 'Campus Life delete',
    'Admissions', 'Admissions list', 'Admissions add', 'Admissions edit', 'Admissions view', 'Admissions delete',
    'Academic Programmes', 'Academic Programmes list', 'Academic Programmes add', 'Academic Programmes edit', 'Academic Programmes view', 'Academic Programmes delete',
    'Administration', 'Administration list', 'Administration add', 'Administration edit', 'Administration view', 'Administration delete',
    'academic', 'campus life', 'admission'
];

\Spatie\Permission\Models\Permission::whereIn('name', $permissions)->delete();
echo "Done";
