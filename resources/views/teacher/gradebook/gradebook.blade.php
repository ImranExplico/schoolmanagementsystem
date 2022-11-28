<?php 

use App\Models\User;
use App\Models\Subject;
use App\Models\Section;

$index = 0;

?>

@extends('teacher.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Gradebooks') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Academic') }}</a></li>
                        <li><a href="#">{{ get_phrase('Gradebooks') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <form method="GET" class="d-block ajaxForm" action="{{ route('teacher.gradebook') }}">
                <div class="row mt-3">

                    <div class="col-md-1"></div>

                    <div class="col-md-2">
                        <select name="class_id" id="class_id" class="form-select eForm-select eChoice-multiple-with-remove" required onchange="classWiseSection(this.value)">
                            <option value="">{{ get_phrase('Select a class') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $class_id == $class->id ?  'selected':'' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="section_id" id="section_id" class="form-select eForm-select eChoice-multiple-with-remove" required >
                            <?php if($class_id !=""){
                                $sections = Section::get()->where('class_id', $class_id); ?>
                                @foreach($sections as $section)
                                    <option value="{{ $section->id }}" {{ $section_id == $section->id ?  'selected':'' }}>{{ $section->name }}</option>
                                @endforeach
                            <?php } else { ?>
                                <option value="">{{ get_phrase('First select a class') }}</option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <select name="exam_category_id" id="exam_category_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                            <option value="">{{ get_phrase('Select a exam category') }}</option>
                            @foreach($exam_categories as $exam_category)
                                <option value="{{ $exam_category->id }}" {{ $exam_category_id == $exam_category->id ?  'selected':'' }}>{{ $exam_category->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <button class="btn btn-block btn-secondary" type="submit" id = "filter_routine">{{ get_phrase('Filter') }}</button>
                    </div>

                    <div class="card-body gradebook_content" id="gradebook_report">
                        @if(count($filter_list) > 0)
                            <table class="table eTable" id="gradebook_report">
                                <thead>
                                    <th>#</th>
                                    <th>{{ get_phrase('Student Name') }}</th>
                                    @foreach($subjects as $subject)
                                       <th>{{ $subject->name }}</th>
                                    @endforeach
                                </thead>
                                <tbody>
                                    @foreach($filter_list as $student)
                                    <?php $subject_list = json_decode($student->marks, true); ?>
                                    <tr>
                                        <td>{{ $index = $index+1 }}</td>
                                        <?php 
                                        $student_details = User::find($student->student_id);
                                        $info = json_decode($student_details->user_information);
                                        ?>
                                        <td>{{ $student_details->name }}</td>
                                        @foreach($subject_list as $key => $mark)
                                            <?php $subject_details = json_decode(Subject::find($key), true); ?>
                                           <td>{{ $mark }}</td>
                                        @endforeach
                                    <tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="empty_box center">
                                <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
                            </div>
                        @endif
                    </div>
                    
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">

  "use strict";


    function classWiseSection(classId) {
        let url = "{{ route('class_wise_sections', ['id' => ":classId"]) }}";
        url = url.replace(":classId", classId);
        $.ajax({
            url: url,
            success: function(response){
                $('#section_id').html(response);
            }
        });
    }

    function manage_gradebook(){
        var class_id = $('#class_id').val();
        var section_id = $('#section_id').val();
        var exam_category_id = $('#exam_category_id').val();
        if(class_id != "" && section_id != "" && exam_category_id != ""){
            show_student_list();
        }else{
            toastr.error("Please select all the fields correctly");
        }
    }

</script>
@endsection