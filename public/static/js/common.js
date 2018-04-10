$('.tbody #delete').click(function(){
    var id=$(this).attr('attr-id');
    var url=SCOPE.delete_url;
    data = {};
    data['id'] = id;
    data['status'] = -1;

    layer.open({
        type : 0,
        title : '是否提交？',
        btn: ['yes', 'no'],
        icon : 3,
        closeBtn : 2,
        content: "是否确定删除",
        scrollbar: true,
        yes: function(){
            // 执行相关跳转
            todelete(url, data);
        },
    });
});
$('#submit').click(function(){
	var data = $("#form").serializeArray();
    postData = {};
    $(data).each(function(i){
       postData[this.name] = this.value;
    });
   // console.log(postData);
    // 将获取到的数据post给服务器
    save_url = SCOPE.save_url;
    jump_url = SCOPE.jump_url;
    $.post(save_url,postData,function(result){
        if(result.status == 1) {
            //成功
            dialog.success(result.message,jump_url);
        }else if(result.status == 0) {
            // 失败
            dialog.error(result.message);
        }
    },"JSON");
});
$(".tbody #status").click(function(){
    var id = $(this).attr('attr-id');
    var status = $(this).attr("attr-status");
    var url = SCOPE.status_url;
    data = {};
    data['id'] = id;
    if(status==1){
        data['status'] = 0;
    }else{
        data['status'] = 1;
    }
    layer.open({
        type : 0,
        title : '是否提交？',
        btn: ['yes', 'no'],
        icon : 3,
        closeBtn : 2,
        content: "是否确定更改状态",
        scrollbar: true,
        yes: function(){
            // 执行相关跳转
            todelete(url, data);
        },
    });
});
$("#listorder").click(function(){
    var data = $('#form-listorder').serializeArray();
    postData={};
    $(data).each(function(i){
        postData[this.name]=this.value;
    });
    var url=SCOPE.listorder_url;
    var jump_url=SCOPE.jump_url;
    $.post(url,postData,function(result){
        if(result.status==1){
            dialog.success(result.message,result['data']['jump_url']);
        }else{
            dialog.error(result.message);
        }
    },"JSON");
});
$("#push").click(function(){
    var id = $("#select-push").val();
    // if(id==0) {
    //     return dialog.error("请选择推荐位");
    // }
    var data=$('#form-listorder').serializeArray();
    push = {};
    postData = {};
    $("input[name='push']:checked").each(function(i){
        push[i] = $(this).val();
    });
    postData['push'] = push;
    postData['position_id']  =  id;
  //  console.log(postData);return;
    var url = SCOPE.push_url;
    $.post(url, postData, function(result){
        if(result.status == 1) {
            // TODO
            return dialog.success(result.message,result['data']['jump_url']);
        }
        if(result.status == 0) {
            // TODO
            return dialog.error(result.message);
        }
    },"json");
});
function todelete(url, data) {
    $.post(url,data,function(s){
            if(s.status == 1) {
                dialog.success(s.message,'');
                // 跳转到相关页面
            }else {
                dialog.error(s.message);
            }
        }
    ,"JSON");
}

