<?php

use App\Models\Department;;

?>
@extends('parent.navigation')

@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4>{{ get_phrase('Teaches') }}</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap pb-2">
        	@if(count($teachers) > 0)
				<table id="basic-datatable" class="table eTable">
				    <thead>
				        <tr>
				            <th>{{ get_phrase('Name') }}</th>
				            <th>{{ get_phrase('Department') }}</th>
				            <th>{{ get_phrase('Designation') }}</th>
				        </tr>
				    </thead>
				    <tbody>
				        @foreach($teachers as $teacher)
				        	<?php $department = Department::find($teacher['department_id']); ?>
				            <tr>
				                <td>{{ get_phrase($teacher['name']) }}</td>
				                <td>{{ get_phrase($department['name']) }}</td>
				                <td>{{ $teacher['designation'] }}</td>
				            </tr>
				        @endforeach
				    </tbody>
				</table>
				{!! $teachers->links() !!}
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
