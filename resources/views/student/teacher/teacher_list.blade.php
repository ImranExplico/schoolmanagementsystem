<?php 

use App\Models\Department;;

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
                    <h4>{{ get_phrase('Teachers') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Teachers') }}</a></li>
                    </ul>
                </div>
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
				        	<th>#</th>
				            <th>{{ get_phrase('Name') }}</th>
				            <th>{{ get_phrase('Department') }}</th>
				            <th>{{ get_phrase('Designation') }}</th>
				        </tr>
				    </thead>
				    <tbody>
				        @foreach($teachers as $teacher)
				        	<?php $department = Department::find($teacher['department_id']); ?>
				            <tr>
				            	<td scope="row"><p class="row-number">{{ $loop->index + 1 }}</p></td>
				                <td>{{ get_phrase($teacher['name']) }}</strong></td>
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