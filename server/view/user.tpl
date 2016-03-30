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
<h1>user</h1>
<form id="login_form" action="/index.php/user/login/" method="post">
<fieldset>
<div class="form-row"><label>用户名:<input type="text" name="user_name" value="<{$smarty.cookies.u_name}>" placeholder="请输入用户名"></label></div>
<div class="form-row"><label>密&nbsp; &nbsp;码:<input type="password" name="pwd" placeholder="请输入密码"></label></div>
<div class="form-row img"><label>验 证 码:<input type="text" name="captcha" placeholder="请输入验证码"><img src="/index.php/captcha/" alt="验证码" id="captcha_img"></label></div>
</fieldset>
<input type="submit" value="登录"> <a href="/index.php/user/reg/" title="注册">注册</a>
<input type="hidden" name="token" value="<{$token}>">
</form>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
<script>
function refreshImg() {
	$('#captcha_img').removeAttr('src').attr('src', '/index.php/captcha/?v='+new Date().getTime());
}

$('#captcha_img').on('click', refreshImg);
$('#login_form').on('submit', function(){
	var data = $u.getFormValues(this);
	if(!data.user_name) {
		alert('用户名不能为空');
		$(this).find('input[name="user_name"]').focus();
		return false;
	}
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
					location.href = '/index.php/user/center/';
				}
			},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
