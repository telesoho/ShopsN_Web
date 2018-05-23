/**
 *检测支付提交 
 */
function checkSubmit(id) {
	var obj = document.getElementById(id);
	obj.disabled =  true;
	
	var id = setInterval(function () {
		obj.disabled = false;
	}, 3000);
	
	clearInterval(id);
	
	return true;
}