@extends('layouts.app')
@section('content')
    <div id="app">
        @if ($act == 'create')
            <sp2t-nominal-create 
                :program_data='{!! json_encode($program) !!}'
                :kegiatan_data='{!! json_encode($kegiatan) !!}'
                :belanja_data='{!! json_encode($belanja) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </sp2t-nominal-create>
        @else 
            <sp2t-nominal-update 
                :sp2t='{!! json_encode($sp2t) !!}'
                :program_data='{!! json_encode($program) !!}'
                :kegiatan_data='{!! json_encode($kegiatan) !!}'
                :belanja_data='{!! json_encode($belanja) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </sp2t-nominal-update>
        @endif
    </div>
@stop