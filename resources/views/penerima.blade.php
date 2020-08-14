@extends('layouts.app')
@section('content')
    <div id="app">
        <penerima-index :api='{!! json_encode($api) !!}'></penerima-index>
    </div>
@stop