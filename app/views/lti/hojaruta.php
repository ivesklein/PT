<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> Hoja de Ruta</strong></div>
        <div class="panel-body row">
    	    <div class="panel-body">

                <p><?php if(isset($declaracion)){echo $declaracion;}else{echo "Declaración";}?></p>
                <div class="col-xs-offset-2 col-xs-3"><div class="btn btn-info" id="aceptardec">Aceptar declaración</div></div>
            </div>
        </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script src="../scripts/ajx.js"></script>
<script type="text/javascript">


    $("#aceptardec").on("click",function() {
        ajx({
            data:{
                    f:"ajxfirmarhoja"
                },
            ok: function(data) {
                document.reload();
            }
        })
    })
</script>

<?php echo View::make('lti.footer'); ?>