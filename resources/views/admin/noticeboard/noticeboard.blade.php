@extends('admin.navigation')
   
@section('content')

<style>

  body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #top {
    background: #eee;
    border-bottom: 1px solid #ddd;
    padding: 0 10px;
    line-height: 40px;
    font-size: 12px;
  }

  #calendar {
    max-width: 1100px;
    margin: 40px auto;
    padding: 0 10px;
  }

</style>

<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ get_phrase('Noticeboard calendar') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="row mb-3">
                <div class="expense_add">
                    <a href="javascript:;" class="btn btn-outline-primary float-end m-1" data-bs-toggle="tooltip" onclick="rightModal('{{ route('admin.noticeboard.open_modal') }}', '{{ get_phrase('Add New Notice') }}')"><i class="bi bi-plus"></i>{{ get_phrase('Add New Notice') }}</a>
                </div>
            </div>
            <div class="row">
                <div class="col-12 noticeboard_content">
                    @include('admin.noticeboard.list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script type="text/javascript">
  
    "use strict";

    document.addEventListener('DOMContentLoaded', function() {
        var initialLocaleCode = 'en';
        var localeSelectorEl = document.getElementById('locale-selector');
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
          headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
          },
          initialDate: '{{ date('Y-m-d') }}',
          navLinks: true, // can click day/week names to navigate views
          selectable: true,
          selectMirror: true,
          select: function(arg) {
            rightModal('{{ route('admin.noticeboard.open_modal') }}', 'Add New Notice')
          },
          eventClick: function(arg) {
            let url = "{{ route('admin.edit.noticeboard', ['id' => ":arg.event.id"]) }}";
            url = url.replace(":arg.event.id", arg.event.id);
            rightModal(url, 'Update notice')

            // if (confirm('Are you sure you want to delete this event?')) {
            //   arg.event.remove()
            // }
          },
          editable: false,
          dayMaxEvents: true, // allow "more" link when too many events
          events: <?php echo $events; ?>,
        });

        calendar.render();

        // build the locale selector's options
        calendar.getAvailableLocaleCodes().forEach(function(localeCode) {
          var optionEl = document.createElement('option');
          optionEl.value = localeCode;
          optionEl.selected = localeCode == initialLocaleCode;
          optionEl.innerText = localeCode;
          localeSelectorEl.appendChild(optionEl);
        });

        // when the selected option changes, dynamically change the calendar option
        localeSelectorEl.addEventListener('change', function() {
          if (this.value) {
            calendar.setOption('locale', this.value);
          }
        });

    });

</script>
