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
<form action="/index.php/user/login/" method="post">
<fieldset>
<div class="form-row"><label>用户名:<input type="text" name="user_name"></label></div>
<div class="form-row"><label>密&nbsp; &nbsp;码:<input type="password" name="password"></label></div>
<div class="form-row img"><label>验证码:<input type="text" name="captcha"><img src="/index.php/captcha/" alt="验证码"></label></div>
</fieldset>
<input type="submit" value="登录">
</form>
<p><{$elapsed_time}>&<{$memory_usage}></p>
<{$js}>
</body>
</html>
