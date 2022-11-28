@extends('parent.navigation')

@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Grades') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Examination') }}</a></li>
                        <li><a href="#">{{ get_phrase('Grades') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap pb-2">
        	<table class="table eTable">
	            <thead>
	                <tr>
	                    <th>#</th>
	                    <th>{{ get_phrase('Grade') }}</th>
	                    <th>{{ get_phrase('Grade Point') }}</th>
	                    <th>{{ get_phrase('Mark From') }}</th>
	                    <th>{{ get_phrase('Mark Upto') }}</th>
	                </tr>
	            </thead>
	            <tbody>
	                @foreach($grades as $grade)
	                     <tr>
	                        <td>{{ $loop->index + 1 }}</td>
	                        <td>{{ $grade->name }}</td>
	                        <td>{{ $grade->grade_point }}</td>
	                        <td>{{ $grade->mark_from }}</td>
	                        <td>{{ $grade->mark_upto }}</td>
	                    </tr>
	                @endforeach
	            </tbody>
	        </table>
	        {!! $grades->links() !!}
        </div>
    </div>
</div>
@endsection
