@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-6 ">
           <h3>الفرز</h3>
        </div>
    </div>

    

    <form >
        @csrf
        <div class="row">

          

          <div class="col-2 mb-4">
            <label for="name">البلدة</label>

            <select class="form-control" id="district" name="district" >
                @foreach ($districts as $district)
                <option value="{{$district->district}}" >{{$district->district}}</option>
                @endforeach
            </select>


            </div>

        <div class="col-2 mb-4">
            <label for="name">مركز الاقتراع</label>
            <select class="form-control" id="election_center" name="election_center">
            </select>
        </div>

        <div class="col-1 mb-4">
            <label for="name">قلم الاقتراع</label>
            <select class="form-control" id="ballot_pen" name="ballot_pen">
            </select>
        </div>

  

    </div>

    
    <h2 id="tbody">
    </h2>
    

    </form>
</div>
<script type="text/javascript">
    // get election centers
    getElectionCenters($('#district').val())

    $('#district').on('change', function() {
        var district = this.value;
        getElectionCenters(district)
        
    });

    function getElectionCenters(district){
   
        $('#election_center').empty();

        $.ajax({
            url:"{{route('getDistrictCenters')}}",
            type:'get',
            data:{
                district:district
            },
            success:function(data){
                $.each(data,function(k,v){
                    $('#election_center').append($('<option>',{
                        value:v.election_center,
                        text:v.election_center
                    }))
                })
                getBallotPen($('#election_center').val())
            }
        })
    }
    // get ballot pen

    $('#election_center').on('change', function() {
        var election_center = this.value;
        getBallotPen(election_center)
        getData()
    });

    $('#ballot_pen').on('change', function() {
        getData()
    });

    function getBallotPen(election_center){

        $('#ballot_pen').empty();

        $.ajax({
            url:"{{route('getCenterBallotPens')}}",
            type:'get',
            data:{
                election_center:election_center,
                district:$('#district').val()
            },
            success:function(data){
                $.each(data,function(k,v){
                    $('#ballot_pen').append($('<option>',{
                        value:v.ballot_pen,
                        text:v.ballot_pen
                    }))
                })
                getData()
            }
        })
    }

  

    getData()
        
    function getData(){
        $.ajax({
        type:'post',
        url:'/getTotalByPen',
        data:{
            "_token": "{{ csrf_token() }}",
            district:$('#district').val(),
            center:$('#election_center').val(),
            ballot_pen:$('#ballot_pen').val(),
        },
        success:function(data){
            $('#tbody').empty()
            $('#tbody').html(' عدد الاصوات ' + data)
        }
    });
    }
</script>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script type="text/javascript">
    $('select').select2();
</script>
@endsection
