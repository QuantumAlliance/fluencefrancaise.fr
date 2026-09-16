<?php
include 'vendor/autoload.php';
$app = include_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Page;

$page = Page::where('slug', '/books')->first();
if ($page) {
    echo "Found page: " . $page->name . " (ID: " . $page->id . ")\n";
} else {
    echo "No page found with slug /books\n";
}
