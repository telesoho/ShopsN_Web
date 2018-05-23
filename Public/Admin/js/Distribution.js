var Distribution =
    {
        ajaxForMyOrder: function (page) {
            if(isNaN(page)){
                layer.msg('参数错误');
            }
            var data = $('#conditionForm').serialize();
            var url = $('#conditionForm').attr('url') + '?p=' + page;
            $.post(url, data, function (res) {
                $("#ajaxGetReturn").html('');
                $("#ajaxGetReturn").append(res);
            })

        },
        
        distribution : function () {
            var json = {};
            var data = $('input[name*=\'selected\']:checked');
            if(data.length === 0){
                alert('请选择订单');return;
            }
            data.each(function(i,e){
                json[i] = e.value;
            });

            var url = $('#myTable').attr('data-url');
            $.post(url, json, function (res) {
                if(res.status === 1){
                    layer.msg('分销成功');
                    Distribution.ajaxForMyOrder(1);
                    return;
                }
                layer.msg(res.message);
            })
        },
    };

$(function () {
    Distribution.ajaxForMyOrder(1);
});


