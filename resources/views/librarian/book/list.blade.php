<?php 

use App\Models\BookIssue;

?>

@extends('librarian.navigation')
   
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
                        <li><a href="#">{{ get_phrase('Book') }}</a></li>
                    </ul>
                </div>
                <div class="export-btn-area">
                    <a href="javascript:;" class="export_btn" onclick="rightModal('{{ route('librarian.book.open_modal') }}', '{{ get_phrase('Add book') }}')"><i class="bi bi-plus"></i>{{ get_phrase('Add book') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap">
			@if(count($books) > 0)
				<table id="basic-datatable" class="table eTable">
		            <thead>
		                <tr>
		                	<th>#</th>
		                    <th>{{ get_phrase('Book name') }}</th>
							<th>{{ get_phrase('Author') }}</th>
							<th>{{ get_phrase('Copies') }}</th>
							<th>{{ get_phrase('Available copies') }}</th>
							<th class="text-center">{{ get_phrase('Option') }}</th>
		                </tr>
		            </thead>
		            <tbody>
		                @foreach ($books as $book)
			                <tr>
			                	<td>{{ $loop->index + 1 }}</td>
					            <td> <strong>{{ $book['name'] }}</strong> </td>
					            <td> {{ $book['author'] }} </td>
					            <td> {{ $book['copies'] }} </td>
					            <td>
									<?php $number_of_issued_book = BookIssue::get()->where('book_id', $book['id'])->where('status', 0); ?>
									{{ $book['copies'] - count($number_of_issued_book) }}
					            </td>
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
			                                <a class="dropdown-item" href="javascript:;" onclick="rightModal('{{ route('librarian.edit.book', ['id' => $book->id]) }}', '{{ get_phrase('Edit Book') }}')">{{ get_phrase('Edit') }}</a>
			                              </li>
			                              <li>
			                                <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('librarian.book.delete', ['id' => $book->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
			                              </li>
			                            </ul>
			                        </div>
					            </td>
					          </tr>
			            @endforeach
		            </tbody>
		        </table>
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