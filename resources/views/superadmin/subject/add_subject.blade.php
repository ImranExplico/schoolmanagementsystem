<div class="eoff-form">
    <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.create.subject') }}">
         @csrf 
        <div class="form-row">
            <div class="fpb-7">
                <label for="name" class="eForm-label">{{ get_phrase('Class Number') }}</label>
                <input type="text" class="form-control eForm-control" id="class_number" name = "class_number" placeholder="Provide class number" required>
            </div>
            <div class="fpb-7">
                <label for="school_id" class="eForm-label">{{ get_phrase('Branch') }}</label>
                <select  name="school_id" id="school_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                    <option value="">--Select Branch--</option> 
                    @foreach($schools as $school)
                        <option value="{{ $school->id }}">{{ $school->title }}</option>
                    @endforeach
                </select>
            </div>
            
            <div class="fpb-7">
                <label for="class_room_id" class="eForm-label">{{ get_phrase('Class Room') }}</label>
                <select  name="class_room_id" id="class_room_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                    <option value="">--Select Classroom--</option>
                </select>
            </div>
            <div class="fpb-7">
                <label for="class_id" class="eForm-label">{{ get_phrase('Level') }}</label>
                <select name="class_id" id="class_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                    <option value="">{{ get_phrase('Select a level') }}</option>
                     @foreach($classes as $class)
                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fpb-7">
                <label for="department_id" class="eForm-label">{{ get_phrase('Course') }}</label>
                <select  name="department_id" id="department_id" class="form-select eForm-select eChoice-multiple-with-remove" required>
                    
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fpb-7">
                <label for="enrollment_type" class="eForm-label">{{ get_phrase('Enrollment Type') }}</label>
                <select  name="enrollment_type" id="enrollment_type" class="form-select eForm-select eChoice-multiple-with-remove" required>
                    <option value="">--Select Enrollment Type--</option> 
                    @foreach($arrEnrollmentTypes as $_enrollmentType)
                        <option  value="{{ $_enrollmentType }}">{{ $_enrollmentType }}</option>
                    @endforeach
                </select>
            </div>

            <div class="fpb-7">
                <label for="f2f_enrollment_size" class="eForm-label">{{ get_phrase('F2F Enrollment Size') }}</label>
                <input type="text"  readonly class="form-control eForm-control" id="f2f_enrollment_size" name = "f2f_enrollment_size" placeholder="Provide class size (f2f)" required>
            </div>
            <div class="fpb-7">
                <label for="online_enrollment_size" class="eForm-label">{{ get_phrase('Online Enrollment Size') }}</label>
                <input type="text" class="form-control eForm-control" id="online_enrollment_size" name = "online_enrollment_size" placeholder="Provide class size (online)" required>
            </div>
            <div class="fpb-7">
                <label for="steam" class="eForm-label">{{ get_phrase('Stream') }}</label>
                <input type="text" class="form-control eForm-control" id="stream" name = "stream" placeholder="Provide stream" >
            </div>


            <div class="fpb-7">
                <label for="enrollmentr_year" class="eForm-label">{{ get_phrase('Enrollment Year') }}</label>
                <select name="enrollment_year" id="enrollment_year" class="form-select eForm-select eChoice-multiple-with-remove">
                    @for ($enrollment_year=2022 ; $enrollment_year<2024; $enrollment_year++)
                        <option value="{{$enrollment_year}}">{{$enrollment_year}}</option>
                    @endfor
                </select>
            </div>
           
            <div class="fpb-7">
                <label for="start_date" class="eForm-label">{{ get_phrase('Start-End Date') }}</label>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control eForm-control" id="start_date" name = "start_date" placeholder="Start Date" value="" autocomplete="off"  required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control eForm-control" id="end_date" name = "end_date" placeholder="End Date" value="" autocomplete="off" required>
                    </div>
                </div>
            </div>

            <div class="fpb-7">
                <label for="start_time" class="eForm-label">{{ get_phrase('Start-End Time') }}</label>
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control eForm-control" id="start_time" name = "start_time" placeholder="Start Time" value="" autocomplete="off"  required>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control eForm-control" id="end_time" name = "end_time" placeholder="End Time" value="" autocomplete="off"  required>
                    </div>
                </div>
            </div>

            <div class="fpb-7">
                <label for="day" class="eForm-label">{{ get_phrase('Day') }}</label>
                <select multiple name="day[]" id="day" class="form-select eForm-select eChoice-multiple-with-remove">
                    @foreach ($arrWeekDays as $weekday )
                        <option value="{{$weekday}}">{{ get_phrase($weekday) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fpb-7">
                <label for="remarks" class="eForm-label">{{ get_phrase('Remarks') }}</label>
                <input type="text" class="form-control eForm-control" id="remarks" name = "remarks" placeholder="Remarks" required>
            </div>

            <div class="fpb-7">
                <label for="status" class="eForm-label">{{ get_phrase('Course Status') }}</label>
                <select name="status" id="status" class="form-select eForm-select eChoice-multiple-with-remove">
                    <option value="active">{{ get_phrase('Active') }}</option>
                    <option value="inactive">{{ get_phrase('Inactive') }}</option>
                </select>
            </div>


            <div class="fpb-7 pt-2">
                <button class="btn-form" type="submit">{{ get_phrase('Create Class') }}</button>
            </div>
        </div>
    </form>
</div>

<script type="text/javascript">
    "use strict";
    $(document).ready(function () {
      $(".eChoice-multiple-with-remove").select2();
      //documentation: https://xdsoft.net/jqplugins/datetimepicker/
      $('#start_date').datetimepicker({
        format: "d-m-Y", 
        timepicker:false 
      });
      $('#end_date').datetimepicker({
        format: "d-m-Y",
        timepicker:false
      });

      //documentation: https://timepicker.co/
      $('#start_time').timepicker({
            timeFormat: 'h:mm p',
            interval: 5,
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });
      $('#end_time').timepicker({
            timeFormat: 'h:mm p',
            interval: 5,
            dynamic: false,
            dropdown: true,
            scrollbar: true
        });


      $(document).on("change", "#school_id", function(e){
        var school_id=$(this).val();
        if(school_id==""){
            $('#class_room_id option').remove();
                $('#class_room_id').append($("<option></option>")
                        .attr("value", '')
                        .text("--Select Classroom--")
                    ); 
            return false;
        }
        var options = {
                path: '/superadmin/classrooms/'+school_id,
                method: 'GET',
                data: $('#registerForm').serializeObject()
            };

            callAjax(options, handleClassRoomsResponse);  
        });

        function handleClassRoomsResponse(response){
            console.log(response);
            $('#class_room_id option').remove();
            $('#class_room_id').append($("<option></option>")
                                .attr("value", '')
                                .attr("capacity", '')
                                .text("--Select Classroom--")
                            ); 
            $.each(response, function(index, option){
                
                $('#class_room_id').append($("<option></option>")
                                .attr("value", option.id)
                                .attr("capacity", option.capacity)
                                .text(option.name)
                            ); 
            });
        }


        $(document).on("change", "#class_room_id", function(e){
            var selected_capacity=$("#class_room_id option:selected").attr('capacity');
            //if($("#f2f_enrollment_size").val()==""){
                $("#f2f_enrollment_size").val(selected_capacity);
            //}
        });

    });
</script>