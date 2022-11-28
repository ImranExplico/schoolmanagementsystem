@extends('superadmin.navigation')
   
@section('content')

<?php use App\Models\School; ?>

<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Confirmed Request') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Subscriptions') }}</a></li>
                        <li><a href="#">{{ get_phrase('Confirmed Payment') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
			@if(count($payment_histories) > 0)
				<table class="table eTable">
					<thead>
	                    <th>#</th>
	                    <th>{{ get_phrase('School Name') }}</th>
	                    <th>{{ get_phrase('Price') }}</th>
	                    <th>{{ get_phrase('Payment For') }}</th>
	                    <th>{{ get_phrase('Payment Document') }}</th>
	                    <th>{{ get_phrase('Status') }}</th>
	                    <th class="text-center">{{ get_phrase('Action') }}</th>
	                </thead>
	                <tbody>
	                	@foreach($payment_histories as $payment_history)
	                	<?php $school = School::find($payment_history->school_id); ?>
	                		<tr>
	                			<td>{{ $loop->index + 1 }}</td>
	                			<td><strong>{{ $school->title }}</strong></td>
	                			<td>{{ currency($payment_history->amount) }}</td>
	                			<td>{{ ucwords($payment_history->payment_type) }}</td>
	                			<td>
	                				<strong>
	                					<a href="{{ asset('public/assets/uploads/offline_payment/'.$payment_history->document_image) }}" download> {{ $payment_history->document_image }} </a>
	                				</strong>
	                			</td>
	                			<td><span class="eBadge ebg-success">{{ get_phrase('Approve') }}</span></td>
	                			<td class="text-start">
	                				<div class="adminTable-action">
	                                    <button
	                                      type="button"
	                                      class="eBtn eBtn-black dropdown-toggle table-action-btn-2"
	                                      data-bs-toggle="dropdown"
	                                      aria-expanded="false"
	                                    >
	                                      {{ get_phrase('Actions') }}
	                                    </button>
	                                    <ul
	                                      class="dropdown-menu dropdown-menu-end eDropdown-menu-2 eDropdown-table-action"
	                                    >
	                                      <li>
	                                        <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('superadmin.subscription.delete', ['id' => $payment_history->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
	                                      </li>
	                                    </ul>
	                                </div>
			                    </td>
	                		</tr>
	                	@endforeach
	                </tbody>
				</table>
			@else
				<div class="empty_box center">
                    <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
                    <br>
                </div>
			@endif
		</div>
	</div>
</div>
@endsection