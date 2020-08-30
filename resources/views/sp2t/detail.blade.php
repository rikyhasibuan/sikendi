@extends('layouts.app')
@section('content')
  <div id="app">
    <sp2t-detail
      :sp2t='{!! json_encode($sp2t) !!}'
      :sp2tdetail='{!! json_encode($sp2tdetail) !!}'
      :route='{!! json_encode($route) !!}'
      :api='{!! json_encode($api) !!}'
      :access='{!! json_encode($access) !!}'>
    </sp2t-detail>
  </div>
@stop