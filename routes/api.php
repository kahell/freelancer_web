<?php

Route::group(['namespace' => 'Api'], function(){
  Route::post('register','CA_authController@register');
  Route::post('login','CA_authController@login');
  Route::post('me','CA_authController@me');
  Route::post('payload','CA_authController@payload');
  Route::post('refresh','CA_authController@refresh');
  Route::group(['middleware' => ['jwt.auth']], function() {
      Route::get('logout', 'CA_authController@logout');
  });
  /* Company */
  Route::post('company','CA_company@store');
  Route::post('company/update/{id}','CA_company@update');
  Route::get('company','CA_company@index');
  Route::get('company/{id}','CA_company@show');
  Route::get('company/user/{id}','CA_company@showByUser');
  Route::delete('company/{id}','CA_company@destroy');
  /* Projects */
  Route::post('project','CA_project@store');
  Route::post('project/update/{id}','CA_project@update');
  Route::get('project','CA_project@index');
  Route::get('project/show/{id}','CA_project@show');
  Route::get('project/company/{id}','CA_project@showByCompany');
  Route::get('project/publish/','CA_project@showByPublish');
  Route::delete('project/delete/{id}','CA_project@destroy');
  /* Projects */
  Route::post('project','CA_project@store');
  Route::post('project/update/{id}','CA_project@update');
  Route::get('project','CA_project@index');
  Route::get('project/show/{id}','CA_project@show');
  Route::get('project/company/{id}','CA_project@showByCompany');
  Route::get('project/publish/','CA_project@showByPublish');
  Route::delete('project/delete/{id}','CA_project@destroy');
  /* Bids */
  Route::post('bid','CA_bid@store');
  Route::post('bid/update/{id}','CA_bid@update');
  Route::get('bid','CA_bid@index');
  Route::get('bid/show/{id}','CA_bid@show');
  Route::get('bid/user/{id}','CA_bid@showByUser');
  Route::delete('bid/delete/{id}','CA_bid@destroy');
  /* Contract */
  Route::post('contract','CA_contract@store');
  Route::post('contract/update/{id}','CA_contract@update');
  Route::post('contract/start/{id}','CA_contract@start');
  Route::get('contract','CA_contract@index');
  Route::get('contract/show/{id}','CA_contract@show');
  Route::delete('contract/delete/{id}','CA_contract@destroy');
  /* Payment */
  Route::post('payment','CA_transaction@payment');
  /* Buy Rank */
  Route::post('buy_rank','CA_transaction@buy_rank');
  /* User or Expert */
  Route::post('expert','CA_user@store');
  Route::post('expert/update/{id}','CA_user@update');
  Route::get('expert','CA_user@index');
  Route::get('expert/show/{id}','CA_user@show');
  Route::delete('expert/delete/{id}','CA_user@destroy');
  /* Portofolio */
  Route::post('portofolio','CA_portofolio@store');
  Route::post('portofolio/update/{id}','CA_portofolio@update');
  Route::get('portofolio','CA_portofolio@index');
  Route::get('portofolio/show/{id}','CA_portofolio@show');
  Route::delete('portofolio/delete/{id}','CA_portofolio@destroy');
  /* Portofolio */
  Route::post('review','CA_review@store');
  Route::post('review/update/{id}','CA_review@update');
  Route::get('review','CA_review@index');
  Route::get('review/show/{id}','CA_review@show');
  Route::delete('review/delete/{id}','CA_review@destroy');

});
