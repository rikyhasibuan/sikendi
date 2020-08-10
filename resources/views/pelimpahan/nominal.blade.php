@extends('layouts.app')
@section('content')
    <div id="app">
        @if ($act == 'create')
            <pelimpahan-nominal-create 
                :bendahara_data='{!! json_encode($bendahara) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </pelimpahan-nominal-create>
        @else 
            <pelimpahan-nominal-update 
                :pelimpahan='{!! json_encode($pelimpahan) !!}'
                :bendahara_data='{!! json_encode($bendahara) !!}'
                :route='{!! json_encode($route) !!}'
                :api='{!! json_encode($api) !!}'>
            </pelimpahan-nominal-update>
        @endif
    </div>
@stop