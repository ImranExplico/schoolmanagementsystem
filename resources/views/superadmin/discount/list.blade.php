@extends('superadmin.navigation')
   
@section('content')
<div class="mainSection-title">
    <div class="row">
        <div class="col-12">
            <div
              class="d-flex justify-content-between align-items-center flex-wrap gr-15"
            >
                <div class="d-flex flex-column">
                    <h4>{{ get_phrase('Discount Setting') }}</h4>
                    <ul class="d-flex align-items-center eBreadcrumb-2">
                        <li><a href="#">{{ get_phrase('Home') }}</a></li>
                        <li><a href="#">{{ get_phrase('Discount') }}</a></li>
                        <li><a href="#">{{ get_phrase('Discount List') }}</a></li>
                    </ul>
                </div>
                <div class="export-btn-area">
                    <a href="{{ route('superadmin.discount.add') }}" class="export_btn"><i class="bi bi-plus"></i>{{ get_phrase('Add Discount') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="eSection-wrap pb-2">
            @if(count($discounts) > 0)
        	<table class="table eTable">
        		<thead>
                    <th>{{ get_phrase('Discount Title') }}</th>
                    <th>{{ get_phrase('Description') }}</th>
                    <th>{{ get_phrase('Discount Amount') }}</th>
                    <th>{{ get_phrase('Discount Type') }}(%/$)</th>
                    <th>{{ get_phrase('Status') }}</th>
                    <th>{{ get_phrase('Action') }}</th>
        		</thead>
        		<tbody>
                	@foreach($discounts as $discount)
                		<tr>
                			<td>
                				<strong>{{ $discount->title }}</strong>
                			</td>
                			<td>{!! $discount->remarks !!}</td>
                			<td>{{ $discount->discount_amount }}</td>
                			<td>
                                <?php if ($discount->discount_type == 1): ?>
                                    <span>%</span>
                                <?php else: ?>
                                    <span>$</span>
                                <?php endif; ?></td>
                			<td>
                				<?php if ($discount->status == '1'): ?>
		                            <span class="eBadge ebg-success">{{ get_phrase('Active') }}</span>
		                        <?php else: ?>
		                            <span class="eBadge ebg-danger">{{ get_phrase('Deactive') }}</span>
		                        <?php endif; ?>
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
                                        <a class="dropdown-item" href="{{ route('superadmin.discount.edit',['id' => $discount->id]) }}">{{ get_phrase('Edit') }}</a>
                                      </li>
                                      <li>
                                        <a class="dropdown-item" href="javascript:;" onclick="confirmModal('{{ route('superadmin.discount.delete', ['id' => $discount->id]) }}', 'undefined');">{{ get_phrase('Delete') }}</a>
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