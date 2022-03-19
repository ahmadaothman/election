@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-12 text-left">
            <a  href="/ElectoralLists/add" class="btn btn-outline-primary">اضافة لائحة</a>
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
                <th>اسم اللائحة</th>
                <th>الجهة السياسية</th>
                <th>عدد المرشحين</th>
                <th>الحاصل المتوقع </th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($lists as $list)
                <tr>
                    <td>{{ $list->name}}</td>
                    <td>{{ $list->side}}</td>
                    <td>{{ $list->ConcadidatesCount}}</td>
                    <td>{{ $list->TotalVotes}}</td>
                    <td>
                        <a href="/ElectoralLists/edit/{{ $list->id}}">تعديل</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
