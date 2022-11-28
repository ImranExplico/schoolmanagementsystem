<?php
use App\Models\User;
use App\Models\Role;
use App\Http\Controllers\CommonController;
?>

@extends('parent.navigation')

@section('content')

<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">

                <h4>
                    {{ get_phrase('Attendance Report') }}
                </h4>

            </div>
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="row mt-3 d-print-none">

                <div class="col-md-2 mb-1"></div>
                <div class="col-md-2 mb-1">
                    <select name="month" id="month" class="form-select eForm-select eChoice-multiple-with-remove" required>
                        <option value="">
                            {{ get_phrase('select a month') }}
                        </option>
                        <option value="Jan" {{ date('M')=='Jan' ? 'selected':'' }}>
                            {{ get_phrase('January') }}
                        </option>
                        <option value="Feb" {{ date('M')=='Feb' ? 'selected':'' }}>
                            {{ get_phrase('February') }}
                        </option>
                        <option value="Mar" {{ date('M')=='Mar' ? 'selected':'' }}>
                            {{ get_phrase('March') }}
                        </option>
                        <option value="Apr" {{ date('M')=='Apr' ? 'selected':'' }}>
                            {{ get_phrase('April') }}
                        </option>
                        <option value="May" {{ date('M')=='May' ? 'selected':'' }}>
                            {{ get_phrase('May') }}
                        </option>
                        <option value="Jun" {{ date('M')=='Jun' ? 'selected':'' }}>
                            {{ get_phrase('June') }}
                        </option>
                        <option value="Jul" {{ date('M')=='Jul' ? 'selected':'' }}>
                            {{ get_phrase('July') }}
                        </option>
                        <option value="Aug" {{ date('M')=='Aug' ? 'selected':'' }}>
                            {{ get_phrase('August') }}
                        </option>
                        <option value="Sep" {{ date('M')=='Sep' ? 'selected':'' }}>
                            {{ get_phrase('September') }}
                        </option>
                        <option value="Oct" {{ date('M')=='Oct' ? 'selected':'' }}>
                            {{ get_phrase('October') }}
                        </option>
                        <option value="Nov" {{ date('M')=='Nov' ? 'selected':'' }}>
                            {{ get_phrase('November') }}
                        </option>
                        <option value="Dec" {{ date('M')=='Dec' ? 'selected':'' }}>
                            {{ get_phrase('December') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-2 mb-1">
                    <select name="year" id="year" class="form-select eForm-select eChoice-multiple-with-remove" required>
                        <option value="">
                            {{ get_phrase('Select a year') }}
                        </option>
                        <?php for($year = 2015; $year <= date('Y'); $year++){ ?>
                        <option value="{{ $year }}" {{ date('Y')==$year? 'selected':'' }}>
                            {{ $year }}
                        </option>
                        <?php } ?>

                    </select>
                </div>



                    <div class="col-md-2 mb-1">
                        <select name="student" id="student_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                            <option value="">{{ get_phrase('Select student') }}</option>
                            @foreach($student_data as $key => $each_student)
                            <option value="{{ $each_student['user_id'] }}">{{ $each_student['name'] }}</option>
                            @endforeach


                    </select>
                </div>


                <div class="col-md-2">
                    <button class="btn btn-block btn-secondary" onclick="filter_attendance()">

                        {{ get_phrase('Filter') }}
                    </button>
                </div>
            </div>
            <div class="card-body attendance_content">

                <div class="empty_box text-center center">
                    <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
                    <br>
                    <span class="">
                        {{ get_phrase('Search Attendance Report') }}
                    </span>
                </div>


            </div>


        </div>
    </div>
</div>

<script type="text/javascript">
    
    "use strict";

    function filter_attendance(){
        var month = $('#month').val();
        var year = $('#year').val();
        var student_id = $('#student_id').val();
        if(student_id != "" && month != "" && year != ""){
            getHrDailtyAttendance();
        }else{
            toastr.error('{{ get_phrase('please_select_in_all_fields !') }}');
        }
    }

  var getHrDailtyAttendance = function () {


    var month = $('#month').val();
    var year = $('#year').val();
    var student_id = $('#student_id').val();

    if(student_id != "" && month != "" && year != ""){
        $.ajax({

            url: '{{ route('parent.attendance_filter') }}',
            data: {month : month, year : year, student_id : student_id},
            success: function(response){
                $('.attendance_content').html(response);


            }
        });
    }
  }


</script>

@endsection
