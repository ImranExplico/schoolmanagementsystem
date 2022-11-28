<div class="eForm-layouts">
    <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.session.update', ['id' => $session->id]) }}">
         @csrf 
        <div class="form-row">
            <div class="fpb-7">
                <label for="session_title" class="eForm-label">{{ get_phrase('Session title') }}</label>
                <input type="number" min="0" class="form-control eForm-control" value="{{ $session->session_title }}" id="session_title" name="session_title" required>
            </div>
            <div class="fpb-7">
                <label for="starting_date" class="eForm-label">{{ get_phrase('Starting date') }}<span class="required">*</span></label>
                <input type="text" class="form-control eForm-control inputDate" id="starting_date" name="starting_date" value="{{ date('m/d/Y', strtotime($session->starting_date)) }}" required/>
            </div>
            <div class="fpb-7">
                <label for="ending_date" class="eForm-label">{{ get_phrase('Ending date') }}<span class="required">*</span></label>
                <input type="text" class="form-control eForm-control inputDate" id="ending_date" name="ending_date" value="{{ date('m/d/Y', strtotime($session->ending_date)) }}" required/>
            </div>
            <div class="fpb-7">
                <label for="ending_date" class="eForm-label">{{ get_phrase('Description') }}<span class="required">*</span></label>
                <textarea class="form-control eForm-control" id="description" name = "description" rows="5" required>{{ $session->description }}</textarea>
            </div>
            <div class="fpb-7 pt-2">
                <button class="btn-form" type="submit">{{ get_phrase('Update session') }}</button>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">

  "use strict";

    $(function () {
      $('.inputDate').daterangepicker(
        {
            singleDatePicker: true,
            showDropdowns: true,
            minYear: parseInt(moment().format("YYYY"), 10),
            maxYear: parseInt(moment().add(15, 'Y').format("YYYY"), 10),
        },
        function (start, end, label) {
          var years = moment().diff(start, "years");
        }
      );
    });
</script>