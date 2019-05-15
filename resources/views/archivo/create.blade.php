@extends('layouts/app')

@section('titleHead', 'Subir liquidacion')

@section('content')
	
	<h3>Subir liquidación</h3>
	<div>
		<form action="{{ route('archivo.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
			{{ csrf_field() }}

			@include('archivo.fields')

			@if(Session::has('status'))
				<div>
					<input type="checkbox" name="sobreescribir"> {{ Session::get('status') }}
				</div>
			@endif

			<div class="">
                <div class="">
                    <button type="submit" class="">Subir</button>
                </div>
            </div>
		</form>
	</div>

@endsection
