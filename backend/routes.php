<?php
require_once("util/RequestParametersHelper.php");

// Enlazar controladores con recursos.
Route::resource("api/users", "UserController");
Route::resource("api/hotels", "HotelController");
Route::resource("api/reservations", "ReservationController");
Route::resource("api/rooms", "RoomController");


// Authentication Routes  
Route::post('api/login', 'LoginController@login');
Route::get('api/logout', 'LoginController@logout');
Route::get('api/info', 'LoginController@info');

// Registration Routes
Route::post('api/register', 'RegisterController@register');

// Update user routes
Route::post('api/update/(:number)', 'RegisterController@update');

Route::post('api/search', 'SearchController@search');

Route::dispatch();
