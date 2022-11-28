@extends('superadmin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Create Discount') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Discount') }}</a></li>
                        <li><a href="#">{{ get_phrase('Edit Discount') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="title">
                <h3>{{ get_phrase('Discount Form') }}</h3>
                <p>{{get_phrase('Discount Information')}}</p>
            </div>
            <div class="eMain">
                <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.discount.update') }}">
                    @csrf 
                    <input type="hidden" name="id" value="{{ $discount->id }}">
                    <div class="row">
                        <div class="col-md-6 pb-3">
                            <div class="eForm-layouts">
                                <div class="fpb-7">
                                    <label for="title" class="eForm-label">{{ get_phrase('Discount Title') }}</label>
                                    <input type="text" class="form-control eForm-control" id="title" name="title" value="{{$discount->title}}" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="discount_type" class="eForm-label">{{ get_phrase('Discount Type') }}</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="discount_type" value="1" class="present" @if($discount->discount_type == "1") checked @endif required> {{ get_phrase('Percentage') }} &nbsp;
                                        <input type="radio" name="discount_type" value="2" class="absent" @if($discount->discount_type == "2") checked @endif required> {{ get_phrase('Value') }}
                                    </div>
                                </div>
                                <div class="fpb-7">
                                    <label for="discount_rule" class="eForm-label">{{ get_phrase('Single/Multiple') }}</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="discount_rule" value="1" class="present" @if($discount->discount_rule == "1") checked @endif required> {{ get_phrase('Single') }} &nbsp;
                                        <input type="radio" name="discount_rule" value="2" class="absent" @if($discount->discount_rule == "2") checked @endif required> {{ get_phrase('Multiple') }}
                                    </div>
                                </div>
                                <div class="fpb-7">
                                    <label for="discount_amount" class="eForm-label">{{ get_phrase('Discount Amount') }}</label>
                                    <input type="number" class="form-control eForm-control" id="discount_amount" name="discount_amount" value="{{$discount->discount_amount}}" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="frequency" class="eForm-label">{{ get_phrase('Frequency') }}</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="frequency" value="1" class="present" @if($discount->frequency == "1") checked @endif required> {{ get_phrase('Monthly') }} &nbsp;
                                        <input type="radio" name="frequency" value="2" class="absent" @if($discount->frequency == "2") checked @endif required> {{ get_phrase('Once') }}
                                    </div>
                                </div>
                                <div class="fpb-7">
                                    <label for="status" class="eForm-label">{{ get_phrase('Status') }}</label>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" name="status" value="1" class="present" @if($discount->status == "1") checked @endif required> {{ get_phrase('Active') }} &nbsp;
                                        <input type="radio" name="status" value="0" class="absent" @if($discount->status == "0") checked @endif required> {{ get_phrase('Inactive') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="mx-2 bt-1 b-light-gray pb-3">
                        </div>
                        <div class="title border-0">
                            <h3>{{ get_phrase('Discount Condition') }}</h3>
                        </div>
                        <div class="col-md-2 pb-3">
                        </div>
                        <div class="col-md-6 pb-3">
                            <div class="eForm-layouts">
                                <div class="fpb-7">
                                    <label for="enrolment_year" class="eForm-label">{{ get_phrase('Enrolment Year')}}</label>
                                    <select name="enrolment_year" id="enrolment_year" class="form-select eForm-select">
                                        <option value="">{{ get_phrase('Select Enrolment Year') }}</option>
                                        @foreach($enrolment_years as $enrolment_year)
                                            <option @if($enrolment_year->id == $discount->enrolment_year) selected @endif value="{{$enrolment_year->id}}">{{$enrolment_year->session_title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fpb-7">
                                    <label for="branch_id" class="eForm-label">{{ get_phrase('Select Branch')}}</label>
                                    <select name="branches[]" id="branch_id" multiple="multiple" class="form-select eForm-select eChoice-multiple-with-remove" data-placeholder="{{ get_phrase('Select Branch')}}">
                                        <option value="">{{ get_phrase('Select Branch') }}</option>
                                        @foreach($branches as $branch)
                                            <option @if(in_array($branch->id, $selected_branches)) selected @endif value="{{$branch->id}}">{{$branch->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fpb-7">
                                    <label for="level_id" class="eForm-label">{{ get_phrase('Select Level')}}</label>
                                    <select name="levels[]" id="level_id" multiple="multiple" class="form-select eForm-select eChoice-multiple-with-remove" data-placeholder="{{ get_phrase('Select Level')}}">
                                        <option value="">{{ get_phrase('Select Level') }}</option>
                                        @foreach($levels as $level)
                                            <option @if(in_array($level->id, $selected_levels)) selected @endif value="{{$level->id}}">{{$level->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="fpb-7">
                                    <label for="course_id" class="eForm-label">{{ get_phrase('Select Course')}}</label>
                                    <select name="courses[]" id="course_id" multiple="multiple" class="form-select eForm-select eChoice-multiple-with-remove" data-placeholder="{{ get_phrase('Select Course')}}">
                                        <option value="">{{ get_phrase('Select Course') }}</option>
                                        @foreach($courses as $course)
                                            <option @if(in_array($course->id, $selected_courses)) selected @endif value="{{$course->id}}">{{$course->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="mx-2 bt-1 b-light-gray pb-3">
                        </div>
                        <div class="title border-0">
                            <h3>{{ get_phrase('Discount Commencement Type') }}</h3>
                        </div>
                        <div class="col-md-2 pb-3">
                        </div>
                        <div class="col-md-6 pb-3">
                            <div class="eForm-layouts">
                                <div class="fpb-7">
                                    <input type="checkbox" name="earliest_commencement_date_check" id="earliest_commencement_date" @if($discount->earliest_commencement_start_date) checked @endif value="1">
                                    <label for="earliest_commencement_date" class="eForm-label">{{ get_phrase('Earliest Commencement Date')}}</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control daterange-commencement eForm-control" name="earliest_commencement_date" value="@if($discount->earliest_commencement_start_date) {{ date('m/d/Y', strtotime($discount->earliest_commencement_start_date)).' - '.date('m/d/Y', strtotime($discount->earliest_commencement_end_date)) }} @endif" />
                                    </div>
                                    <!--
                                    <div class="row"> 
                                        <div class="col-md-6">
                                            <input type="text" class="form-control eForm-control inputDate" id="earliest_commencement_start_date" name="earliest_commencement_start_date" value="{{ date('m/d/Y') }}" required="">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control eForm-control inputDate" id="earliest_commencement_end_date" name="earliest_commencement_end_date" value="{{ date('m/d/Y') }}" required="">
                                        </div>
                                    </div>-->
                                </div>
                                <div class="fpb-7">
                                    <input type="checkbox" name="course_commencement_date_check" id="course_commencement_date" @if($discount->course_commencement_start_date) checked @endif value="1">
                                    <label for="course_commencement_date" class="eForm-label">{{ get_phrase('Course Commencement Date')}}</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control daterange-commencement eForm-control" name="course_commencement_date" value="@if($discount->course_commencement_start_date) {{ date('m/d/Y', strtotime($discount->course_commencement_start_date)).' - '.date('m/d/Y', strtotime($discount->course_commencement_end_date)) }} @endif" />
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control eForm-control inputDate" id="course_commencement_start_date" name="course_commencement_start_date" value="{{ date('m/d/Y') }}" required="">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control eForm-control inputDate" id="course_commencement_end_date" name="course_commencement_end_date" value="{{ date('m/d/Y') }}" required="">
                                        </div>
                                    </div> -->
                                </div>
                                <div class="fpb-7">
                                    <input type="checkbox" name="invoice_commencement_date_check" id="invoice_commencement_date" @if($discount->invoice_commencement_start_date) checked @endif  value="1">
                                    <label for="invoice_commencement_date" class="eForm-label">{{ get_phrase('Invoice Date')}}</label>
                                    <div class="col-md-12">
                                        <input type="text" class="form-control daterange-commencement eForm-control" name="invoice_commencement_date" value="@if($discount->invoice_commencement_start_date){{ date('m/d/Y', strtotime($discount->invoice_commencement_start_date)).' - '.date('m/d/Y', strtotime($discount->invoice_commencement_end_date)) }} @endif " />
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-6">
                                            <input type="text" class="form-control eForm-control inputDate" id="invoice_commencement_start_date" name="invoice_commencement_start_date" value="{{ date('m/d/Y') }}" required="">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" class="form-control eForm-control inputDate" id="invoice_commencement_end_date" name="invoice_commencement_end_date" value="{{ date('m/d/Y') }}" required="">
                                        </div>
                                    </div> -->
                                </div>
                                <div class="fpb-7">
                                    <label for="remarks" class="eForm-label">{{ get_phrase('Remarks')}}</label>
                                    <textarea class="form-control eForm-control" id="remarks" name="remarks" rows="5" required>{{ $discount->remarks }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="pt-2">
                            <button type="submit" class="btn-form float-end">{{ get_phrase('Submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">

    "use strict";

    $(document).ready(function () {
        $(".eChoice-multiple-with-remove").select2({
            placeholder: function(){
                $(this).data('placeholder');
            }
        });
    });

    $(function () {
      $('.daterange-commencement').daterangepicker(
        {
            autoUpdateInput: false,
            opens: "right",
        },
        function (start, end, label) {
          console.log(
            "A new date selection was made: " +
              start.format("YYYY-MM-DD") +
              " to " +
              end.format("YYYY-MM-DD")
          );
        }
      );
    });
</script>
@endpush