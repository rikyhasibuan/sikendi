@extends('layouts.app')
@section('content')
    <div id="app">
        <pelimpahan-index
            :route='{!! json_encode($route) !!}'
            :api='{!! json_encode($api) !!}'
            :access='{!! json_encode($access) !!}'>
        </pelimpahan-index>
    </div>
@stop