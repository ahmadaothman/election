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
    <h3 class="text-center">اجمالي الاصوات المحتسبة <strong id="total"></strong></h3>
    <div class="row m-2">
      <div class="col-sm-6 border p-2 text-center">
        <h3 class="mb-3">الاصوات التي نالها المرشح النائب سامي فتفت</h3>
        <strong class="h4 bg-primary px-4 text-white rounded py-1 " id="total_sami">0</strong>
        <hr>
        <div class="row" id="sami_restuls_by_disteict">

        </div>
       
      </div>
      <div class="col-sm-6 border p-2">
        <div id="total_for_each"></div>
      </div>
    </div>
  
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
<script type="text/javascript">
  function getData(){
    $.ajax({
      url:'/resultsApi',
      type:'get',
      success:function(data){
        $('#total_sami').html(data.total_sami + ' - ' + data.total_sami_percantage + ' %')
        $('#sami_restuls_by_disteict').empty();

        $('#total').html(data.total_votes);

        $.each(data.sami_district_total,function(k,v){
          html = '<div class="col-sm-4 mb-2"><strong>';
          html += '<table class="table">'
          html += '<tr>'
          html += '<td>' + v.district + '</td>'
          html += '<td>' + v.count + '</td>'
          html += '</tr>'
          html += '</table>'
          html += '</strong</div>'
        
          $('#sami_restuls_by_disteict').append(html )
        })
      }
    });

    ///resultsApiDataForEachConcadidate
    $('#total_for_each').dxChart({
        rotated: false,
        dataSource: '/resultsApiDataForEachConcadidate',
        series: {
        label: {
            visible: true,
            backgroundColor: '#c18e92',
        },
        size:{
          height: 300,
          width: 200
        },
        color: '#79cac4',
        type: 'bar',
        argumentField: 'name',
        valueField: 'count',
        },
        title: 'الناخبين حسب البلدات',
        argumentAxis: {
        label: {
            customizeText() {
            return `${this.valueText}`;
            },
        },
        },
        valueAxis: {
        tick: {
            visible: false,
        },
        label: {
            visible: false,
        },
        },
        export: {
        enabled: true,
        },
        legend: {
        visible: false,
        },
       
    });
  }

  getData()

  setInterval(function() {
                  window.location.reload();
                }, 300000);
</script>
@endsection
