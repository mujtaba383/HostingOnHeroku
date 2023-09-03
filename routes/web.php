<?php

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
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function(){
// Author
        Route::get('author/status_active', 'AuthorController@status_active')->name('status_active');
        Route::get('author/status_deactive', 'AuthorController@status_deactive')->name('status_deactive');
        Route::get('author/delete_all', 'AuthorController@delete_all')->name('delete_all');
// Category
        Route::get('category/status_active', 'CategoryController@status_active')->name('status_active_category');
        Route::get('category/status_deactive', 'CategoryController@status_deactive')->name('status_deactive_category');
        Route::get('category/delete_all', 'CategoryController@delete_all')->name('delete_all_category');
// Book
        Route::get('book/status_active', 'BookController@status_active')->name('status_active_book');
        Route::get('book/status_deactive', 'BookController@status_deactive')->name('status_deactive_book');
        Route::get('book/delete_all', 'BookController@delete_all')->name('delete_all_book');
// Media
        Route::get('media/status_active', 'MediaController@status_active')->name('status_active_media');
        Route::get('media/status_deactive', 'MediaController@status_deactive')->name('status_deactive_media');
        Route::get('media/delete_all', 'MediaController@delete_all')->name('delete_all_media');
// Team
        Route::get('team/status_active', 'TeamController@status_active')->name('status_active_team');
        Route::get('team/status_deactive', 'TeamController@status_deactive')->name('status_deactive_team');
        Route::get('team/delete_all', 'TeamController@delete_all')->name('delete_all_team');

        Route::put('author/{id}/status', 'AuthorController@status');
        Route::put('book/{id}/status', 'BookController@status');
        Route::put('media/{id}/status', 'MediaController@status');
        Route::put('team/{id}/status', 'TeamController@status');
        Route::put('category/{id}/status', 'CategoryController@status');

        Route::resource('author', 'AuthorController');
        Route::resource('media', 'MediaController');
        Route::resource('category', 'CategoryController');
        Route::resource('team', 'TeamController');
        Route::resource('book', 'BookController');
// change password and update profile
        Route::post('/updatepassword', 'HomeController@update_password')->name('update.password');
        Route::get('profile', 'HomeController@profile')->name('profile');
        Route::put('/profile/update', 'HomeController@profile_update')->name('profile.update');

});





// Front end
Route::get('/contact', 'MainController@contact')->name('contact');
Route::get('/author_detail/{slug}', 'MainController@author_detail')->name('author_detail');
Route::get('/author', 'MainController@author')->name('author');
Route::get('/gallery', 'MainController@gallery')->name('gallery');
Route::get('/about', 'MainController@about')->name('about');
Route::get('/book_detail/{slug}', 'BookController@detail')->name('book.detail');
Route::get('/category_detail/{slug}', 'CategoryController@detail')->name('category.detail');
Route::get('/', 'MainController@index');

// Authentication
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');