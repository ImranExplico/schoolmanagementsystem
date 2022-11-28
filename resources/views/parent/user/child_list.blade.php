<?php

use App\Models\Department;;
use App\Models\School;

?>
@extends('parent.navigation')

@section('content')
<div class="mainSection-title">
    <div class="row">
      <div class="col-12">
        <div
          class="d-flex justify-content-between align-items-center flex-wrap gr-15"
        >
          <div class="d-flex flex-column">
            <h4>{{ get_phrase('Student List') }}</h4>
            <ul class="d-flex align-items-center eBreadcrumb-2">
              <li><a href="#">{{ get_phrase('Home') }}</a></li>
              <li><a href="#">{{ get_phrase('Users') }}</a></li>
              <li><a href="#">{{ get_phrase('Student List') }}</a></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap pb-2">
        	@if(count($students) > 0)
				<table id="basic-datatable" class="table eTable eTable-2">
				    <thead>
				        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ get_phrase('Name') }}</th>
                            <th scope="col">{{ get_phrase('Email') }}</th>
                            <th scope="col">{{ get_phrase('User Info') }}</th>
                            <th scope="col">{{ get_phrase('Id card') }}</th>
				        </tr>
				    </thead>
				    <tbody>
                        @foreach($students as $student)
                            <?php
                            $info = json_decode($student->user_information);
                            if(!empty($info->photo)){
                                $user_image = 'uploads/user-images/'.$info->photo;
                            }else{
                                $user_image = 'uploads/user-images/thumbnail.png';
                            }
                            $school = School::find($student->school_id)->title;
                            ?>
                            <tr>
                                <th scope="row">
                                    <p class="row-number">{{ $loop->index + 1 }}</p>
                                </th>
                                <td>
                                    <div class="dAdmin_profile d-flex align-items-center min-w-200px">
                                        <div class="dAdmin_profile_img">
                                          <img
                                            class="img-fluid"
                                            width="50"
                                            height="50"
                                            src="{{ asset('public/assets') }}/{{ $user_image }}"
                                          />
                                        </div>
                                        <div class="dAdmin_profile_name">
                                          <h4>{{ $student->name }}</h4>
                                          <p>{{ $school }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dAdmin_info_name min-w-250px">
                                        <p>{{ $student->email }}</p>
                                    </div>
                                </td>
                                <td>
                                    <div class="dAdmin_info_name min-w-250px">
                                        <p><span>{{ get_phrase('Phone') }}:</span> {{ $info->phone }}</p>
                                        <p>
                                          <span>{{ get_phrase('Address') }}:</span> {{ $info->address }}
                                        </p>
                                    </div>
                                </td>
                                    
                                <td>
                                    <a href="javascript:;" class="btn btn-light-success py-1 px-2 text-14px mt-1" data-bs-toggle="tooltip" title="Id Card" onclick="largeModal('{{ route('parent.student.id_card', ['id' => $student->id]) }}', '{{ get_phrase('Generate id card') }}')"><i class="bi bi-person-badge"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
				</table>
				{!! $students->links() !!}
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
