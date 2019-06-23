$('#{{$tableId}}').DataTable({
    "order": ([{{$indexOrder}}, "@if(!empty($directionOrder)) {{$directionOrder}} @else asc @endif"]),
    "pageLength": 10,
    "language":{
        "decimal":        "",
        "emptyTable":     "Ningun dato disponible en esta tabla",
        "info":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "infoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "infoFiltered":   "(filtrando de un total de _MAX_ registros)",
        "infoPostFix":    "",
        "thousands":      ",",
        "lengthMenu":     "Mostrar _MENU_ registros",
        "loadingRecords": "Cargando...",
        "processing":     "Procesando...",
        "search":         "Buscar:",
        "zeroRecords":    "No se encontraron registros",
        "paginate": {
        "first":      "Primero",
        "last":       "Ultimo",
        "next":       "Siguiente",
        "previous":   "Anterior"
        },
        "aria": {
        "sortAscending":  ": Activar para ordenar la columna de manera ascendente",
        "sortDescending": ": Activar para ordenar la columna de manera de descendente"
        }
    }
});
