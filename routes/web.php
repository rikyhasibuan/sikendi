<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
*/

Route::get('/', 'Web\LoginController@login');
Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::get('login', 'Web\LoginController@login');
Route::get('logout', 'Web\LoginController@logout');
Route::get('logout', 'Web\LoginController@logout');

Route::get('dashboard', 'Web\DashboardController@index');
Route::get('profile', 'Web\ProfileController@index');
Route::get('logaktifitas', 'Web\LogActivityController@index');
Route::get('report', 'Web\ReportController@index');

// program
Route::get('program', 'Web\ProgramController@index');
Route::get('program/create', 'Web\ProgramController@create');
Route::get('program/edit', 'Web\ProgramController@edit');

// kegiatan
Route::get('kegiatan', 'Web\KegiatanController@index');
Route::get('kegiatan/create', 'Web\KegiatanController@create');
Route::get('kegiatan/edit', 'Web\KegiatanController@edit');

// belanja
Route::get('belanja', 'Web\BelanjaController@index');
Route::get('belanja/create', 'Web\BelanjaController@create');
Route::get('belanja/edit', 'Web\BelanjaController@edit');

// pegawai
Route::get('pegawai', 'Web\PegawaiController@index');
Route::get('pegawai/create', 'Web\PegawaiController@create');
Route::get('pegawai/edit', 'Web\PegawaiController@edit');

// user
Route::get('user', 'Web\UserController@index');
Route::get('user/create', 'Web\UserController@create');
Route::get('user/edit', 'Web\UserController@edit');

// anggaran
Route::get('anggaran', 'Web\AnggaranController@index');
Route::get('anggaran/create', 'Web\AnggaranController@create');
Route::get('anggaran/edit', 'Web\AnggaranController@edit');

// SP2D
Route::get('sp2d', 'Web\Sp2dController@index');
Route::get('sp2d/create', 'Web\Sp2dController@create');
Route::get('sp2d/edit', 'Web\Sp2dController@edit');
Route::get('sp2d/detail', 'Web\Sp2dController@detail');
Route::get('sp2d/nominal/create', 'Web\Sp2dController@create_nominal');
Route::get('sp2d/nominal/edit', 'Web\Sp2dController@edit_nominal');

// pelimpahan
Route::get('pelimpahan', 'Web\PelimpahanController@index');
Route::get('pelimpahan/create', 'Web\PelimpahanController@create');
Route::get('pelimpahan/edit', 'Web\PelimpahanController@edit');
Route::get('pelimpahan/detail', 'Web\PelimpahanController@detail');
Route::get('pelimpahan/nominal/create', 'Web\PelimpahanController@create_nominal');
Route::get('pelimpahan/nominal/edit', 'Web\PelimpahanController@edit_nominal');
