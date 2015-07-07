<div class="page page-table">

    <div class="panel panel-default error" style="display:none;">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Error</strong></div>
        <div class="panel-body">
            <div class="alert alert-danger"></div>
        </div>
    </div>

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Proyecto</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group"><label for="" class="col-sm-2">Tema</label><div class="col-sm-10" data-table="Subject" data-col="subject"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Profesor Guía</label><div class="col-sm-4" data-table="Subject" data-col="adviser"></div><label for="" class="col-sm-2">Estado</label><div class="col-sm-4" data-table="Subject" data-col="status"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Semestre</label><div class="col-sm-10" data-table="Subject" data-col="periodo"></div></div>
            </div>
        </div>
    </div>

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Memoristas</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group">
                    <label for="" class="col-sm-2">Alumno 1</label><div class="col-sm-4"  data-table="Subject" data-col="student1"></div>
                    <label for="" class="col-sm-2">Alumno 2</label><div class="col-sm-4" data-table="Subject" data-col="student2"></div>
                </div>
            </div>
        </div>
    </div>    

    <div class="panel panel-default tabla">
        <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> Comisión</strong></div>
        <div class="panel-body">
            <div class="form-horizontal">
                <div class="form-group"><label for="" class="col-sm-2">Presidente</label><div class="col-sm-10" data-col="pr"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Invitado</label><div class="col-sm-10" data-col="in"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Fecha Predefensa</label><div class="col-sm-10" data-col="pre"></div></div>
                <div class="form-group"><label for="" class="col-sm-2">Fechas Defensa</label><div class="col-sm-10" data-col="def"></div></div>                
            </div>
        </div>
    </div>    
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script type="text/javascript">

        function jcall (id) {
            console.log(id);
            ajx({
                data:{
                    f:"Reportes_gmtdata",
                    id:id
                },
                ok:function(data) {
                    $('[data-col="subject"]').html(textedit(data.subject, "text"))
                    $('[data-col="adviser"]').html(textedit(data.adviser, "staff"))
                    $('[data-col="status"]').html(textedit(data.status, "substatus"))
                    $('[data-col="periodo"]').html(textedit(data.periodo, "periodo"))
                    $('[data-col="student1"]').html(textedit(data.student1, "student"))
                    $('[data-col="student2"]').html(textedit(data.student2, "student"))

                    $('[data-col="pr"]').html(textedit(data.pr, "staff"))
                    $('[data-col="in"]').html(textedit(data.in, "staff"))

                    $('[data-col="pre"]').html(textedit(data.pre, "date"))
                    $('[data-col="def"]').html(textedit(data.def, "date"))

                },
                error:function(data) {
                    $(".error .alert").html(data)
                    $(".tabla").hide()
                    $(".error").show()
                }
            })
        }

        function textedit (text, type) {
            return '<span>'+text+'</span><div class="btn edit" data-type="'+type+'"><span class="fa fa-pencil"></span></div>';
        }

        function textbox (content) {
            return '<div class="input-group"><input type="text" class="form-control" value="'+content+'"><span class="input-group-btn"><button class="btn btn-default" type="button"><span class="fa fa-save"></span></button></span></div>';
        }

        function optionbox (selected, options) {
            var res = "<div class='input-group'><select class='form-control'>";
            for(opt in options){
                var sel = selected==options[opt]?" selected='selected'":"";
                res += '<option value="'+options[opt]+'" '+sel+'>'+options[opt]+'</option>'
            }
            res += '</select><span class="input-group-btn"><button class="btn btn-default" type="button"><span class="fa fa-save"></span></button></span>';
            return res;
        }

        function datebox (content) {
            return '<div class="input-group"><input type="text" class="form-control" value="'+content+'"><span class="input-group-btn"><button class="btn btn-default" type="button"><span class="fa fa-save"></span></button></span></div>';
        }

        $(".tabla").on("click", ".edit", function() {
            var type = $(this).attr("data-type");
            if(type=="text"){
                var content = $(this).parent().find("span").html();
                $(this).parent().html(textbox(content));
            }
            if(type=="staff"){
                var box = $(this).parent();
                var content = box.find("span").html();
                
                box.html(textbox(content));

                box.find(".form-control").autocomplete({
                    minLength: 2,
                    source: "th/staffs",
                    focus: function( event, ui ) {
                    //$( "#buscarprofesor" ).val( ui.item.label );
                    return false;
                    },
                    select: function( event, ui ) {
                    box.find(".form-control").val( ui.item.mail );
                    return false;
                }
                })
                .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                    .append( "<a>" + item.label +"</a>" )
                    .appendTo( ul );
                };
            }
            if(type=="student"){
                var box = $(this).parent();
                var content = box.find("span").html();
                
                box.html(textbox(content));

                box.find(".form-control").autocomplete({
                    minLength: 2,
                    source: "th/students",
                    focus: function( event, ui ) {
                    //$( "#buscarprofesor" ).val( ui.item.label );
                    return false;
                    },
                    select: function( event, ui ) {
                    box.find(".form-control").val( ui.item.mail );
                    return false;
                }
                })
                .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                    .append( "<a>" + item.label +"</a>" )
                    .appendTo( ul );
                };
            }
            if(type=="periodo"){
                var box = $(this).parent();
                var content = box.find("span").html();
                
                box.html(textbox(content));

                box.find(".form-control").autocomplete({
                    minLength: 0,
                    source: "th/periodos",
                    focus: function( event, ui ) {
                    //$( "#buscarprofesor" ).val( ui.item.label );
                    return false;
                    },
                    select: function( event, ui ) {
                    box.find(".form-control").val( ui.item.value );
                    return false;
                }
                })
                .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                    .append( "<a>" + item.value +"</a>" )
                    .appendTo( ul );
                };
            }
            if(type=="substatus"){
                var box = $(this).parent();
                var content = box.find("span").html();
                var selected = content;
                var options = [
                ["confirm"],
                ["confirmed"],
                ["not-confirmed"]
                ];
                box.html(optionbox(selected, options));

            }
            if(type=="date"){
                var box = $(this).parent();
                var content = box.find("span").html();
                box.html(datebox(content));

            }
        })



    </script>

</div>