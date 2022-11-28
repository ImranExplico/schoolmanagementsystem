<div class="eoff-form">
    <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.create.class_room') }}">
        @csrf 
        <div class="form-row">
            <div class="fpb-7">
                <label for="name" class="eForm-label">{{ get_phrase('Name') }}</label>
                <input type="text" class="form-control eForm-control" id="name" name="name" required>
            </div>
            <div class="fpb-7">
                <label for="code" class="eForm-label">{{ get_phrase('Code') }}</label>
                <input type="text" class="form-control eForm-control"  id="code" name = "code" required>
            </div>
            <div class="fpb-7">
                <label for="capacity" class="eForm-label">{{ get_phrase('Capacity') }}</label>
                <input type="text" class="form-control eForm-control" id="capacity" name = "capacity" required>
            </div>
            <div class="fpb-7">
                <label for="status" class="eForm-label">{{ get_phrase('Status') }}</label>
                <select name="status" id="status" class="form-select eForm-select eChoice-multiple-with-remove">
                    <option value="active" >{{ get_phrase('Active') }}</option>
                    <option value="inactive" >{{ get_phrase('Inactive') }}</option>
                </select>
            </div>
            <div class="fpb-7 pt-2">
                <input type="hidden" name="school_id" id="school_id"  value="{{$schoolId}}">
                <button class="btn-form" type="submit">{{ get_phrase('Create') }}</button>
            </div>
        </div>
    </form>
</div>