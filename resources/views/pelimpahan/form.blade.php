@extends('layouts.app')
@section('content')
    <div id="app">
        @if ($act == 'create')
            <pelimpahan-create 
                :tahun_data='{!! json_encode($tahun) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </pelimpahan-create>
        @else 
            <pelimpahan-update 
                :pelimpahan='{!! json_encode($pelimpahan) !!}'
                :tahun_data='{!! json_encode($tahun) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </pelimpahan-update>
        @endif
    </div>
@stop