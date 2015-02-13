<div class="page page-table">
    <?php 
        $per = Periodo::active_obj();
        if($per!="false"){
            if(!$per->wc_course==""){
                    
                $temas = Subject::wherePeriodo($per->name)->get();
                $reg = 0;
                $notreg = 0;
                $users = array();

                if(!$temas->isEmpty()){
                    foreach ($temas as $tema) {

                        
                        $guia = $tema->guia;
                        if(!empty($guia->wc_uid)){
                            $users[$guia->wc_id] = 1;
                        }else{
                            $users[$guia->wc_id] = 0;
                        }

                        
                        $comision = $tema->comision;
                        if(!$comision->isEmpty()){
                            foreach ($comision as $prof) {
                                if(!empty($prof->wc_uid)){
                                    $users[$prof->wc_id] = 1;
                                }else{
                                    $users[$prof->wc_id] = 0;
                                }
                            }
                        }


                        $alumno1 = $tema->ostudent1;
                        $alumno2 = $tema->ostudent2;
                        
                        //print_r($alumno1);
                        if(!empty($alumno1->wc_uid)){
                            $users[$alumno1->wc_id] = 1;
                        }else{
                            $users[$alumno1->wc_id] = 0;
                        }
                        
                        if(!empty($alumno2->wc_uid)){
                            $users[$alumno2->wc_id] = 1;
                        }else{
                            $users[$alumno2->wc_id] = 0;
                        }
                                
                
                    }

                    foreach ($users as $value) {
                        if($value==0){
                            $notreg++;
                        }elseif ($value==1) {
                            $reg++;
                        }
                    }
                }else{
                    echo "mmm";
                }

                ?>
                <div class="row">
                    <div class="col-lg-3 .col-xsm-6">
                        <div class="panel mini-box">
                            <span class="box-icon bg-info"><i class="fa fa-graduation-cap"></i></span>
                            <div class="box-info">
                                <p class="text-muted">Curso Registrado</p>
                                <a class='btn btn-info' href='http://webcursos.uai.cl/course/view.php?id=<?=$per->wc_course?>' target='_blanc'>Ver</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 .col-xsm-6">
                        <div class="panel mini-box">
                            <span class="box-icon bg-success"><i class="fa fa-users"></i></span>
                            <div class="box-info">
                                <p class="size-h2"><?=$reg?></p>
                                <p class="text-muted">Usuarios Registrados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 .col-xsm-6">
                        <div class="panel mini-box">
                            <span class="box-icon bg-danger"><i class="fa fa-users"></i></span>
                            <div class="box-info">
                                <p class="size-h2"><?=$notreg?></p>
                                <p class="text-muted">No Registrados</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 .col-xsm-6">
                        <div class="panel mini-box">
                            <span class="box-icon bg-warning"><i class="fa fa-question"></i></span>
                            <div class="box-info">
                                <p class="size-h2">0</p>
                                <p class="text-muted">No existentes</p>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="panel panel-default">
                        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Webcursos</strong></div>
                        <div class="panel-body">
                            <div class="btn btn-warning">Actualizar</div><div class="space"></div>
                            <div class="btn btn-warning">Registrar Usuarios</div><div class="space"></div>
                            <div class="btn btn-warning">Crear Recursos en Curso</div>
                        </div>
                </div>

                <?php

            }else{
                echo "<div class='alert alert-warning'>Aun no se selecciona curso para llevar el proceso</div><div class='btn btn-warning' id='confcourse'>Configurar</div>
                <div id='selectcourse'></div>
                <div id='btnselectcourse'></div>
                ";
            }
        }else{
            echo "<div class='alert alert-danger'>No hay periodo activo. Coordinación debe activar un periodo para poder continuar.</div>";
        }
    ?>

</div>


<!--h3>Crear Conexión</h3>
<form action="#/webcursos" method="POST">
<input type="hidden" name="f" value="ltinew"></input>
<label>name</label>
<input type="text" name="name"></input>
<label>Public</label>
<input type="text" name="public"></input>
<label>Secret</label>
<input type="text" name="secret"></input>
<input type="submit" value="Crear"></input>
</form>
<br-->
<?php
/*
$ltis = Consumer::all();


foreach($ltis as $lti){
	echo $lti->name."<br>";
}

*/
?>

<script type="text/javascript">
	
	$('#confcourse').on("click", function() {
		var res = prompt("Ingrese contraseña de webcursos(<?=Auth::user()->wc_id ?>) :");
		if(res!=null && res!=""){
            var datos = {
                "f":"ajxcursos",
                "p":res
            };
            ajx({
                data:datos,
                ok:function(data) {
                	$('#selectcourse').append("<span class='ui-select'><select></select></span>")
                    for(i in data.data){
                    	var item = data.data[i];
                    	$('#selectcourse select').append("<option value='"+item.id+"'>"+item.title+"</option>");
                    }
                    $('#btnselectcourse').append("<div class='btn btn-success sel'>Elegir</div>")
                }
            });
		}

	});

	$("#btnselectcourse").on("click", ".sel", function() {
		var id = $('#selectcourse select').val();
	    var datos = {
            "f":"ajxsetcurso",
            "id":id
        };
        ajx({
            data:datos,
            ok:function(data) {
            	location.reload();
            }
        });
	})

</script>