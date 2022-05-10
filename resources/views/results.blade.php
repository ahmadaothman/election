@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 text-left  ">
            <a href="{{ route('statistic') }}" id="title"><h4 class=" p-3"> الماكينة الانتخابية للمرشح النائب سامي فتفت</h4></a>
        </div>
        <div class="col-sm-6 " style="text-align: end !important;">
            <img src="{{ asset('/img/logo.png')}}" style=" height:70px;width:auto;">
        </div>
    </div>
    <hr>
    <h3 class="text-center">نتائج فرز الاصوات في الضنية</h3>

  
</div>



<style>
      #title {
  font-family: "Cairo", sans-serif !important;
}
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
