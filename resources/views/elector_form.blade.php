@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-2">
        <div class="col-sm-12 text-right">
           <h3>{{ $heading_title}}</h3>
        </div>
    </div>

    <form method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row mb-2">
          <div class="col">
            <label for="name">الاسم</label>
            <input id="name" name="name" type="text" class="form-control"  value="{{ $elector->firstname }}" disabled>
          </div>
          <div class="col">
            <label for="district">اسم العائلة</label>
            <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->lastname }}" disabled>
          </div>
          <div class="col">
            <label for="district">اسم الاب</label>
            <input id="district" name="district" type="text"  class="form-control"  value="{{ $elector->fathername }}" disabled>
          </div>
          <div class="col">
            <label for="district">اسم الام</label>
            <input id="district" name="district" type="text"  class="form-control"  value="{{ $elector->mothername }}" disabled>
          </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <label for="name">تاريخ الميلاد</label>
                <input id="name" name="name" type="text"  class="form-control"  value="{{ $elector->date_of_birth }}" disabled>
            </div>
            <div class="col">
                <label for="district">الجنس</label>
                <input id="district" name="district" type="text"  class="form-control"  value="{{ $elector->sex }}" disabled>
            </div>
            <div class="col">
                <label for="district">المذهب</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->doctrine }}" disabled>
            </div>
            <div class="col">
                <label for="district">رقم السجل</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->log }}" disabled>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label for="district">مذهب السجل</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->log_doctrine }}" disabled>
            </div>
            <div class="col">
                <label for="district">القرية/ البلدة</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->district }}" disabled>
            </div>
            <div class="col">
                <label for="district">القضاء</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->zone }}" disabled>
            </div>
            <div class="col">
                <label for="district">المحافظة</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->state }}" disabled>
            </div>
        </div>
        <div class="row mb-2">
            <div class="col">
                <label for="district">الدائرة الإنتخابية</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ $elector->election_zone }}" disabled>
            </div>
            <div class="col">
                <label for="district">بلد الإقتراع</label>
                <input id="district" name="district" type="text" class="form-control"  value="{{ empty($elector->election_country) ? 'لبنان' : $elector->election_country }}" disabled>
            </div>
            <div class="col">
                <label for="telephone">رقم الهاتف</label>
                <input id="telephone" name="telephone" type="text" class="form-control"  value="{{ $elector->telephone }}" >
            </div>
            <div class="col">
                <label for="telephone">اقترع العام الماضي</label>
                <div class="d-none">{{ $elected_last_election = $elector->elected_last_election }}</div>
                <select class="form-control" id="elected_last_election" name="elected_last_election">
                    @if ($elected_last_election == 1)
                        <option value="1" selected>Yes</option>
                    @else
                        <option value="1">Yes</option>
                    @endif
                    @if ($elected_last_election == 0)
                        <option value="0" selected>No</option>
                    @else
                        <option value="0">No</option>
                    @endif
                </select>
            </div>
            
        </div>
        <div class="row mb-2">
            <div class="col">
                <label for="election_center"> مركز الاقتراع</label>
                <input id="election_center" name="election_center" type="text" class="form-control"  value="{{ $elector->election_center }}">
            </div>
            <div class="col">
                <label for="ballot_pen">القلم </label>
                <input id="ballot_pen" name="ballot_pen" type="text" class="form-control"  value="{{ $elector->ballot_pen }}" >
            </div>

           
            <div class="col">
                <label for="telephone">اقترع العام الماضي</label>
                <div class="d-none">{{ $elected_last_election = $elector->elected_last_election }}</div>
                <select class="form-control" id="elected_last_election" name="elected_last_election">
                    @if ($elected_last_election == 1)
                        <option value="1" selected>Yes</option>
                    @else
                        <option value="1">Yes</option>
                    @endif
                    @if ($elected_last_election == 0)
                        <option value="0" selected>No</option>
                    @else
                        <option value="0">No</option>
                    @endif
                </select>
            </div>
            <div class="col">
                <label for="telephone">الصوت التفضيلي</label>
                <div class="d-none">{{ $preferential_vote = $elector->preferential_vote }}</div>

                <select class="form-control" id="preferential_vote" name="preferential_vote" >
                    <option value="">--none--</option>
                    @if ($preferential_vote == 'none')
                        <option value="none" selected>غير معروف</option>
                    @else
                        <option value="none">غير معروف</option>
                    @endif

                    @if ($preferential_vote == 'will_no')
                        <option value="will_no" selected>لن يقترغ</option>
                    @else
                        <option value="will_no">لن يقترع</option>
                    @endif

                    @foreach ($concadidates as $item)
                        @if ($preferential_vote == $item->id)
                            <option value="{{ $item->id}}" selected>{{ $item->name}}</option>
                        @else
                            <option value="{{ $item->id}}">{{ $item->name}}</option>
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
@endsection
