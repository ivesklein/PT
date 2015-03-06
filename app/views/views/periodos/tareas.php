<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <style type="text/css">

        .wait-icon{
            display: none;
        }

        .waiting .wait-icon{
            display: block;
        }

    </style>

    <link rel="stylesheet" href="bootstrap-datepicker/css/datepicker2.css" />
    <script src="bootstrap-datepicker/js/bootstrap-datepicker2.js"></script>

    <?php $n = count($data); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Configurar Tareas</strong></div>
                <div class="panel-body" id="tareasbox">
                	<div class="form-horizontal" >
                         <input type="hidden" name="f" value="temas"></input>
                        <div class="form-group">
                            <label for="" class="col-sm-3">N° de Entregas (Incluyendo defensas)</label>
                            <div class="col-xs-4">
                                <div class="input-group" data-ui-spinner id="spinnercont">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-primary nchange" data-spin="up">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </span>
                                    <input type="text" class="spinner-input form-control disabled" id="ntareas" data-min="0" value="<?=$n?>">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-default nchange" data-spin="down">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>

	                	<div class="form-group submit">
                            <div class="col-sm-offset-3 col-sm-9">
	                		    <button id="guardar" class="btn btn-success disabled"><font id="savelabel">Guardado</font><font class="wait-icon"> <i class="fa fa-refresh fa-spin"></i></font></button>
                		    </div>
                        </div>
                	</div>
                </div>
            </div>

        </div>

    </div>


    <script type="text/javascript">

        var setted = <?php echo json_encode($data) ?>;
        
        function modified () {
            var el = $("#guardar")
            if(!el.hasClass("waiting")){
                $("#savelabel").html("Guardar");
                el.removeClass("disabled");
            }
        }

        function tareaview (i, titulo, fecha, tipo) {

            var t0 = tipo==0?"selected":"";
            var t1 = tipo==1?"selected":"";
            var t2 = tipo==2?"selected":"";

            return      '<div class="thumbnail bloque e'+i+'">'+
                        '<h3 class="col-sm-offset-1 e'+i+'">Entrega '+i+'</h3>'+
                        '<div class="form-group e'+i+'">'+
                        '    <label for="" class="col-sm-2">Título Entrega</label>'+
                        '    <div class="col-sm-2">'+
                        '        <input class="form-control titulo" n="'+i+'" type="text" value="'+titulo+'"></input>'+
                        '    </div>'+

                        '    <label for="" class="col-sm-2">Fecha Entrega</label>'+
                        '    <div class="col-sm-2">'+

                        '       <div class="input-group date">'+
                        '       <input type="text" class="form-control datepicker" value="'+fecha+'"><span class="input-group-addon"><i class="fa fa-calendar"></i></span>'+
                        '       </div>'+

                        '    </div>'+

                        '    <label for="" class="col-sm-1">Tipo de Entrega</label>'+
                        '    <div class="col-sm-3">'+
                        '        <span class="ui-select">'+
                        '        <select n="'+i+'" class="tipo">'+
                        '            <option value="0" '+t0+'>Entrega</option>'+
                        '            <option value="1" '+t1+'>Predefensa</option>'+
                        '            <option value="2" '+t2+'>Defensa</option>'+
                        '        </select>'+
                        '        </span>'+
                        '    </div>'+
                        '</div>'+
                        '</div>';
        }

        function update() {
            var n = +$("#ntareas").val();
            console.log(n);

            for (var i = 1; i<100; i++) {
                var els = $(".e"+i);
                if(els.length>0){

                    //if n es menor borrar
                    if(n<i){
                        els.hide();
                    }else{
                        els.show();
                    }

                }else{
                    if(n>=i){
                        var tit = "";
                        var dat = "";
                        var tip = "";
                        if(i in setted){
                            tit = setted[i].title;
                            dat = setted[i].date;
                            tip = setted[i].tipo;
                        }
                        $('.submit').before(tareaview(i,tit,dat,tip));

                        $('.e'+i+' .input-group.date').datepicker({
                            todayBtn: true,
                            language: "es"
                            });

                        /*$('.e'+i+" .datepicker").datepicker({autoclose:true,"orientation":"top"});
                        $('.e'+i+" .datepicker").next().on("click",function() {
                            $(this).prev().datepicker("show");
                        })*/

                        if(i in setted){
                            if("wc" in setted[i])
                                $('.bloque.e'+i).removeClass("thumbnail").addClass("alert").addClass("alert-info").prepend("<a target='_blanc' class='btn btn-info pull-right' href='http://webcursos.uai.cl/mod/assign/view.php?id="+setted[i].wc+"'>Ver Recurso</a>");
                        }

                    
                    }else{
                        break;
                    }
                }
            };


        }

    	//$("#ntareas").on("spinstop", update);

        $("#spinnercont").spinner(
            {
                delay: 1,
                changed:function(e, newVal, oldVal){
                    update();
                    modified();       
                }
            }
        );

        $("#tareasbox").on("change", "input", modified);


        $(function(){

            if(<?php echo $dis==true?"true":"false"; ?>){
                $('.nchange').addClass("disabled");
                $("#ntareas").attr("disabled",1);
            }
            setTimeout(function() {
                update();
            },500)
            
        });

        function isValidDate(d) {
          if ( Object.prototype.toString.call(d) !== "[object Date]" )
            return false;
          return !isNaN(d.getTime());
        }

        $('#guardar').on("click",function() {
            


            var n = +$("#ntareas").val();
            console.log(n);

            var data = {};

            var ok = true;
            var err = 0;

            for (var i = 1; i<=n; i++) {
                var els = $(".e"+i);
                if(els.length>0){
                    data[i] = {
                                "title":$(".bloque.e"+i+" .titulo").val(),
                                "date":$(".bloque.e"+i+" .datepicker").val(),
                                "tipo":$(".bloque.e"+i+" .tipo").val()
                              }

                    var d = new Date($(".bloque.e"+i+" .datepicker").val());
                    if(!isValidDate(d)){
                        ok = false;
                        err = i;
                    }

                }else{
                    //error
                }
            };

            if(ok){

                $("#guardar").addClass("disabled").addClass("waiting");
                $("#savelabel").html("Guardando");

                $('.mensaje').hide();
                var datos = {
                    "f":"Tareas_guardar",
                    "n":n,
                    "data":JSON.stringify(data)
                };

                ajx({
                    data:datos,
                    ok:function(data) {
                        
                        console.log(data);   
                        $("#guardar").removeClass("waiting").addClass("disabled");
                        $("#savelabel").html("Guardado");
                    },
                    error:function(data) {
                        $('.bloque.e1').append("<div class='mensaje alert alert-danger'>"+data+"</div>");
                        $("#guardar").removeClass("waiting").removeClass("disabled");
                        $("#savelabel").html("Guardar");
                    }
                });
            }else{

                $('.bloque.e'+err).append("<div class='mensaje alert alert-danger'>Seleccione Fecha</div>");

            }


        })
    </script>

</div>