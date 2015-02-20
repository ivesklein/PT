<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <link rel="stylesheet" href="bootstrap-datepicker/css/datepicker.css" />
    <script src="bootstrap-datepicker/js/bootstrap-datepicker.js"></script>

    <?php $n = count($data); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Configurar Tareas</strong></div>
                <div class="panel-body">
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
	                		    <button id="guardar" class="btn btn-success">Guardar</button>
                		    </div>
                        </div>
                	</div>
                </div>
            </div>

        </div>

    </div>


    <script type="text/javascript">

        var setted = <?php echo json_encode($data) ?>;
        

        function tareaview (i, titulo, fecha, tipo) {

            return      '<div class="thumbnail bloque e'+i+'">'+
                        '<h3 class="col-sm-offset-1 e'+i+'">Entrega '+i+'</h3>'+
                        '<div class="form-group e'+i+'">'+
                        '    <label for="" class="col-sm-2">Título Entrega</label>'+
                        '    <div class="col-sm-2">'+
                        '        <input class="form-control titulo" n="'+i+'" type="text" value="'+titulo+'"></input>'+
                        '    </div>'+

                        '    <label for="" class="col-sm-2">Fecha Entrega</label>'+
                        '    <div class="col-sm-2">'+

                        '       <div class="input-group">'+
                        '           <input type="text" '+
                        '               class="form-control datepicker" value="'+fecha+'" required>'+
                        '           <span class="input-group-addon"><i class="fa fa-calendar"></i></span>'+
                        '       </div>'+



                        '    </div>'+

                        '    <label for="" class="col-sm-2">Tipo de Entrega</label>'+
                        '    <div class="col-sm-2">'+
                        '        <input class="form-control tipo" n="'+i+'" type="text" value="'+tipo+'"></input>'+
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
                        $('.e'+i+" .datepicker").datepicker({autoclose:true});
                        $('.e'+i+" .datepicker").next().on("click",function() {
                            $(this).prev().datepicker("show");
                        })
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

        $("#spinnercont").spinner('delay', 1).spinner('changed', function(e, newVal, oldVal){
            update();
        })



        $(function(){

            if(<?php echo $dis==true?"true":"false"; ?>){
                $('.nchange').addClass("disabled");
                $("#ntareas").attr("disabled",1);
            }

            update();
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

                $('.mensaje').hide();
                var datos = {
                    "f":"ajxtareas",
                    "n":n,
                    "data":JSON.stringify(data)
                };

                ajx({
                    data:datos,
                    ok:function(data) {
                        
                        console.log(data);   


                    }
                });
            }else{

                $('.bloque.e'+err).append("<div class='mensaje alert alert-danger'>Seleccione Fecha</div>");

            }


        })
    </script>

</div>