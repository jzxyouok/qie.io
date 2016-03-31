<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>用户中心-<{$title}></title>
<{$css}>
</head>
<body class="user-center">
<{include file="/theme/`$theme`/header.tpl"}>
<div class="wrap-middle">
  <div class="wrap panel default-form login-form">
    <h2 class="header">用户中心</h2>
    <form class="body" id="modify_form" action="/index.php/user/update/" method="post">
      <fieldset>
        <div class="form-group">
          <label><div class="title inline-block">原 密 码 :</div>
            <input type="password" name="old_pwd">
          </label>
        </div>
        <div class="form-group">
          <label><div class="title inline-block">密&nbsp; &nbsp; &nbsp; 码:</div>
            <input type="password" name="pwd">
          </label>
        </div>
        <div class="form-group">
          <label><div class="title inline-block">确认密码:</div>
            <input type="password" name="confirm_pwd">
          </label>
        </div>
        <div class="form-group">
          <label><div class="title inline-block">电子邮箱:</div>
            <input type="text" name="email">
          </label>
        </div>
        <div class="form-group">
          <label><div class="title inline-block">昵&nbsp; &nbsp; &nbsp; 称:</div>
            <input type="text" name="nick" value="<{$smarty.cookies.u_nick}>">
          </label>
        </div>
      </fieldset>
      <input type="hidden" name="token" value="<{$token}>">
      <button type="submit">修改</button>
    </form>
  </div>
  <{include file="/theme/`$theme`/footer.tpl"}> </div>
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
