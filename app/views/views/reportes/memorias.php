<?php // ?>
<style type="text/css">
	.p30{
		width: 30%;
	}
	.p1{
		width: 1%;
	}
    .form-inline{
        font-size: 9px;
    }
</style>
<div class="page page-table">

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Memorias <div class="btn btn-xs btn-success download"><i class="fa fa-download"></i></div></strong></div>

    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script src="js/jquery.tablesorter.min.js"></script>
    <script src="js/table.js"></script>
    <script type="text/javascript">

        var tabla = new Tabla(".panel-heading", ".tabla");

        tabla.setajax("Memorias_filtro");
        tabla.addcol("sem", "Semestre",      [0,1], 1, 1);
        tabla.addcol("tema","Tema",          [1,1], 1, 1, 20);
        tabla.addcol("cat", "Categoria",     [1,1], 1, 1);
        tabla.addcol("a1",  "Alumno 1",      [1,0], 1, 1);
        tabla.addcol("pa1", "Promedio A.1",  [1,0], 0, 1);
        tabla.addcol("ea1", "Estado A.1",    [1,1], 1, 1);
        tabla.addcol("a2",  "Alumno 2",      [1,0], 1, 1);
        tabla.addcol("pa2", "Promedio A.2",  [1,0], 0, 1);
        tabla.addcol("ea2", "Estado A.2",    [1,1], 1, 1);
        tabla.addcol("pg",  "Profesor Guía", [1,1], 1, 1);
        tabla.addcol("em",  "Empresa",       [1,1], 1, 1);


        /*var finder = $('<input type="text" class="form-control find" placeholder="Buscar...">');
    	$('.table:nth-child(2)').addClass('titulos');
    	$('.titulos thead tr').append('<th class="p1 csem">Semestre</th>');
        $('.titulos thead tr').append('<th class="p1 ctema">Tema</th>');
        $('.titulos thead tr').append('<th class="p1 ccat">Categoria</th>');
    	$('.titulos thead tr').append('<th class="p1 ca1">Alumno 1</th>');
        $('.titulos thead tr').append('<th class="p1 cpa1">Promedio A.1</th>');
    	$('.titulos thead tr').append('<th class="p1 cea1">Estado A.1</th>');
        $('.titulos thead tr').append('<th class="p1 ca2">Alumno 2</th>');
        $('.titulos thead tr').append('<th class="p1 cpa2">Promedio A.2</th>');
        $('.titulos thead tr').append('<th class="p1 cea2">Estado A.2</th>');
        $('.titulos thead tr').append('<th class="p1 cpg">Profesor Guía</th>');
        $('.titulos thead tr').append('<th class="p1 cem">Empresa</th>');


    	$('.table:nth-child(3)').addClass('finders');
    	$('.finders thead tr').append('<th class="p1 csem" id="fsem"></th>');
    	$('.finders thead tr').append('<th class="p1 ctema" id="ftema"></th>');
    	$('.finders thead tr').append('<th class="p1 ccat" id="fcat"></th>');
        $('.finders thead tr').append('<th class="p1 ca1" id="fa1"></th>');
        $('.finders thead tr').append('<th class="p1 cpa1" id="fpa1"></th>');
        $('.finders thead tr').append('<th class="p1 cea1" id="fea1"></th>');
        $('.finders thead tr').append('<th class="p1 ca2" id="fa2"></th>');
        $('.finders thead tr').append('<th class="p1 cpa2" id="fpa2"></th>');
        $('.finders thead tr').append('<th class="p1 cea2" id="fea2"></th>');
        $('.finders thead tr').append('<th class="p1 cpg" id="fpg"></th>');
        $('.finders thead tr').append('<th class="p1 cem" id="fem"></th>');

    	$('#fsem, #ftema, #fcat, #fa1, #fea1, #fa2, #fea2, #fpg, #fem').append(finder);
    	
        $( "#fsem input" ).autocomplete({
            minLength: 1,
            source: "th/periodos",
            focus: function( event, ui ) {
            //$( "#buscarprofesor" ).val( ui.item.label );
            return false;
            },
            select: function( event, ui ) {
            $( "#fsem input" ).val( ui.item.value );
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
    		var per = $('#fper input').val();
    		var name = $('#fname input').val();
            datos = {
                f:"Reportes_memorias",
                per:per,
                name:name
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

        */
    </script>

</div>