$(function(){
	
	$('.tabla td:first-child').addClass("editable").attr("style",'cursor:url(icon/pencil.cur),auto;');

	$(".tabla").on("click",".editable",function() {
		var titulo = $(this).html();
		var td = $(this);

		$(this).removeClass("editable")
			.html("<div class='input-group'><input type='text' value='"+titulo+"' class='form-control texto'><div class='input-group-btn'><div class='btn btn-success save'><span class='glyphicon glyphicon-floppy-disk'></span></div></div></div>");

		$(this).find(".save").on("click",function() {
			var id = $(this).parents("tr").attr("n");
			
			var texto =	$(this).parents(".input-group").find("input");
			var titulo2 = texto.val();
			texto.attr("disabled",1)
			
			var btn = $(this);
			btn.find("span").removeClass("glyphicon glyphicon-floppy-disk").addClass("fa fa-refresh fa-spin");
			btn.addClass("disabled");
			ajx({
                data:{
                    f:'Memorias_cambiarTitulo',
                    id: id,
                    titulo: titulo2
                },
                ok:function(data){
                    td.html(titulo2).addClass("editable")
                },
                error:function(data) {
                	texto.attr("disabled",0)
                	btn.find("span").addClass("glyphicon glyphicon-floppy-disk").removeClass("fa fa-refresh fa-spin")
                	btn.removeClass("disabled");
                }
            });


		})

	})

});