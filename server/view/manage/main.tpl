<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>qie.io</title>
<{include file="../css.tpl"}>
</head>
<body class="manage manage-main">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-form">
      <h3 class="head">修改密码</h3>
      <form class="body" id="modify_form" action="<{if $admin_relogin}><{$dir}>/index.php/main/update/<{else}>/index.php/user/update/<{/if}>" method="post">
        <fieldset>
          <div class="form-group">
            <label>
            <div class="title">原 密 码:</div>
            <input type="password" name="old_pwd">
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">密&nbsp; &nbsp;码:</div>
            <input type="password" name="pwd">
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title inline-block">确认密码:</div>
            <input type="password" name="confirm_pwd">
            </label>
          </div>
        </fieldset>
        <div class="form-button"><input type="submit" value="修改"></div>
      </form>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../js.tpl"}>
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
					alert('修改成功');
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
