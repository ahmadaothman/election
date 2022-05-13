@extends('layouts.app')

@section('content')
<div class="container-fluid">
  
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
         <!--   <th>السجل</th>-->
        </tr>
    </thead>
    <tbody id="tbody">
    </tbody>
</table>
 
</div>

<div class="modal" tabindex="-1" role="dialog" id="modal">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">حفظ</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#modal').modal('hide')">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="modal_id" />
          <strong>الرقم:  </strong><strong id="modal_number"></strong>
          <br>
          <strong>الاسم:  </strong><strong id="modal_name"></strong>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onclick="saveData()">حفظ</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#modal').modal('hide')">الغاء</button>
        </div>
      </div>
    </div>
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
                var html = '';
                if(v.done == "1"){
                    html = '<tr id="e-'+v.id+'" class="elector-row done" >'
                }else{
                    html = '<tr id="e-'+v.id+'" class="elector-row" >'
                }
                html += '<input type="hidden" class="id" value="'+v.id+'">'
                html += '<td class="number">'
                html += v.virtual_number
                html += '</td>'
                html += '<td class="name">'
                html += v.FullName
                html += '</td>'
                html += '<td>'
                html += v.mothername
                html += '</td>'
                html += '<td>'
               /* html += v.log
                html += '</td>'*/
                html += '</tr>'

                $('#tbody').append(html)
            })
        }
    })


    $(document).on("click", ".elector-row", function() {

        $('#modal_id').val($('#'+$(this).attr('id') + ' .id').val())
        $('#modal_number').html($('#'+$(this).attr('id') + ' .number').html())
        $('#modal_name').html($('#'+$(this).attr('id') + ' .name').html())
        $('#modal').modal('show')

    });
}

function saveData(){
    $.ajax({
        type:'POST',
        url:'/electors/save_mobile_data',
        data:{
            "_token": "{{ csrf_token() }}",
            id:$('#modal_id').val()
        },
        success:function(){
            $('#modal').modal('hide')
            $('#e-'+$('#modal_id').val()).addClass('done')
        }
    })
}

</script>

<style>
    .navbar.navbar-expand-md.navbar-light.bg-white.shadow-sm{
        display: none !important;
    }
    .done{
        background-color: greenyellow !important;
    }
</style>

<script>
$(document).ready(function(){
    $("#search").on("keyup", function() {
    var value = $(this).val().toLowerCase();
    $("#table  tr").filter(function() {
        $(this).toggle($(this.children[1]).text().toLowerCase() == value && value != '')
        if(value == ''){
            $(this).show()
        }
    });
    });
});
</script>

@endsection
