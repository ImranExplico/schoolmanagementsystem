@extends('superadmin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('System Settings') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Settings') }}</a></li>
                        <li><a href="#">{{ get_phrase('System Settings') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-7">
        <div class="eSection-wrap">
            <div class="eMain">
                <div class="row">
                    <div class="col-md-11 pb-3">
                        <div class="eForm-layouts">
                            <p class="column-title">{{ get_phrase('SYSTEM SETTINGS') }}</p>
                            <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.system.update') }}">
                                @csrf 
                                <div class="fpb-7">
                                    <label for="system_name" class="eForm-label">{{ get_phrase('System Name') }}</label>
                                    
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('system_name') }}" id="system_name" name = "system_name" required>
                                    
                                </div>
                                <div class="fpb-7">
                                    <label for="system_title" class="eForm-label">{{ get_phrase('System Title') }}</label>
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('system_title') }}" id="system_title" name = "system_title" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="system_email" class="eForm-label">{{ get_phrase('System Email') }}</label>
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('system_email') }}" id="system_email" system_email = "name" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="phone" class="eForm-label">{{ get_phrase('Phone') }}</label>
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('phone') }}" id="phone" name = "phone" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="fax" class="eForm-label">{{ get_phrase('Fax') }}</label>
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('fax') }}" id="fax" name = "fax" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="address" class="eForm-label">{{ get_phrase('Address') }}</label>
                                    <textarea class="form-control eForm-control" id="address" name = "address" rows="5" required>{{ get_settings('address') }}</textarea>
                                </div>
                                <div class="fpb-7">
                                    <label for="name" class="eForm-label">{{ get_phrase('Timezone') }}</label>
                                    <select class="form-select eForm-select eChoice-multiple-with-remove" id="timezone" name="timezone" required>
                                        <?php $tzlist = DateTimeZone::listIdentifiers(DateTimeZone::ALL); ?>
                                        <?php foreach ($tzlist as $tz): ?>
                                        <option value="{{ $tz  }}" {{ get_settings('timezone') == $tz ?  'selected':'' }}>{{ $tz  }}</option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="fpb-7">
                                    <label for="footer_text" class="eForm-label">{{ get_phrase('Footer Text') }}</label>
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('footer_text') }}" id="footer_text" name = "footer_text" required>
                                </div>
                                <div class="fpb-7">
                                    <label for="footer_link" class="eForm-label">{{ get_phrase('Footer Link') }}</label>
                                    <input type="text" class="form-control eForm-control" value="{{ get_settings('footer_link') }}" id="footer_link" name = "footer_link" required>
                                </div>
                                 <div class="fpb-7 pt-2">
                                    <button type="submit" class="btn-form">{{ get_phrase('Submit') }}</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-5">
        <div class="eSection-wrap">
            <div class="eMain">
                <div class="row">
                    <div class="col-md-12 pb-3">
                        <p class="column-title">{{ get_phrase('PRODUCT UPDATE') }}</p>
                        <div class="eForm-file">
                            <form action="{{route('superadmin.product.update')}}" method="post" enctype="multipart/form-data">
                                @CSRF
                                <div class="mb-3">
                                    <label for="formFileSm" class="eForm-label">{{ get_phrase('File') }}</label>
                                    <input class="form-control eForm-control-file" id="formFileSm" type="file" name="file">
                                </div>
                                <button type="submit" class="btn-form float-end">{{ get_phrase('Update') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-12">
    <div class="eSection-wrap">
        <div class="eMain">
            <div class="row">
                <div class="col-md-12 pb-3">
                    <p class="column-title">{{ get_phrase('SYSTEM LOGO') }}</p>
                    <div class="eForm-file">
                        <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.logo.update') }}">
                            @csrf 
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <label class="col-form-label" for="example-fileinput">{{ get_phrase('Dark logo') }}</label>
                                    <div class="eCard d-block text-center bg-light">
                                        <img src="{{ asset('public/assets/uploads/logo/'.get_settings('dark_logo')) }}" class="mx-4 my-5" width="200px"
                                            alt="...">
                                        <div class="eCard-body">
                                            <input class="form-control eForm-control-file" id="formFileSm" type="file" name="dark_logo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <label class="col-form-label" for="example-fileinput">{{ get_phrase('Light logo') }}</label>
                                    <div class="eCard d-block text-center bg-secondary">
                                        <img src="{{ asset('public/assets/uploads/logo/'.get_settings('light_logo')) }}" class="mx-4 my-5" width="200px"
                                            alt="...">
                                        <div class="eCard-body">
                                        <input class="form-control eForm-control-file" id="formFileSm" type="file" name="light_logo">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 text-center">
                                    <label class="col-form-label" for="example-fileinput">{{ get_phrase('Favicon') }}</label>
                                    <div class="eCard d-block text-center bg-light">
                                        <img src="{{ asset('public/assets/uploads/logo/'.get_settings('favicon')) }}" class="mx-4 my-5" width="53px" height="60px"
                                            alt="...">
                                        <div class="eCard-body">
                                        <input class="form-control eForm-control-file" id="formFileSm" type="file" name="favicon">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn-form">{{ get_phrase('Update Logo') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection