<?php //asignar revisor aleatorio ?>
<div class="page page-table">

    <link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <style type="text/css">

        .wait-icon{
            display: none;
        }

        .waiting .wait-icon{
            display: block;
        }

    </style>
    <!--script src="js/bloodhound.min.js"></script>
    <script src="js/typeahead.jquery.min.js"></script-->
    <script src="jui/jquery-ui.min.js"></script>

    <div class="panel panel-default periodos">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Asignar Revisor</strong></div>
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
                <div class="form-group">
                    <label for="" class="col-sm-2">Profesor Guía</label>
                    <div class="col-sm-10" id="guia"></div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2">Revisor</label>
                    <div class="col-sm-10" id="">
                        <label class="ui-radio"><input id="cbuscar" name="asd" type="radio" value="0" checked><span>Buscar</span></label>
                        <label class="ui-radio"><input id="cotro" name="asd" type="radio" value="1" ><span>Otro</span></label>
                    </div>
                </div>
                <div class="form-group" id="gbuscar">
                    <label for="" class="col-sm-2">Buscar</label>
                    <div class="col-sm-10" id="buscado">
                        <input type="text" class="form-control" id="buscarprofesor"></input>
                        <input type="hidden" id="prof"></input>
                    </div>
                </div>
                <div class="form-group" style="display:none;" id="gotro">
                    <label for="" class="col-sm-2">Otro</label>
                    <div class="col-sm-10" id="otrado">
                        <div class="col-md-3"><input type="text" class="form-control" id="nombre" placeholder="Nombre"></input></div>
                        <div class="col-md-3"><input type="text" class="form-control" id="apellido" placeholder="Apellido"></input></div>
                        <div class="col-md-3"><input type="text" class="form-control" id="email" placeholder="Email UAI"></input></div>
                    </div>
                </div>
                <hr></hr>
                <div class="form-group" id="gbuscar">
                    <label for="" class="col-sm-2"><?php echo Auth::user()->wc_id;?></label>
                    <div class="col-sm-10" id="buscado">
                        <input type="password" class="form-control" id="wcpass" placeholder="Contraseña webcursos"></input>
                        <input type="hidden" id="prof"></input>
                    </div>
                </div>
                <div class="alert alert-danger" id="mensaje" style="display:none;"></div>
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <div class="btn btn-info" id="submit">Asignar<font class="wait-icon"> <i class="fa fa-refresh fa-spin"></i></font></div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script type="text/javascript">

    	$(function() {

            var option = 0;

            $( "#buscarprofesor" ).autocomplete({
                minLength: 2,
                source: "th/staffs",
                focus: function( event, ui ) {
                //$( "#buscarprofesor" ).val( ui.item.label );
                return false;
                },
                select: function( event, ui ) {
                $( "#buscarprofesor" ).val( ui.item.label );
                $( "#prof" ).val( ui.item.value );
                return false;
            }
            })
            .autocomplete( "instance" )._renderItem = function( ul, item ) {
                return $( "<li>" )
                .append( "<a>" + item.label +" ("+item.comisions+" comisiones)</a>" )
                .appendTo( ul );
            };

            $("#cbuscar, #cotro").on("click",function() {
                var who = $(this).val();
                if(who==1){//otro
                    $("#gbuscar").hide();
                    $("#gotro").show();
                    option = 1;
                }else{//buscar
                    $("#gotro").hide();
                    $("#gbuscar").show();
                    option = 0;
                }
            })

            $("#submit").on("click", function() {

                var ok=false;

                
                if(option==0){
                    //ver busqueda
                    if($("#prof").val()>0){
                        $("#mensaje").hide();
                        ok = true;
                    }else{
                        $("#mensaje").html("Seleccione Revisor").show();
                        $("#buscarprofesor").focus();
                        ok=false;
                    }
                }else{
                    //ver campos
                    if($("#nombre").val()==""){
                        $("#mensaje").html("Ingrese nombre revisor").show();
                        $("#nombre").focus();
                        ok=false;
                    }else if($("#apellido").val()==""){
                        $("#mensaje").html("Ingrese apellido revisor").show();
                        $("#apellido").focus();
                        ok=false;
                    }else if($("#email").val()==""){
                        $("#mensaje").html("Ingrese email revisor").show();
                        $("#email").focus();
                        ok=false;
                    }else if($("#wcpass").val()==""){
                        $("#mensaje").html("Ingrese contraseña webcursos para poder registrar usuario en curso.").show();
                        $("#wcpass").focus();
                        ok=false;
                    }else{
                        $("#mensaje").hide();
                        ok = true;
                    }
                }


                if(ok){
                    var datos = {};
                    $(this).addClass("disabled").addClass("waiting");
                    if(option==0){
                        datos = {
                            f:"ajxasignar",
                            id:angular.element($('.page')).scope().idtema,
                            wcpass:$("#wcpass").val(),
                            option:option,
                            idstaff:$("#prof").val()
                        }
                    }else{
                        datos = {
                            f:"ajxasignar",
                            id:angular.element($('.page')).scope().idtema,
                            wcpass:$("#wcpass").val(),
                            option:option,
                            name:$("#nombre").val(),
                            surname:$("#apellido").val(),
                            email:$("#email").val()
                        }
                    }
                    
                    ajx({
                        data:datos,
                        ok:function(data) {
                            console.log(data);
                            location = "#/listahojasruta";
                        },//ok
                        error:function(data){
                            if(data="bad-login"){
                                $("#mensaje").html("Usuario o contraseña de webcursos inválida.").show();
                            }else{
                                $("#mensaje").html(data).show();
                            }

                            $("#submit").removeClass("disabled").removeClass("waiting");
                        }
                    });//ajx

                }

            })

            window.setTimeout(function(){
                var datos = {
                    f:"ajxgettema",
                    id:angular.element($('.page')).scope().idtema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                    	$("#tema").html(data.data.titulo);
                        $("#grupo").html(data.data.grupo);
                        $("#guia").html(data.data.guia);


                    }//ok
                });//ajx
            },100);
        });

    </script>

</div>