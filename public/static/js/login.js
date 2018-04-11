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
            dialog.success(result.message,'/index');
        }else{
            dialog.error(result.message);
        }
    },"json");
});
$('#button-register-user').click(function(){
    var username=$.trim($('input[name="username"]').val());
    var password=$.trim($('input[name="password"]').val());
    var repassword=$.trim($('input[name="repassword"]').val());
    if(!username||username==''){
        dialog.error('用户名不能为空');
        return;
    }
    if(!password||password==''){
        dialog.error('密码不能为空');
        return;
    }
    if(password!=repassword){
        dialog.error('两次密码不一致');
        return;
    }
    var data={'username':username,'password':password,'repassword':repassword};
    var url="/Index/login/register";
    $.post(url,data,function(result){
        if(result.status===1){
            dialog.success(result.message,'/index/login');
        }else{
            dialog.error(result.message);
        }
    },"json");
});
$('.loginout').click(function(){
    var url="/Index/login/loginout";
    $.post(url,[],function(result){
        console.log(result);
        if(result.status===1){
            dialog.success(result.message,'/index/login');
        }else{
            dialog.error(result.message);
        }
    },"json");
});