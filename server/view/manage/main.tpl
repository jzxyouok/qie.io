<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>qie.io</title>
<{$css}>
<style>
body {text-align:center;}
</style>
</head>
<body>
<h1>manage</h1>
<a href="<{$dir}>/index.php/main/logout/">退出</a>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
<script>
function refreshImg() {
	$('#captcha_img').removeAttr('src').attr('src', '/index.php/captcha/?v='+new Date().getTime());
}

$('#captcha_img').on('click', refreshImg);
$('#login_form').on('submit', function(){
	var data = $u.getFormValues(this);
	
	if(!data.pwd) {
		alert('密码不能为空');
		$(this).find('input[name="pwd"]').focus();
		return false;
	}
	
	$.ajax({url:this.action,
			method: this.method,
			data: data,
			dataType: 'json',
			success: function(data){
				if(data.status< 1) {
					refreshImg();
					alert(data.result);
				} else {
					location.href = location.href;
				}
			},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
