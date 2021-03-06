<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>后台管理系统-<{$title}></title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-login">
<div class="middle">
  <div class="wrap panel default-form">
    <h2 class="head">后台管理系统<a href="<{$homepage}>" class="fa fa-home"></a></h2>
    <div class="body">
      <form id="login_form" action="<{$admin_dir}>/index.php/main/login/" method="post">
        <fieldset>
          <div class="input-group has-addon">
            <label><span class="inline-block input-addon"><i class="icon fa fa-key"></i></span>
              <input type="password" name="pwd" required autofocus>
            </label>
          </div>
          <div class="input-group has-addon has-img">
            <label><span class="inline-block input-addon"><i class="icon fa fa-image"></i></span>
              <input type="text" name="captcha" maxlength="4" required>
            </label>
            <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
        </fieldset>
        <div class="form-button"><button type="submit" value="">登录</button></div>
        <input type="hidden" name="token" value="<{$token}>">
      </form>
    </div>
  </div><{include file="./footer.tpl"}>
</div>
<{include file="../common/js.tpl"}>
<script>
var captchaImg = document.getElementById('captcha_img');
function refreshImg() {
	var img = new Image();
	img.onload = function(){
		captchaImg.src = this.src;
	}
	img.src = '/index.php/captcha/?w=80&h=32&v='+new Date().getTime();
}
captchaImg.addEventListener('click', refreshImg);
document.getElementById('login_form').addEventListener('submit', function(e){
	e.preventDefault();
	
	var data = $u.getFormValues(e.target);
	
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
});
</script>
</body>
</html>
