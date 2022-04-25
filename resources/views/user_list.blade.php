@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-12 text-left">
            <a  href="/Users/add" class="btn btn-outline-primary">اضافة مندوب</a>
        </div>
    </div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    <table class="table table-sm table-hover table-bordered table-striped">
        <thead>
            <tr>
                <th>اسم المندوب</th>
                <th>البلدة</th>
                <th>البريد الالكتروني</th>
                <th>الهاتف</th>
                <th>الصفة</th>
            </tr>
        </thead>
        <tbody>
       
        </tbody>
    </table>
</div>
@endsection
