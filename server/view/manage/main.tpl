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
<form id="modify_form" action="<{$dir}>/index.php/main/update/" method="post">
<fieldset>
<div class="form-row"><label>原 密 码:<input type="password" name="old_pwd"></label></div>
<div class="form-row"><label>密&nbsp; &nbsp;码:<input type="password" name="pwd"></label></div>
<div class="form-row"><label>确认密码:<input type="password" name="confirm_pwd"></label></div>
</fieldset>
<input type="hidden" name="token" value="<{$token}>">
<input type="submit" value="修改">
</form>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
<script>
$('#modify_form').on('submit', function(){
	var data = $u.getFormValues(this);
	
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
	if(!data.old_pwd) {
			alert('请输入原来的密码');
			$(this).find('input[name="old_pwd"]').focus();
			return false;
		}
	
	$.ajax({url:this.action,
			method: this.method,
			data: data,
			dataType: 'json',
			success: function(data){
				if(data.status< 1) {
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
