@extends('layouts.app')

@section('content')
<div class="container">
  
<div class="row">
    <div class="col-sm-4 "><strong>البلدة: {{ $user->district}} </strong></div>
    <div class="col-sm-4"><strong>المركز: {{ $user->election_center}}</strong></div>
    <div class="col-sm-4"><strong>القلم: {{ $user->ballot_pen}}</strong></div>
</div>
<hr>
<div class="row">
    <div class="col-sm-12">
        <input type="text" class="form-control" id="search" />
    </div>
</div>
<table class="table table-striped table-hover table-sm" id="table">
    <thead>
        <tr>
            <th>الرمز</th>
            <th>الاسم</th>
            <th>الام</th>
            <th>السجل</th>
        </tr>
    </thead>
    <tbody id="tbody">
    </tbody>
</table>
 
</div>

<script type="text/javascript">
getData()

function getData(){
    $('#tbody').empty();

    $.ajax({
        type:'get',
        url:'/electors/data_by_user',
        success:function(data){
            $.each(data,function(k,v){
                html = '<tr id="e-'+v.id+'" class="elector-row">'
                html += '<td>'
                html += v.virtual_number
                html += '</td>'
                html += '<td>'
                html += v.FullName
                html += '</td>'
                html += '<td>'
                html += v.mothername
                html += '</td>'
                html += '<td>'
                html += v.log
                html += '</td>'
                html += '</tr>'

                $('#tbody').append(html)
            })
        }
    })


    $(document).on("click", ".elector-row", function() {
        alert($(this).attr('id'))
    });
}



</script>

<style>
    .navbar.navbar-expand-md.navbar-light.bg-white.shadow-sm{
        display: none !important;
    }
</style>

<script>
$(document).ready(function(){
    $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#table tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
    });
    });
});
</script>

@endsection
