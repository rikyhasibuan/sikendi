<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/
Route::post('login', 'Api\LoginController@postLogin');
Route::get('dashboard', 'Api\DashboardController@getData');
Route::put('profile', 'Api\ProfileController@putUpdatePassword');
Route::get('logaktifitas', 'Api\LogActivityController@get_data');
Route::get('report', 'Api\ReportController@print_data');

// AJAX request
Route::get('ajax/kegiatan/{program}', 'Api\AjaxController@show_kegiatan_by_program');
Route::get('ajax/belanja/{kegiatan}', 'Api\AjaxController@show_belanja_by_kegiatan');
Route::post('ajax/sisa_anggaran', 'Api\AjaxController@show_sisa_anggaran');
Route::post('ajax/total_anggaran', 'Api\AjaxController@show_total_anggaran');
Route::post('ajax/golongan', 'Api\AjaxController@show_golongan_by_pangkat');

// program
Route::get('program', 'Api\ProgramController@get_data');
Route::get('program/{id}', 'Api\ProgramController@show_data');
Route::post('program', 'Api\ProgramController@post_data');
Route::put('program', 'Api\ProgramController@put_data');
Route::delete('program', 'Api\ProgramController@delete_data');

// kegiatan
Route::get('kegiatan', 'Api\KegiatanController@get_data');
Route::get('kegiatan/{id}', 'Api\KegiatanController@show_data');
Route::post('kegiatan', 'Api\KegiatanController@post_data');
Route::put('kegiatan', 'Api\KegiatanController@put_data');
Route::delete('kegiatan', 'Api\KegiatanController@delete_data');

// belanja
Route::get('belanja', 'Api\BelanjaController@get_data');
Route::get('belanja/{id}', 'Api\BelanjaController@show_data');
Route::post('belanja', 'Api\BelanjaController@post_data');
Route::put('belanja', 'Api\BelanjaController@put_data');
Route::delete('belanja', 'Api\BelanjaController@delete_data');

// pegawai
Route::get('pegawai', 'Api\PegawaiController@get_data');
Route::get('pegawai/{id}', 'Api\PegawaiController@show_data');
Route::post('pegawai', 'Api\PegawaiController@post_data');
Route::put('pegawai', 'Api\PegawaiController@put_data');
Route::delete('pegawai', 'Api\PegawaiController@delete_data');

// user
Route::get('user', 'Api\UserController@get_data');
Route::get('user/{id}', 'Api\UserController@show_data');
Route::post('user', 'Api\UserController@post_data');
Route::put('user', 'Api\UserController@put_data');
Route::put('user/resetpassword', 'Api\UserController@reset_password');
Route::delete('user', 'Api\UserController@delete_data');

// anggaran
Route::get('anggaran', 'Api\AnggaranController@get_data');
Route::get('anggaran/{belanja}/{tahun}', 'Api\AnggaranController@get_data_detail');
Route::get('anggaran/{id}', 'Api\AnggaranController@show_data');
Route::post('anggaran', 'Api\AnggaranController@post_data');
Route::put('anggaran', 'Api\AnggaranController@put_data');
Route::delete('anggaran', 'Api\AnggaranController@delete_data');

// SP2D
Route::get('sp2d', 'Api\Sp2dController@get_data');
Route::get('sp2d/{id}', 'Api\Sp2dController@show_data');
Route::post('sp2d', 'Api\Sp2dController@post_data');
Route::put('sp2d', 'Api\Sp2dController@put_data');
Route::delete('sp2d', 'Api\Sp2dController@delete_data');

// Pelimpahan
Route::get('pelimpahan', 'Api\PelimpahanController@get_data');
Route::get('pelimpahan/{id}', 'Api\PelimpahanController@show_data');
Route::post('pelimpahan', 'Api\PelimpahanController@post_data');
Route::put('pelimpahan', 'Api\PelimpahanController@put_data');
Route::delete('pelimpahan', 'Api\PelimpahanController@delete_data');
Route::post('pelimpahan/nominal', 'Web\PelimpahanController@post_nominal_data');
Route::put('pelimpahan/nominal', 'Web\PelimpahanController@put_nominal_data');
Route::delete('pelimpahan/nominal', 'Web\PelimpahanController@delete_nominal_data');
