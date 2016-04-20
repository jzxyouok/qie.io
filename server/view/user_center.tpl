<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>用户中心-<{$title}></title>
<{include file="./common/css.tpl"}>
</head>
<body class="user user-center">
<{include file="`$DOCUMENT_ROOT`/theme/`$theme`/header.tpl"}>
<div class="middle">
  <div class="wrap panel default-form">
    <h2 class="head">用户中心<a href="<{$admin_dir}>/" class="fa fa-cog"></a></h2>
    <form class="body two-collumn" id="modify_form" action="/index.php/user/update/" method="post">
      <fieldset>
        <div class="form-group">
          <label><div class="title">原 密 码 :</div>
            <div class="control"><input type="password" name="old_pwd"></div>
          </label>
        </div>
        <div class="form-group">
          <label><div class="title">密&nbsp; &nbsp; &nbsp; 码:</div>
            <div class="control"><input type="password" name="pwd"></div>
          </label>
        </div>
        <div class="form-group">
          <label><div class="title">确认密码:</div>
            <div class="control"><input type="password" name="confirm_pwd"></div>
          </label>
        </div>
        <div class="form-group">
          <label><div class="title">电子邮箱:</div>
            <div class="control"><input type="text" name="email"></div>
          </label>
        </div>
        <div class="form-group">
          <label><div class="title">昵&nbsp; &nbsp; &nbsp; 称:</div>
            <div class="control"><input type="text" name="nick" value="<{$smarty.cookies.u_nick}>"></div>
          </label>
        </div>
      </fieldset>
      <input type="hidden" name="token" value="<{$token}>">
      <div class="form-button"><button type="submit">修改</button></div>
    </form>
  </div>
  <{include file="`$DOCUMENT_ROOT`/theme/`$theme`/footer.tpl"}> </div>
<{include file="./common/js.tpl"}>
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
