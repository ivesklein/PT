<div class="page page-table">
	<script type="text/javascript" src="js/jquery.easypiechart.min.js"></script>

	<style>
		.chart{
			
		}

		.easypiechart{
			display:inline-block;
			position:relative;
			width:180px;
			height:180px;
			text-align:center;
			margin:5px auto
		}

		.easypiechart canvas{
			position:absolute;
			top:0;
			left:0
		}

		.easypiechart .pie-percent{
			display:inline-block;
			line-height:180px;
			font-size:40px;
			font-weight:300;
			color:#333
		}
		
		.panel-body .value{
			display:inline-block;
			line-height:180px;
			font-size:100px;
			font-weight:400;
			color:#E94B3B
		}

		.easypiechart .pie-percent:after{
			content:'%';
			margin-left:0.1em;
			font-size:.6em
		}

	</style>

	<div class="col-md-3" id="modelito" style="display:none;">
        <div class="panel panel-default">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> <span class="title"></span></strong></div>
            <div class="panel-body text-center">
            	<a href=""><div class="chart easypiechart"></div></a>
            	<div class="legend"></div>
            </div>
        </div>

    </div>

	<script>

	var medidor = function(dom, title, percent, link, color){
		var modelito = $("#modelito");
		this.dom = dom;
		this.color = color;
		this.colores = {
			"blue":"#1C7EBB",
			"cyan":"#449DD5",
			"green":"#23AE89",
			"yellow":"#FFB61C",
			"orange":"#F98E33",
			"red":"#E94B3B",
			"purple":"#6A55C2"
		}

		$('.page').append(modelito.clone().attr("id",dom).show());
		$("#"+dom+" .chart").attr("data-percent", percent).html('<span class="pie-percent">'+percent+'</span>');
		$("#"+dom+" .title").html(title);
		$("#"+dom+" a").attr("href", link);
		/*$("#"+dom+" .chart").easyPieChart({
	        lineCap:"square",
	        size:180,
	        scaleColor:false,
	        lineWidth:20,
	        barColor:this.colores[color]
	    });*/

	    this.setPercent = function(percent) {
	    	if(percent<20){
				this.color = "red";
			}else if(percent<40){
				this.color = "orange";
			}else if(percent<60){
				this.color = "yellow";
			}else if(percent<80){
				this.color = "green";
			}else{
				this.color = "blue";
			}
	    	$("#"+this.dom+" .chart").attr("data-percent", percent).html('<span class="pie-percent">'+percent+'</span>');
			$("#"+this.dom+" .chart").easyPieChart({
		        lineCap:"square",
		        size:180,
		        scaleColor:false,
		        lineWidth:20,
		        barColor:this.colores[this.color]
		    });	   
	    }

	}

	var medidor2 = function(dom, title, value, link, color){
		var modelito = $("#modelito");
		this.dom = dom;
		this.color = color;
		this.colores = {
			"blue":"#1C7EBB",
			"cyan":"#449DD5",
			"green":"#23AE89",
			"yellow":"#FFB61C",
			"orange":"#F98E33",
			"red":"#E94B3B",
			"purple":"#6A55C2"
		}

		$('.page').append(modelito.clone().attr("id",dom).show());
		$("#"+dom+" .chart").html('<span class="value">'+value+'</span>');
		$("#"+dom+" .title").html(title);
		$("#"+dom+" a").attr("href", link);

	    this.setValue = function(value) {
	    	$("#"+this.dom+" .chart").html('<span class="value">'+value+'</span>');	
	    }

	}

	$(function() {

		var m1 = new medidor("comisiones", "Comisiones Conformadas", 0, "#/listacomisiones", "cyan")
		var m2 = new medidor("pre", "Predefensas agendadas", 0, "#/listacomisiones", "red")
		var m3 = new medidor("def", "Defensas agendadas", 0, "#/listacomisiones", "orange")
		var m4 = new medidor("hoja", "Hojas de Rutas", 0, "", "red")
		var m5 = new medidor("evaldoc", "Evaluaciones Docentes", 0, "", "yellow")
		var m6 = new medidor2("rezagados", "Rezagados", 0, "#/listarezagados", "yellow")

		ajx({
			data:{
				f:"Dashboard_comisiones"
			},
			ok:function(data) {
				m1.setPercent(data.percent)
			}
		});
		ajx({
			data:{
				f:"Dashboard_predefensas"
			},
			ok:function(data) {
				m2.setPercent(data.percent)
			}
		});
		ajx({
			data:{
				f:"Dashboard_defensas"
			},
			ok:function(data) {
				m3.setPercent(data.percent)
			}
		});
		ajx({
			data:{
				f:"Dashboard_hojasderutas"
			},
			ok:function(data) {
				m4.setPercent(data.percent)
			}
		});
		ajx({
			data:{
				f:"Dashboard_evaldocentes"
			},
			ok:function(data) {
				m5.setPercent(data.percent)
			}
		});
		ajx({
			data:{
				f:"Dashboard_rezagados"
			},
			ok:function(data) {
				m6.setValue(data.value)
			}
		});

	})

	
    </script>

</div>