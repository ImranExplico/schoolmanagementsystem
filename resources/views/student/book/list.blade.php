<?php 

use App\Models\BookIssue;

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
                    <h4>{{ get_phrase('Book') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Back Office') }}</a></li>
                        <li><a href="#">{{ get_phrase('List Of Book') }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap pb-2">
			@if(count($books) > 0)
				<table id="basic-datatable" class="table eTable">
		            <thead>
		                <tr>
		                	<th>#</th>
		                    <th>{{ get_phrase('Book name') }}</th>
							<th>{{ get_phrase('Author') }}</th>
							<th>{{ get_phrase('Copies') }}</th>
							<th>{{ get_phrase('Available copies') }}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach ($books as $book)
			                <tr>
			                	<td>{{ $loop->index + 1 }}</td>
					            <td> {{ $book['name'] }} </td>
					            <td> {{ $book['author'] }} </td>
					            <td> {{ $book['copies'] }} </td>
					            <td>
									<?php $number_of_issued_book = BookIssue::get()->where('book_id', $book['id'])->where('status', 0); ?>
									{{ $book['copies'] - count($number_of_issued_book) }}
					            </td>
					        </tr>
			            @endforeach
		            </tbody>
		        </table>
		        {!! $books->links() !!}
		    @else
				<div class="empty_box center">
	                <img class="mb-3" width="150px" src="{{ asset('public/assets/images/empty_box.png') }}" />
	                <br>
	                <span class="">{{ get_phrase('No data found') }}</span>
	            </div>
			@endif
		</div>
	</div>
</div>
@endsection