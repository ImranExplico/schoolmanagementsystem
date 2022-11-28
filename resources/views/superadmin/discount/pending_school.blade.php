@extends('superadmin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Pending School List') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Schools') }}</a></li>
                        <li><a href="#">{{ get_phrase('Pending List') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap pb-2">
            @if(count($schools) > 0)
        	<table class="table eTable">
        		<thead>
        			<th>#</th>
                    <th>{{ get_phrase('Name') }}</th>
                    <th>{{ get_phrase('Address') }}</th>
                    <th>{{ get_phrase('Phone') }}</th>
                    <th>{{ get_phrase('Info') }}</th>
                    <th>{{ get_phrase('Status') }}</th>
                    <th class="text-center">{{ get_phrase('Action') }}</th>
        		</thead>
        		<tbody>
                	@foreach($schools as $school)
                		<tr>
                			<td>{{ $loop->index + 1 }}</td>
                			<td>
                				<strong>{{ $school->title }}</strong>
                			</td>
                			<td>{{ $school->address }}</td>
                			<td>{{ $school->phone }}</td>
                			<td>{{ $school->school_info }}</td>
                			<td>
                				<span class="eBadge ebg-info">{{ get_phrase('Pending') }}</span>
                			</td>
                			<td>
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
                                        <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('superadmin.school.approve', ['id' => $school->id]) }}', 'undefined');">{{ get_phrase('Approve') }}</a>
                                      </li>
                                      <li>
                                        <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('superadmin.school.delete', ['id' => $school->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
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