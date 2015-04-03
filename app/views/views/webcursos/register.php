<div class="page page-table">

  <div class='alert alert-warning'>Aun no se selecciona curso para llevar el proceso</div>
                    <div class='form-control'>
                    <label class='col-xs-2'><?=Auth::user()->wc_id ?></label>
                    <div class='col-xs-2'>
                        <input class='form-control' type='password' id='passi' placeholder='wc pass'></input>
                    </div>
                    <div class='btn btn-warning' id='confcourse'>Configurar <i style='display:none' class='fa fa-spin fa-refresh waiting'></i></div>
                    </div>
            <div id='mensaje' class='alert alert-danger' style='display:none;'></div>
            <div id='selectcourse'></div>
            <div id='btnselectcourse'></div>

    <script type="text/javascript">
    	
        ok1=false;

    	$('#confcourse').on("click", function() {
    		var res=$('#passi').val();
            
    		if(res!=null && res!=""){
                $(".waiting").show();
                $('#confcourse').addClass("disabled");
                 $('#mensaje').hide();
                var datos = {
                    "f":"Webcursos_cursos",
                    "p":res
                };
                ajx({
                    data:datos,
                    ok:function(data) {
                        if(ok1==false){
                        	$('#selectcourse').append("<span class='ui-select'><select></select></span>")
                            for(i in data.data){
                            	var item = data.data[i];
                            	$('#selectcourse select').append("<option value='"+item.id+"'>"+item.title+"</option>");
                            }
                            $('#btnselectcourse').append("<div class='btn btn-success sel'>Elegir</div>")
                            ok1=true;
                        }
                        $(".waiting").hide();
                        $('#confcourse').removeClass("disabled");
                    },
                    error:function(data) {
                        $(".waiting").hide();
                        $('#confcourse').removeClass("disabled");
                        alert(data);
                    }
                });
    		}else{
                $('#mensaje').html("Debe ingresar contraseña de webcursos para poder realizar los cambios.").show();
                $("#passi").focus();
            }

    	});

    	$("#btnselectcourse").on("click", ".sel", function() {
    		var id = $('#selectcourse select').val();
    	    var datos = {
                "f":"Webcursos_setcurso",
                "id":id
            };
            ajx({
                data:datos,
                ok:function(data) {
                	location.reload();
                }
            });
    	});


    </script>

</div>