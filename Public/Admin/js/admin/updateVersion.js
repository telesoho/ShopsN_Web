function updateVersion(){
    //loading层
    var index = layer.load(1, {
        shade: [0.1,'#fff'] //0.1透明度的白色背景
    });
    //alert('即将更新,请不要关闭或重启服务器');
    var data = {};
    $("input").each(function(i){
        data[i] = this.value;
    });
    $.post(update_url,data,function(e){
        console.log(e);
            $("#layui-layer1").remove();//layui-layer1是loading 图片的id
            alert(e.message);
            //window.location.reload();
    });

}