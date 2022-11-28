@extends('parent.navigation')

@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('View Marks') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Examination') }}</a></li>
                        <li><a href="#">{{ get_phrase('Marks') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="row mt-3">

                <div class="col-md-4"></div>

                <div class="col-md-3">
                    <select name="student" id="student_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                        <option value="">{{ get_phrase('Select student') }}</option>
                        @foreach($student_data as $key => $each_student)
                        <option value="{{ $each_student['user_id'] }}">{{ $each_student['name'] }}</option>
                        @endforeach


                    </select>
                </div>

                <div class="col-md-3">
                    <button class="btn btn-block btn-secondary" onclick="filter_class_routine()" >{{ get_phrase('Filter') }}</button>
                </div>

                <div class="card-body class_routine_content">
                    <div class="empty_box center">
                        <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
                        <br>
                        {{ get_phrase('No data found') }}
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

<script type="text/javascript">

  "use strict";


    function filter_class_routine(){
        var student_id = $('#student_id').val();

        if(student_id != "" ){
            getFilteredClassRoutine();
        }else{
            toastr.error('{{ get_phrase('Please select student') }}');
        }
    }

    var getFilteredClassRoutine = function() {
        var student_id = $('#student_id').val();

        if(student_id != ""){
            let url = "{{ route('parent.marks_list') }}";
            $.ajax({
                url: url,
                headers: {
                    'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content')
                },
                data: {student_id : student_id},
                success: function(response){
                    $('.class_routine_content').html(response);
                }
            });
        }
    }

</script>
