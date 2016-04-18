<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>qie.io</title>
<{include file="../common/css.tpl"}>
<style>
body.manage .default-form {
	width: 500px;
	display:inline-block;
	vertical-align:top;
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
      <h3 class="head">网站设定</h3>
      <form class="body two-collumn" id="profile_form" action="<{$dir}>/index.php/setting/update/" method="post">
        <fieldset>
          <div class="form-group">
            <div class="title">管理员二次登录:</div>
            <div class="control">
              <label> <input type="radio" name="admin_relogin" value="true"<{if $profile.admin_relogin}> checked<{/if}>>
                是 </label>
              <label> <input type="radio" name="admin_relogin" value="false"<{if !$profile.admin_relogin}> checked<{/if}>>
                否 </label>
            </div>
          </div>
          <div class="form-group">
            <label>
            <div class="title">管理后台地址:</div>
            <div class="control">
              <input type="text" name="manage_dir" value="<{$profile.manage_dir}>">
            </div>
            </label>
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
                <{section name=n loop=$profile.themes}> <option value="<{$profile.themes[n]}>"<{if $profile.themes[n] == $profile.theme}> selected<{/if}>><{$profile.themes[n]}>
                </option>
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
        <div class="form-button">
          <input type="submit" value="确认修改">
        </div>
      </form>
    </div>
    <div class="panel default-form">
      <h3 class="head">数据库设定</h3>
      <form class="body two-collumn" id="database_form" action="<{$dir}>/index.php/setting/update_db/" method="post">
        <fieldset>
          <div class="form-group">
            <label>
            <div class="title">选择配置文件:</div>
            <div class="control"><select name="db_profile"><{foreach from=$database key=k item=i}><option value="<{$k}>"<{if $k == $db_profile}> selected<{/if}>><{$k}></option><{/foreach}></select>
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">用户名:</div>
            <div class="control">
              <input type="text" name="user" value="<{$database[$db_profile].user}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">密码:</div>
            <div class="control">
              <input type="text" name="password" value="<{$database[$db_profile].password}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">主机host:</div>
            <div class="control">
              <input type="text" name="host" value="<{$database[$db_profile].host}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">数据库名称:</div>
            <div class="control">
              <input type="text" name="db" value="<{$database[$db_profile].db}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">端口号:</div>
            <div class="control">
              <input type="text" name="port" value="<{$database[$db_profile].port}>">
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">字符编码:</div>
            <div class="control">
              <input type="text" name="charset" value="<{$database[$db_profile].charset}>">
            </div>
            </label>
          </div>
        </fieldset>
        <div class="form-button">
          <input type="submit" value="确认修改">
        </div>
      </form>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
$('#profile_form').on('submit', function(){
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
$('#database_form select').on('change', function(){
		location.href = '?db_profile='+this.value;
});
$('#database_form').on('submit', function(){
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
