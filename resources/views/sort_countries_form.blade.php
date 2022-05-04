@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-6 ">
           <h3>فرز نتائج المغتربين</h3>
        </div>
      
    </div>

  

    <form method="POST" enctype="multipart/form-data" >
        @csrf
      
        <div class="row">
            <div class="col-sm-1">
                <label for="country">الدولة</label>
            </div>
            <div class="col-sm-3">
                <select class="form-control " id="country">
                    @foreach ($countries as $country)
                        <option value="{{ $country->country}}">{{ $country->country}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-1">
                <label for="candidate">المرشح</label>
            </div>
            <div class="col-sm-3">
                <select class="form-control " id="candidate">
                    @foreach ($candidates as $candidate)
                        <option value="{{ $candidate->id}}">{{ $candidate->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" id="save" class="btn btn-primary">حفظ</button>
            </div>
        </div>
   

    </div>

    </form>
</div>

<script type="text/javascript">
    $('#save').on('click',function(){
        $("#save").prop('disabled', true);
        $("#save").html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>')
        $.ajax({
            type:'post',
            url:'/saveCountryResult',
            data:{
                "_token": "{{ csrf_token() }}",
                country:$('#country').val(),
                candidate:$('#candidate').val()
            },
            success:function(data){
                $("#save").html('حفظ')
                $("#save").prop('disabled', false);
            }
        })
    })
</script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $('#country,#candidate').select2();
</script>
@endsection
