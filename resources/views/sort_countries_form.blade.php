@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-6 ">
           <h3>فرز نتائج المغتربين</h3>
        </div>
      
    </div>

  

    <form method="POST" enctype="multipart/form-data" oninput='repassword.setCustomValidity(repassword.value != password.value ? "Passwords do not match." : "")'>
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
        $.ajax({
            type:'post',
            url:'/saveCountryResult',
            data:{
                "_token": "{{ csrf_token() }}",
                country:$('#country').val(),
                candidate:$('#candidate').val()
            },
            success:function(data){

            }
        })
    })
</script>

@endsection
