<?php

use Illuminate\Support\Facades\Route;

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

Route::redirect('/', 'admin');

Route::get('test', function(){
    $service  = new App\Services\Api\Products\ProductMonthPrice;
});


use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

Route::match(['get', 'post'], 'payment-success', function(Request $request) {
    Log::info('Payment Success Callback', [
        'method' => $request->method(),
        'data' => $request->all(),
    ]);

    return response()->json(['message' => 'success']);
});


Route::post('/stripe/webhook', [\App\Http\Controllers\Api\StripeController::class, 'handleWebhook']);



Route::get('/sitemap.xml', function () {
    $xmlContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://tehsil.avtoicare.az/</loc>
        <lastmod>2025-05-15</lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://tehsil.avtoicare.az/about</loc>
        <lastmod>2025-05-14</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
</urlset>
XML;
    return response($xmlContent, 200)
        ->header('Content-Type', 'application/xml')
        ->header('Access-Control-Allow-Origin', '*')
        ->header('Access-Control-Allow-Methods', 'GET, OPTIONS')
        ->header('Access-Control-Allow-Headers', 'Content-Type, Accept');
});
