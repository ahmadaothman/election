@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-12 text-right">
           <h3>{{ $heading_title}}</h3>
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
            <label for="name">اسم الائحة</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="اسم الائحة" value="{{ isset($name) ? $name : old('name') }}">
          </div>
          <div class="col">
            <label for="name">الجهة السياسية</label>
            <input id="side" name="side" type="text" class="form-control @error('side') is-invalid @enderror" placeholder="الجهة السياسية"  value="{{ isset($side) ? $side : old('side') }}">
          </div>
        
        </div>
        <div class="row mt-2">
            <div class="col-sm-12 text-left" style="text-align: left !important"><button type="submit" class="btn btn-primary">حفظ</button></div>
        </div>
      </form>
</div>
@endsection
