<div class="page page-table">
    
    <div class="col-md-6">
        <div class="panel panel-profile">
            <div class="panel-heading bg-primary clearfix">
                <a href="" class="pull-left profile">
                    <img alt="" src="images/profile1.jpg" class="img-circle img80_80">
                </a>
                <h3 class="name"></h3>
                <p class="roles"></p>
            </div>
            <ul class="list-unstyled list-info">
                <li class="list-group-item">
                    <span class="icon glyphicon glyphicon-user"></span>
                    <label>Nombre</label>
                    <font id="name"></font>
                </li>
                <li class="list-group-item">
                    <span class="icon fa fa-shield"></span>
                    <label>Apellido</label>
                    <font id="surname"></font>
                </li>
                <li class="list-group-item">
                    <i class="icon fa fa-envelope"></i>
                    <label>Email</label>
                    <font id="mail"></font>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="panel panel-default tabla">
            <div class="panel-heading"><strong><div class="form-inline"></div><span class="glyphicon glyphicon-page"></span> <font id="title">Areas</font></strong></div>
            <ul class="list-group" id="areas">
                <li class="list-group-item"><div class="input-group"><input type="text" class="form-control" id="areatext"><span class="input-group-btn"><div class="btn btn-default" id="add">Agregar</div></span></div></li>
            </ul>
        </div>
    </div>
	
	<link rel="stylesheet" href="jui/jquery-ui.min.css" />
    <script src="jui/jquery-ui.min.js"></script>
    <script type="text/javascript">

        var jcall = function(id) {
            
            $(function(){

                ajx({
                    data:{
                        f:"Usuarios_perfil",
                        id:id
                    },
                    ok:function(data) {
                        console.log(data)
                        if("user" in data){
                            var user = data.user

                            $('.name').html(user.name+" "+user.surname);

                            $('#name').html(user.name);
                            $('#surname').html(user.surname);
                            $('#mail').html(user.wc_id);

                            console.log(user.name+" "+user.surname)
                            var roles = "";
                            for(i in user.roles){
                                roles += user.roles[i]+",";
                            }
                            $('.roles').html("<abbr title='"+roles+"'>"+roles.substring(0,24)+"...</abbr>")

                            for(i in user.especialidad){
                                var area = user.especialidad[i];
                                $('#areas').prepend("<li class='list-group-item'><font>"+area+"</font><span class='badge badge-danger del'>X</span></li>");
                            }

                        }   
                    }
                })

                $('#add').on("click",function() {
                    var area = $("#areatext").val();
                    if(area!=""){
                        ajx({
                            data:{
                                f:"Usuarios_addarea",
                                id:id,
                                area:area
                            },
                            ok:function(data) {
                                $('#areas').prepend("<li class='list-group-item'><font>"+area+"</font><span class='badge badge-danger del'>X</span></li>");
                            }
                        })
                    }
                })

                $("#areas").on("click",'.del',function() {
                    var area = $(this).parent().find("font").html();
                    var li = $(this).parent();
                    if(area!=""){
                        ajx({
                            data:{
                                f:"Usuarios_delarea",
                                id:id,
                                area:area
                            },
                            ok:function(data) {
                                li.fadeOut();
                            }
                        })
                    }
                })


                $( "#areatext" ).autocomplete({
                    minLength: 1,
                    source: "th/areas",
                    focus: function( event, ui ) {
                    //$( "#buscarprofesor" ).val( ui.item.label );
                    return false;
                    },
                    select: function( event, ui ) {
                    $( "#areatext" ).val( ui.item.value );
                    //$( "#prof" ).val( ui.item.value );
                    return false;
                }
                })
                .autocomplete( "instance" )._renderItem = function( ul, item ) {
                    return $( "<li>" )
                    .append( "<a>" + item.value + "</a>" )
                    .appendTo( ul );
                };

            });

        }

    </script>

</div>