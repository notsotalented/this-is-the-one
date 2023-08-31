@extends('includes::layout.app_admin_nova')

@section('title', 'Data Tables')

@section('css')
    <style>
        @include('includes::css.custom');
    </style>
@endsection

@section('content')
	{{--Switch Table--}}
	<div class="container">
		<ul class="pagination">
			<li class="page-item btn-primary" id="table_0"><a class="page-link" href="/dashboard">Pick Your Poison</a></li>
			<li class="page-item" id="table_1"><a class="page-link" href="?table=1">LEGACY</a></li>
			<li class="page-item" id="table_2"><a class="page-link" href="?table=2">USERS</a></li>
				
			<li class="page-item" id="table_3"><a class="page-link" href="?table=3">PRODUCT-WIP</a></li>
		</ul>
		</div>
	
		<div>
			
			{{--Display selected Table--}}
			  @isset($_GET['table'])
				@switch($_GET['table'])
				@case(-1)
					{{--Table 1--}}
					<label><b>Showing: login_info (LEGACY)</b></label>
					<div class="table-responsive">
						<table id="products_table" class="table table-info table-striped table-hover table-bordered">                     
							<thead class="thead-dark">
								<tr>
									<th>ID</th>
									<th>USERNAME</th>
									<th>PASSWORD</th>
									<th>ROLE</th>
									<th>DATE-CREATED</th>
									<th>ACTION</th>
								</tr>
							</thead>
							<tbody>
									@foreach($data as $item)
										<tr>
											<td scope="row" style="text-align:right">{{$item->ID}}</td>
											<td>{{$item->username}}</td>
											<td width="200px">
												{{--Password display--}}
												@if(!session('login'))
													@if (intval(session('role')) < intval($item->role))
														<div style="clear;">
															<div style="width: 60%; display: inline-block; float: left;">
																<div id="show-hide-password-{{$item->ID}}" style="display: none">
																{{$item->password}}
																</div>
															</div>
															{{--Hide button--}}
															<div style="width: 20%; display: inline-block; float: right; margin-left: 20px;">
																<button onmouseleave="showHidePassword('show-hide-password-{{$item->ID}}')" onmouseenter="showHidePassword('show-hide-password-{{$item->ID}}')" type="button" class="btn btn-outline-secondary" style="align-self: flex-end"><i class="fas fa-eye fa-xs"></i></button>
															</div>
														</div>
													@else
													@endif
												@endif
											</td>
											<td style="text-align:center">
												@switch($item->role)
													@case(2)
														LEADER
														@break
													@case(3)
														STAFF
														@break
													@default
														ADMIN
												@endswitch
											</td>
											<td style="text-align:center">{{date("d-m-Y \\\n \\\n H:i:s", strtotime($item->created))}}</td>
											<td> 
												@if(session('login'))
													{{--Logic for allow button, will do later. Every users but staffs can delete any lower role.
													Staff can't edit. Leaders can edit themselves. Admins edit everyone but other admins. (Edit password).--}}
													@if(intval(session('role')) < intval($item->role))
														<a href="confirm-page?action=edit&targetusername={{$item->username}}" type="button" class="btn btn-info" role="button" aria-pressed="true">EDIT</a>
														<a href="confirm-page?action=delete&targetusername={{$item->username}}" type="button" class="btn btn-danger" role="button" aria-pressed="true">DELETE</a>
													@else
														<a href="/edit?targetusername={{$item->username}}" type="button" class="btn btn-info disabled" role="button" aria-pressed="true" aria-disabled>EDIT</a>
														<a href="/delete?targetusername={{$item->username}}" type="button" class="btn btn-danger disabled" role="button" aria-pressed="true" aria-disabled>DELETE</a>
													@endif
												@else
													{{--Not logged-in--}}
													{{--
													<a href="/edit?targetusername={{$item->username}}" type="button" class="btn btn-info disabled" role="button" aria-pressed="true" aria-disabled>EDIT</a>
													<a href="/delete?targetusername={{$item->username}}" type="button" class="btn btn-danger disabled" role="button" aria-pressed="true" aria-disabled>DELETE</a>
													--}}
												@endif
											</td>
										</tr>
									@endforeach
							</tbody>
						</table>
					</div>
					@break
				@case(2)
					{{--Table 2--}}
					<label><b>Showing: users</b></label>
					<div class="table-responsive">
						<table id="products_table_2" class="table table-info table-striped table-hover table-bordered">                     
							<thead class="thead-dark">
								<tr>
									<th>ID</th>
									<th>USERNAME</th>
									<th>EMAIL</th>
									<th>GENDER</th>
									<th>BIRTH</th>
									<th>DATE-UPDATED</th>
									<th>ACTION</th>
								</tr>
							</thead>
							<tbody>
									@foreach($user as $item_2)
										{{--Hide deleted--}}
										@if($item_2->deleted_at == NULL)
											<tr>
												<td style="text-align:right">{{$item_2->id}}</td>
												<td>
													@can('search-users')
														<a href="{{route('user-profile', ['id' => $item_2->id])}}" class="link-primary" style="text-decoration: none"><b>{{$item_2->name}}</b></a>
													@endcan
												</td>
												<td width="300px">
													<div style="clear;">
														<div style="width: 60%; display: inline-block; float: left;">
															<div id="show-hide-password-{{$item_2->id}}" style="display: none">
															{{$item_2->email}}
															</div>
														</div>
														{{--Hide button--}}
														<div style="width: 20%; display: inline-block; float: right; margin-left: 20px;">
															<button onmouseleave="showHidePassword('show-hide-password-{{$item_2->id}}')" onmouseenter="showHidePassword('show-hide-password-{{$item_2->id}}')" type="button" class="btn btn-outline-secondary" style="align-self: flex-end"><i class="fas fa-eye fa-xs"></i></button>
														</div>
													</div>
												</td>
												<td>
													@if($item_2->gender)
														{{$item_2->gender}}
													@else
														<i class="fa-regular fa-face-meh-blank"></i>
													@endif
												</td>
												<td style="text-align:center">
													@isset($item_2->birth)
														{{date("d-m-Y", strtotime($item_2->birth))}}
													@endisset
												</td>
												<td style="text-align:center">{{date("d-m-Y \\\n \\\n H:i:s", strtotime($item_2->updated_at))}}</td>
												<td>
													@if(!auth()->check() || $item_2->id == auth()->check() || $item_2->id == '1')
															@can('update-users')
															{{--Dud button--}}
															<a href="#" type="button" class="btn btn-info disabled" role="button" aria-pressed="true" aria-disabled style="visibility: hidden">UPDATE</a>
														@endcan
														@can('delete-users')
															{{--Dud button--}}
															<a href="#" type="button" class="btn btn-danger disabled" role="button" aria-pressed="true" aria-disabled style="visibility: hidden">DEL</a>
														@endcan
													@else
														@can('update-users')
															<a href="{{ route('update-page', ['id' => $item_2->id])}}" type="button" class="btn btn-info" role="button" aria-pressed="true">UPDATE</a>
														@endcan
														@can('delete-users')
															<a href="{{ route('delete-page', ['id' => $item_2->id])}}" type="button" class="btn btn-danger" role="button" aria-pressed="true">DEL</a>
														@endcan
													@endif
												</td>
											</tr>
										@endif
									@endforeach
							</tbody>
						</table>
					</div>
					@break
				@default
					<label><b>Showing: Nothing</b></label>
				@endswitch
			@endisset
		</div>
@endsection


<script>
	new DataTable('#products_table');
	new DataTable('#products_table_2', {
		columns:[
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
            {
                "sortable": true
            },
			{
                "sortable": false
            }
        ],
		scrollCollapse: true,
		scrollY: '380px',
		lengthMenu: [
			[5, 10, 15, -1],
        	[5, 10, 15, 'All']
		]
	});


	function showHidePassword(id) {

		var getDiv = document.getElementById(id);
		if (getDiv.style.display == "none") {
			getDiv.style.display = "inline-block";
		} else {
			getDiv.style.display = "none";
		}
	}

	//Get table parameter
	const urlParams = new URLSearchParams(window.location.search);
	const table = urlParams.get('table');
	const table_1 = document.getElementById("table_1");
	const table_2 = document.getElementById("table_2");
	const table_3 = document.getElementById("table_3");

	table_1.className = "page-item";
	table_2.className = "page-item";
	table_3.className = "page-item";

	switch (table) {
		case '1':
			table_1.className = "page-item active";
			break;
		case '2':
			table_2.className = "page-item active";
			break;
		case '3':
			table_3.className = "page-item active";
			break;
		default:
			break;
	}
</script>

</html>
