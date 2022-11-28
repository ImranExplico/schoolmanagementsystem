@extends('admin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Events') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Back Office') }}</a></li>
                        <li><a href="#">{{ get_phrase('Events') }}</a></li>
                    </ul>
                </div>
                <div class="export-btn-area">
                    <a href="javascript:;" class="export_btn" onclick="rightModal('{{ route('admin.events.open_modal') }}', '{{ get_phrase('Create Event') }}')"><i class="bi bi-plus"></i>{{ get_phrase('Create Event') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
		    @if(count($events) > 0)
		    	<table id="basic-datatable" class="table eTable">
		    		<thead>
		    			<tr>
		    				<th scope="col">#</th>
							<th>{{ get_phrase('Event title') }}</th>
							<th>{{ get_phrase('Date') }}</th>
							<th>{{ get_phrase('Status') }}</th>
							<th class="text-center">{{ get_phrase('Options') }}</th>
						</tr>
		    		</thead>
		    		<tbody>
		    			@foreach($events as $event)
		    				<tr>
		    					<td>{{ $loop->index + 1 }}</td>
								<td>{{ $event['title'] }}</td>
								<td>{{ date('D, d M Y', $event['timestamp']) }}</td>
								<td>
									<?php if ($event['status']): ?>
										<span class="eBadge ebg-success">{{ get_phrase('Active') }}</span>
									<?php else: ?>
										<span class="eBadge ebg-danger">{{ get_phrase('Inactive') }}</span>
									<?php endif; ?>
								</td>
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
                                            <a class="dropdown-item" href="javascript:;" onclick="rightModal('{{ route('admin.edit.event', ['id' => $event->id]) }}', '{{ get_phrase('Update event') }}')">{{ get_phrase('Edit') }}</a>
                                          </li>
                                          <li>
                                            <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('admin.events.delete', ['id' => $event->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
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
			        <span class="">{{ get_phrase('No data found') }}</span>
			    </div>
		    @endif
		</div>
	</div>
</div>
@endsection