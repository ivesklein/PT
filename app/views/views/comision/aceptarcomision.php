<?php echo View::make('lti.header'); ?>

<div class="col-md-offset-2 col-md-8">
    <div class="panel panel-default" style="margin-right: 7px;">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> Aceptar Comisión</strong></div>
            <div class="panel-body row">
        	    <div class="panel-body">
                    <div id="ok" class="alert alert-success" style="display:none;"></div>
                    <div id="error" class="alert alert-danger" style="display:none;"></div>
                </div>
            </div>
    </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script src="../scripts/ajx2.js"></script>
<script type="text/javascript">
    
var jcall = function(id) {
                
        ajx({
            data:{
                    f:"Comision_aceptarcomision",
                    id:id
                },
            ok: function(data) {
                $("#ok").html("Comisión Aceptada.").show();
            },
            error:function(data){
                console.log(data)
                $("#error").html(data).show();
            }
        })
}

setTimeout(function(){jcall('<?php echo $id; ?>');},1000);

</script>

<?php echo View::make('lti.footer'); ?>