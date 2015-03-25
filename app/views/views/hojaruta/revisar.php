<?php //firma hoja de ruta profesor ?>
<div class="page page-table">

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Revisar Memoria <font id="tema"></font></strong></div>
        <div class="panel-body">
        	
            <a id="link" class="btn btn-info" target="_blanc">Ver Memoria en webcursos</a>
            <br><br>
            <div class="alert alert-info">Buscar por nombre de grupo: <h3 id="grupo"></h3></div>
            <hr></hr>
			<p><?php if(isset($declaracion)){echo $declaracion;}else{echo "Declaración";}?></p>
            <div class="col-xs-offset-2 col-xs-3"><div class="btn btn-info" id="aceptardec" n="1">Aceptar declaración</div></div>
            <div class="col-xs-offset-1 col-xs-3"><div class="btn btn-warning" id="rechazardec" n="0">Rechazar declaración</div></div>

        </div>
    </div>

    <script type="text/javascript">

    	$(function() {

            $("#aceptardec, #rechazardec").on("click", function() {

                var decision = $(this).attr("n");
                var mes = "";
                if(decision=="1"){
                    mes = "¿Realmente desea Aceptar?";
                }else{
                    mes = "¿Realmente desea Rechazar?";
                }

                var res = confirm(mes);

                if(res==true){
                    var datos = {
                        f:"HojaRuta_revisar",
                        id:angular.element($('.page')).scope().idtema,
                        decision:decision
                    }
                    ajx({
                        data:datos,
                        ok:function(data) {
                            console.log(data);
                            location = "#/revisartemas";
                        }//ok
                    });//ajx
                }
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
                        $("#link").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+data.data.url+"&action=grading");
                    }//ok
                });//ajx
            },100);
        });

    </script>

</div>