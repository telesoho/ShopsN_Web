/**
 * Created by Administrator on 2017/9/29.
 */
function editAdminPwd(url)
{
    var obj = $('#myForm').serializeArray();
    var data = {};
    $.each(obj,function(){
        if(this.name){
            data[this.name] = this.value;
        }else{
            layer.msg('表单填写完整');
            return;
        }
    });
    $.post(url,data,function(e){
        if(e.status == 1){
            layer.msg(e.message);
            loseWindow();
        }
        layer.msg(e.message);return;
    })
}

function loseWindow() {
var index = parent.layer.getFrameIndex(window.name);//获取窗口索引
var id = setInterval(function () {
    window.parent.iframe.location.reload();
    index ? parent.layer.close(index) : false;
}, 2000)
}
