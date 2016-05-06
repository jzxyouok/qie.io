<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>编辑用户信息-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-user user-edit">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-form">
      <h3 class="head">编辑用户信息</h3>
      <div class="body">
        <form action="<{$admin_dir}>/index.php/user/update/<{$data.id}>/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">用 户 名:</div>
              <div class="control">
                <input type="text" name="user_name" placeholder="请输入用户名" autofocus value="<{$data.name}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">密&nbsp; &nbsp; &nbsp; 码:</div>
              <div class="control">
                <input type="text" name="pwd" placeholder="请输入密码">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">电子邮箱:</div>
              <div class="control">
                <input type="text" name="email" placeholder="请输入电子邮箱" value="<{$data.email}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">昵&nbsp; &nbsp; &nbsp; 称:</div>
              <div class="control">
                <input type="text" name="nick" value="<{$data.nick}>">
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button"><button type="submit">修改</button></div>
        </form>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
document.querySelector('.default-form form').addEventListener('submit', function(e){
	e.preventDefault();
	var data = $u.getFormValues(this);
	
	$.ajax({url:this.action,
			method: this.method,
			data: data,
			dataType: 'json',
			success: function(data){
									if(data.status< 1) {
										alert(data.result);
									} else {
										location.href = location.href;
									}},
			error: function(xhr, data) {}
	})
});
</script>
</body>
</html>
