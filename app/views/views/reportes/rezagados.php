<?php // ?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p10{
		width: 10%;
	}
</style>
<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-page"></span> Rezagados</strong></div>
        <?=View::make('table.table',array('body'=>'','head'=>''))?>
        <?=View::make('table.table',array('body'=>'','head'=>''))?>
        <?=View::make('table.table',array('body'=>'','head'=>''))?>
    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>

    <script type="text/javascript">

    	var finder = $('<div class="input-group"><input type="text" class="form-control" placeholder="Buscar..."><span class="input-group-btn"><button class="btn btn-default find" type="button">Go!</button></span></div>');
    	$('.table:nth-child(2)').addClass('titulos');
    	$('.titulos thead tr').append('<th class="p30">run</th>');
    	$('.titulos thead tr').append('<th class="p30">Nombre</th>');
    	$('.titulos thead tr').append('<th class="p30">Semestre</th>');
    	$('.titulos thead tr').append('<th class="p10">Detalles</th>');

    	$('.table:nth-child(3)').addClass('finders');
    	$('.finders thead tr').append('<th class="p30"><div class="form-group form-inline" id="frun"></div></th>');
    	$('.finders thead tr').append('<th class="p30"><div class="form-group form-inline" id="fname"></div></th>');
    	$('.finders thead tr').append('<th class="p30"><div class="form-group form-inline" id="fper"></div></th>');
    	$('.finders thead tr').append('<th class="p10"></th>');

    	$('#frun, #fname, #fper').append(finder);
    	
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

    	$('.finders').on("click",".find",function() {
    		var find = $(this).parents('.form-group').attr("id");
    		//alert(find);
            datos = {
                f:"Reportes_rezagados",
            }
            ajx({
                data:datos,
                ok:function(data) {
                    console.log(data);

                    

                    $('.table:nth-child(4) tbody').html("");

                    for(n in data['ok']){
                    	var rez = data['ok'][n];
                    	var tr = $("<tr id='"+rez.id+"'></tr>");

                    	var button = "<a href='#/rezagado/"+rez.id+"' class='btn btn-info ver'>Ver</a>";

                    	tr.append("<td class='p30'>"+rez.run+"</td>");
                    	tr.append("<td class='p30'>"+rez.name+"</td>");
                    	tr.append("<td class='p30'>"+rez.periodo+"</td>");
                    	tr.append("<td class='p10'>"+button+"</td>");

                    	$('.table:nth-child(4) tbody').append(tr);

                    }

                    

                }
            });//ajx






    	})


    	//$('.table').tablesorter();
    </script>

</div>