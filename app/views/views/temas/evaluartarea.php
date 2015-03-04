<?php //Ingreso Temas Memoria ?>
<div class="page page-table" data-ng-controller="TareaController">

    <style type="text/css">

        .wait-icon{
            display: none;
        }

        .waiting .wait-icon{
            display: block;
        }

    </style>

    <link rel="stylesheet" href="bootstrap-datepicker/css/datepicker.css" />
    <script src="bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <div class="row tareas">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-file"></span> <font id="titulomaestro"></font></strong>
                </div>
            </div>
        </div>

        <div class="col-md-6 caja" style="display:none;" id="modelito">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> <font class="titulo"></font></strong></div>
                <div class="panel-body">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="col-sm-2">Nota</label>
                            <div class="col-sm-7">
                                <input class="form-control nota" type="number" min="1" max="7" step="0.1"></input>
                            </div>
                            <div class="col-sm-3">
                                <a href="#" class="btn btn-warning verentrega" target="_blanc">Ver Entrega</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2">Feedback</label>
                            <div class="col-sm-10">
                                <textarea class="form-control feedback"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <div class="btn btn-success submit"><font class="savelabel">Evaluar</font><font class="wait-icon"> <i class="fa fa-refresh fa-spin"></i></font></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(function() {

            function enviar() {

                var id = $(this).attr("n");
                var nota = $(this).parent().parent().parent().find(".nota").val();
                var feed = $(this).parent().parent().parent().find(".feedback").val();
                $(this).addClass("waiting");

                var datos = {
                    f:"ajxsetnota",
                    id:angular.element($('.page')).scope().tema,
                    tarea:id,
                    nota:nota,
                    feedback:feed
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        location.reload();
                    }//ok
                });//ajx
            }

            function modify() {

                if($(this).find(".savelabel").html()=="Modificar"){
                    $(this).parent().parent().parent().find(".nota").attr("disabled",false);
                    $(this).parent().parent().parent().find(".feedback").attr("disabled",false);
                    $(this).addClass("btn-success").removeClass("btn-warning").find(".savelabel").html("Guardar");
                }else if($(this).find(".savelabel").html()=="Guardar"){
                    $(this).addClass("waiting");
                    var id = $(this).attr("n");
                    var nota = $(this).parent().parent().parent().find(".nota").val();
                    var feed = $(this).parent().parent().parent().find(".feedback").val();

                    var datos = {
                        f:"ajxsetnota",
                        id:angular.element($('.page')).scope().tema,
                        tarea:id,
                        nota:nota,
                        feedback:feed,
                        modify:1
                    }
                    ajx({
                        data:datos,
                        ok:function(data) {
                            console.log(data);
                            location.reload();
                        }//ok
                    });//ajx
                }
            }

            window.setTimeout(function(){
                var datos = {
                    f:"ajxgettareas",
                    id:angular.element($('.page')).scope().tema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        var modelito = $('#modelito');

                        $("#titulomaestro").html(data.grupo);

                        for(n in data.data){
                            var tarea = data.data[n];
                            $('.tareas').append(modelito.clone().attr("id","t"+n).show());
                            
                            $("#t"+n+" .titulo").html(tarea.title);

                            if(tarea.active==0){//disable all
                                $("#t"+n+" .nota").attr("disabled",1);
                                $("#t"+n+" .feedback").attr("disabled",1);
                                $("#t"+n+" .submit").addClass("disabled");
                                $("#t"+n+" .verentrega").hide();
                                $("#t"+n+" .panel-body").css("background","#f6f6f6");
                                $("#t"+n+" .panel-heading").append('<font style="color:red;" class="pull-right">'+tarea.date+'</font>');


                            }if(tarea.active==1){//if nota mostrar
                                $("#t"+n+" .nota").val(tarea.nota);
                                $("#t"+n+" .feedback").val(tarea.feedback);
                                $("#t"+n+" .submit").attr("n",tarea.id).on("click",enviar);
                                $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                if(tarea.nota!=""){
                                    $("#t"+n+" .nota").attr("disabled",1).val(tarea.nota);
                                    $("#t"+n+" .feedback").attr("disabled",1).val(tarea.feedback);
                                    $("#t"+n+" .submit").attr("n",tarea.id).addClass("btn-warning").removeClass("btn-success").html("Modificar").on("click",modify);
                                    $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                    $("#t"+n+" .panel-body").css("background","#f6f6f6");
                                }

                            }if(tarea.active==2){//disable all if nota mostrar
                                $("#t"+n+" .nota").attr("disabled",1).val(tarea.nota);
                                $("#t"+n+" .feedback").attr("disabled",1).val(tarea.feedback);
                                $("#t"+n+" .submit").attr("n",tarea.id).hide();
                                $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                $("#t"+n+" .panel-body").css("background","#f6f6f6");
                            }

                        }//for
                    }//ok
                });//ajx
            },100);
        });
    </script>

</div>