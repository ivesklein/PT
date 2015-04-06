<div class="page page-table">
                <?php echo isset($message)? $message : ""; ?>
                <div class="row">
                    <div class="col-lg-3 .col-xsm-6" id="curso">
                        <div class="panel mini-box">
                            <span class="box-icon bg-info"><i class="fa fa-graduation-cap"></i></span>
                            <div class="box-info">
                                <p class="text-muted">Curso Registrado</p>
                                <a class='btn btn-info' href='http://webcursos.uai.cl/course/view.php?id=<?=$wc_course?>' target='_blanc'>Ver</a>
                            </div>
                            <hr class='todo'></hr>
                            <span class="box-icon todo"></span>
                            <div class="box-info todo">
                                <div class='btn btn-warning' id='confcourse'>Cambiar</div>
                            </div>
                            <div id="selectcourse"></div>
                        </div>
                    </div>
                    <div class="col-lg-3 .col-xsm-6" id="lti">
                        <div class="panel mini-box">
                            <span class="box-icon bg-info"><i class="fa fa-retweet"></i></span>
                            <div class="box-info">
                                <p class="size-h3">Recursos LTI</p>
                                <p class="text-muted"><?=$lti?></p>
                            </div>
                            <hr class='todo'></hr>
                            <span class="box-icon todo"></span>
                            <div class="box-info todo">
                                <div class='btn btn-warning' id='clti'>Crear</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 .col-xsm-6" id="tareas">
                        <div class="panel mini-box">
                            <span class="box-icon bg-warning"><i class="fa fa-pencil"></i></span>
                            <div class="box-info">
                                <p class="size-h2">Tareas</p>
                                <p class="text-muted"><?=$tareas?></p>
                            </div>
                            <hr class='todo'></hr>
                            <span class="box-icon todo"></span>
                            <div class="box-info todo">
                                <div class='btn btn-warning' id='ctareas'>Actualizar</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 .col-xsm-6" id="usuarios">
                        <div class="panel mini-box">
                            <span class="box-icon bg-success"><i class="fa fa-users"></i></span>
                            <div class="box-info">
                                <p class="size-h2">Usuarios</p>
                                <p class="text-muted"><?=$usuarios?></p>
                            </div>
                            <hr class='todo'></hr>
                            <span class="box-icon todo"></span>
                            <div class="box-info todo">
                                <div class='btn btn-warning' id='cusuarios'>Actualizar</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-offset-3 col-lg-6 .col-xsm-6" id="todo">
                        <div class="panel mini-box">
                            <span class="box-icon bg-success"><i class="fa fa-cubes"></i></span>
                            <div class="box-info">
                                <p class="size-h2">Todo <?=$todo?></p>
                                <div class='btn btn-warning todo' id='ctodo'>Actualizar todo </div>
                            </div>
                        </div>
                    </div>

                </div>

<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="gridSystemModalLabel"><?=Auth::user()->wc_id ?></h4>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="form-group"><label>Contraseña Webcursos</label><input type="password" class="form-control" id="passi"></div>
            <div class="form-group">
                <div class="btn btn-warning" id="ok">Realizar Cambios  <i style='display:none' class='fa fa-spin fa-refresh waiting'></i><font id="percent"></font></div>
            </div>
          </div>
        </div>
      </div>        
    </div>
  </div>
</div>


<script src="js/modal.js"></script>
<script type="text/javascript">
	

    ok1=false;

    var resumen = <?=$resumen ?>;

    $(function() {

        //$('.modal').modal();

        if(resumen.lti == 0)
            $('#lti .todo').hide();
        if(resumen.tareas == 0)
            $('#tareas .todo').hide();
        if(resumen.usuarios == 0)
            $('#usuarios .todo').hide();
        if(resumen.todo == 0)
            $('#todo .todo').hide();
    })

    var todo = "";

    $('#clti').click(function() {
        todo = "reglti";
        $('.modal').modal("show");
    });

    $('#ctareas').click(function() {
        todo = "regtareas";
        $('.modal').modal("show");
    });

    $('#cusuarios').click(function() {
        todo = "regusuarios";
        $('.modal').modal("show");
    });

    $('#ctodo').click(function() {
        todo = "regtodo";
        $('.modal').modal("show");
    });

    $('#confcourse').click(function() {
        todo = "cursos";
        $('.modal').modal("show");
    });

    var tot = 0;
    var totdone = 0;

    $('#ok').on("click", function() {
        var res=$('#passi').val();
        
        if(res!=null && res!=""){
            $(".waiting").show();
            $('#ok').addClass("disabled");
             $('#mensaje').hide();
            var datos = {
                "f":"Webcursos_"+todo,
                "p":res
            };
            if(todo=="regusuarios"){
                $('#percent').html("6%");
            }
            ajx({
                data:datos,
                ok:function(data) {

                    $(".waiting").hide();
                    $('#ok').removeClass("disabled");
                    $('.modal').modal("hide");
                    if(todo=="cursos"){
                        $('#selectcourse').append("<span class='ui-select'><select></select></span><div class='btn btn-warning' id='btnselectcourse'>Seleccionar</div>")
                        for(i in data.data){
                            var item = data.data[i];
                            $('#selectcourse select').append("<option value='"+item.id+"'>"+item.title+"</option>");
                        }
                        $('#btnselectcourse').append("<div class='btn btn-success sel'>Elegir</div>")
                    }else if(todo=="regusuarios"){
                        $(".waiting").show();
                        $('#ok').addClass("disabled");

                        var n = data['n'];
                        var done = data['done'];
                        tot = n;
                        totdone = done;
                        var perc = Math.floor((done*94/n)+6);
                        $('#percent').html(perc+"%");
                        if(n==done){
                            //location.reload();
                        }else{
                            regusuarios(n,res);
                        }
                    }else{
                        //location.reload();    
                    }
                    
                },
                error:function(data) {
                    $(".waiting").hide();
                    $('#ok').removeClass("disabled");
                    $('.modal').modal("hide");
                    alert(data);
                }
            });
        }
    });

function regusuarios(n,res) {
    var datos = {
        "f":"Webcursos_regusuarios",
        "p":res
    }
    ajx({
        data:datos,
        ok:function(data) {
            var n = data['n'];
            var done = data['done'];
            //tot = n;
            totdone += done;
            var perc = Math.floor((totdone*94/tot)+6);
            $('#percent').html(perc+"%");
            if(n==done){
                //location.reload();
            }else{
                regusuarios(n,res);
            }
        },
        error:function(data) {
            $(".waiting").hide();
            $('#ok').removeClass("disabled");
            $('.modal').modal("hide");
            alert(data);
        }
    });
}



	/*$('#confcourse').on("click", function() {
		var res=$('#passi').val();
        
		if(res!=null && res!=""){
            $(".waiting").show();
            $('#confcourse').addClass("disabled");
             $('#mensaje').hide();
            var datos = {
                "f":"Webcursos_cursos",
                "p":res
            };
            ajx({
                data:datos,
                ok:function(data) {
                    if(ok1==false){
                    	$('#selectcourse').append("<span class='ui-select'><select></select></span><div class='btn btn-warning' id='btnselectcourse'></div>")
                        for(i in data.data){
                        	var item = data.data[i];
                        	$('#selectcourse select').append("<option value='"+item.id+"'>"+item.title+"</option>");
                        }
                        $('#btnselectcourse').append("<div class='btn btn-success sel'>Elegir</div>")
                        ok1=true;
                    }
                    $(".waiting").hide();
                    $('#confcourse').removeClass("disabled");
                },
                error:function(data) {
                    $(".waiting").hide();
                    $('#confcourse').removeClass("disabled");
                    alert(data);
                }
            });
		}else{
            $('#mensaje').html("Debe ingresar contraseña de webcursos para poder realizar los cambios.").show();
            $("#passi").focus();
        }

	});*/

	$("#btnselectcourse").on("click", ".sel", function() {
		var id = $('#selectcourse select').val();
	    var datos = {
            "f":"Webcursos_setcurso",
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
                "f":"Webcursos_registrar",
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
                "f":"Webcursos_crearrecursos",
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