<?php use App\Models\Section; ?>

@extends('admin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
      <div class="col-12">
        <div
          class="d-flex justify-content-between align-items-center flex-wrap gr-15"
        >
          <div class="d-flex flex-column">
            <h4>{{ get_phrase('Levels') }}</h4>
            <ul class="d-flex align-items-center eBreadcrumb-2">
              <li><a href="#">{{ get_phrase('Home') }}</a></li>
              <li><a href="#">{{ get_phrase('Academic') }}</a></li>
              <li><a href="#">{{ get_phrase('Levels') }}</a></li>
            </ul>
          </div>
          <div class="export-btn-area">
            <a href="javascript:;" class="export_btn" onclick="rightModal('{{ route('admin.class.open_modal') }}', '{{ get_phrase('Create Level') }}')">{{ get_phrase('Add level') }}</a>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
            <div class="table-responsive">
                  <table class="table eTable">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">{{ get_phrase('Name') }}</th>
                            {{-- <th scope="col">{{ get_phrase('Section') }}</th> --}}
                            <th scope="col">{{ get_phrase('Code') }}</th>
                            <th scope="col">{{ get_phrase('Status') }}</th>
                            <th scope="col">{{ get_phrase('Description') }}</th>
                            <th scope="col" class="text-end">{{ get_phrase('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($class_lists as $class_list)
                             <tr>
                                <td>{{ $loop->index + 1 }}</td>
                                <td>{{ $class_list->name }}</td>
                                {{-- <td>
                                    <ul>
                                        <?php $sections = Section::get()->where('class_id', $class_list['id']); ?>
                                        @foreach($sections as $section)
                                            <li>{{ $section->name }}</li>
                                        @endforeach
                                    </ul>
                                </td> --}}
                                <td>{{ $class_list->code }}</td>
                                <td>{{ $class_list->status }}</td>
                                <td>{{ $class_list->description }}</td>
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
                                          {{-- <li>
                                            <a class="dropdown-item" href="javascript:;" onclick="rightModal('{{ route('admin.edit.section', ['id' => $class_list->id]) }}', '{{ get_phrase('Edit Section') }}')">{{ get_phrase('Edit Section') }}</a>
                                          </li> --}}
                                          <li>
                                            <a class="dropdown-item" href="javascript:;" onclick="rightModal('{{ route('admin.edit.class', ['id' => $class_list->id]) }}', '{{ get_phrase('Edit Level') }}')">{{ get_phrase('Edit Level') }}</a>
                                          </li>
                                          <li>
                                            <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('admin.class.delete', ['id' => $class_list->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
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