<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>qie.io</title>
<{$css}>
<style>
body.manage .default-form {
	width: 500px;
}
.default-form .title {
	width: 30%;
}
.default-form .control {
	width: 70%;
}
</style>
</head><body class="manage manage-setting">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-form">
      <h3 class="head">系统设定</h3>
      <form class="body two-collumn" id="modify_form" action="<{$dir}>/index.php/setting/save/" method="post">
        <fieldset>
          <div class="form-group">
            <div class="title">管理员二次登录:</div>
            <div class="control">
              <label>
                <input type="radio" name="admin_relogin" value="true"<{if $profile.admin_relogin}> checked<{/if}>>
                是 </label>
              <label>
                <input type="radio" name="admin_relogin" value="false"<{if !$profile.admin_relogin}> checked<{/if}>>
                否 </label>
            </div>
          </div>
          <div class="form-group">
              <label>
            <div class="title">管理后台地址:</div>
            <div class="control">
              <input type="text" name="manage_dir" value="<{$profile.manage_dir}>">
            </div></label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">网站域名:</div>
            <div class="control">
              <input type="text" name="domain" value="<{$profile.domain}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">网站首页:</div>
            <div class="control">
              <input type="text" name="homepage" value="<{$profile.homepage}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">选择主题:</div>
            <div class="control">
              <select name="theme">
              <{section name=n loop=$profile.themes}>
                <option value="<{$profile.themes[n]}>"<{if $profile.themes[n] == $profile.theme}> selected<{/if}>><{$profile.themes[n]}></option>
                <{/section}>
              </select>
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">网站标题:</div>
            <div class="control">
              <input type="text" name="title" value="<{$profile.title}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">网站关键词:</div>
            <div class="control">
              <input type="text" name="keywords" value="<{$profile.meta.keywords}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">网站简介:</div>
            <div class="control">
              <input type="text" name="description" value="<{$profile.meta.description}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">流量监测代码:</div>
            <div class="control">
              <textarea rows="3" name="analytics"><{$profile.analytics}></textarea>
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">ICP证:</div>
            <div class="control">
              <input type="text" name="icp" value="<{$profile.icp}>">
            </div>
            </label>
          </div>
        </fieldset>
        <div class="form-button"><input type="submit" value="确认修改"></div>
      </form>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<script>
$('#modify_form').on('submit', function(){
	var data = $u.getFormValues(this);
	
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
	});
	return false;
});
</script>
</body>
</html>
