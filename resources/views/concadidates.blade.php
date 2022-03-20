@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-12 text-left">
            <a href="/Concadidates/add" class="btn btn-outline-primary">اضافة مرشح</a>
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
                <th>اسم المرشح</th>
                <th>القرية/البلدة</th>
                <th>رقم السجل</th>
                <th>اللائحة</th>
                <th>الاصوات التفضيلية المتوقعة</th>
                <th>ملاحظة</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($concadidates as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->district }}</td>
                    <td>{{ $item->log }}</td>
                    <td>{{ is_null($item->List->name) ? '' : $item->List->name }}</td>
                    <td>{{ $item->TotalVotes }}</td>
                    <td>{{ $item->note }}</td>
                    <td><a href="/Concadidates/edit/{{ $item->id }}">تعديل</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
