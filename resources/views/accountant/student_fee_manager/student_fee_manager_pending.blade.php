@extends('accountant.navigation')

@section('content')
<div class="mainSection-title">
    <div class="row">
	    <div class="col-12">
	        <div
	          class="d-flex justify-content-between align-items-center flex-wrap gr-15"
	        >
				<div class="d-flex flex-column">
					<h4>{{ get_phrase('Offline payment Request') }}</h4>
					<ul class="d-flex align-items-center eBreadcrumb-2">
						<li><a href="#">{{ get_phrase('Home') }}</a></li>
						<li><a href="#">{{ get_phrase('Accounting') }}</a></li>
						<li><a href="#">{{ get_phrase('Offline payment Request') }}</a></li>
					</ul>
				</div>
	        </div>
	    </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
    	<div class="eSection-wrap">
            <form method="GET" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('accountant.offline_payment_pending') }}">
            	<div class="row">
                    <div class="row justify-content-md-center">
                    	<div class="col-xl-3 mb-3">
                            <input type="text" class="form-control eForm-control" name="eDateRange"
                                value="{{ date('m/d/Y', $date_from).' - '.date('m/d/Y', $date_to) }}" />
                        </div>
						<div class="col-xl-2 mb-3">
							@if(isset($selected_class) && $selected_class != "")
					            <div class="form-group">
									<select name="class" id="class_id" class="form-select eForm-select eChoice-multiple-with-remove">
										<option value="all">{{ get_phrase('All class') }}</option>
										@foreach($classes as $class)
											<option value="{{ $class['id'] }}" {{ $class['id'] == $selected_class ?  'selected':'' }}>{{ $class['name'] }}</option>
										@endforeach
									</select>
					            </div>
					        @else
					        	<div class="form-group">
									<select name="class" id="class_id" class="form-select eForm-select eChoice-multiple-with-remove">
										<option value="all">{{ get_phrase('All class') }}</option>
										<?php foreach($classes as $class){
										  ?>
										  <option value="{{ $class['id'] }}">{{ $class['name'] }}</option>
										<?php } ?>
									</select>
					            </div>
					        @endif
				        </div>

				        <div class="col-xl-2 mb-3">
				            <button type="submit" class="btn btn-icon btn-secondary form-control">{{ get_phrase('Filter') }}</button>
				        </div>
            		</div>
            	</div>
            </form>
            <?php
        	if($selected_class == ""){
			    $sel_class = 'all';
			} else {
			    $sel_class = $selected_class;
			}

			if($date_from == "") {
			    $date_from = strtotime(date('d-M-Y', strtotime(' -30 day')));
			} else {
			    $date_from = strtotime(date('d-M-Y', $date_from));
			}

			if($date_to == "") {
			    $date_to = strtotime(date('d-M-Y'));
			} else {
			    $date_to = strtotime(date('d-M-Y', $date_to));
			}

			if($selected_status == ""){
			    $sel_status = 'all';
			} else {
			    $sel_status = $selected_status;
			}
        	?>
            <div class="invoice_content" id="student_fee_manager">
	            @include('accountant.student_fee_manager.list_pending')
	        </div>
		</div>
	</div>
</div>
@endsection
