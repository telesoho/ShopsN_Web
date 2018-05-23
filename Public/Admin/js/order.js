
// +----------------------------------------------------------------------
// | OnlineRetailers [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2003-2023 www.yisu.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed 亿速网络（http://www.yisu.cn）
// +----------------------------------------------------------------------
// | Author: 王强 <opjklu@126.com>
// +----------------------------------------------------------------------
/**
 * 订单js
 */
(function() {
	function Order() {
		this.page = 0;
		/**
		 * ajax 获取订单数据 
		 * @param  string id  form id
		 * @param  int page   页数
		 */
		this.ajaxForMyOrder = function(id, page) {
			this.page = page;
			if (!$('#' + id).length || !this.isNumer(page)) {
				layer.msg('参数错误');
				return false;
			}
			var data = $('#' + id).serialize();
			var url = document.getElementById(id).getAttribute('url')+'?p='+page;
			this.dataType = '';
			return this.post(url, data, function(res) {
				$("#ajaxGetReturn").html('');
				$("#ajaxGetReturn").append(res);
			})

		}
		/**
		 * 排序 
		 */
		this.sort = function(id, field) {
			$("input[name='order_by']").val(field);
			var v = $("input[name='sort']").val() == 'desc' ? 'asc' : 'desc';
			$("input[name='sort']").val(v);
			this.ajaxForMyOrder(id, this.page);
		}
		
		/**
		 * 是否收到货 
		 */
		this.isReceive = function (url, event, jre) {
			if (!(event instanceof Object) || !(jre instanceof Object)) {
				layer.msg('参数错误');
				return false;
			}
			var obj = $(event);
			var name= obj.attr('name');
			var value = obj.attr('value');
			value = value === '1' ? 2 : (value === '2' ? 1 : 1); 
			if (!(name) || !(value)) {
				layer.msg("参数异常");
				return false;
			}
			var json = {};
			json[name] = value;
			json = eval('('+(JSON.stringify(json)+JSON.stringify(jre)).replace(/}{/,',')+')');
			return this.ajax(url, json , function (res){
				layer.msg(res.message);
				if (res.hasOwnProperty('status') && res.status == 1) {
					return setInterval(function () {
						location.reload();
					}, 3000);
					return false;
				}
			});
		}
		/**
		 * 删除用户 
		 */
		this.deleteUser = function(url, id) {
			
			
			if(!this.isNumer(id)) {
				layer.msg('参数错误');
				return false;
			}
			
			if(!confirm('确定删除吗')) {
				return false;
			}
			
			return this.ajax(url, {id :id}, function(res) {
				if(res.hasOwnProperty('status') && res.status == 1) {
					layer.msg(res.message);
					return Tool.closeWindow();
				}
			});
		}
        /**
         * 导出excel
         */
        this.export = function() {
            var url = this.export_url;
            var data= [];
            data['realname'] = $("#input-member-id").val();
            data['order_sn_id'] = $("#input-order-id").val();
            data['goods'] = $("#input-goods-id").val();
            data['mobile'] = $("#input-mobile").val();
            data['timegap'] = $("#create_time").val();
            data['timeEnd'] = $("#timeEnd").val();
            data['order_status'] = $('#status option:selected') .val();//选中的值

            var dataa = {realname:data['realname'],order_sn_id:data['order_sn_id'],goods:data['goods'],mobile:data['mobile'],timegap:data['timegap'],timeEnd:data['timeEnd'],order_status:data['order_status']}

            submitForm(url, dataa);
        }
	}
    function submitForm(action, params) {
        var form = 	$("<form></form>");
        form.attr('action', action);
        form.attr('method', 'get');
        form.attr('target', '_self');
        var input1 = $("<input type='hidden' name='realname' value='' />");
        input1.attr('value', params.realname);
        form.append(input1);
        var input2 = $("<input type='hidden' name='order_sn_id' value='' />");
        input2.attr('value', params.order_sn_id);
        form.append(input2);
        var input3 = $("<input type='hidden' name='mobile' value='' />");
        input3.attr('value', params.mobile);
        form.append(input3);
        var input4 = $("<input type='hidden' name='timegap' value='' />");
        input4.attr('value', params.timegap);
        form.append(input4);
        var input5 = $("<input type='hidden' name='timeEnd' value='' />");
        input5.attr('value', params.timeEnd);
        form.append(input5);
        var input6 = $("<input type='hidden' name='order_status' value='' />");
        input6.attr('value', params.order_status);
        form.append(input6);
        var input7 = $("<input type='hidden' name='goods' value='' />");
        input7.attr('value', params.goods);
        form.append(input7);
        form.appendTo('body');
        form.css('display', 'none');
        form.submit();
    }
	Order.prototype = Tool;
	window.Order = new Order();
})(window);

/**
 * 页面加载完成时【运行的方法】 
 */
window.onload = function() {
	Order.ajaxForMyOrder('conditionForm', 1);
}