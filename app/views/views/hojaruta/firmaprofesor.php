<?php //firma hoja de ruta profesor ?>
<div class="page page-table">

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Firma Hoja de Ruta</strong></div>
        <div class="panel-body">
             <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-sm-2">Grupo</label>
                    <div class="col-sm-10" id="grupo"></div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2">Tema</label>
                    <div class="col-sm-10" id="tema"></div>
                </div>
            </div>
            <hr></hr>
			<p><?php if(isset($declaracion)){echo $declaracion;}else{echo "Declaración";}?></p>
            <div class="col-xs-offset-2 col-xs-3"><div class="btn btn-info" id="aceptardec">Aceptar declaración</div></div>

        </div>
    </div>

    <script type="text/javascript">

    	$(function() {

            $("#aceptardec").on("click", function() {

                var datos = {
                    f:"HojaRuta_firmaprofesor",
                    id:angular.element($('.page')).scope().idtema,
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        location = "#/listahojasruta";
                    }//ok
                });//ajx
            })

            window.setTimeout(function(){
                var datos = {
                    f:"Memorias_memoria",
                    id:angular.element($('.page')).scope().idtema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                    	$("#tema").html(data.data.titulo);
                        $("#grupo").html(data.data.grupo);
                    }//ok
                });//ajx
            },100);
        });

    </script>

</div>