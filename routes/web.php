<?php

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

Route::get('/', function () {
    return redirect('/dashboard');
});
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::group(['middleware' => ['auth', 'ipcheck']], function () {

    Route::get('/dashboard', 'DashboardController@index');

    Route::prefix('bestellingen')->group(function () {
        Route::get('/goedgekeurd', 'ParticipantController@confirmedOrders')->name('confirmed');
        Route::get('/afgekeurd', 'ParticipantController@deniedOrders')->name('denied');
        Route::get('/', 'StatusController@pendingOrders')->name('pending');
        Route::get('/dagelijks', 'ParticipantController@ordersSpecific');
        Route::post('/dagelijks/zoek', 'ParticipantController@ordersSpecificSearch')->name('orders.searchbyday');
        Route::post('/zoekenorders', 'ParticipantController@searchorders')->name('orders.search');
        Route::post('/zoekenorders/afgekeurd', 'ParticipantController@searchdeniedorders')->name('orders.searchdenied');
        Route::get('/{id}', 'ParticipantController@show')->name('backoffice.participant.show');
        Route::post('/{id}/update', 'ParticipantController@update')->name('backoffice.participant.update');
    });

    Route::prefix('vertalingen')->group(function () {
        Route::get('/', 'TranslationController@index');
        Route::get('/{id}', 'TranslationController@show')->name('translation.show');
        Route::POST('/update', 'TranslationController@update')->name('translation.update');
        Route::post('/gecategoriseerd', 'TranslationController@showCategorizedTranslations')->name('category');
    });

    Route::prefix('producten')->group(function () {
        Route::get('/nieuw', 'ProductController@createProductIndex')->name('product.new');
        Route::post('/nieuw/maak', 'ProductController@createProduct')->name('createproduct');
        Route::get('/', 'ProductController@index')->name('productindex');
        Route::get('/{id}', 'ProductController@showProduct')->name('product.show');
        Route::POST('/{id}/update', 'ProductController@update')->name('product.update');
        Route::POST('/{id}/delete', 'ProductController@delete')->name('product.delete');
        Route::post('/getcroppedimage', 'ProductController@getCroppedImage')->name('getCroppedImage');
    });

    Route::prefix('sliders')->group(function () {
        Route::get('/nieuw', 'SliderController@createSliderIndex');
        Route::post('/nieuw/create', 'SliderController@createSlider')->name('newslider');
        Route::get('/', 'SliderController@index')->name('sliderindex');
        Route::get('/{id}', 'SliderController@showSlider')->name('slider.show');
        Route::POST('/{id}/update', 'SliderController@update')->name('slider.update');
        Route::POST('/{id}/delete', 'SliderController@delete')->name('slider.delete');
    });

    Route::prefix('gebruikers')->group(function () {
        Route::get('/', 'UserController@index');
        Route::get('/{id}', 'UserController@edit')->name('gebruiker.edit');
        Route::POST('/create', 'UserController@register')->name('gebruiker.aanmaken');
        Route::POST('/{id}/delete', 'UserController@delete')->name('gebruiker.verwijderen');
        Route::POST('/{id}/update', 'UserController@update')->name('gebruiker.update');
    });

    Route::prefix('status')->group(function () {
        Route::post('/weigeren/{id}', 'StatusController@deny')->name('status.deny');
        Route::post('/accepteren/{id}', 'StatusController@accept')->name('status.accept');
    });

    route::get('/deelnemerlijstdownload', 'ParticipantController@downloaddata');
    route::get('/deelnemerlijstdownload/export', 'ParticipantController@exportview')->name('participants.export');
    route::get('/deelnemerlijstdownload/export/search', 'ParticipantController@exportsearch')->name('participants.exportsearch');
    route::get('/orderlijstdownload/export', 'ParticipantController@exportvieworders')->name('orders.export');
    route::get('/orderlijstdownload/export/denied', 'ParticipantController@exportdeniedorders')->name('orders.exportdenied');
    route::get('/orderlijstdownload/export/search', 'ParticipantController@exportsearchorders')->name('orders.exportsearch');

});

Auth::routes(['register' => false]);
//Auth::routes();
