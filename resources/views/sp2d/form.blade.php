@extends('layouts.app')
@section('content')
    <div id="app">
        @if ($act == 'create')
            <sp2d-create 
                :tahun_data='{!! json_encode($tahun) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </sp2d-create>
        @else 
            <sp2d-update 
                :tahun_data='{!! json_encode($tahun) !!}'
                :sp2d='{!! json_encode($sp2d) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </sp2d-update>
        @endif
    </div>
@stop