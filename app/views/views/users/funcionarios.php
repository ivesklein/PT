<?php //lista profesores ?>
<div class="page page-table">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default" id="agregarusuarios">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Agregar Usuario</strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group" id="agregarform">
                        
                            <div class="col-md-2"><input type="text" class="form-control" id="nombre" placeholder="Nombre"></input></div>
                            <div class="col-md-2"><input type="text" class="form-control" id="apellido" placeholder="Apellido"></input></div>
                            <div class="col-md-3"><input type="text" class="form-control" id="email" placeholder="Email UAI"></input></div>
                            <div class="col-md-3"><?php

                            $rol = Session::get('rol' ,"0");

                            if($rol == "CA" || $rol == "SA"){
                                $array = array("items"=>array(
                                    "CA"=>array("title"=>"Cordinador Académico", "value"=>"CA"),
                                    "SA"=>array("title"=>"Secretario Académico", "value"=>"SA"),
                                    "P"=>array("title"=>"Profesor", "value"=>"P"),
                                    "PT"=>array("title"=>"Profesor Taller", "value"=>"PT", "sel"=>1),
                                    "AY"=>array("title"=>"Ayudante Taller", "value"=>"AY")
                                ));
                            }elseif($rol == "PT"){
                                $array = array("items"=>array(
                                    "P"=>array("title"=>"Profesor", "value"=>"P"),
                                    "PT"=>array("title"=>"Profesor Taller", "value"=>"PT"),
                                    "AY"=>array("title"=>"Ayudante Taller", "value"=>"AY", "sel"=>1)
                                ));
                            }elseif($rol == "AY"){
                                $array = array("items"=>array(
                                    "P"=>array("title"=>"Profesor", "value"=>"P"),
                                    "AY"=>array("title"=>"Ayudante Taller", "value"=>"AY", "sel"=>1)
                                ));
                            }else{
                                $array = array("items"=>array()); 
                            }
                            $array['id'] = "roldrop";

                            echo View::make("html.drop", $array);



                            ?></div>
                            <div class="col-md-2"><div class="btn btn-success" id="btn-agregar">Agregar</div></div>
                        </div>
                    </div>
                    <div class="row" id="mesbox" style="display:none;"><div class="col-md-12"><div class="alert alert-danger" id="mensaje" style="margin-bottom: 0;"></div></div></div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default" id="agregarlote">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Por lotes</strong><a class="pull-right" href="examples/profesores.csv" style="text-transform: initial;"><span class="glyphicon glyphicon-file"></span> ejemplo.csv</a></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-8">
                                <input id="subir" type="file" name="csv" title="Buscar Archivo">
                            </div>
                            <div class="col-md-4"><div class="btn btn-success" id="btn-agregar2">Agregar</div></div>
                        </div>
                    </div>
                    <div class="row" id="mesbox2" style="display:none;"><div class="col-md-12"><div class="alert alert-danger" id="mensaje2" style="margin-bottom: 0;margin-top: 10px;"></div></div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-default" id="profesorlist">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> Usuarios</strong></div>
        <?=$table?>
    </div>

    <script type="text/javascript">

        $("#profesorlist").on("change", 'input[type="checkbox"]', function() {

            var val = $(this).val();
            var id = $(this).attr("n");
            var name = $(this).parent().parent().parent().find(":nth-child(1)").html()+" "+$(this).parent().parent().parent().find(":nth-child(2)").html();
            var perm = $(this).next().html();

            var action = $(this).is(':checked');
            var act = "";
            //ar name = "yo";
            if(action){
                var res = confirm("¿Realmente desea atribuir permisos de "+val+" a "+name+"?");
                act = "add";
            }else{
                var res = confirm("¿Realmente desea quitar permisos de "+val+" a "+name+"?");
                act = "del";
            }
            if(res==true){
                ajx({
                    data:{
                        f:'Usuarios_editrol',
                        id: id,
                        rol: val,
                        action:act
                    },
                    ok:function(data){
                        
                    }
                });
            }else{
                if(action){
                    $(this).attr("checked",false);
                }else{
                    $(this).attr("checked",true);
                }
                
            }

        });

        $("#btn-agregar").on("click",function() {
            if($("#nombre").val()==""){
                $("#mensaje").html("Ingrese nombre revisor");
                $("#mesbox").show();
                $("#nombre").focus();
                ok=false;
            }else if($("#apellido").val()==""){
                $("#mensaje").html("Ingrese apellido revisor");
                $("#mesbox").show();
                $("#apellido").focus();
                ok=false;
            }else if($("#email").val()==""){
                $("#mensaje").html("Ingrese email revisor");
                $("#mesbox").show();
                $("#email").focus();
                ok=false;
            }else{
                $("#mesbox").hide();
                ok = true;
            }

            if(ok){
                var datos = {};
                $(this).addClass("disabled").addClass("waiting");

                datos = {
                    f:"Usuarios_agregar",
                    name:$("#nombre").val(),
                    surname:$("#apellido").val(),
                    email:$("#email").val(),
                    rol:$("#roldrop").val()
                }
        
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        //location.reload();// = "#/profesores";
                    },//ok
                    error:function(data){
                        $("#mensaje").html(data)
                        $("#mesbox").show();
                        
                        $("#btn-agregar").removeClass("disabled").removeClass("waiting");
                    }
                });//ajx

            }

        })

    </script>
</div>