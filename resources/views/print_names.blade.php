@extends('layouts.app')

@section('content')
<div class="container-fluid">


    
    

 

    <table class="table table-hover table-sm table-striped w-100">
        <thead>
            <tr>
                <th colspan="8" class="text-center border"><h3>الماكينة الانتخابية للمرشح النائب سامي فتفت</h3></th>
            </tr>
            <tr>
                <th colspan="2" class="text-left">البلدة: <strong>{{$district}}</strong></th>
                <th class="text-left">مركز الاقتراع: <strong>{{$election_center}}</strong></th>
                <th colspan="2" class="text-left">القلم: <strong>{{$ballot_pen}}</strong></th>
                <th colspan="2" class="text-end" style="text-align: end !important">اجمالي الناخبين: <strong>{{$count}}</strong></th>
            </tr>

            <tr>
                <th class="text-center">الرمز</th>
                <th>الاسم</th>
                <th>اسم الام</th>
                <th class="text-center">رقم السجل</th>
                <th>تاريخ الميلاد</th>
                <th class="text-center">مذهب السجل</th>
                <th class="text-center">بلد الاقتراع</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($electors as $name)
                <tr>
                    <td class="text-center"><strong>{{$name->virtual_number}}</strong></td>
                    <td>{{$name->fullName}}</td>
                    <td>{{$name->mothername}}</td>
                    <td class="text-center">{{$name->log}}</td>
                    <td>{{$name->date_of_birth}}</td>
                    <td class="text-center">{{$name->log_doctrine}}</td>
                    <td class="text-center">{{$name->election_country}}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot class="d-none">
            <tr>
                <td ><div id="content">
                    <div id="pageFooter">Page </div>
                  </div></td>
            </tr>
        </tfoot>
    </table>
    
       

    </form>
</div>


<style>
    
    .navbar.navbar-expand-md.navbar-light.bg-white.shadow-sm{
        display: none !important;
    }
    @media print {
    .head {
        position: static !important;
    }
}


@page { counter-increment: page }
@page:first { counter-reset: page 9 }

@media print{@page {size: landscape}}

#content {
    display: table;
}

#pageFooter {
    display: table-footer-group;
}

#pageFooter:after {
    content: counter(page);
    counter-increment: page;
}


</style>
<script type="text/javascript">
    window.print();
</script>
@endsection
