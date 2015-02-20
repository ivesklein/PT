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
                    $message = "<div class='alert alert-warning'>No hay temas de memoria registrados.</div>";
                }

                ?>
                <?php echo isset($message)? $message : ""; ?>
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
                            <div class="col-xs-2"><input class="form-control" type="password" id="passi" placeholder="wc pass"></input></div>
                            <div class="col-xs-8">
                                <div class="btn btn-warning">Actualizar</div><div class="space"></div>
                                <div class="btn btn-warning" id="regusers">Registrar Usuarios</div><div class="space"></div>
                                <div class="btn btn-warning" id="recursos">Crear Recursos en Curso</div>
                            </div>
                            <div class="col-xs-12">
                                <div id="mensaje" class='alert alert-danger' style="display:none;"></div>
                            </div>
                            <div class="row" id="porcregistrado" data-ng-controller="ProgressCtrl">
                                <h3>Progreso</h3>
                                <div class="col-xs-12">
                                    <progressbar class="progress-striped active" value="dynamic" type="{{type}}">{{dynamic}}%</progressbar>
                                </div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="tableusers">
                                        
                                    </tbody>
                                </table>
                            </div>

                            

                        </div>
                </div>

                <?php

            }else{
                echo "<div class='alert alert-warning'>Aun no se selecciona curso para llevar el proceso</div><div class='col-xs-2'><input class='form-control' type='password' id='passi' placeholder='wc pass'></input></div><div class='btn btn-warning' id='confcourse'>Configurar</div>
                <div id='mensaje' class='alert alert-danger' style='display:none;'></div>
                <div id='selectcourse'></div>
                <div id='btnselectcourse'></div>
                ";
            }
        }else{
            echo "<div class='alert alert-danger'>No hay semestre activo. Coordinación debe activar un semestre para poder continuar.</div>";
        }
    ?>




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
		var res=$('#passi').val();
		if(res!=null && res!=""){
             $('#mensaje').hide();
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
		}else{
            $('#mensaje').html("Debe ingresar contraseña de webcursos para poder realizar los cambios.").show();
            $("#passi").focus();
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
	});


    function regusers (limit, pass) {
        

            var datos = {
                "f":"ajxregistrarwc",
                "p":pass,
                "n":limit
            };
            ajx({
                data:datos,
                ok:function(data) {
                    console.log(data);
                    if("continue" in data){

                        asd = data;
                        var total = Object.keys(data.users).length;
                        var actual = +data["continue"]-1;
                        var perc = Math.floor(actual*90/total);

                        var scope = angular.element($("#porcregistrado")).scope();
                        scope.$apply(function(s){s.dynamic=10+perc;s.update;})
                        regusers(data["continue"],pass);
                    }else{
                        var perc = 90;
                        console.log(perc);
                        var scope = angular.element($("#porcregistrado")).scope();
                        scope.$apply(function(s){s.dynamic=10+perc;s.update;});
                         $('#regusers').removeClass("disabled");
                        //desbloquear, actualizar, etc
                    }
                }
            });



    }

    $('#regusers').on("click", function() {
        var res=$('#passi').val();
        if(res!=null && res!=""){
            $('#mensaje').hide();
            //desabilitar botones
            $('#regusers').addClass("disabled");

            var scope = angular.element($("#porcregistrado")).scope();
            scope.$apply(function(s){s.dynamic=20;s.update;})
            //esperando
            //$("#progress-bar").append('<progressbar class="progress-striped active" value="dynamic" type="{{type}}">{{type}}</progressbar>');

            regusers(1,res);
        }else{
            $('#mensaje').html("Debe ingresar contraseña de webcursos para poder realizar los cambios.").show();
            $("#passi").focus();
        }

    });

    $('#recursos').on("click", function() {
        var res=$('#passi').val();
        if(res!=null && res!=""){
            $('#mensaje').hide();
            //desabilitar botones
            $('#recursos').addClass("disabled");

            var datos = {
                "f":"ajxcrearrecursos",
                "p":res
            };
            ajx({
                data:datos,
                ok:function(data) {
                    console.log(data);
                    $('#recursos').removeClass("disabled");
                    $("#mensaje").hide();

                },
                error:function(data){
                    if(data=="bad wc login"){
                        $("#mensaje").html("Contraseña erronea")
                    }
                    if(data=="no tareas"){
                        $("#mensaje").html("No se ha configurado las tareas <a href='<?=url("#/tareas")?>' class='btn btn-info'>Configurar Tareas</a>")
                    }
                    if(data=="no hay semestre activo"){
                        $("#mensaje").html("No hay semestre activo. Coordinación debe activar un semestre para poder continuar.</a>")
                    }
                    if(data=="not permission"){
                        $("#mensaje").html("Session Caducada, porfavor ingrese otra vez <a href='<?=url("login")?>' class='btn btn-info'>Ingresar</a>")
                    }
                    if(data=="faltan variables"){
                        $("#mensaje").html("Ingrese Contraseña")
                    }
                    $("#mensaje").show();
                    $('#recursos').removeClass("disabled");

                }
            });
        }else{
            $('#mensaje').html("Debe ingresar contraseña de webcursos para poder realizar los cambios.").show();
            $("#passi").focus();
        }

    });
</script>

</div>