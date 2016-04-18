<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>用户登录-<{$title}></title>
<{include file="./common/css.tpl"}>
</head>
<body class="user">
<{include file="`$DOCUMENT_ROOT`/theme/`$theme`/header.tpl"}>
<div class="middle">
  <div class="wrap panel default-form">
    <h2 class="head">用户登录</h2>
    <form class="body two-collumn" id="login_form" action="/index.php/user/login/" method="post">
      <fieldset>
        <div class="form-group">
          <label>
          <div class="title inline-block">用户名:</div>
          <div class="control">
            <input class="control" type="text" name="user_name" value="<{$smarty.cookies.u_name}>" placeholder="请输入用户名" required autofocus>
          </div>
          </label>
        </div>
        <div class="form-group">
          <label>
          <div class="title inline-block">密&nbsp; &nbsp;码:</div>
          <div class="control">
            <input class="control" type="password" name="pwd" placeholder="请输入密码" required>
          </div>
          </label>
        </div>
        <div class="form-group has-img">
          <label>
          <div class="title inline-block">验证码:</div>
          <div class="control">
            <input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required></div>
          </label>
            <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img">
        </div>
      </fieldset>
      <div class="form-button">
        <button type="submit">登录</button>
        <a class="button" href="/index.php/user/reg/" title="注册">注册</a></div>
      <input type="hidden" name="token" value="<{$token}>">
    </form>
  </div>
  <{include file="`$DOCUMENT_ROOT`/theme/`$theme`/footer.tpl"}> </div>
<{include file="./common/js.tpl"}>
<script>
function refreshImg() {
	var img = new Image();
	img.onload = function(){
		$('#captcha_img').removeAttr('src').attr('src', this.src);
	}
	img.src = '/index.php/captcha/?w=80&h=32&v='+new Date().getTime();
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
					location.href = '<{$smarty.get.url|default:($smarty.server.HTTP_REFERER|default:'/index.php/user/center/')}>';
				}
			},
			error: function(xhr, data) {}
	})
	return false;
});
</script>
</body>
</html>
