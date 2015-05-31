var Tabla = function(head, body) {
	
	var yo = this;
	yo.head = head;
	yo.body = body;
	yo.cols = {};
	yo.order = [];

	yo.csv = "";

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

	yo.addcol = function(name, title, opcional, search, sorted, abbr, control, link){
		
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

		search = search!=undefined?search:0;
		sorted = sorted!=undefined?sorted:0;

		abbr = abbr!=undefined?abbr:0;

		control = control!=undefined?control:"text";
		link = link!=undefined?link:0;

		yo.order.push(name);
		yo.cols[name] = {"name":name, "title":title, "opcional":opcional, "search":search, "sorted":sorted, "abbr":abbr, "control":control, "link":link};
		
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
                yo.csv="";

                var i1 = 0;
                for(col in yo.cols){
					if(i1>0){yo.csv+=";"}
					yo.csv+=yo.cols[col]['title'];
					i1++;
				}

                datostabla = data['rows'];
                var color = {"confirmar":"text-warning","confirmado":"text-success","rechazado":"text-danger","":""};

                for(var n in data['rows']){

					var row = data['rows'][n];
					var tr = $("<tr id='"+row.id+"' class='rowlista'></tr>");
					yo.csv += "\n";

					var flag = 0;
					i1 = 0;
					for(var col in yo.order){
						var colname = yo.order[col];
						if (colname in row) {
							flag=1;

							if(yo.cols[colname]['control']=="checkbox"){
								var sel = row[colname]['status'];
								var dis = row[colname]['perm']==1?0:1;

								tr.append("<td class='ct"+colname+"'>"+yo.checkbox("",row.id,colname,yo.cols[colname]['title'],sel,dis)+"</td>");

							}else if(yo.cols[colname]['control']=="link"){
								tr.append("<td class='ct"+colname+"'><a href='"+yo.cols[colname]['link']+row.id+"'>"+row[colname]+"</a></td>");
							}else if(yo.cols[colname]['abbr']>0){
								tr.append("<td class='ct"+colname+"'><abbr title='"+row[colname]+"'>"+row[colname].substring(0,yo.cols[colname]['abbr'])+"...</abbr></td>");
							}else{
								tr.append("<td class='ct"+colname+"'>"+row[colname]+"</td>");
							}

							if(i1>0){yo.csv+=";"}
							i1++;
							if(yo.cols[colname]['control']=="checkbox"){
								yo.csv += row[colname]['status'];
							}else{
								yo.csv += row[colname];
							}
							
							
						}else{
							tr.append("<td class='ct"+colname+"'></td>");
							if(i1>0){yo.csv+=";"}
							i1++;
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

	yo.download = function(filename, text) {
		var pom = document.createElement('a');
		pom.setAttribute('href', 'data:text/plain;charset=utf-8,' + encodeURIComponent(text));
		pom.setAttribute('download', filename);

		if (document.createEvent) {
			var event = document.createEvent('MouseEvents');
			event.initEvent('click', true, true);
			pom.dispatchEvent(event);
		}
		else {
			pom.click();
		}
	}

	$(yo.head+" .download").on("click", function() {
		yo.download("reporte.csv",yo.csv);
	})

	yo.checkbox = function(name, n, value, title, sel, dis){

		name = name!=undefined?'name="'+name+'"':"";
		n = n!=undefined?'n="'+n+'"':"";
		value = value!=undefined?'value="'+value+'"':"";
		title = title!=undefined?title:"";
		sel = sel!=undefined?sel:0;
		dis = dis!=undefined?dis:0;

		sel = sel==1?"checked":"";
		dis = dis==1?"disabled":"";

		return '<label class="ui-checkbox"><input type="checkbox" '+name+' '+value+' '+n+' '+sel+' '+dis+'><span>'+title+'</span></label>'
	}


};