@extends('layouts.app')
@section('content')
    <div id="app">
        <sp2d-index
            :route='{!! json_encode($route) !!}'
            :api='{!! json_encode($api) !!}'
            :access='{!! json_encode($access) !!}'>
        </sp2d-index>
    </div>
@stop