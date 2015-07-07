<div class="page page-table">
	<script type="text/javascript" src="js/jquery.easypiechart.min.js"></script>
	<script type="text/javascript" src="js/jquery.flot.min.js"></script>
	<script type="text/javascript" src="js/jquery.flot.categories.min.js"></script>
	<script type="text/javascript" src="js/jquery.flot.pie.min.js"></script>


	<style>
		.chart2{
			height:180px;
		}

		.title{
			font-size: 11px;
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

		.easypiechart .pie-percent:after{
			content:'%';
			margin-left:0.1em;
			font-size:.6em
		}

	</style>

	<div class="col-md-6" id="modelito2" style="display:none;">
        <div class="panel panel-default">
            <div class="panel-heading"><strong><span class="glyphicon glyphicon-th"></span> <span class="title"></span></strong></div>
            <div class="panel-body text-center">
            	<div class="col-md-6">
            		<a href=""><div class="chart easypiechart"></div></a>
            	</div>
            	<div class="col-md-6">
            		<div class="chart2"></div>
            	</div>
            	
            </div>
        </div>

    </div>

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

	function labelFormatter(label, series) {
		return "<div style='font-size:12pt; text-align:center; padding:2px; color:white;' title='" + Math.round(series.percent) + "%'>" + label + "</div>";
	}

	var medidor4 = function(dom, title, percents, link, color){
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
		//$("#"+dom+" .chart").attr("data-percent", percent).html('<span class="pie-percent">'+percent+'</span>');
		$("#"+dom+" .title").html(title);
		$("#"+dom+" a").attr("href", link);

	    this.setPercent = function(data) {

		    var foptions = {
			    series: {
			        pie: {
			            show: true,
			            radius: 0.95,
			            innerRadius: 0.6,
			            label: {
			                show: true,
			                radius: (0.95+0.6)/2,
			                formatter: labelFormatter,
			                threshold: 0.1
			            }
			        }
			    },
			    legend: {
			        show: false
			    }
			};

			var cero = data.total - data.alumno - data.profesor - data.revisor - data.secretaria;

			var data = [
				{"label":"0/4", "data":cero, "color":this.colores["red"]},
				{"label":"1/4", "data":data.alumno, "color":this.colores["orange"]},
				{"label":"2/4", "data":data.profesor, "color":this.colores["yellow"]},
				{"label":"3/4", "data":data.revisor, "color":this.colores["green"]},
				{"label":"4/4", "data":data.secretaria, "color":this.colores["cyan"]},
				];

    		$.plot("#"+this.dom+" .chart", data, foptions);

	    }
	}

	var medidor3 = function(dom, title, percent, link, hist){
		var modelito = $("#modelito2");
		this.dom = dom;
		this.color = "red";
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
	    

    	var opt2 = {
			series: {
				bars: {
					show: true,
					barWidth: 0.3,
					align: "right",
					fill: 1
				}

			},
			xaxis: {
				//mode: "categories",
				tickLength: 0,
				min:1,
				max:7
			},
			yaxis: {
			    show: true,

			},
			grid: {
    			borderWidth :{ "top":0, "bottom":1, "left":0, "right":0 },
    			markings : [ { xaxis: { from: 1, to: 4 },color:"#ffdddd"}]
			
			}
		};
		var dataas = [	
						[1.5, hist["1.5"] ],
						[2.0, hist["2.0"] ],
						[2.5, hist["2.5"] ],
						[3.0, hist["3.0"] ],
						[3.5, hist["3.5"] ],
						[4.0, hist["4.0"] ],
						[4.5, hist["4.5"] ],
						[5.0, hist["5.0"] ],
						[5.5, hist["5.5"] ],
						[6.0, hist["6.0"] ],
						[6.5, hist["6.5"] ],
						[7.0, hist["7.0"] ]
					];
		$.plot("#"+this.dom+" .chart2", [dataas], opt2);

	}

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

	$(function() {

		ajx({
			data:{
				f:"Dashboard_tareas"
			},
			ok:function(data) {

				for(tarean in data.tareas){
					var tarea = data.tareas[tarean];
					var t0 = new medidor3(tarea.name, tarea.title, tarea.percent, "#/notas", tarea.hist)
				}

				var m1 = new medidor("comisiones", "Comisiones Conformadas", 0, "#/listacomisiones", "cyan")
				var m2 = new medidor("pre", "Predefensas agendadas", 0, "#/listacomisiones", "red")
				var m3 = new medidor4("hoja", "Hojas de Rutas", 0, "#/rephojaruta", "red")
				var m4 = new medidor("evaldoc", "Evaluaciones Docentes", 0, "", "yellow")

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
						f:"Dashboard_hojasderutas"
					},
					ok:function(data) {
						m3.setPercent(data)
					}
				});
				ajx({
					data:{
						f:"Dashboard_evaldocentes"
					},
					ok:function(data) {
						m4.setPercent(data.percent)
					}
				});

			}
		});
	})

	
    </script>

</div>