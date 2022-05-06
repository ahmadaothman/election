@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-6 ">
           <h3>طباعة</h3>
        </div>
       
    </div>

    
    <form method="POST" enctype="multipart/form-data" target="_blank">
        @csrf
        <div class="row">

          <div class="col-4 mb-4">
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

        <div class="col-4 mb-4">
            <label for="name">مركز الاقتراع</label>
            <select class="form-control" id="election_center" name="election_center">
            </select>
        </div>

        <div class="col-4 mb-4">
            <label for="name">قلم الاقتراع</label>
            <select class="form-control" id="ballot_pen" name="ballot_pen">
            </select>
        </div>
      

    </div>

    <div class="row mt-2">
        <div class="col-sm-12 text-left" style="text-align: left !important"><button type="submit" class="btn btn-primary">طباعة</button></div>
    </div>

       

    </form>
</div>
<script type="text/javascript">
    // get election centers
    getElectionCenters($('#district').val())

    $('#district').on('change', function() {
        var district = this.value;
        getElectionCenters(district)
    });

    var election_center = "{{ isset($election_center) ? $d = $election_center : $d = old('election_center') }}"
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
                    if(v.election_center == election_center){
                        $('#election_center').append($('<option>',{
                            value:v.election_center,
                            text:v.election_center
                        }).attr('selected',true))
                    }else{
                        $('#election_center').append($('<option>',{
                            value:v.election_center,
                            text:v.election_center
                        }))
                    }
                })
                getBallotPen($('#election_center').val())
            }
        })
    }
    // get ballot pen

    $('#election_center').on('change', function() {
        var election_center = this.value;
        getBallotPen(election_center)
    });

    var ballot_pen = "{{ isset($ballot_pen) ? $d = $ballot_pen : $d = old('ballot_pen') }}"

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
                    if(v.ballot_pen == ballot_pen){
                        $('#ballot_pen').append($('<option>',{
                            value:v.ballot_pen,
                            text:v.ballot_pen
                        }).attr('selected',true))
                    }else{
                        $('#ballot_pen').append($('<option>',{
                            value:v.ballot_pen,
                            text:v.ballot_pen
                        }))
                    }
                })
            }
        })
    }

</script>
@endsection
