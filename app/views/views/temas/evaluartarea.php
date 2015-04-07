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
                        <div class="col-sm-5">
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
                        <div class="col-sm-2 text-center">
                            <div class="form-group">
                                <a href="#" class="btn btn-warning verentrega" target="_blanc">Ver Entrega</a>
                            </div>
                            <div class="form-group">
                                <label><i class="fa fa-arrow-left"></i></label>   
                                <div class="btn btn-default equal nota" data-val="0"><font class="eq" style='display:none;'>=</font><font class="ineq">≠</font></div>
                                <label><i class="fa fa-arrow-right"></i></label>
                            </div>
                            <div class="form-group">
                                Subir revisión
                                <input type='file' class="form-control subir nota"></input>
                            </div>
                            <div class="form-group fileup" style="display:none;">
                                <a class="btn btn-info ">Bajar</a>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="form-horizontal">
                                <h4 class="col-sm-offset-2 alumno2" style="margin-top: 0;"></h4>
                                <div class="form-group">
                                    <label for="" class="col-sm-2">Nota</label>
                                    <div class="col-sm-10">
                                        <input class="form-control nota nota2" type="number" min="1" max="7" step="0.1"></input>
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

    <script type="text/javascript">
        $(function() {

            $(".tareas").on("keypress",".nota",function(event) {
                if ( event.which == 44 ) {
                    event.preventDefault();
                    $(this).val($(this).val()+".");
                }
            });

            $(".tareas").on("change", ".nota, .feedback", function(){
                var parent = $(this).parents('.caja');
                console.log(parent.find(".equal").attr("data-val"))
                if(parent.find(".equal").attr("data-val")=="1"){
                    console.log(5)
                    parent.find(".feedback2").val(parent.find(".feedback1").val());
                    parent.find(".nota2").val(parent.find(".nota1").val());
                }
            });

            $(".tareas").on("keyup", ".nota, .feedback", function(){
                var parent = $(this).parents('.caja');
                console.log(parent.find(".equal").attr("data-val"))
                if(parent.find(".equal").attr("data-val")=="1"){
                    console.log(5)
                    parent.find(".feedback2").val(parent.find(".feedback1").val());
                    parent.find(".nota2").val(parent.find(".nota1").val());
                }
            });

            $(".tareas").on("click", ".equal", function(){
                var yo = $(this);
                var parent = $(this).parents('.caja');
                if(yo.attr("data-val")=="0"){//cambiar a igual
                    yo.attr("data-val","1");
                    yo.find(".ineq").hide();
                    yo.find(".eq").show();
                    parent.find(".feedback2").attr("disabled",1);
                    parent.find(".nota2").attr("disabled",1);
                    parent.find(".feedback2").val(parent.find(".feedback1").val());
                    parent.find(".nota2").val(parent.find(".nota1").val());
                }else if(yo.attr("data-val")=="1"){//cambiar a distinto
                    yo.attr("data-val","0");
                    yo.find(".eq").hide();
                    yo.find(".ineq").show();
                    parent.find(".feedback2").attr("disabled",false);
                    parent.find(".nota2").attr("disabled",false);
                }
            });

            function modify() {
                console.log(0)
                if($(this).find(".savelabel").html()=="Guardar"){
                    console.log(2);
                    $(this).addClass("waiting");
                    var id = $(this).attr("n");
                    var top = $(this).parents(".caja");
                    var nota = JSON.stringify([ top.find(".nota.nota1").val() , top.find(".nota.nota2").val() ]);
                    var feed = JSON.stringify([ top.find(".feedback.feedback1").val() , top.find(".feedback.feedback2").val() ]);
                    var file = 0;
                    var ok = true;

                    if(top.find(".subir")[0].files.length>0){
                        file=1;

                        var size = top.find(".subir")[0].files[0].size;
                        if(size>33554432){
                            ok = false;
                            message = "El archivo debe pesar menos de 32MB.";
                        }
                    }
                    
                    var formData = new FormData();

                    formData.append("f", "Tareas_setnota");
                    formData.append("id", angular.element($('.page')).scope().tema); 
                    formData.append("tarea", id);
                    formData.append("nota", nota);
                    formData.append("feedback", feed);
                    formData.append("modify", 1);
                    formData.append("file", file);

                    if(file==1){
                        formData.append("archivo", top.find(".subir")[0].files[0]);
                    }

                    var message = ""; 
                    //hacemos la petición ajax  
                    $.ajax({
                        url: '',  
                        type: 'POST',
                        // Form data
                        //datos del formulario
                        data: formData,
                        //necesario para subir archivos via ajax
                        cache: false,
                        contentType: false,
                        processData: false,
                        //mientras enviamos el archivo
                        //una vez finalizado correctamente
                        success: function(output){
                            if(output=="not logged"){
                                alert(output);
                                window.location = "login";
                            }else{
                                var data1 = JSON.parse(output);
                                if("error" in data1){
                                    alert(data1.error);
                                }else{
                                    location.reload();
                                }
                            }
                        },
                        //si ha ocurrido un error
                        error: function(){
                            $(this).removeClass("waiting");
                            alert("Ha ocurrido un error.");
                            //message = $("<span class='error'>Ha ocurrido un error.</span>");
                            //showMessage(message);
                        }
                    });






                }else if($(this).find(".savelabel").html()=="Modificar"){
                    console.log(1);
                    $(this).parents(".caja").find(".nota").attr("disabled",false);
                    $(this).parents(".caja").find(".feedback").attr("disabled",false);
                    $(this).addClass("btn-success").removeClass("btn-warning").find(".savelabel").html("Guardar");
                    if($(this).parents(".caja").find(".equal").attr("data-val")=="1"){
                        $(this).parents(".caja").find(".feedback2").attr("disabled",1);
                        $(this).parents(".caja").find(".nota2").attr("disabled",1);
                    }

                } 
            }

            window.setTimeout(function(){
                var datos = {
                    f:"Tareas_gettareas",
                    id:angular.element($('.page')).scope().tema
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        var modelito = $('#modelito');

                        $("#titulomaestro").html(data.grupo);
                        $("#modelito .alumno1").html(data.alumno1);
                        $("#modelito .alumno2").html(data.alumno2);

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
                                //$("#t"+n+" .nota").val(tarea.nota);
                                //$("#t"+n+" .feedback").val(tarea.feedback);
                                $("#t"+n+" .submit").attr("n",tarea.id).on("click",modify);
                                $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                var equal = 1;
                                if(tarea.nota!=""){
                                    var notas = JSON.parse(tarea.nota);
                                    $("#t"+n+" .nota.nota1").val(notas[0]);
                                    $("#t"+n+" .nota.nota2").val(notas[1]);
                                    if(notas[0]!=notas[1]){
                                        equal = 0;
                                    }
                                }
                                if(tarea.feedback!=""){
                                    var feedbacks = JSON.parse(tarea.feedback);
                                    $("#t"+n+" .feedback.feedback1").val(feedbacks[0]);
                                    $("#t"+n+" .feedback.feedback2").val(feedbacks[1]);
                                    if(feedbacks[0]!=feedbacks[1]){
                                        equal = 0;
                                    }
                                }

                                if(tarea.file>0){
                                    $("#t"+n+" .fileup").show().find("a").attr("href","feedback/"+tarea.file);
                                }

                                var eqel = $("#t"+n+" .equal");

                                eqel.attr("data-val",equal);
                                if(equal==1){
                                    eqel.find(".ineq").hide();
                                    eqel.find(".eq").show();
                                }else{
                                    eqel.find(".ineq").show();
                                    eqel.find(".eq").hide();
                                }
                                

                                if(tarea.nota!=""){
                                    $("#t"+n+" .submit").attr("n",tarea.id).addClass("btn-warning").removeClass("btn-success").find(".savelabel").html("Modificar");
                                    //$("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                    $("#t"+n+" .panel-body").css("background","#f6f6f6");
                                    $("#t"+n+" .nota").attr("disabled",1);
                                    $("#t"+n+" .feedback").attr("disabled",1);
                                }


                            }if(tarea.active==2){//disable all if nota mostrar
                                if(tarea.nota!=""){
                                    var notas = JSON.parse(tarea.nota);
                                    $("#t"+n+" .nota.nota1").val(notas[0]);
                                    $("#t"+n+" .nota.nota2").val(notas[1]);
                                }
                                if(tarea.feedback!=""){
                                    var feedbacks = JSON.parse(tarea.feedback);
                                    $("#t"+n+" .feedback.feedback1").val(feedbacks[0]);
                                    $("#t"+n+" .feedback.feedback2").val(feedbacks[1]);
                                }
                                $("#t"+n+" .nota").attr("disabled",1);
                                $("#t"+n+" .feedback").attr("disabled",1);
                                $("#t"+n+" .submit").attr("n",tarea.id).hide();
                                $("#t"+n+" .verentrega").attr("href","http://webcursos.uai.cl/mod/assign/view.php?id="+tarea.url+"&action=grading");
                                $("#t"+n+" .panel-body").css("background","#f6f6f6");
                            }

                        }//for
                    },//ok
                    error:function(message) {
                        $('.panel-default:first').append('<div class="panel-body"><div class="alert alert-danger">'+message+'</div></div>');
                    }
                });//ajx
            },100);
        });
    </script>
</div>
