<div class="eoff-form">
    <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.class_room.update', ['id' => $class_room->id]) }}">
         @csrf 
         <div class="form-row">
            <div class="fpb-7">
                <label for="name" class="eForm-label">{{ get_phrase('Name') }}</label>
                <input type="text" class="form-control eForm-control" value="{{ $class_room->name }}" id="name" name = "name" required>
            </div>
            <div class="fpb-7">
                <label for="code" class="eForm-label">{{ get_phrase('Code') }}</label>
                <input type="text" class="form-control eForm-control" value="{{ $class_room->code }}" id="code" name = "code" required>
            </div>
            <div class="fpb-7">
                <label for="capacity" class="eForm-label">{{ get_phrase('Capacity') }}</label>
                <input type="text" class="form-control eForm-control" value="{{ $class_room->capacity }}" id="capacity" name = "capacity" required>
            </div>
            <div class="fpb-7">
                <label for="status" class="eForm-label">{{ get_phrase('Status') }}</label>
                <select name="status" id="status" class="form-select eForm-select eChoice-multiple-with-remove">
                    <option value="active" {{ $class_room->status == "active" ? 'selected=selected' : ""}} >{{ get_phrase('Active') }}</option>
                    <option value="inactive" {{ $class_room->status == "inactive" ? 'selected=selected' : "" }}>{{ get_phrase('Inactive') }}</option>
                </select>
            </div>

            <div class="fpb-7 pt-2">
                <button class="btn-form" type="submit">{{ get_phrase('Save') }}</button>
            </div>
        </div>
    </form>
</div>