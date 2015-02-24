<?php echo View::make('lti.header'); ?>


<div class="panel panel-default" style="margin-right: 7px;">
        <div class="panel-heading"><strong><span class="glyphicon glyphicon-check"></span> Hoja de Ruta</strong></div>
        <div class="panel-body row">
    	    <div class="panel-body">

                <p><?php if(isset($declaracion)){echo $declaracion;}else{echo "Declaración";}?></p>

            </div>
        </div>
</div>

<script src="../scripts/vendor.js"></script>
<script src="../scripts/ui.js"></script>
<script type="text/javascript">

</script>

<?php echo View::make('lti.footer'); ?>