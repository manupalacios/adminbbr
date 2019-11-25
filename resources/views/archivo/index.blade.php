@extends('layouts/app')

@section('titleHead', 'Listado de archivos')

@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Inicio</a></li>
	<li class="breadcrumb-item active">Listado de archivos</li>
@endsection

@section('content')
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12">
				@if(Session::has('status'))
					<div class="alert alert-success alert-dismissible fade show" role="alert">
						{{ Session::get('status') }}
						<button type="button" class="close" data-dismiss="alert" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				@endif
            	<div class="card card-info">
					<div class="card-body">
						<div class="form-group">
							<label class="control-label" for="anio">Año *</label>
							<input class="form-control" type="text" name="anio" id="anio" maxlength="4" value="" required>
							<span class="help-block" id="errorAnio"></span>
						</div>
						<div class="form-group">
							<label class="control-label" for="grupo">Grupo *</label>
							<select class="form-control" name="grupo" id="grupo" required>
								<option value="0">Planta funcional (Of)</option>
							</select>
							<span class="help-block" id="errorGrupo"></span>
						</div>
					</div>
					<div class="card-footer">
						<button type="button" id="listar" class="btn btn-outline-info"><i class="fa fa-list"></i> Listar</button>
					</div>
				</div>
			</div>
		</div>
        <div class="row"id="row-list" style="display: none">
        	<div class="col-md-12">
            	<div class="card card-info">
					<div class="card-body">
						<table id="listado"  class="table table-hover" style="display: visible">
							<thead>
								<tr>
									<th>Mes</th>
									<th>Tipo</th>
									<th>Número</th>
									<th>Nivel</th>
									<th>Accion</th>
								</tr>
							</thead>
							<tbody id="tableBody">

							</tbody>
						</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		$(document).ready( function() {

			@include('widgets.dataTable', array('tableId' => 'listado', 'indexOrder' => 0, 'directionOrder' => 'desc'))
			$('#listado').parents('div.dataTables_wrapper').first().hide();

			$("#listar").click(function( event ) {
				$("#status").hide();
				var anio = $("#anio").val();
				var grupo = $("#grupo").val();
                request = {
                	'anio': anio,
                	'grupo': grupo
                };
                $.post({
                    url: "{{ route('archivo.getArchivos')}}",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: request,
                    success: function( archivos ) {
						const tipos = ['', 'NORMAL', 'ADICIONAL', 'SAC'];
						const niveles = ['TODOS', 'INICIAL', 'PRIMARIO', 'MEDIO', 'SUPERIOR'];

						$("#errorAnio").empty();
						$("#errorGrupo").empty();
						$("#tableBody").empty();
						$("#row-list").show();

						archivos.forEach( archivo => {
							let btnImportar = '<form action="{{ url('archivo/import') }}/' + archivo.id + '" method="POST" class="form-horizontal">{{ csrf_field() }}<button type="submit" class="btn btn-outline-info btn-sm" title="Generar"><i class="far fa-share"></i> </button></form> ';
							let btnBajar = '<button type="button" class="btn btn-outline-info btn-sm" title="Bajar"><i class="far fa-download"></i> </button> ';
							let btnEliminar = '<button type="button" class="btn btn-outline-danger btn-sm" title="Eliminar"><i class="far fa-trash-alt"></i> </button> ';
							let acciones = btnImportar + btnBajar + btnEliminar;
	                    	let date = new Date(anio, archivo.mes - 1, 1);
							let mes = date.toLocaleString('{{ app()->getLocale() }}', { month: 'long' });
							let tipo = tipos[ archivo.tipo_id ];
							let nivel = niveles[ archivo.nivel_id ];
                            $("#tableBody").append("<tr><td>" + mes.toUpperCase() + "</td><td>" + tipo + "</td><td>" + archivo.numero + "</td><td>" + nivel + "</td><td>" + acciones + "</td></tr>");
						});

                        $('#listado').parents('div.dataTables_wrapper').first().slideDown();
                    },
                    error: function( data ) {
                        var errors = data.responseJSON.errors;
                        if (errors.anio) {
							$("#anio").addClass("is-invalid");
                        	$("#errorAnio").empty().append("<strong>" + errors.anio + "</strong>");
                        }
                        if (errors.grupo) {
                     	   $("#errorGrupo").empty().append("<strong>" + errors.grupo + "</strong>");
                        }
                    }
                });
			});
		});
	</script>
@endsection
