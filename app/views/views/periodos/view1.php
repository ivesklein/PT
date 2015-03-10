<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Ingresar Temas de Memoria</strong></div>
                <div class="panel-body">
                	<form class="form-horizontal" method="POST" action="#/vista2" enctype="multipart/form-data">
                         <input type="hidden" name="f" value="Memorias_crear"></input>
                        <div class="form-group">
                            <label for="" class="col-sm-2">Semestre</label>
                                <div class="col-sm-6">
                                            <?=$periodo?>
                                </div>
                            <div class="col-sm-4">
                                <a href="#/vista3" id="addper" class="btn btn-warning">Agregar Periodo</a>
                            </div>
                        </div>
	                	<div class="form-group">
	                		<label for="" class="col-sm-2">Seleccionar Archivo</label>
                            <div class="col-sm-10">
                                <input id="subir" type="file" name="csv" title="Buscar">
                            </div>
	                	</div>
                        <div class="row" id="mesbox2" style="display:none;"><div class="col-md-12"><div class="alert alert-danger" id="mensaje2"></div></div></div>
	                	<div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
	                		    <input type="submit" class="btn btn-success" value="Subir">
                		    </div>
                        </div>
                	</form>
                </div>
            </div>

        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-save"></span> Plantilla de Ejemplo</strong></div>
                <div class="panel-body">
                	<a href="examples/temas.csv"><span class="glyphicon glyphicon-file"></span> Plantilla Ejemplo.csv</a>

                </div>
            </div>         
        </div>
    </div>

    <script type="text/javascript">
    	//$('#subir').bootstrapFileInput();
        $(function(){
            if($('input[name="periodo"]').val()!=0){
                $("#addper").hide();
            }


        });

        <?php if(Session::has('alert')){ 

        $mensaje = Session::get('alert');
        Session::forget('alert');

        ?>

        $(function() {
            $('#mesbox2').show();
            $('#mensaje2').html("<?=$mensaje?>");
        });


    <?php } ?>
    </script>

</div>