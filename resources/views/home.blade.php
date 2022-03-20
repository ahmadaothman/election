@extends('layouts.app')

@section('content')
<div class="container ">
  <div class="row">
    <div class="col-md-12 text-center">
      <h1> الماكنة الانتخابية للنائب سامي فتفت</h1>
    </div>
  </div>
  <hr>
  <div class="row">
    <div class="col-md-12 text-center">
      <h2>اجمالي الناخبين {{ $count }} ناخب</h2>
    </div>
  </div>
  <hr>
  <div class="row">
      <div class="col-md-6">
          <div id="total_by_doctrine"></div>
      </div>
      <div class="col-md-6">
          <div id="total_by_sex"></div>
      </div>
    
      <div class="col-md-12">
          <div id="total_by_district"></div>
      </div>
      <div class="col-md-12">
          <div id="total_by_country"></div>
      </div>
  
  </div>
</div>
<!--data by district-->
<script type="text/javascript">
    $(() => {
    $('#total_by_district').dxChart({
        rotated: true,
        dataSource: '/data/getTotalByDistrict',
        series: {
        label: {
            visible: true,
            backgroundColor: '#c18e92',
        },
        color: '#79cac4',
        type: 'bar',
        argumentField: 'البلدة',
        valueField: 'المجموع',
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
        size: {
            height: 1000,
        }
    });
    });
</script>
<!--data by doctrine-->
<script type="text/javascript">
$(() => {   
  $('#total_by_doctrine').dxPieChart({
    type: 'doughnut',
    palette: 'Soft Pastel',
    dataSource:'/data/getTotalBydoctrine',
    title: 'الناخبين حسب المذهب',
   
    tooltip: {
      enabled: true,
      customizeTooltip(arg) {
        return {
          text: `${arg.valueText} - ${arg.argumentText}`,
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
      enabled: true,
    },
    series: [{
      argumentField: 'المذهب',
      valueField: 'المجموع',
      label: {
        visible: true,
        connector: {
          visible: true,
        },
      },
    }],
  });
});
</script>
<!--data by sex-->
<script type="text/javascript">
$(() => {   
  $('#total_by_sex').dxPieChart({
    type: 'doughnut',
    palette: 'Soft Pastel',
    dataSource:'/data/getTotalBySex',
    title: 'الناخبين حسب الجنس',
    tooltip: {
      enabled: true,
      customizeTooltip(arg) {
        return {
          text: `${arg.valueText} - ${arg.argumentText}`,
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
      enabled: true,
    },
    series: [{
      argumentField: 'الجنس',
      valueField: 'المجموع',
      label: {
        visible: true,
        connector: {
          visible: true,
        },
      },
    }],
  });
});
</script>
<!--data by country-->
<script type="text/javascript">
$(() => {
  $('#total_by_country').dxChart({
    rotated: true,
    dataSource:'/data/getTotalByCountry',
    title: 'الناخبين حسب الدولة',
    size: {
        height: 2000,
    },
    series: {
      label: {
        visible: true,
        backgroundColor: '#c18e92',
      },
      color: '#79cac4',
      type: 'bar',
      argumentField: 'الدولة',
      valueField: 'المجموع',
    },
    argumentAxis: {
      label: {
        customizeText() {
          return ` ${this.valueText}`;
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
});

$(() => {
  $('#').dxPieChart({
    palette: 'bright',
    dataSource:'/data/getTotalByCountry',
    title: 'الناخبين حسب الدولة',
    size:{
        height:1000
    },
    legend: {
      orientation: 'horizontal',
      itemTextPosition: 'right',
      horizontalAlignment: 'center',
      verticalAlignment: 'bottom',
      columnCount: 4,
    },
    export: {
      enabled: true,
    },
    series: [{
      argumentField: 'الدولة',
      valueField: 'المجموع',
      label: {
        visible: true,
        font: {
          size: 16,
        },
        connector: {
          visible: true,
          width: 0.5,
        },
        position: 'columns',
        customizeText(arg) {
          return `${arg.valueText} (${arg.argumentText})`;
        },
      },
    }],
  });
});
</script>
@endsection
