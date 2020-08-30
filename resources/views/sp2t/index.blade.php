@extends('layouts.app')
@section('content')
    <div id="app">
        <sp2t-index
            :route='{!! json_encode($route) !!}'
            :api='{!! json_encode($api) !!}'
            :access='{!! json_encode($access) !!}'>
        </sp2t-index>
    </div>
@stop