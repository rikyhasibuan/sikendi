@extends('layouts.app')
@section('content')
  <div id="app">
    <pelimpahan-detail
      :sp2d='{!! json_encode($sp2d) !!}'
      :pelimpahan='{!! json_encode($pelimpahan) !!}'
      :pelimpahandetail='{!! json_encode($pelimpahandetail) !!}'
      :route='{!! json_encode($route) !!}'
      :api='{!! json_encode($api) !!}'
      :access='{!! json_encode($access) !!}'>
    </pelimpahan-detail>
  </div>
@stop