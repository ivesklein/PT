<?php //firma hoja de ruta profesor ?>
<div class="page page-table">

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Firma Hoja de Ruta</strong></div>
        <div class="panel-body">
        	<p id="tema"></p>
			<p><?php if(isset($declaracion)){echo $declaracion;}else{echo "Declaración";}?></p>
            <div class="col-xs-offset-2 col-xs-3"><div class="btn btn-info" id="aceptardec">Aceptar declaración</div></div>

        </div>
    </div>

    <script type="text/javascript">

    	$(function() {

            $("#aceptardec").on("click", function() {

                var datos = {
                    f:"ajxfirmaprofesor",
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
                    f:"ajxgettema",
                    id:angular.element($('.page')).scope().idtema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                    	$("#tema").html(data.data.grupo+" "+data.data.titulo);
                    }//ok
                });//ajx
            },100);
        });

    </script>

</div>