//后台微信操作js
var do_wechat = {
    post_ajax : function(id,url){
        var data = $('#'+id).serializeArray();
        $.post( url,data,function (e) {
            if(e.status == 1){
                layer.msg(e.message);
            }
            layer.msg(e.message);
        })
    },

    delmenu : function(id) {
    //询问框
    layer.confirm('您确定要删除么？', {btn: ['确定', '取消']}, function () {
            $.ajax({
                url: delmenu_url,
                type: 'post',
                data: {id: id},
                success: function (e) {
                    if (e.status == 1) {
                        $('.pmenu' + id).remove();
                        $('.menu' + id).remove();
                        layer.msg(e.message,{icon:1});
                    } else {
                        layer.msg(e.message,{icon:2});
                    }
                }
            });
        }
    )
}

};

