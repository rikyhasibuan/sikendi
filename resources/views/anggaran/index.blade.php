@extends('layouts.app')
@section('content')
    <div id="app">
        <anggaran-index
            :kegiatan_data='{!! json_encode($kegiatan) !!}'
            :program_data='{!! json_encode($program) !!}'
            :belanja_data='{!! json_encode($belanja) !!}'
            :tahun_data='{!! json_encode($tahun) !!}'
            :route='{!! json_encode($route) !!}'
            :api='{!! json_encode($api) !!}'
            :access='{!! json_encode($access) !!}'>
        </anggaran-index>
    </div>
@stop