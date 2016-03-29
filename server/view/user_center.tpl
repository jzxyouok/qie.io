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
<p>hello, <{$user.nick}> . <a href="/index.php/user/logout/?url=/">退出</a></p>
<form id="modify_form" action="/index.php/user/update/" method="post">
<fieldset>
<div class="form-row"><label>原 密 码:<input type="password" name="old_pwd"></label></div>
<div class="form-row"><label>密&nbsp; &nbsp;码:<input type="password" name="pwd"></label></div>
<div class="form-row"><label>确认密码:<input type="password" name="confirm_pwd"></label></div>
<div class="form-row"><label>昵&nbsp; &nbsp;称:<input type="text" name="nick" value="<{$smarty.cookies.u_nick}>"></label></div>
<div class="form-row"><label>电子邮箱:<input type="text" name="email"></label></div>
</fieldset>
<input type="hidden" name="token" value="<{$token}>">
<input type="submit" value="修改">
</form>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
<script>
$('#modify_form').on('submit', function(){
	var data = $u.getFormValues(this);
	
	if(data.pwd) {
		if(data.confirm_pwd != data.pwd) {
			alert('2次输入密码不一致');
			$(this).find('input[name="confirm_pwd"]').focus();
			return false;
		}
		if(!data.old_pwd) {
			alert('请输入原来的密码');
			$(this).find('input[name="old_pwd"]').focus();
			return false;
		}
	}
	
	$.ajax({url:this.action,
			method: this.method,
			data: data,
			dataType: 'json',
			success: function(data){
				if(data.status > 0) {
					alert('修改成功');
					location.href = location.href;
				} else {
					alert(data.result);
				}
			},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
