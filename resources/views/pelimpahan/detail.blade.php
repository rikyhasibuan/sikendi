@extends('layouts.app')
@section('content')
  <div id="app">
    <pelimpahan-detail
      :pelimpahan='{!! json_encode($pelimpahan) !!}'
      :pelimpahandetail='{!! json_encode($pelimpahandetail) !!}'
      :route='{!! json_encode($route) !!}'
      :api='{!! json_encode($api) !!}'
      :send_api='{!! json_encode($send_api) !!}'
      :print_api='{!! json_encode($print_api) !!}'
      :access='{!! json_encode($access) !!}'>
    </pelimpahan-detail>
  </div>
@stop
