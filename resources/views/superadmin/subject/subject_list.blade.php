<?php use App\Models\Classes; ?>

@extends('superadmin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
      <div class="col-12">
        <div
          class="d-flex justify-content-between align-items-center flex-wrap gr-15"
        >
          <div class="d-flex flex-column">
            <h4>{{ get_phrase('Classes') }}</h4>
            <ul class="d-flex align-items-center eBreadcrumb-2">
              <li><a href="#">{{ get_phrase('Home') }}</a></li>
              {{-- <li><a href="#">{{ get_phrase('Academic') }}</a></li> --}}
              <li><a href="#">{{ get_phrase('Classes') }}</a></li>
            </ul>
          </div>
          <div class="export-btn-area">
            <a href="javascript:;" class="export_btn" onclick="rightModal('{{ route('superadmin.subject.open_modal') }}', '{{ get_phrase('Create Class') }}')"><i class="bi bi-plus"></i>{{ get_phrase('Add Class') }}</a>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <form method="GET" class="d-block ajaxForm" action="{{ route('superadmin.subject_list') }}">
                <div class="row mt-3 mb-3">
                    <div class="col-md-4">
                        <select name="class_id" id="class_id" class="form-select eForm-select eChoice-multiple-with-remove"  onchange="classWiseSection(this.value)">
                            <option value="">{{ get_phrase('Select a level') }}</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ $class_id == $class->id ?  'selected':'' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-block btn-secondary" type="submit" id = "filter_routine">{{ get_phrase('Filter') }}</button>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table eTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ get_phrase('Code') }}</th>
                            <th>{{ get_phrase('Level') }}</th>
                            <th>{{ get_phrase('Days') }}</th>
                            <th>{{ get_phrase('Status') }}</th>
                            <th class="text-end">{{ get_phrase('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subjects as $subject)
                            <?php $class = Classes::get()->where('id', $subject->class_id)->first(); ?>
                             <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $subject->code }}</td>
                                <td>{{ $class->name }}</td>
                                <td>
                                  <?php
                                    $strDays='-';
                                    $arrDays=json_decode($subject->day);
                                    if(!is_null($arrDays)){
                                      $strDays=implode(', ',$arrDays);
                                    }

                                  ?>
                                  {{ $strDays}}</td>
                                <td>{{ $subject->status }}</td>
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
                                            <a class="dropdown-item" href="javascript:;" onclick="rightModal('{{ route('superadmin.edit.subject', ['id' => $subject->id]) }}', '{{ get_phrase('Edit Class') }}')">{{ get_phrase('Edit') }}</a>
                                          </li>
                                          <li>
                                            <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('superadmin.subject.delete', ['id' => $subject->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
                                          </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection