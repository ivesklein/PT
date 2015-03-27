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

    <div class="row textos">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-file"></span> <font id="titulomaestro">Textos</font></strong>
                </div>
            </div>
        </div>

        <div class="col-md-12 caja" style="display:none;" id="modelito">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> <font class="titulo"></font></strong></div>
                <div class="panel-body">
                    
                    <div class="form-group">
                        
                        <textarea class="form-control"></textarea>
                        
                    
                        <div class="col-sm-offset-2 col-sm-10" style="margin-top: 10px;">
                            <div class="btn btn-success submit"><font class="savelabel">Guardar</font><font class="wait-icon"> <i class="fa fa-refresh fa-spin"></i></font></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script type="text/javascript">
        $(function() {

            $(".textos").on("click", ".submit" , function() {
                var btn = $(this);
                var id = btn.parents('.caja').attr("id");
                var texto = btn.parents('.panel-body').find('textarea').val();

                btn.addClass("waiting");

                var datos = {
                    f:"Textos_guardar",
                    id:id,
                    texto:texto
                }
                ajx({
                    data:datos,
                    ok:function(data) {
                        console.log(data);
                        location.reload();
                    },//ok
                    error:function(d) {
                        btn.removeClass("waiting");
                        alert(d);
                    }
                });//ajx


            })

/*
                var datos = {
                    f:"Tareas_setnota",
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
            

            function modify() {

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
*/
            window.setTimeout(function(){
                var datos = {
                    f:"Textos_gettextos",
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
                            $('.textos').append(modelito.clone().attr("id",n).show());
                            $("#"+n+" .titulo").html(n);
                            $("#"+n+" textarea").html(data.data[n]);

                            

                        }//for
                    },//ok
                    error:function(message) {
                        $('.panel-default:first').append('<div class="panel-body"><div class="alert alert-danger">'+message+'</div></div>');
                    }
                });//ajx */
            },100);
        });
    </script>
</div>