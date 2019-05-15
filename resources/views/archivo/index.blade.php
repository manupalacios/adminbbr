@extends('layouts/app')

@section('titleHead', 'Listado liquidaciones')

@section('content')

	@if(Session::has('status'))
		{{ Session::get('status') }}
	@endif

@endsection