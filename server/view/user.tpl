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
<div class="form-row"><label>用户名:<input type="text" name="user_name"></label></div>
<div class="form-row"><label>密&nbsp; &nbsp;码:<input type="password" name="password"></label></div>
<div class="form-row img"><label>验 证 码:<input type="text" name="captcha"><img src="/index.php/captcha/" alt="验证码" id="captcha_img"></label></div>
</fieldset>
<input type="submit" value="登录"> <a href="/index.php/user/reg/" title="注册">注册</a>
</form>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
<script>
$('#captcha_img').on('click', function(){
	$(this).removeAttr('src').attr('src', '/index.php/captcha/?v='+new Date().getTime());
});
$('#login_form').on('submit', function(){
	var data = $u.getFormValues(this);
	
	$.ajax({url:this.action,
			method: this.method,
			data: data,
			dataType: 'json',
			success: function(data){console.log(data);},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
