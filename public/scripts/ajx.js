var ajx = function(data){
    $.ajax({
        type:"post",
        data: data.data ,
        url: "../lti",
        success: function(output){
            if(output=="not logged"){
                alert(output);
                window.location = "login";
            }else{
                var data1 = JSON.parse(output);
                if("error" in data1){
                	if("error" in data){
                		data.error();
                	}else{
                    	alert(data1.error);
                	}
                }else{
                    data.ok(data1);
                }
            }
        }
    });
}