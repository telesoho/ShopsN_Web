
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
 * 品牌js
 */
(function(){
	function brand() {
		
		this.url = '';
		var value = '';
		var ele = '';
		
		
		
		this.setURL = function (url) {
			this.url = url;
		}
		
		this.addBrand = function(id, url ) {
			var req = $('#'+id).formToArray();
			return this.ajax(url, req);
		};
		
		this.recommend = function (id, e, url) {
			ele = e;
			value = e.getAttribute('data-status') == 0 ? 1 : 0;
			//value = value == 0 ? 1 : 0;
			return this.ajax(url, {id : id, recommend : value},this.changeSrc);
		}
		this.changeSrc = function ()
		{
			ele.setAttribute('src',imgUrl[value]);
			ele.setAttribute('data-status',value);
		}
		//编辑
		
		this.editBrand = function(id, url) {
			var req = $('#'+id).formToArray();
			return this.ajax(url, req);
		}
		
		//删除
		this.delBrand = function(id, url) {
			return this.ajax(url,{id :id});
		}
		
		//分类
		this.getClassId = function (obj) {
			
			if (!(obj instanceof Object)) {
				return false;
			}
			
			var json = {};
			
			json[obj.name] = obj.value;
			
			this.dataType = '';
			
			return this.post(this.url, json);
		}
	};
	
	brand.prototype = Tool;
	window.Brand = new brand();
})(window)