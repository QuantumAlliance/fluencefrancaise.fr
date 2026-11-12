<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\PageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Installer Routes - Must be before catch-all route
Route::get('/installer', [InstallerController::class, 'index']);
Route::post('/api/installer/save-database', [InstallerController::class, 'saveDatabase']);
Route::post('/api/installer/create-admin', [InstallerController::class, 'createAdmin']);
Route::post('/api/installer/migrate', [InstallerController::class, 'migrate']);
Route::get('/installer/reset', [InstallerController::class, 'reset']);
Route::post('/logout', function() {
    // Pro Fix: Revoke all frontend API tokens when backend session ends
    $user = Auth::guard('web')->user();
    \Illuminate\Support\Facades\Log::info('Web Logout Triggered for User ID: ' . ($user ? $user->id : 'Guest'));
    
    if ($user) {
        $user->tokens()->delete();
        \Illuminate\Support\Facades\Log::info('API Tokens revoked for User ID: ' . $user->id);
    }

    Auth::guard('web')->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
});

// Debug route for WordPress hash analysis
Route::get('/debug-hash', function () {
    $user = \App\Models\User::where('email', 'dutagarang123123@gmail.com')->first();
    if (!$user) {
        return "User not found";
    }

    $hash = \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->value('password');
    $password = 'admin098@';

    echo "<pre>";
    echo "=== WordPress Hash Debug ===\n";
    echo "Email: " . htmlspecialchars($user->email) . "\n";
    echo "User ID: " . $user->id . "\n";
    echo "Raw hash from DB: " . htmlspecialchars($hash) . "\n";
    echo "Hash length: " . strlen($hash) . "\n";
    echo "Password to test: " . htmlspecialchars($password) . "\n\n";

    // Hex dump first 20 chars
    echo "=== Hex Dump (first 20 chars) ===\n";
    for ($i = 0; $i < 20 && $i < strlen($hash); $i++) {
        echo sprintf("%d: %s (0x%02x)\n", $i, htmlspecialchars($hash[$i]), ord($hash[$i]));
    }
    echo "\n";

    // Try the $wp$ removal
    echo "=== Tests ===\n";
    $converted = str_replace('$wp$', '$', $hash);
    echo "After str_replace(\$wp\$, \$): " . htmlspecialchars($converted) . "\n";
    echo "Length after: " . strlen($converted) . "\n\n";

    // Test password_verify
    echo "password_verify with converted: " . (@password_verify($password, $converted) ? 'TRUE' : 'FALSE') . "\n";
    echo "password_verify with original: " . (@password_verify($password, $hash) ? 'TRUE' : 'FALSE') . "\n\n";

    // Try to determine the actual algorithm
    echo "=== Determine Algorithm ===\n";
    if (preg_match('/^\$2[aby]\$\d{2}\$/', $hash)) {
        echo "Looks like bcrypt: YES\n";
    } else if (preg_match('/^\$P\$/', $hash)) {
        echo "Looks like phpass: YES\n";
    } else if (preg_match('/^\$wp\$2[aby]\$\d{2}\$/', $hash)) {
        echo "Looks like WordPress bcrypt: YES\n";
    } else {
        echo "Unknown format\n";
    }

    echo "</pre>";
});

// Homepage route (explicit)
Route::get('/', function () {
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $classTypes = \App\Models\ClassType::where('is_active', true)
        ->orderBy('display_order', 'asc')
        ->get();
    return view('homepage', compact('settings', 'classTypes'));
});

// Why French page (static)
Route::get('/why-french', function () {
    return view('why-french');
});

// TEF/TCF page (static)
Route::get('/tef-tcf', function () {
    return view('tef-tcf');
});

// About Us page (static)
Route::get('/about-us', function () {
    return view('about-us');
});

// Our Courses page (static)
Route::get('/our-courses', function () {
    return view('our-courses');
});

// Contact Us page (static)
Route::get('/contact-us', function () {
    return view('contact-us');
});

// Beginner page (static)
Route::get('/beginner', function () {
    return view('beginner');
});

// Elementary page (static)
Route::get('/elementary', function () {
    return view('elementary');
});

// Intermediate page (static)
Route::get('/intermediate', function () {
    return view('intermediate');
});

// Upper Intermediate page (static)
Route::get('/upper-intermediate', function () {
    return view('upper-intermediate');
});

// Authentication Routes (Vue-handled)
Route::get('/login', function () {
    return view('app');
})->name('login');

Route::get('/register', function () {
    return view('app');
})->name('register');

Route::get('/forgot-password', function () {
    return view('app');
})->name('password.request');

// Handle reset-password with query params (from email link)
Route::get('/reset-password', function () {
    return view('app');
});

Route::get('/reset-password/{token}', function ($token) {
    return view('app');
})->name('password.reset');

// App dashboards and related routes (Vue-handled)
Route::get('/dashboard', function () { return view('app'); });
Route::get('/home', function () { return view('app'); });

Route::prefix('student')->group(function () {
    Route::get('/{any?}', function () { return view('app'); })->where('any', '.*');
});

Route::prefix('admin')->group(function () {
    Route::get('/{any?}', function () { return view('app'); })->where('any', '.*');
});

Route::prefix('tutor')->group(function () {
    Route::get('/{any?}', function () { return view('app'); })->where('any', '.*');
});

// Guest demo dashboard — served by the SPA so /demo links can be opened directly.
Route::prefix('demo')->group(function () {
    Route::get('/{any?}', function () { return view('app'); })->where('any', '.*');
});

Route::get('/books', function () { return view('app'); });

// =========================================================================
// DOCUMENTATION SPECIFICATION ROUTES (Built strictly from official pack)
// =========================================================================

// Page 01: Homepage (English /en/, French / or /fr/)
Route::get('/en', function () {
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $classTypes = \App\Models\ClassType::where('is_active', true)->orderBy('display_order', 'asc')->get();
    return view('homepage', compact('settings', 'classTypes'));
});
Route::get('/fr', function () {
    $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
    $classTypes = \App\Models\ClassType::where('is_active', true)->orderBy('display_order', 'asc')->get();
    return view('homepage', compact('settings', 'classTypes'));
});

// Page 02: TCF IRN (English /en/tcf-irn/, French /fr/tcf-irn/)
Route::get('/en/tcf-irn', function () { return view('tcf-irn'); });
Route::get('/fr/tcf-irn', function () { return view('tcf-irn'); });
Route::get('/tcf-irn', function () { return view('tcf-irn'); });

// Page 06: TEF IRN (English /en/tef-irn/, French /fr/tef-irn/)
Route::get('/en/tef-irn', function () { return view('tef-irn'); });
Route::get('/fr/tef-irn', function () { return view('tef-irn'); });
Route::get('/tef-irn', function () { return view('tef-irn'); });

// Fide test (English /en/fide-test, French /fr/test-fide and /fr/fide-test)
Route::get('/en/fide-test', function () { return view('fide-test'); });
Route::get('/fr/test-fide', function () { return view('fide-test'); });
Route::get('/fr/fide-test', function () { return view('fide-test'); });
Route::get('/fide-test', function () { return view('fide-test'); });

// C Permit Switzerland (English /en/c-permit-switzerland, French /fr/permis-c-suisse and /fr/c-permit-switzerland)
Route::get('/en/c-permit-switzerland', function () { return view('c-permit-switzerland'); });
Route::get('/fr/permis-c-suisse', function () { return view('c-permit-switzerland'); });
Route::get('/fr/c-permit-switzerland', function () { return view('c-permit-switzerland'); });
Route::get('/c-permit-switzerland', function () { return view('c-permit-switzerland'); });

// Page 14: DELF A2 (English /en/delf-a2/, French /fr/delf-a2/)
Route::get('/en/delf-a2', function () { return view('delf-a2'); });
Route::get('/fr/delf-a2', function () { return view('delf-a2'); });
Route::get('/delf-a2', function () { return view('delf-a2'); });

// Page 08: DELF B1 (English /en/delf-b1/, French /fr/delf-b1/)
Route::get('/en/delf-b1', function () { return view('delf-b1'); });
Route::get('/fr/delf-b1', function () { return view('delf-b1'); });
Route::get('/delf-b1', function () { return view('delf-b1'); });

// Page 07: DELF B2 (English /en/delf-b2/, French /fr/delf-b2/)
Route::get('/en/delf-b2', function () { return view('delf-b2'); });
Route::get('/fr/delf-b2', function () { return view('delf-b2'); });
Route::get('/delf-b2', function () { return view('delf-b2'); });

// Page 04: French civic exam (English /en/french-civic-exam/, French /fr/examen-civique/)
Route::get('/en/french-civic-exam', function () { return view('french-civic-exam'); });
Route::get('/fr/examen-civique', function () { return view('french-civic-exam'); });
Route::get('/fr/french-civic-exam', function () { return view('french-civic-exam'); });
Route::get('/en/examen-civique', function () { return view('french-civic-exam'); });
Route::get('/french-civic-exam', function () { return view('french-civic-exam'); });

// Page 16: French citizenship guide (English /en/french-citizenship/, French /fr/naturalisation-francaise/)
Route::get('/en/french-citizenship', function () { return view('french-citizenship'); });
Route::get('/fr/naturalisation-francaise', function () { return view('french-citizenship'); });
Route::get('/fr/french-citizenship', function () { return view('french-citizenship'); });
Route::get('/en/naturalisation-francaise', function () { return view('french-citizenship'); });
Route::get('/french-citizenship', function () { return view('french-citizenship'); });

// Page 15: TCF vs TEF vs DELF (English /en/tcf-vs-tef-vs-delf/, French /fr/tcf-tef-ou-delf/)
Route::get('/en/tcf-vs-tef-vs-delf', function () { return view('tcf-vs-tef-vs-delf'); });
Route::get('/fr/tcf-tef-ou-delf', function () { return view('tcf-vs-tef-vs-delf'); });
Route::get('/fr/tcf-vs-tef-vs-delf', function () { return view('tcf-vs-tef-vs-delf'); });
Route::get('/en/tcf-tef-ou-delf', function () { return view('tcf-vs-tef-vs-delf'); });
Route::get('/tcf-vs-tef-vs-delf', function () { return view('tcf-vs-tef-vs-delf'); });

// Page 03: French citizenship language test (English /en/french-citizenship-language-test/, French /fr/test-francais-naturalisation/)
Route::get('/en/french-citizenship-language-test', function () { return view('citizenship-language-test'); });
Route::get('/fr/test-francais-naturalisation', function () { return view('citizenship-language-test'); });
Route::get('/citizenship-language-test', function () { return view('citizenship-language-test'); });

// Swiss citizenship (English /en/swiss-citizenship, French /fr/naturalisation-suisse)
Route::get('/en/swiss-citizenship', function () { return view('swiss-citizenship'); });
Route::get('/fr/naturalisation-suisse', function () { return view('swiss-citizenship'); });
Route::get('/fr/swiss-citizenship', function () { return view('swiss-citizenship'); });
Route::get('/swiss-citizenship', function () { return view('swiss-citizenship'); });

// Page 12: Online French classes (English /en/online-french-classes/, French /fr/cours-de-francais-en-ligne/)
Route::get('/en/online-french-classes', function () { return view('our-courses'); });
Route::get('/fr/cours-de-francais-en-ligne', function () { return view('our-courses'); });
Route::get('/online-french-classes', function () { return view('our-courses'); });

// Page 17: About (English /en/about/, French /fr/a-propos/)
Route::get('/en/about', function () { return view('about-us'); });
Route::get('/fr/a-propos', function () { return view('about-us'); });
Route::get('/fr/about', function () { return view('about-us'); });
Route::get('/about', function () { return view('about-us'); });

// Page 18: Contact (English /en/contact/, French /fr/contact/)
Route::get('/en/contact', function () { return view('contact-us'); });
Route::get('/fr/contact', function () { return view('contact-us'); });
Route::get('/contact', function () { return view('contact-us'); });

// Page 13: DELF guide / all levels (English /en/delf/, French /fr/delf/)
Route::get('/en/delf', function () { return view('delf-b2'); });
Route::get('/fr/delf', function () { return view('delf-b2'); });
Route::get('/delf', function () { return view('delf-b2'); });

// Page 09: French residence permits (English /en/french-residence-permit-language/, French /fr/titre-de-sejour-niveau-francais/)
Route::get('/en/french-residence-permit-language', function () { return view('french-residence-permit'); });
Route::get('/fr/titre-de-sejour-niveau-francais', function () { return view('french-residence-permit'); });
Route::get('/fr/french-residence-permit-language', function () { return view('french-residence-permit'); });
Route::get('/en/titre-de-sejour-niveau-francais', function () { return view('french-residence-permit'); });
Route::get('/french-residence-permit-language', function () { return view('french-residence-permit'); });

// Policies page (Blade template)
Route::get('/new-policies', function () { return view('new-policies'); });

Route::get('/courses/preview/{id}/{slug}', function () {
    return view('app');
});

Route::get('/exam-preps/preview/{id}/{slug}', function () {
    return view('app');
});

Route::prefix('payment')->group(function () {
    Route::get('/{any?}', function () { return view('app'); })->where('any', '.*');
});

// Catch-all route for dynamic pages from database
Route::get('/{slug}', [PageController::class, 'show'])
    ->where('slug', '^(?!installer|api|debug-hash|storage|why-french|tef-tcf|about-us|our-courses|contact-us|beginner|elementary|intermediate|upper-intermediate|login|register|forgot-password|reset-password|dashboard|home|student|admin|tutor|demo|books|courses|exam-preps|payment|new-policies).*$');
