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
<form id="reg_form" action="/index.php/user/insert/" method="post">
<fieldset>
<div class="form-row"><label>用 户 名:<input type="text" name="user_name"></label></div>
<div class="form-row"><label>密&nbsp; &nbsp;码:<input type="password" name="pwd"></label></div>
<div class="form-row"><label>确认密码:<input type="password" name="confirm_pwd"></label></div>
<div class="form-row"><label>昵&nbsp; &nbsp;称:<input type="text" name="nick"></label></div>
<div class="form-row"><label>电子邮箱:<input type="text" name="email"></label></div>
<div class="form-row img"><label>验 证 码:<input type="text" name="captcha"><img src="/index.php/captcha/" alt="验证码" id="captcha_img"></label></div>
</fieldset>
<input type="hidden" name="token" value="<{$token}>">
<input type="submit" value="注册">
</form>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
<script>
function refreshImg() {
	$('#captcha_img').removeAttr('src').attr('src', '/index.php/captcha/?v='+new Date().getTime());
}
$('#captcha_img').on('click', refreshImg);
$('#reg_form').on('submit', function(){
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
	if(data.confirm_pwd != data.pwd) {
		alert('2次输入密码不一致');
		$(this).find('input[name="confirm_pwd"]').focus();
		return false;
	}
	if(!data.captcha) {
		alert('验证码不能为空');
		$(this).find('input[name="captcha"]').focus();
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
									}},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
