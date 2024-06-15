<?php
require_once("util/RequestParametersHelper.php");

// Enlazar controladores con recursos.
Route::resource("users", "UserController");
Route::resource("hotels", "HotelController");
Route::resource("reservations", "ReservationController");
Route::resource("rooms", "RoomController");


// Authentication Routes  
Route::post('login', 'LoginController@login');
Route::get('logout', 'LoginController@logout');
Route::get('info', 'LoginController@info');

// Registration Routes
Route::post('register', 'RegisterController@register');

// Update user routes
Route::post('update/(:number)', 'RegisterController@update');

Route::post('search', 'SearchController@search');

Route::dispatch();
