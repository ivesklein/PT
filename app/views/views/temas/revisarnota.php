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

        <div class="col-md-12 caja" style="display:none;" id="modelito">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> <font class="titulo"></font></strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-horizontal">
                                <h4 class="col-sm-offset-2 alumno1" style="margin-top: 0;"></h4>
                                <div class="form-group">
                                    <label for="" class="col-sm-2">Nota</label>
                                    <div class="col-sm-10">
                                        <input class="form-control nota nota1" type="number" min="1" max="7" step="0.1"></input>
                                    </div>
                                    
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-2">Feedback</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control feedback feedback1"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-horizontal">
                                <h4 class="col-sm-offset-2 alumno2" style="margin-top: 0;"></h4>
                                <div class="form-group">
                                    <label for="" class="col-sm-2">Nota</label>
                                    <div class="col-sm-7">
                                        <input class="form-control nota nota2" type="number" min="1" max="7" step="0.1"></input>
                                    </div>
                                    <div class="col-sm-3">
                                        <a href="#" class="btn btn-warning verentrega" target="_blanc">Ver Entrega</a>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-2">Feedback</label>
                                    <div class="col-sm-10">
                                        <textarea class="form-control feedback feedback2"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr></hr>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10">
                            <div class="btn btn-success submit"><font class="savelabel">Evaluar</font><font class="wait-icon"> <i class="fa fa-refresh fa-spin"></i></font></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="js/collapse.js"></script>
    <script type="text/javascript">
        $(function() {

            $(".tareas").on("keypress",".nota",function(event) {
                if ( event.which == 44 ) {
                    event.preventDefault();
                    $(this).val($(this).val()+".");
                }
            });

            function enviar() {

                var id = $(this).attr("n");
                var top = $(this).parent().parent().parent();
                var nota = JSON.stringify([ top.find(".nota.nota1").val() , top.find(".nota.nota2").val() ]);
                var feed = JSON.stringify([ top.find(".feedback.feedback1").val() , top.find(".feedback.feedback2").val() ]);
                $(this).addClass("waiting");

                var datos = {
                    f:"Tareas_setnota",
                    id:angular.element($('.page')).scope().idtema,
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
                console.log($(this).find(".savelabel").html())

                if($(this).find(".savelabel").html()=="Modificar"){
                    $(this).parent().parent().parent().find(".nota").attr("disabled",false);
                    $(this).parent().parent().parent().find(".feedback").attr("disabled",false);
                    $(this).addClass("btn-success").removeClass("btn-warning").find(".savelabel").html("Guardar");
                }else if($(this).find(".savelabel").html()=="Guardar"){
                    $(this).addClass("waiting");
                    var id = $(this).attr("n");
                    var top = $(this).parent().parent().parent();
                    var nota = JSON.stringify([ top.find(".nota.nota1").val() , top.find(".nota.nota2").val() ]);
                    var feed = JSON.stringify([ top.find(".feedback.feedback1").val() , top.find(".feedback.feedback2").val() ]);

                    var datos = {
                        f:"Tareas_setnota",
                        id:angular.element($('.page')).scope().idtema,
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
                    f:"Memorias_getnotas",
                    id:angular.element($('.page')).scope().idtema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        var modelito = $('#modelito');
                        $("#modelito .alumno1").html(data.alumno1);
                        $("#modelito .alumno2").html(data.alumno2);

                        $("#titulomaestro").html(data.grupo);

                        for(n in data.data){
                            var tarea = data.data[n];
                            $('.tareas').append(modelito.clone().attr("id","t"+n).show());
                            
                            $("#t"+n+" .titulo").html(tarea.title);

                            if(tarea.tipo<3){

                                $("#t"+n+" .panel-heading").attr("data-toggle","collapse");
                                $("#t"+n+" .panel-heading").attr("data-target","#t"+n+" .panel-body");
                                $("#t"+n+" .panel-heading").attr("aria-expanded","false");
                                $("#t"+n+" .panel-heading").attr("aria-controls","collapseExample");

                                $("#t"+n+" .panel-body").addClass("collapse");

                                $("#t"+n).attr("ng-controller","CollapseCtrl");
                                
                                $("#t"+n+" .panel-heading").collapse();
                                //angular.bootstrap($("#t"+n), ["app.ui.ctrls"]);

                            }

                            if(tarea.active==0){//disable all
                                $("#t"+n+" .nota").attr("disabled",1);
                                $("#t"+n+" .feedback").attr("disabled",1);
                                $("#t"+n+" .submit").addClass("disabled").removeClass("btn-success").addClass("btn-default").html("Aun no");
                                $("#t"+n+" .verentrega").hide();
                                $("#t"+n+" .panel-body").css("background","#f6f6f6");
                                $("#t"+n+" .panel-heading").append('<font style="color:red;" class="pull-right">'+tarea.date+'</font>');


                            }if(tarea.active==1){//if nota mostrar
                                if(tarea.nota!=""){
                                    var notas = JSON.parse(tarea.nota);
                                    var feedbacks = JSON.parse(tarea.feedback);
                                    $("#t"+n+" .nota.nota1").val(notas[0]);
                                    $("#t"+n+" .nota.nota2").val(notas[1]);
                                    $("#t"+n+" .feedback.feedback1").val(feedbacks[0]);
                                    $("#t"+n+" .feedback.feedback2").val(feedbacks[1]);
                                }
                                
                                $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                if(tarea.nota!="" || tarea.tipo<3){
                                    $("#t"+n+" .nota").attr("disabled",1);
                                    $("#t"+n+" .feedback").attr("disabled",1);
                                    $("#t"+n+" .submit").attr("n",tarea.id).addClass("btn-warning").removeClass("btn-success").on("click",modify).find(".savelabel").html("Modificar");
                                    $("#t"+n+" .panel-body").css("background","#f6f6f6");
                                }else{
                                    $("#t"+n+" .submit").attr("n",tarea.id).on("click",enviar);
                                }
                                if(tarea.url!=""){
                                    $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                }else{
                                    $("#t"+n+" .verentrega").hide();
                                }

                            }if(tarea.active==2){//disable all if nota mostrar
                                $("#t"+n+" .nota").attr("disabled",1).val(tarea.nota);
                                $("#t"+n+" .feedback").attr("disabled",1).val(tarea.feedback);
                                $("#t"+n+" .submit").attr("n",tarea.id).hide();
                                if(tarea.url!=""){
                                    $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                }else{
                                    $("#t"+n+" .verentrega").hide();
                                }
                                $("#t"+n+" .panel-body").css("background","#f6f6f6");
                            }

                            if(tarea.tipo>=3){
                                $("#t"+n+" .glyphicon").removeClass("glyphicon-check").addClass("glyphicon-user");
                            }

                        }//for
                    }//ok
                });//ajx
            },100);
        });
    </script>

</div>