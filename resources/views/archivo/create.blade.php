@extends('layouts/app')

@section('titleHead', 'Subir archivo')

@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Inicio</a></li>
	<li class="breadcrumb-item">Nuevo período</li>
	<li class="breadcrumb-item active">Subir archivo</li>
@endsection

@section('content')

	<div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
            	<div class="card card-primary">
					<form action="{{ route('archivo.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
						<div class="card-body">
							{{ csrf_field() }}

							@include('archivo.fields')

							@if(Session::has('status'))
								<div>
									<input type="checkbox" name="sobreescribir"> {{ Session::get('status') }}
								</div>
							@endif

						</div>
						<div class="card-footer" >
							<button type="submit" class="btn btn-outline-info"><i class="fa fa-upload"></i> Subir</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

@endsection
