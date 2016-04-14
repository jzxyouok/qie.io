<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>用户注册-<{$title}></title>
<{include file="./css.tpl"}>
</head>
<body class="user user-reg">
<{include file="`$DOCUMENT_ROOT`/theme/`$theme`/header.tpl"}>
<div class="middle">
  <div class="wrap panel default-form">
    <h2 class="head">用户注册</h2>
    <form class="body two-collumn" id="reg_form" action="/index.php/user/insert/" method="post">
      <fieldset>
        <div class="form-group">
          <label>
          <div class="title">用 户 名:</div>
          <div class="control"><input type="text" name="user_name" placeholder="请输入用户名" required autofocus></div>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title">密&nbsp; &nbsp; &nbsp; 码:</div>
          <div class="control"><input type="password" name="pwd" placeholder="请输入密码" required></div>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title">确认密码:</div>
          <div class="control"><input type="password" name="confirm_pwd" placeholder="请再次输入密码" required></div>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title">电子邮箱:</div>
          <div class="control"><input type="text" name="email" placeholder="请输入电子邮箱" required></div>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title">昵&nbsp; &nbsp; &nbsp; 称:</div>
          <div class="control"><input type="text" name="nick"></div>
          </label>
        </div>
        <div class="form-group has-img">
          <label>
          <div class="title">验 证 码:</div>
          <div class="control"><input type="text" name="captcha" maxlength="4" placeholder="请输入验证码" required></div>
          </label>
          <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
      </fieldset>
      <div class="form-button"><button type="submit">注册</button>
      <a class="button" href="/index.php/user/" title="登录">登录</a></div>
      <input type="hidden" name="token" value="<{$token}>">
    </form>
  </div>
  <{include file="`$DOCUMENT_ROOT`/theme/`$theme`/footer.tpl"}></div>
<{include file="./js.tpl"}>
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
