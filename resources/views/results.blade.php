@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12 text-center  ">
      <a href="{{ route('statistic') }}" id="title"><h3 class="border p-3"> الماكينة الانتخابية للمرشح النائب سامي فتفت</h3></a>
    </div>
    <div class="col-md-12 text-center  ">
       <h3 class=" p-3">نتائج فرز الاصوات في الضنية</h3>
    </div>
  </div>

  
</div>



<style>
  .navbar.navbar-expand-md.navbar-light.bg-white.shadow-sm{
    display: none !important;
  }
  body {
    height: 95vh !important;
}
#title{
  text-decoration: none;
  color: black !important;
}
</style>  
@endsection
