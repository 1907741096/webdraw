$('#button-login').click(function(){
	var username=$.trim($('input[name="username"]').val());
	var password=$.trim($('input[name="password"]').val());
    if(!username||username==''){
        dialog.error('用户名不能为空');
        return;
    }
	if(!password||password==''){
		dialog.error('密码不能为空');
		return;
	}
	var data={'username':username,'password':password};
	var url="/admin/login/checklogin";
	$.post(url,data,function(result){
		if(result.status===1){
			dialog.success(result.message,'/admin');
		}else{
			dialog.error(result.message);
		}
	},"json");
});
$('#button-login-user').click(function(){
    var username=$.trim($('input[name="username"]').val());
    var password=$.trim($('input[name="password"]').val());
    if(!username||username==''){
        dialog.error('用户名不能为空');
        return;
    }
    if(!password||password==''){
        dialog.error('密码不能为空');
        return;
    }
    var data={'username':username,'password':password};
    var url="/Index/login/checklogin";
    $.post(url,data,function(result){
        if(result.status===1){
            dialog.success(result.message,'/Index');
            getsound(3);
        }else{
            dialog.error(result.message);
        }
    },"json");
});
