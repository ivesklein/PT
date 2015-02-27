<?php //Ingreso Temas Memoria ?>
<div class="page page-table">

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading"><strong><span class="glyphicon glyphicon-th-list"></span> Cambiar Contraseña</strong></div>
                <div class="panel-body">
                	<div class="form-horizontal">
                         <input type="hidden" name="f" value="changepass"></input>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Contraseña Actual</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="antpass"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Nueva Contraseña</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="newpass"></input>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-3">Repetir Nueva Contraseña</label>
                            <div class="col-sm-9">
                                <input class="form-control" type="password" id="newrepeat"></input>
                            </div>
                        </div>
                        <div class="alert alert-danger" id="mensaje" style="display:none;"></div>
	                	<div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
	                		    <div class="btn btn-success" id="submitpass">Cambiar</div>
                		    </div>
                        </div>
                	</div>
                </div>
            </div>

        </div>
    </div>

    <script type="text/javascript">
    	$("#submitpass").on("click",function() {

             $("#mensaje").addClass("alert-danger").removeClass("alert-success");            

            if($("#antpass").val()==""){
                $("#mensaje").html("Ingrese Contraseña actual").show();
                $("#antpass").focus();
                ok=false;
            }else if($("#newpass").val()==""){
                $("#mensaje").html("Ingrese nueva contraseña").show();
                $("#newpass").focus();
                ok=false;
            }else if($("#newrepeat").val()==""){
                $("#mensaje").html("Repita la nueva contraseña").show();
                $("#newrepeat").focus();
                ok=false;
            }else if($("#newrepeat").val()!=$("#newpass").val()){
                $("#mensaje").html("La contraseña no coincide").show();
                $("#newrepeat").focus();
                ok=false;
            }else{
                $("#mesbox").hide();
                ok = true;
            }

            if(ok){
                var datos = {};
                $(this).addClass("disabled").addClass("waiting");

                datos = {
                    f:"ajxchangepass",
                    pass:$("#antpass").val(),
                    passnew:$("#newpass").val()
                }
        
                ajx({
                    data:datos,
                    ok:function(data) {
                        //console.log(data);
                        $("#mensaje").html(data.ok).removeClass("alert-danger").addClass("alert-success").show();
                        $("#submitpass").removeClass("disabled").removeClass("waiting");
                        //location.reload();// = "#/profesores";
                    },//ok
                    error:function(data){
                        $("#mensaje").html(data).show();
                        
                        $("#submitpass").removeClass("disabled").removeClass("waiting");
                    }
                });//ajx

            }

        })
    </script>

</div>