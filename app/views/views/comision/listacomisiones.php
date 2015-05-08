<?php
/**
*-----------------------------------------------------------------------
*| Grupo | Tema | Alumno | Alumno | Pguia | Pres | Invitado | PD | DEF |
*-----------------------------------------------------------------------
*| find  | find | find   |  find  | find  | find |  find    |    |     |
*-----------------------------------------------------------------------
*|       |      |        |        |       |      |          |    |     |
*|       |      |        |        |       |      |          |    |     |
*|       |      |        |        |       |      |          |    |     |
*|       |      |        |        |       |      |          |    |     |
*|       |      |        |        |       |      |          |    |     |
*-----------------------------------------------------------------------
*/
?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p10{
		width: 10%;
	}
	.p15{
		width: 15%;
	}
	.p1-8{
		width: 12.5%;
	}
	.p1-16{
		width: 6.25%;
	}
	.checkbox{
		margin-left: 15px;
	}
</style>

<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong>
<div class="form-inline">
	<span class="glyphicon glyphicon-users"></span> Comisiones 
  <div class="checkbox pull-right">
    <label>
      <input class="mostrar" data-col="inv" type="checkbox"> Invitado
    </label>
  </div>
  <div class="checkbox pull-right">
    <label>
      <input class="mostrar" data-col="pres" type="checkbox"> Presidente
    </label>
  </div>
  <div class="checkbox pull-right">
    <label>
      <input class="mostrar" data-col="pg" type="checkbox" checked> Profesor Guía
    </label>
  </div>
  <div class="checkbox pull-right">
    <label>
      <input class="mostrar" data-col="a2" type="checkbox"> Alumno 2
    </label>
  </div>
  <div class="checkbox pull-right">
    <label>
      <input class="mostrar" data-col="a1" type="checkbox"> Alumno 1
    </label>
  </div>
  <div class="checkbox pull-right">
    <label>
      <input class="mostrar" data-col="tema" type="checkbox" checked> Tema
    </label>
  </div>
</div>
</strong></div>
        <?=View::make('table.table',array('body'=>'','head'=>''))?>
        <?=View::make('table.table',array('body'=>'','head'=>''))?>
        <?=View::make('table.table',array('body'=>'','head'=>''))?>
    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
	
	<script src="js/tooltip.js"></script>
   	<script src="js/popover.js"></script>

    <script type="text/javascript">

    	//var finder = $('<div class="input-group"><input type="text" class="form-control" placeholder="Buscar..."><span class="input-group-btn"><button class="btn btn-default find" type="button">Go!</button></span></div>');
    	var finder = $('<input type="text" class="form-control find" placeholder="Buscar...">');
    	
    	var selector = '<select class="user form-control"><option value="nc">Nombre Completo</option><option value="name">Nombre</option><option value="surname">Apellido</option><option value="wc_id">Email</option><option value="run">RUN</option></select>';
		var selector2 = '<select class="user form-control"><option value="nc">Nombre Completo</option><option value="name">Nombre</option><option value="surname">Apellido</option><option value="wc_id">Email</option></select>';

    	$('.table:nth-child(2)').addClass('titulos');
    	$('.titulos thead tr').append('<th class="p1-8 ">Grupo</th>');
    	$('.titulos thead tr').append('<th class="p1-8 ctema">Tema</th>');
    	$('.titulos thead tr').append('<th class="p1-8 ca1" data-who="s1">Alumno 1<br>'+selector+'</th>');
    	$('.titulos thead tr').append('<th class="p1-8 ca2" data-who="s2">Alumno 2<br>'+selector+'</th>');
    	$('.titulos thead tr').append('<th class="p1-8 cpg" data-who="pg">Profesor Guía<br>'+selector2+'</th>');
    	$('.titulos thead tr').append('<th class="p1-8 cpres" data-who="pr">Presidente<br>'+selector2+'</th>');
    	$('.titulos thead tr').append('<th class="p1-8 cinv" data-who="in">Invitado<br>'+selector2+'</th>');
    	$('.titulos thead tr').append('<th class="p1-16">Predefensa</th>');
    	$('.titulos thead tr').append('<th class="p1-16">Defensa</th>');
    	$('.titulos thead tr').append('<th class="p1-16"></th>');

    	$('.table:nth-child(3)').addClass('finders');
    	$('.finders thead tr').append('<th class="p1-8"></th>');
    	$('.finders thead tr').append('<th class="p1-8 ctema" id="ftema"></th>');
    	$('.finders thead tr').append('<th class="p1-8 ca1" id="fa1"></th>');
    	$('.finders thead tr').append('<th class="p1-8 ca2" id="fa2"></th>');
    	$('.finders thead tr').append('<th class="p1-8 cpg" id="fpg"></th>');
    	$('.finders thead tr').append('<th class="p1-8 cpres" ></th>');
    	$('.finders thead tr').append('<th class="p1-8 cinv" ></th>');
    	$('.finders thead tr').append('<th class="p1-16"></th>');
    	$('.finders thead tr').append('<th class="p1-16"></th>');
    	$('.finders thead tr').append('<th class="p1-16"></th>');

    	$('#fgrupo, #ftema, #fa1, #fa2, #fpg, #fpres, #finv').append(finder);
    	
    	//aparecer y desaparecer columnas!!

    	$('.checkbox input').click(function() {
	        if (!$(this).is(':checked')) {
	            
	            //hide
	            $(".c"+$(this).attr("data-col")).hide();

	        }else{

	        	//show
				$(".c"+$(this).attr("data-col")).show();	            
	        }
	    });

	    $(function(){
	    	$(".ca1").hide();
	    	$(".ca2").hide();
	    	$(".cpres").hide();
	    	$(".cinv").hide();
	    	findfun();
	    });

	    
	    $('select.user').on('change', function() {

	    	var who = $(this).parent().attr('data-who');
	    	var toshow = $(this).val();

	    	$('.rowlista').each( function() {
	    		var id = $(this).attr("id");
	    		var val = datostabla[id][who+toshow];
	    		$(this).find(".d"+who).html(val);
	    	});
	
	    });
	
        $( "#fper input" ).autocomplete({
            minLength: 1,
            source: "th/periodos",
	            focus: function( event, ui ) {
	            //$( "#buscarprofesor" ).val( ui.item.label );
	            return false;
            },
            select: function( event, ui ) {
	            $( "#fper input" ).val( ui.item.value );
	            //$( "#prof" ).val( ui.item.value );
	            return false;
        	}
        })

        .autocomplete( "instance" )._renderItem = function( ul, item ) {
            return $( "<li>" )
            .append( "<a>" + item.value + "</a>" )
            .appendTo( ul );
        };

        var datostabla = {};

        
        var typewatch = function(callback,ms){
		    var timer = 0;
		    return function(callback, ms){
		        clearTimeout (timer);
		        timer = setTimeout(callback, ms);
		    }  
		}();

	
		function findfun () {
			var tema = $('#ftema input').val();
    		var a1 = $('#fa1 input').val();
    		var a2 = $('#fa2 input').val();
    		var pg = $('#fpg input').val();

            datos = {
                f:"Comision_lista"
            }

            if(tema!=""){datos['tema']=tema}
            if(a1!=""){datos['a1']=a1}
            if(a2!=""){datos['a2']=a2}
            if(pg!=""){datos['pg']=pg}

            ajx({
                data:datos,
                ok:function(data) {
                    console.log(data);

                    

                    $('.table:nth-child(4) tbody').html("");

                    datostabla = data['temas'];
                    var color = {"confirmar":"text-warning","confirmado":"text-success","rechazado":"text-danger","":""};

                    for(n in data['temas']){
                    	var tema = data['temas'][n];
                    	var tr = $("<tr id='"+tema.id+"' class='rowlista'></tr>");

                    	var button = "<a href='#/editarcomision/"+tema.id+"' class='btn btn-info ver'>Ver</a>";

                    	tr.append("<td class='p1-8'>"+tema.grupo+"</td>");
                    	//tr.append("<td class='p1-8'>"+'<button type="button" class="btn btn-default" data-placement="top" data-toggle="popover" data-content="'+tema.tema+'"><div class="fa fa-eye"></div></button>'+"</td>");
                    	//tr.append("<td class='p1-8'>"+tema.tema.substring(0,20)+'... <button type="button" class="btn btn-default" data-placement="top" data-toggle="popover" data-content="'+tema.tema+'"><div class="fa fa-eye"></div></button></td>');
                    	//abbr

                    	tr.append("<td class='p1-8'><abbr title='"+tema.tema+"'>"+tema.tema.substring(0,20)+'...</abbr></td>');
                    	
                    	//what to show
                    	var toshow = $('.ca1 select').val();
                    	tr.append("<td class='p1-8 ca1 ds1'>"+tema['s1'+toshow]+"</td>");
                    	console.log(tema['s1'+toshow]);
                    	var toshow = $('.ca2 select').val();
                    	console.log(toshow);
                    	tr.append("<td class='p1-8 ca2 ds2'>"+tema['s2'+toshow]+"</td>");
                    	console.log(tema['s2'+toshow]);
                    	var toshow = $('.cpg select').val();
                    	tr.append("<td class='p1-8 cpg dpg'>"+tema['pg'+toshow]+"</td>");
                    	console.log(tema['pg'+toshow]);

                    	var toshow = $('.cpres select').val();
                    	tr.append("<td class='p1-8 cpres dpr "+color[tema.prstatus]+"'>"+tema['pr'+toshow]+"</td>");
                    	console.log(tema['pr'+toshow]);
                    	var toshow = $('.cinv select').val();
                    	tr.append("<td class='p1-8 cinv din "+color[tema.instatus]+"'>"+tema['in'+toshow]+"</td>");
                    	console.log(tema['in'+toshow]);

                    	tr.append("<td class='p1-16'>"+tema.pre+"</td>");
                    	tr.append("<td class='p1-16'>"+tema.def+"</td>");

                    	tr.append("<td class='p1-16'>"+button+"</td>");

                    	$('.table:nth-child(4) tbody').append(tr);

                    }

                    $('button').popover();

                    var arr = ["tema","a1", "a2", "pg", "pres", "inv"];

                    for(i in arr){
                    	var col = arr[i];
                    	if (!$('.mostrar[data-col="'+col+'"]').is(':checked')) {
		            		//hide
				            $(".c"+col).hide();
				        }
                    }

                }
            });//ajx
		}

    	$('.finders').on("keyup",".find",function() {
    		typewatch(findfun,200);


    	})


    	//$('.table').tablesorter();
    </script>

</div>