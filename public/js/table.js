var Tabla = function(head, body) {
	
	var yo = this;
	yo.head = head;
	yo.body = body;
	yo.cols = {};
	yo.order = [];

	yo.vars = {};

	yo.csv = "";

	yo.ajax = "";
	yo.gm = "";

	yo.setajax = function(direccion){
		yo.ajax = direccion;
	};

	yo.setgm = function(target){
		yo.gm = target;
		yo.order.push("gm");
		yo.cols["gm"] = {"name":"gm", "title":"", "opcional":[0,1], "search":0, "sorted":0, "abbr":0, "control":"gm", "link":target};
		yo.head1.append("<th class='ctgm'></th>");
		yo.head2.append("<th class='ctgm'></th>");
	};

	//views
	yo.finder = '<input type="text" class="form-control find" placeholder="Buscar...">';

	$(yo.body).append("<table class='table'><thead><tr class='ttitle'></tr></thead><thead><tr class='tsearch'></tr></thead><tbody class='tbody'></tbody></table>");

	yo.head1 = $(yo.body+" .ttitle");
	yo.head2 = $(yo.body+" .tsearch");
	yo.rows = $(yo.body+" .tbody");

	yo.setvar = function(name,val){
		yo.vars[name] = val;
	};

	yo.addcol = function(name, title, opcional, search, sorted, abbr, control, link){
		
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
		
		if(control=="check"){
			yo.head1.append("<th class='ct"+name+" text-center'>"+title+"</th>");
		}else{
			yo.head1.append("<th class='ct"+name+"'>"+title+"</th>");
		}
		
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

	yo.add2col = function(name, title, opcional, search, sorted, abbr, control, link){
		
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
		yo.cols[name] = {"name":name, "title":title, "opcional":opcional, "search":search, "sorted":sorted, "abbr":abbr, "control":control, "link":link, "span":2};
		
		if(control=="check"){
			yo.head1.append("<th class='ct"+name+" text-center'>"+title+"</th>");
		}else{
			yo.head1.append("<th class='ct"+name+"'>"+title+"</th>");
		}
		
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

	yo.add2col1 = function(name, title, opcional, search, sorted, abbr, control, link){
		
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
		yo.cols[name] = {"name":name, "title":title, "opcional":opcional, "search":search, "sorted":sorted, "abbr":abbr, "control":control, "link":link, "span":1, "row1":name};
		
		if(control=="check"){
			yo.head1.append("<th class='ct"+name+" text-center'>"+title+"</th>");
		}else{
			yo.head1.append("<th class='ct"+name+"'>"+title+"</th>");
		}
		
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

	yo.add2col2 = function(name, name2){
		yo.cols[name]["row2"] = name2;
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

	yo.findfun = function(putcols, extra) {

        datos = {
            f:yo.ajax
        };

        for(vari in yo.vars){
        	datos[vari] = yo.vars[vari];
        }

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

                putcols = putcols!=undefined?putcols:0;
                extra = extra!=undefined?extra:{"type":0};
                if(putcols==1){
                	if("cols" in data){
                		for(col in data["cols"]){
                			yo.add2col1("a1t"+data["cols"][col]["id"], data["cols"][col]["title"], [0,1], 0, 1);
                			yo.add2col2("a1t"+data["cols"][col]["id"],"a2t"+data["cols"][col]["id"]);
                		}
                	}

                	if(extra.type=="buttonid"){
                		yo.add2col("btn", extra.title, [0,1], 0, 1, 0, "button", extra.link);
                	}

                }

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
					var tr2 = $("<tr id='k"+row.id+"' class='rowlista'></tr>");
					var span2 = false;

					yo.csv += "\n";

					var flag = 0;
					i1 = 0;
					for(var col in yo.order){
						var colname = yo.order[col];
						var span = "";
						if("span" in yo.cols[colname]){

							span = " rowspan='"+yo.cols[colname]["span"]+"' style='vertical-align: middle;' ";
						}

						if (colname in row) {
							flag=1;

							if(yo.cols[colname]['control']=="checkbox"){
								var sel = row[colname]['status'];
								var dis = row[colname]['perm']==1?0:1;

								tr.append("<td "+span+" class='ct"+colname+"'>"+yo.checkbox("",row.id,colname,yo.cols[colname]['title'],sel,dis)+"</td>");

							}else if(yo.cols[colname]['control']=="link"){
								tr.append("<td "+span+" class='ct"+colname+"'><a href='"+yo.cols[colname]['link']+row.id+"'>"+row[colname]+"</a></td>");
							}else if(yo.cols[colname]['control']=="button"){
								tr.append("<td "+span+" class='ct"+colname+"'><a class='btn btn-success' href='"+yo.cols[colname]['link']+row.id+"'>"+yo.cols[colname]['title']+"</a></td>");
							}else if(yo.cols[colname]['control']=="check"){
								if(row[colname]=="1"){
									tr.append("<td "+span+" class='ct"+colname+" popsel text-center' style='color:rgb(0, 192, 0);'><span class='glyphicon glyphicon-ok'></span></td>");
								}else if(row[colname]=="0"){
									tr.append("<td "+span+" class='ct"+colname+" popsel text-center' style='color:red;'><span class='glyphicon glyphicon-remove'></span></td>");
								}else{
									tr.append("<td "+span+" class='ct"+colname+" popsel'></td>");
								}
							}else if(yo.cols[colname]['abbr']>0){
								tr.append("<td "+span+" class='ct"+colname+"'><abbr title='"+row[colname]+"'>"+row[colname].substring(0,yo.cols[colname]['abbr'])+"...</abbr></td>");
							}else{
								tr.append("<td "+span+" class='ct"+colname+"'>"+row[colname]+"</td>");
							}

							if(i1>0){yo.csv+=";"}
							i1++;
							if(yo.cols[colname]['control']=="checkbox"){
								yo.csv += row[colname]['status'];
							}else{
								yo.csv += row[colname];
							}
							
						}else if(colname=="gm"){
							tr.append("<td class='ctgm'><a href='#/"+yo.cols[colname]['link']+"/"+row.id+"' class='btn'><span class='fa fa-pencil'></span></a></td>");

						}else{
							tr.append("<td class='ct"+colname+"'></td>");
							if(i1>0){yo.csv+=";"}
							i1++;
						}
						if("row2" in yo.cols[colname]){
							tr2.append("<td "+span+" class='ct"+colname+"'>"+row[yo.cols[colname]["row2"]]+"</td>");
							span2 = true;
						}
					}
					//tr.append("<td class='p1-8'><abbr title='"+tema.tema+"'>"+tema.tema.substring(0,20)+'...</abbr></td>');
					yo.rows.append(tr);
					if(span2){
						yo.rows.append(tr2);
					}

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