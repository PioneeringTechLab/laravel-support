<?php

/*
|--------------------------------------------------------------------------
| Support Package Routes
|--------------------------------------------------------------------------
|
| This file defines the various routes that are used by the support package
| and they can be modified. These routes are protected by the "auth"
| middleware to ensure only authenticated users can submit data.
|
*/

Route::group(['middleware' => ['auth']], function () {
	Route::get('support', 'SupportController@create');
	Route::post('support', 'SupportController@store');

	Route::get('feedback', 'FeedbackController@create');
	Route::post('feedback', 'FeedbackController@store');
});