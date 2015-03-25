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
        </div>
    </div>

    <style type="text/css">
    
        .disabled .panel-body{
            background: #f6f6f6;
        }

        .rechazado, .aceptado{
            display: none;
        }

        .panel-success .aceptado{
            display: block;
        }

        .panel-success .waiting{
            display: none;
        }

        .panel-danger .rechazado{
            display: block;
        }

        .panel-danger .waiting{
            display: none;
        }

        .disabled .waiting{
            display: none;
        }


    </style>

    <div class="row">
        <div class="col-xs-6">
            <div class="panel" id="a1">
                    <div class="panel-heading"><strong><span class="glyphicon glyphicon-user"></span> Memorista <font id="a1name"></font></strong></div>
                    <div class="panel-body">
                    <p id="dec-a1"></p>
                    <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
                    <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
                    <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
                    </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="panel" style="margin-right: 7px;" id="a2">
                    <div class="panel-heading"><strong><span class="glyphicon glyphicon-user"></span> Memorista <font id="a2name"></font></strong></div>
                    <div class="panel-body">
                    <p id="dec-a2"></p>
                    <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
                    <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
                    <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
                    </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel" style="margin-right: 7px;" id="prof">
                    <div class="panel-heading"><strong><span class="fa fa-graduation-cap"></span> Profesor Guía <font id="profename"></font></strong></div>
                    <div class="panel-body">
                    <p id="dec-prof"></p>
                    <div class="col-xs-offset-2 col-xs-3 boton" style="display:none;"><div class="btn btn-info" id="aceptardec">Aceptar declaración</div></div>
                    <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
                    <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
                    <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
                    </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <div class="panel" style="margin-right: 7px;" id="revisor">
                    <div class="panel-heading"><strong><span class="glyphicon glyphicon-indent-left"></span> Revisor Aleatorio</strong></div>
                    <div class="panel-body">
                    <p id="dec-aleatorio"></p>
                    <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
                    <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
                    <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
                    </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <div class="panel" id="secre1">
                    <div class="panel-heading"><strong><span class="fa fa-gavel"></span> Secretaría Académica</strong></div>
                    <div class="panel-body">
                    <p id="dec-secre1"></p>
                    <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
                    <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
                    <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
                    </div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="panel" style="margin-right: 7px;" id="secre2">
                    <div class="panel-heading"><strong><span class="fa fa-gavel"></span> Secretaría Académica</strong></div>
                    <div class="panel-body">
                    <p id="dec-secre2"></p>
                    <span class="aceptado pull-right glyphicon glyphicon-ok-circle" style="color: green; font-size: 48px;"></span>
                    <span class="rechazado pull-right glyphicon glyphicon-remove-circle" style="color: red; font-size: 48px;"></span>
                    <span class="waiting pull-right glyphicon glyphicon-edit" style="font-size: 48px;"></span>
                    </div>
            </div>
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
                    f:"HojaRuta_estado",
                    id:angular.element($('.page')).scope().idtema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                    	$("#tema").html(data.data.titulo);
                        $("#grupo").html(data.data.grupo);



                        function color (n) {
                            switch (n) {
                                case 1:
                                    return "panel-default";
                                    break;
                                case 2:
                                    return "panel-success";
                                    break;
                                case -1:
                                    return "panel-danger";
                                    break;
                                case 0:
                                    return "panel-default disabled";
                                    break;

                                default:
                                    return "panel-default disabled";
                                    break;
                            }
                        }

                        if("alumno1" in data["hoja"]){
                            if("name" in data["hoja"]["alumno1"]){
                                $('#a1name').html(data["hoja"]["alumno1"]["name"]);
                            }
                            $('#a1').addClass(color(data["hoja"]["alumno1"]["status"]));
                            $('#dec-a1').html(data["hoja"]["alumno1"]["declaracion"]);
                        }
                        if("alumno2" in data["hoja"]){
                            if("name" in data["hoja"]["alumno2"]){
                                $('#a2name').html(data["hoja"]["alumno2"]["name"]);
                            }
                            $('#a2').addClass(color(data["hoja"]["alumno2"]["status"]));
                            $('#dec-a2').html(data["hoja"]["alumno1"]["declaracion"]);
                        }
                        if("profesor" in data["hoja"]){
                            if("name" in data["hoja"]["profesor"]){
                                $('#profename').html(data["hoja"]["profesor"]["name"]);
                            }
                            $('#prof').addClass(color(data["hoja"]["profesor"]["status"]));
                            if(data["hoja"]["profesor"]["status"]==1){
                                $('.boton').show();
                            }
                            $('#dec-prof').html(data["hoja"]["profesor"]["declaracion"]);
                        }
                        if("aleatorio" in data["hoja"]){
                            if("name" in data["hoja"]["aleatorio"]){
                                $('#revisorname').html(data["hoja"]["aleatorio"]["name"]);
                            }
                            $('#revisor').addClass(color(data["hoja"]["aleatorio"]["status"]));
                            $('#dec-aleatorio').html(data["hoja"]["aleatorio"]["declaracion"]);
                        }
                        if("secretaria1" in data["hoja"]){
                            if("name" in data["hoja"]["secretaria1"]){
                                $('#secrename1').html(data["hoja"]["secretaria1"]["name"]);
                            }
                            $('#secre1').addClass(color(data["hoja"]["secretaria1"]["status"]));
                            $('#dec-secre1').html(data["hoja"]["secretaria1"]["declaracion"]);
                        }
                        if("secretaria2" in data["hoja"]){
                            if("name" in data["hoja"]["secretaria2"]){
                                $('#secrename2').html(data["hoja"]["secretaria2"]["name"]);
                            }
                            $('#secre2').addClass(color(data["hoja"]["secretaria2"]["status"]));
                            $('#dec-secre2').html(data["hoja"]["secretaria2"]["declaracion"]);
                        }

                    }//ok
                });//ajx
            },100);
        });

    </script>

</div>