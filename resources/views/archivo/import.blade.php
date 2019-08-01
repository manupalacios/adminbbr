@extends('layouts/app')

@section('titleHead', 'Generar período')

@section('breadcrumb')
	<li class="breadcrumb-item"><a href="#">Inicio</a></li>
	<li class="breadcrumb-item">Nuevo período</li>
	<li class="breadcrumb-item active">Generar período</li>
@endsection

@section('content')

	<div class="container-fluid">
        <div class="row">
        	<div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div id="progreso"></div>
                        <div class="progress active" style="height: 20px;">
                            <div id="prog" class=".progress-bar bg-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                        <br />
                        <div id="text_retry"></div>
                        <div class="progress active" style="height: 20px;">
                            <div id="prog_retry" class=".progress-bar bg-warning progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function(){

            var periodo = @json($periodo);
            var pages = @json($pages);
            var paginasTotales = Object.keys(pages).length;
            var progreso = 0;
            var paginasProcesadas = 0;
            var pbarId = 'prog';

            /* Exp. regulares */
            var isSeparator = /^[_ ]+$/;
            var endOfFile = /empleados/i;
            var beginFooter = /_+$/;

            var liquidacionMes, liquidacion, empleado, cargo, inHeader, indexHeader, inFooter, indexFooter, eof, countRequest, countResponse;
            var errorLiq = new Array();
            eof = false;
            countRequest = 0;
            countResponse = 0;
            initializeLiquidacion();

            pages.map(( page, index) => {
                page.map((line, lineIndex) => {
                    /** skip first 8 lines */
                    /* parse line */
                    if ( lineIndex > 8) {
                        parseLinePDF( line );
                    }
                })
            })

            function initializeLiquidacion (){
                liquidacionMes = {
                    liquidacion: {
                        empleado: {
                            dni: '',
                            nombre: '',
                            apellido: '',
                            genero: '',
                            fechaAnti: '',
                            fechaNac: '',
                        },
                        rol: '',
                        cargo: '',
                        horas: 0.00,
                        fechaAlta: '',
                        situacionRevista: 0,
                    },
                    detalle: [],
                    liquido: 0.00,
                    sujeto: 0.00,
                    noSujeto: 0.00,
                    salario: 0.00,
                    bruto: 0.00,
                    descuentos: 0.00,
                    periodo: periodo
                };
                inFooter = false;
                indexFooter = 0;
                inHeader = true;
                indexHeader = 0;
            }

            function parseLinePDF( line ) {
                if (eof)
                    return;
                if ( inFooter ) {
                    indexFooter++;
                    /* analizo en que linea del footer estoy parado para parsear el contenido */
                    /*
                     * 1 => Total descuentos
                     * 2 => Total remuneraciones (RM + NRM)
                     * 5 => Total NRM, Total RM (concatenado sin espacios)
                     * 7 => Salario familiar (mas otro importe concatenado)
                     * 9 => Liquido a cobrar
                     */
                    let importes;
                    switch (indexFooter) {
                        case 2:
                            liquidacionMes.bruto = parseImporteFooter( line );
                            break;
                        case 5:
                            importes = parseImportesConcat( line );
                            liquidacionMes.noSujeto = importes[0];
                            liquidacionMes.sujeto = importes[1];
                            break;
                        case 7:
                            importes = parseImportesConcat( line );
                            liquidacionMes.salario = importes[0];
                            break;
                        case 9:
                            liquidacionMes.liquido = parseImporteFooter( line );
                            break;
                        case 10:
                            inFooter = false;
                            indexFooter = 0;
                            break;

                    }
                } else if ( endOfFile.test( line ) ){
                    eof = true;
                } else if ( inHeader ) {
                    indexHeader++;
                    switch ( indexHeader ){
                        case 1:
                            parseApellidoNombre( line );
                            break;
                        case 2:
                            parseSitRevista( line );
                            break;
                        case 3:
                            parseRemDescLiq( line );
                            inHeader = false;
                            indexHeader = 0;
                            break;
                    }
                } else if ( isSeparator.test( line ) ) {
                    /* separador de liquidacion, envio la liquidacion para guardar */
                    countRequest++;
                    enviarLiquidacion(liquidacionMes);
                    //ultimaLiquidacion = String(liquidacionMes.liquidacion.empleado.dni) + String(liquidacionMes.liquidacion.rol);
                    initializeLiquidacion();
                } else if ( beginFooter.test( line ) ){
                    liquidacionMes.descuentos = parseImporteFooter( line );
                    inFooter = true;
                    indexFooter++;
                } else {
                    /* es un concepto */
                    parseConcepto( line );
                }

            }

            /**
             * Parsea la linea que contiene el nombre y apelido
             * Se obtienen los siguientes datos
             *      rol (int)
             *      cargo (string)
             *      dni (string)
             *      nombre (string)
             *      dias trabajados (int)
             *      fecha de alta (date)
             */
            function parseApellidoNombre( line ) {

                let rol = line.substring(0, line.indexOf('Apellido') );
                let nombreApellido = rol.substring( 0, rol.lastIndexOf(' ') ).trim();

                let nombre = nombreApellido.substring(rol.indexOf(',')+1).trim();
                let apellido = nombreApellido.substring(0, rol.indexOf(',')).trim();

                rol = rol.substring( rol.lastIndexOf(' ') ).trim();

                let dni = line.substring( (line.indexOf('Rol') + 5), (line.indexOf('Rol') + 13) );

                let genero = line.substring( (line.indexOf('Rol') + 4), (line.indexOf('Rol') + 5) );

                let cargo = line.substring( (line.indexOf('Rol') + 4) );
                cargo = cargo.substring( cargo.indexOf(' ') + 1, cargo.indexOf(' ') + 6 );

                let dias = line.substring( (line.indexOf('EE12601530') - 2), line.indexOf('EE12601530') ).trim();

                let fecha = line.match(/\d{2}\/\d{2}\/\d{4}/);
                let digits = fecha[0].split('/');
                let fechaAlta = digits[2] + '-' + digits[1] + '-' + digits[0];

                liquidacionMes.liquidacion.empleado.dni = dni;
                liquidacionMes.liquidacion.empleado.nombre = nombre;
                liquidacionMes.liquidacion.empleado.apellido = apellido;
                liquidacionMes.liquidacion.empleado.genero = genero;
                liquidacionMes.liquidacion.cargo = cargo;
                liquidacionMes.liquidacion.rol = rol;
                liquidacionMes.liquidacion.fechaAlta = fechaAlta;
                liquidacionMes.dias = dias;

            }

            /**
             * Parsea la linea que contiene la situacion de revista
             * Se obtienen los siguientes datos
             *      situacion de revista (int)
             *      fecha de antiguedad (date)
             */
            function parseSitRevista( line ) {

                let sitRevista = line.substring(0, 2);
                let fecha = line.match(/\d{2}\/\d{2}\/\d{4}/);
                let digitos = fecha[0].split('/');
                let fechaAnti = digitos[2] + '-' + digitos[1] + '-' + digitos[0];

                liquidacionMes.liquidacion.empleado.fechaAnti = fechaAnti;
                liquidacionMes.liquidacion.situacionRevista = sitRevista;

            }

            /**
             * Parsea la linea que contiene Remuneracion Descuentos Liquido
             * Se obtienen los siguientes datos
             *      fecha de nacimiento (date)
             *      horas (float)
             *      liquido (float)
             */
            function parseRemDescLiq( line ) {

                let dateStart = line.search(/\d{1}\/\d{2}\/\d{4}/) - 1;
                let impStart = line.lastIndexOf('_') + 1;

                let imp = line.match(/\d+,\d{2}/g); //matchea liquido y horas

                let fecha = line.match(/\d{2}\/\d{2}\/\d{4}/);
                let digitos = fecha[0].split('/');
                let fechaNac = digitos[2] + '-' + digitos[1] + '-' + digitos[0];

                liquidacionMes.liquido = imp[0];
                liquidacionMes.liquidacion.empleado.fechaNac = fechaNac;
                liquidacionMes.liquidacion.horas = imp[1];

            }

            /**
             * Pasea una linea que contiene un concepto
             * Se obtienen los siguientes datos
             *      codigo (string)
             *      importe (float)
             */
            function parseConcepto( line ) {

                let codigo = line.substring(0, line.indexOf(' ') );
                line = line.trim();
                let imp = line.match(/\d+,\d{2}/);
                let descripcion = line.substring( line.indexOf(' '), line.indexOf(imp[0]) -1 ).trim();
                if (imp) {
                    liquidacionMes.detalle.push( { codigo: codigo, importe: imp[0], descripcion: descripcion } );
                }

            }

            function parseImporteFooter( line ) {

                let imp = line.match(/\d+,\d{2}/);
                return imp[0];

            }

            function parseImportesConcat( line ) {

                let impEnd = line.indexOf(',') + 3;
                let imp1 = line.substring(0, impEnd).trim();
                let imp2 = line.substring(impEnd).match(/\d+,\d{2}/);

                return [imp1, imp2[0]];

            }

            function enviarLiquidacion(liquidacionMes) {
                $.post({
                    url: "{{ route('liquidacionMes.store') }}",
                    dataType: "json",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: liquidacionMes,
                    success: function (response) {
                        console.log("----------- SUCCESS -----------");
                        progress( pbarId );
                        countResponse++;
                        if ( countResponse == countRequest ) {
                            console.log('----------- FIN -----------');
                            reintentarErroneos();
                        }
                    },
                    error: function (errors) {
                        console.log("----------- ERROR -----------");
                        progress( pbarId );
                        errorLiq.push( liquidacionMes );
                        countResponse++;
                        if ( countResponse == countRequest ) {
                            console.log('----------- FIN -----------');
                            reintentarErroneos();
                        }
                    }
                })
            }

            function reintentarErroneos(){
                ultimaLiquidacion = "";
                paginasProcesadas = 0;
                paginasTotales = errorLiq.length;
                pbarId = 'prog_retry';
                if ( errorLiq.length > 0 ) {
                    errorLiq.map((liquidacion, index) => {
                        enviarLiquidacion(liquidacion);
                    })
                }
            }

            function progress(progressId) {
                paginasProcesadas = paginasProcesadas + 1;

                if (paginasProcesadas <= paginasTotales) {
                    //    $("#progreso").empty().text("Proceso completo");
                    progreso = (paginasProcesadas * 100) / paginasTotales;
                    switch ( progressId ) {
                        case 'prog':
                            $("#progreso").empty().text("Procesando páginas " + paginasProcesadas + " de " + paginasTotales);
                            break;
                        case 'prog_retry':
                            $("#text_retry").empty().text("Reenviando erroneos " + paginasProcesadas + " de " + paginasTotales);
                            break;
                        default:
                            break;
                    }

                    let pbar = document.getElementById(progressId);
                    pbar.style.width = progreso + "%";

                }


            }

        });

    </script>
@endsection
