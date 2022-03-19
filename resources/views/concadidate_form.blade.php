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
    @error('district')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col">
            <label for="name">اسم المرشح</label>
            <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="اسم الائحة" value="{{ isset($name) ? $name : old('name') }}">
          </div>
          <div class="col">
            <label for="district">البلدة/القرية</label>
            <input id="district" name="district" type="text" class="form-control @error('district') is-invalid @enderror" placeholder="البلدة/القرية"  value="{{ isset($district) ? $district : old('district') }}">
          </div>
        
        </div>
        <div class="row mt-2">
            <div class="col">
                <label for="log">السجل</label>
                <input id="log" name="log" type="text" class="form-control @error('log') is-invalid @enderror" placeholder="السجل"  value="{{ isset($log) ? $log : old('log') }}">
            </div>
            <div class="col">
                <label for="list_id">اللائحة</label>
                <div class="d-none">{{ $list_id = isset($list_id) ? $list_id : old('list_id')}}</div>
                <select name="list_id" id="list_id" class="form-control">
                    @foreach ($lists as $list)
                       
                        @if ($list->id == $list_id)
                        <option value="{{ $list->id}}" selected>{{ $list->name }}</option>
                        @else
                        <option value="{{ $list->id}}" >{{ $list->name }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label for="note">ملاحظة</label>
                <input id="note" name="note" type="text" class="form-control @error('note') is-invalid @enderror" placeholder="ملاحظة"  value="{{ isset($note) ? $note : old('note') }}">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-sm-12 text-left" style="text-align: left !important"><button type="submit" class="btn btn-primary">حفظ</button></div>
        </div>
      </form>
</div>
@endsection
