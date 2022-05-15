@extends('layouts.app')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-sm-6 text-left  ">
      <a href="{{ route('results') }}" id="title"><h4 class=" p-3"> الماكينة الانتخابية للمرشح النائب سامي فتفت</h4></a>
    </div>
    <div class="col-sm-6 " style="text-align: end !important;">
      <img src="{{ asset('/img/logo.png')}}" style=" height:70px;width:auto;">
    </div>
  </div>
  <hr>
  
  <div class="row m-2">

      <div class="col-md-4 border rounded  text-center bg-white border-dark">
         <h4 class="m-3">اجمالي الناخبين في الضنية</h4>
         <strong class="h4 bg-primary px-4 text-white rounded py-1 " id="total_voters"></strong>
         <h4 class="m-3">اجمالي المقترعين في الضنية</h4>
         <strong class="h4 bg-primary px-4 text-white rounded py-1 " id="total_voted"></strong>
         <h4 class="m-3">نسبة الاقتراع في الضنية</h4>
         <strong class="h4 bg-primary px-4 text-white rounded py-1" id="voted_percentage"></strong>
       
      </div>

      <div class="col-md-4">
          <div id="total_by_sex"></div>
          <hr>
        <div id="total_by_doctrine" class="m-2"></div>
      </div>

      <div class="col-md-4 bg-white rounded border border-dark">
        <div class="row">
          <div class="col-sm-12 text-center h5 m-0 py-2">نسب الاقتراع في قرى الضنية</div>
          <hr >
        </div>
        <div id="total_by_towns" class="m-2 row">
        </div>
      </div>
    
      <div class="col-md-12">
          <div id="total_by_district"></div>
      </div>
      <div class="col-md-12">
          <div id="total_by_country"></div>
      </div>
  
  </div>
</div>

<script type="text/javascript">
  getData();
  function getData(){
    // total voters
    $.ajax({
      type:'get',
      url:'/statistic/voters',
      success:function(data){
        $('#total_voters').html(data.total_electors.toLocaleString())
        $('#total_voted').html(data.total_voted.toLocaleString())
        $('#voted_percentage').html(data.voted_percentage + '%')
        
      }
    })

    // total by doctrine
    $('#total_by_doctrine').dxPieChart({
          type: 'doughnut',
          palette: 'Soft Pastel',
           size: {
            height: 350,
          },
          dataSource:'/statistic/doctrine',
          title: {
            text:'المقترعين حسب المذهب',
            font:{
              size:18
            }
          },
          tooltip: {
            enabled: true,
            customizeTooltip(arg) {
              return {
                text: `${arg.valueText}% - ${arg.argumentText}`,
              };
            },
          },
          legend: {
            horizontalAlignment: 'right',
            verticalAlignment: 'top',
            margin: 0,
   
          },
          onPointClick(arg) {
            arg.target.select();
          },
          export: {
            enabled: false,
          },
          series: [{
            argumentField: 'doctrine',
            valueField: 'voted_percentage_from_total_voters',
            label: {
              visible: true,
              customizeText: function (v) {
                console.log(v)
                    return v.value + '% - ' ;
              },
              connector: {
                visible: true,
              },
            },
          }],
        });
    // total by towns
    $.ajax({
      type:'get',
      url:'/statistic/towns',
      success:function(data){
        $('#total_by_towns').empty()
        $.each(data,function(k,v){
    
          html = '<div class="col-sm-3 h-6" style="margin-bottom:5px"><strong data-toggle="tooltip" data-placement="top" title="النسبة على صعيد الضنية '+v.voted_percentage_from_total_voters+' % ">'+v.town +'</strong></div>'
          html += '<div class="col-sm-3 h-6 p-0">'+ v.total_voted + ' - ' + v.voted_percentage + '%' +'</div>'

          $('#total_by_towns').append(html);
        })
        $(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
        
      }
    })

    // total by sex
    $('#total_by_sex').dxChart({
      dataSource:'/statistic/sex',
      size: {
        height: 200,
      },
      commonSeriesSettings: {
          argumentField: 'state',
          type: 'bar',
       
          hoverMode: 'allArgumentPoints',
          selectionMode: 'allArgumentPoints',
          label: {
            visible: true,
            format: {
              type: 'fixedPoint',
              precision: 0,
            },
            font:{
              size:22,
              family:"Cairo"
            }
          },
        },
        title: {
          text:'نسبة المقترعين حسب الجنس',
          font:{
            size:16
          }
        },
        series: {
          argumentField: 'sex',
          valueField: 'total',
          name:' ',
          type: 'bar',
          color: '#0000FF',
        },
    });
  

  }

  setInterval(function() {
                  window.location.reload();
                }, 300000);
</script>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cairo">

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
