@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-6 text-left  ">
            <a href="{{ route('statistic') }}" id="title"><h4 class=" p-3"> الماكينة الانتخابية للمرشح النائب سامي فتفت</h4></a>
        </div>
        <!--<div class="col-sm-6 " style="text-align: end !important;">
            <img src="{{ asset('/img/logo.png')}}" style=" height:70px;width:auto;">
        </div>-->
    </div>
    <hr>
    <h3 class="text-center">
        <table id="table" class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th>اسم المرشح</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($candidates as $item)
                    <tr><td class="text-center vote-row" id="{{ $item->id }}" ><strong>{{$item->name}}</strong></td></tr>
                @endforeach
            </tbody>
        </table>
    </h3>

  
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
.done{
    background-color: greenyellow !important;
}
</style>  

<script>
    $(document).on('click','.vote-row',function(){

        var id = $(this).attr('id')
        $.ajax({
            type:'POST',
            url:'/electors/vote',
            data:{
                "_token": "{{ csrf_token() }}",
                id:id,
            },
            success:function(data){
                alert('تم الحفظ')
            }
        })
    })
</script>
@endsection
