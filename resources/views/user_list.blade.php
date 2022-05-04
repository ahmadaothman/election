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
                <th>الهاتف</th>
                <th>البريد الالكتروني</th>
                <th>البلدة</th>
                <th>مركز الاقتراع</th>
                <th>القلم</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{$user->name}}</td>
                    <td>{{$user->telephone}}</td>
                    <td>{{$user->email}}</td>
                    <td>{{$user->district}}</td>
                    <td>{{$user->election_center}}</td>
                    <td>{{$user->ballot_pen}}</td>
                    <td><a href="/Users/edit/{{$user->id}}">تعديل</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
