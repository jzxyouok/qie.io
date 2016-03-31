<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>用户注册-<{$title}></title>
<{$css}>
</head>
<body class="user-reg">
<{include file="/theme/`$theme`/header.tpl"}>
<div class="wrap-middle">
  <div class="wrap panel default-form login-form">
    <h2 class="header">用户注册</h2>
    <form class="body" id="reg_form" action="/index.php/user/insert/" method="post">
      <fieldset>
        <div class="form-group">
          <label>
          <div class="title inline-block">用 户 名:</div>
          <input type="text" name="user_name" placeholder="请输入用户名" required>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title inline-block">密&nbsp; &nbsp; &nbsp; 码:</div>
          <input type="password" name="pwd" placeholder="请输入密码" required>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title inline-block">确认密码:</div>
          <input type="password" name="confirm_pwd" placeholder="请再次输入密码" required>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title inline-block">电子邮箱:</div>
          <input type="text" name="email" placeholder="请输入电子邮箱" required>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title inline-block">昵&nbsp; &nbsp; &nbsp; 称:</div>
          <input type="text" name="nick">
          </label>
        </div>
        <div class="form-group img">
          <label>
          <div class="title inline-block">验 证 码:</div>
          <input type="text" name="captcha" maxlength="4" placeholder="请输入验证码" required>
          </label>
          <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
      </fieldset>
      <button type="submit">注册</button>
      <a class="button" href="/index.php/user/" title="登录">登录</a>
      <input type="hidden" name="token" value="<{$token}>">
    </form>
  </div>
  <{include file="/theme/`$theme`/footer.tpl"}></div>
<script>
function refreshImg() {
	var img = new Image();
	img.onload = function(){
		$('#captcha_img').removeAttr('src').attr('src', this.src);
	}
	img.src = '/index.php/captcha/?w=80&h=32&v='+new Date().getTime();
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
										location.href = '<{$smarty.get.url|default:($smarty.server.HTTP_REFERER|default:'/index.php/user/center/')}>';
									}},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
