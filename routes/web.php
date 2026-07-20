<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/locale/{locale}', function (string $locale) {
    if (! in_array($locale, ['ar', 'en'], true)) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('locale.switch');

/*
|--------------------------------------------------------------------------
| Storage fallback (shared hosting without symlink/exec)
|--------------------------------------------------------------------------
| Serves files from storage/app/public when `php artisan storage:link` fails
| because symlink() and exec() are disabled. If public/storage exists as a
| real symlink, the web server serves files directly and this route is unused.
*/
Route::get('/storage/{path}', function (string $path) {
    $path = str_replace('..', '', $path);
    $fullPath = storage_path('app/public/'.$path);

    if (! is_file($fullPath)) {
        abort(404);
    }

    return response()->file($fullPath);
})->where('path', '.*')->name('storage.fallback');
