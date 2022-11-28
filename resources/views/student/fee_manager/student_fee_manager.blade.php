<?php 

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

?>

@extends('student.navigation')

@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Fee Manager') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Fee Manager') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <form method="GET" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('student.fee_manager.list') }}">
            	<div class="row">
                    <div class="row justify-content-md-center">
                    	@if($date_from != "" && $date_to !="")
                            <div class="col-xl-3 mb-3">
                                <input type="text" class="form-control eForm-control" name="eDateRange"
                                    value="{{ date('m/d/Y', $date_from).' - '.date('m/d/Y', $date_to) }}" />
                            </div>
                        @else
		                    <div class="col-xl-3 mb-3">
                                <input type="text" class="form-control eForm-control" name="eDateRange"
                                    value="{{ date('m/d/Y', strtotime(' -30 day')).' - '.date('m/d/Y') }}" />
                            </div>
		                @endif

				        <div class="col-xl-2 mb-3">
				        	@if(isset($selected_status) && $selected_status != "")
					            <div class="form-group">
					              <select name="status" id="status" class="form-select eForm-select eChoice-multiple-with-remove">
					                <option value="all" {{ $selected_status == "all" ?  'selected':'' }}>{{ get_phrase('All status') }}</option>
					                <option value="paid" {{ $selected_status == "paid" ?  'selected':'' }}>{{ get_phrase('Paid') }}</option>
					                <option value="unpaid" {{ $selected_status == "unpaid" ?  'selected':'' }}>{{ get_phrase('Unpaid') }}</option>
					              </select>
					            </div>
					        @else
						        <div class="form-group">
					              <select name="status" id="status" class="form-select eForm-select eChoice-multiple-with-remove">
					                <option value="all">{{ get_phrase('All status') }}</option>
					                <option value="paid">{{ get_phrase('Paid') }}</option>
					                <option value="unpaid">{{ get_phrase('Unpaid') }}</option>
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
            <div class="invoice_content" id="student_fee_manager">
            	@if(count($invoices) > 0)
	            	@include('student.fee_manager.list')
	            @else
	            	<div class="empty_box center">
	                    <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
	                    <br>
	                    <span class="">{{ get_phrase('No data found') }}</span>
	                </div>
	            @endif
	        </div>
		</div>
	</div>
</div>
@endsection
