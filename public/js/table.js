var Tabla = function(head, body) {
	
	var yo = this;
	yo.head = head;
	yo.body = body;
	yo.cols = {};
	yo.order = [];

	yo.ajax = "";

	yo.setajax = function(direccion){
		yo.ajax = direccion;
	};

	//views
	yo.finder = '<input type="text" class="form-control find" placeholder="Buscar...">';

	$(yo.body).append("<table class='table'><thead><tr class='ttitle'></tr></thead><thead><tr class='tsearch'></tr></thead><tbody class='tbody'></tbody></table>");

	yo.head1 = $(yo.body+" .ttitle");
	yo.head2 = $(yo.body+" .tsearch");
	yo.rows = $(yo.body+" .tbody");

	yo.addcol = function(name, title, opcional, search, sorted, abbr){
		
		//name, title, opcional, search, sorted
		/*var name = ("short" in vararray)?vararray["short"]:"nn"+Math.floor(Math.random()*1000);
		var title = ("title" in vararray)?vararray["title"]:"Title";
		var opcional = ("opcional" in vararray)?vararray["opcional"]:[0,1];
		if(opcional[0]==0){opcional[1]=1;}

		var search = ("search" in vararray)?vararray["search"]:0;
		var sorted = ("sorted" in vararray)?vararray["sorted"]:0;

		var abbr = ("abbr" in vararray)?vararray["abbr"]:0;*/

		name = name!=undefined?name:"nn"+Math.floor(Math.random()*1000);
		title = title!=undefined?title:"Title";
		opcional = opcional!=undefined?opcional:[0,1];
		if(opcional[0]==0){opcional[1]=1;}

		search = name!=undefined?search:0;
		sorted = name!=undefined?sorted:0;

		abbr = name!=undefined?abbr:0;


		yo.order.push(name);
		yo.cols[name] = {"name":name, "title":title, "opcional":opcional, "search":search, "sorted":sorted, "abbr":abbr};
		
		yo.head1.append("<th class='ct"+name+"'>"+title+"</th>");

		if(search==1){
			yo.head2.append("<th class='ct"+name+"' data-name='"+name+"'>"+yo.finder+"</th>");
		}else{
			yo.head2.append("<th class='ct"+name+"'></th>");
		}

		if(opcional[0]==1){
			if(opcional[1]==1){
				$(yo.head).find(".form-inline").prepend('<div class="checkbox pull-right" style="margin-left: 15px;"><label><input class="mostrar" data-col="'+name+'" type="checkbox" checked> '+title+'</label></div>');
			}else{
				$(yo.head).find(".form-inline").prepend('<div class="checkbox pull-right" style="margin-left: 15px;"><label><input class="mostrar" data-col="'+name+'" type="checkbox"> '+title+'</label></div>');
				yo.head1.find(".ct"+name).hide();
				yo.head2.find(".ct"+name).hide();
				yo.rows.find(".ct"+name).hide();
			}
		}

	};

    $(yo.head).on('click','.checkbox input',function() {
        if (!$(this).is(':checked')) {
            //hide
            $(yo.body+" .ct"+$(this).attr("data-col")).hide();
            yo.cols[$(this).attr("data-col")]['opcional'][1] = 0;
        }else{//show
			$(yo.body+" .ct"+$(this).attr("data-col")).show();
			yo.cols[$(this).attr("data-col")]['opcional'][1] = 1;
        }
    });

    yo.typewatch = function(callback,ms){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	}();

	yo.findfun = function() {

        datos = {
            f:yo.ajax
        };

        $(yo.body+" .find").each(function() {
			var name = $(this).parents("th").attr("data-name");
			var value = $(this).val();
			if(value!=""){
				datos[name] = value;
        	}
        });

        ajx({
            data:datos,
            ok:function(data) {
                console.log(data);

                yo.rows.html("");

                datostabla = data['rows'];
                var color = {"confirmar":"text-warning","confirmado":"text-success","rechazado":"text-danger","":""};

                for(var n in data['rows']){

					var row = data['rows'][n];
					var tr = $("<tr id='"+row.id+"' class='rowlista'></tr>");

					var flag = 0;
					for(var col in yo.order){
						var colname = yo.order[col];
						if (colname in row) {
							flag=1;

							if(yo.cols[colname]['abbr']>0){
								tr.append("<td class='ct"+colname+"'><abbr title='"+row[colname]+"'>"+row[colname].substring(0,yo.cols[colname]['abbr'])+"...</abbr></td>");
							}else{
								tr.append("<td class='ct"+colname+"'>"+row[colname]+"</td>");
							}
							
							
						}else{
							tr.append("<td class='ct"+colname+"'></td>");
						}
					}
					//tr.append("<td class='p1-8'><abbr title='"+tema.tema+"'>"+tema.tema.substring(0,20)+'...</abbr></td>');
					yo.rows.append(tr);

                }

                for(col in yo.cols){
					if(yo.cols[col]['opcional'][1]==0){
						//hide
						$(".ct"+yo.cols[col]['name']).hide();
					}
				}

            }
        });//ajx
	};

	$(yo.body).on("keyup",".find",function() {
		yo.typewatch(yo.findfun,200);
	});



};