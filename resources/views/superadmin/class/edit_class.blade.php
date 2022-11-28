<div class="eoff-form">
    <form method="POST" enctype="multipart/form-data" class="d-block ajaxForm" action="{{ route('superadmin.class.update', ['id' => $class->id]) }}">
         @csrf 
        <div class="form-row">
            <div class="fpb-7">
                <label for="name" class="eForm-label">{{ get_phrase('Name') }}</label>
                <input type="text" class="form-control eForm-control" id="name" name="name" value="{{ $class->name }}" required>
            </div>
            <div class="fpb-7">
                <label for="code" class="eForm-label">{{ get_phrase('Code') }}</label>
                <input type="text" class="form-control eForm-control" id="code" name="code" value="{{$class->code}}" required>
            </div>
            <div class="fpb-7">
                <label for="description" class="eForm-label">{{ get_phrase('Description') }}</label>
                <input type="text" class="form-control eForm-control" id="description" name="description" required value="{{$class->description}}">
            </div>
            <div class="fpb-7">
                <label for="status" class="eForm-label">{{ get_phrase('Status') }}</label>
       
                <select id="status" name="status" class="form-select eForm-control eForm-select eChoice-multiple-with-remove" >
                    <option value="active" @if ($class->status == 'active')
                        selected="selected"
                    @endif>ACTIVE</option>
                    <option value="inactive" @if ($class->status == 'inactive')
                        selected="selected"
                    @endif>INACTIVE</option>
                </select>
            </div>
            <div class="fpb-7 pt-2">
                <button class="btn-form" type="submit">{{ get_phrase('Update level') }}</button>
            </div>
        </div>
    </form>
</div>