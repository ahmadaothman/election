@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-12 text-right">
           <h3>المندوب</h3>
        </div>
    </div>

    @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    @error('side')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">

          <div class="col">
            <label for="name">اسم المندوب</label>
            <input id="name" name="name" type="text" required class="form-control @error('name') is-invalid @enderror" placeholder="اسم المندوب" value="{{ isset($name) ? $name : old('name') }}">
          </div>

          <div class="col">
            <label for="name">البريد الالكتروني</label>
            <input id="email" name="email" type="text" required class="form-control @error('email') is-invalid @enderror" placeholder="البريد الالكتروني"  value="{{ isset($email) ? $email : old('email') }}">
          </div>

          <div class="col">
            <label for="name">الهاتف</label>
            <input id="telephone" name="telephone" required type="text" class="form-control @error('telephone') is-invalid @enderror" placeholder="الهاتف"  value="{{ isset($telephone) ? $telephone : old('telephone') }}">
          </div>

          <div class="col">
            <label for="name">البلدة</label>
            <div class="d-none">
                {{ isset($district) ? $d = $district : $d = old('district') }}
            </div>
            <select class="form-control" id="district" name="district" >
                @foreach ($districts as $district)
                   @if ($district->district == $d)
                   <option value="{{$district->district}}" selected>{{$district->district}}</option>
                   @else
                   <option value="{{$district->district}}" >{{$district->district}}</option>
                   @endif
                @endforeach
            </select>


        </div>
        
        </div>
        <div class="row mt-2">
            <div class="col-sm-12 text-left" style="text-align: left !important"><button type="submit" class="btn btn-primary">حفظ</button></div>
        </div>
      </form>
</div>
<script type="text/javascript">
    
    $('#district').on('change', function() {
        var district = this.value;
        $.ajax({
            url:"{{route('getDistrictCenters')}}",
            type:'get',
            data:{
                district:district
            },
            success:function(data){

            }
        })
    });
</script>
@endsection
