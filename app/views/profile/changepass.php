<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Cambiar Contraseña</strong></div>
                <div class="panel-body">
                	<div class="form-horizontal">
                         <input type="hidden" name="f" value="changepass"></input>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Contraseña Actual</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="antpass"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Nueva Contraseña</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="newpass"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Repetir Nueva Contraseña</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="newrepeat"></input>
                            </div>
                        </div>
	                	<div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
	                		    <div class="btn btn-success" id="submitpass">Cambiar</div>
                		    </div>
                        </div>
                	</div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
    	//$('#subir').bootstrapFileInput();
    </script>

</div>