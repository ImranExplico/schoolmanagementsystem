@extends('admin.navigation')
   
@section('content')

<?php use App\Models\School; ?>

<div class="mainSection-title">
    <div class="row">
      <div class="col-12">
        <div
          class="d-flex justify-content-between align-items-center flex-wrap gr-15"
        >
          <div class="d-flex flex-column">
            <h4>{{ get_phrase('Accountant') }}</h4>
            <ul class="d-flex align-items-center eBreadcrumb-2">
              <li><a href="#">{{ get_phrase('Home') }}</a></li>
              <li><a href="#">{{ get_phrase('Users') }}</a></li>
              <li><a href="#">{{ get_phrase('Accountant') }}</a></li>
            </ul>
          </div>
          <div class="export-btn-area">
            <a href="javascript:;" class="export_btn" onclick="rightModal('{{ route('admin.accountant.open_modal') }}', '{{ get_phrase('Create Accountant') }}')">{{ get_phrase('Create Accountant') }}</a>
          </div>
        </div>
      </div>
    </div>
</div>
<!-- Start Teacher area -->
<div class="row">
    <div class="col-12">
        <div class="eSection-wrap-2">
            
            <!-- Table -->
            <div class="table-responsive">
              <table class="table eTable eTable-2">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">{{ get_phrase('Name') }}</th>
                    <th scope="col">{{ get_phrase('Email') }}</th>
                    <th scope="col">{{ get_phrase('User Info') }}</th>
                    <th scope="col">{{ get_phrase('Options') }}</th>
                </thead>
                <tbody>
                    @foreach($accountants as $accountant)
                    <?php 
                        $info = json_decode($accountant->user_information);
                        $user_image = $info->photo;
                        if(!empty($info->photo)){
                            $user_image = 'uploads/user-images/'.$info->photo;
                        }else{
                            $user_image = 'uploads/user-images/thumbnail.png';
                        }

                        $school = School::find($accountant->school_id)->title;
                    ?>
                      <tr>
                        <th scope="row">
                          <p class="row-number">{{ $loop->index + 1 }}</p>
                        </th>
                        <td>
                          <div
                            class="dAdmin_profile d-flex align-items-center min-w-200px"
                          >
                            <div class="dAdmin_profile_img">
                              <img
                                class="img-fluid"
                                width="50"
                                height="50"
                                src="{{ asset('public/assets') }}/{{ $user_image }}"
                              />
                            </div>
                            <div class="dAdmin_profile_name">
                              <h4>{{ $accountant->name }}</h4>
                              <p>{{ $school }}</p>
                            </div>
                          </div>
                        </td>
                        <td>
                          <div class="dAdmin_info_name min-w-250px">
                            <p>{{ $accountant->email }}</p>
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
                                <a class="dropdown-item" href="javascript:;" onclick="rightModal('{{ route('admin.accountant_edit_modal', ['id' => $accountant->id]) }}', '{{ get_phrase('Edit Accountant') }}')">{{ get_phrase('Edit') }}</a>
                              </li>
                              <li>
                                <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('admin.accountant.delete', ['id' => $accountant->id]) }}', 'undefined');">{{ get_phrase('Edit') }}</a>
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
<!-- End Accountant area -->
@endsection