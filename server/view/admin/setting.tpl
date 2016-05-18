<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>网站设定-qie.io</title>
<{include file="../common/css.tpl"}>
<style>
body.manage .default-form {
	width: 500px;
	display: inline-block;
	vertical-align: top;
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
      <div class="body">
        <form class="two-collumn" id="profile_form" action="<{$admin_dir}>/index.php/setting/update/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">网站标题:</div>
              <div class="control">
                <input type="text" name="title" value="<{$profile.title}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">网站关键词:</div>
              <div class="control">
                <input type="text" name="keywords" value="<{$profile.meta.keywords}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">网站简介:</div>
              <div class="control">
                <input type="text" name="description" value="<{$profile.meta.description}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">流量监测代码:</div>
              <div class="control">
                <textarea rows="3" name="analytics"><{$profile.analytics}></textarea>
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">ICP证:</div>
              <div class="control">
                <input type="text" name="icp" value="<{$profile.icp}>">
              </div>
              </label>
            </div>
            <div class="split-line"><span class="text">高级选项(<a href="" class="more">显示</a>)</span></div>
            <div class="field-group hide">
            <div class="input-group">
              <label>
              <div class="title">网站状态:</div>
              <div class="control">
                <select name="state"><option value="1"<{if $profile.state==1}> selected<{/if}>>正常</option>
                <option value="0"<{if $profile.state==0}> selected<{/if}>>关闭</option>
                <option value="-1"<{if $profile.state==-1}> selected<{/if}>>测试</option></select>
              </div>
              </label>
            </div>
              <div class="input-group">
                <div class="title">管理员二次登录:</div>
                <div class="control">
                  <label> <input type="radio" name="admin_relogin" value="true"<{if $profile.admin_relogin}> checked<{/if}>>
                    是 </label>
                  <label> <input type="radio" name="admin_relogin" value="false"<{if !$profile.admin_relogin}> checked<{/if}>>
                    否 </label>
                </div>
              </div>
              <div class="input-group">
                <label>
                <div class="title">管理后台地址:</div>
                <div class="control">
                  <input type="text" name="admin_dir" value="<{$profile.admin_dir}>">
                </div>
                </label>
              </div>
              <div class="input-group">
                <label>
                <div class="title">加密SALT:</div>
                <div class="control">
                  <input type="text" name="salt" value="<{$profile.salt}>">
                </div>
                </label>
              </div>
              <div class="input-group">
                <label>
                <div class="title">数据库配置:</div>
                <div class="control">
                  <select name="db_config">
                    <{foreach from=$database key=k item=i}><option value="<{$k}>"<{if $k == $profile.db_config}> selected<{/if}>><{$k}>
                    </option>
                    <{/foreach}>
                  </select>
                </div>
                </label>
              </div>
              <div class="input-group">
                <label>
                <div class="title">网站域名:</div>
                <div class="control">
                  <input type="text" name="domain" value="<{$profile.domain}>">
                </div>
                </label>
              </div>
              <div class="input-group">
                <label>
                <div class="title">网站首页:</div>
                <div class="control">
                  <input type="text" name="homepage" value="<{$profile.homepage}>">
                </div>
                </label>
              </div>
              <div class="input-group">
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
            </div>
          </fieldset>
          <div class="form-button">
            <input type="submit" value="确认修改">
          </div>
        </form>
        <div class="tips">
          <p>* 如果修改管理后台地址失败，请手动修改文件夹名称和设置profile.php对应的值(profile['admin_dir'])。</p>
          <p>* 网站域名只是用于setcookie。一般留空即可。</p>
        </div>
      </div>
    </div>
    <div class="panel default-form">
      <h3 class="head">数据库设定</h3>
      <div class="body">
        <form class="two-collumn" id="database_form" action="<{$admin_dir}>/index.php/setting/update_db/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">选择配置文件:</div>
              <div class="control">
                <select name="db_config">
                  <{foreach from=$database key=k item=i}><option value="<{$k}>"<{if $k == $db_config}> selected<{/if}>><{$k}>
                  </option>
                  <{/foreach}> <option value="add_profile"<{if 'add_profile' == $db_config}> selected<{/if}>>(+)增加配置
                  </option>
                </select>
              </div>
              </label>
            </div>
            <{if 'add_profile' == $db_config}>
            <div class="input-group">
              <label>
              <div class="title">配置名称:</div>
              <div class="control">
                <input type="text" name="profile_name" value="" required autofocus>
              </div>
              </label>
            </div>
            <{/if}>
            <div class="input-group">
              <label>
              <div class="title">用户名:</div>
              <div class="control">
                <input type="text" name="user" value="<{$database[$db_config].user}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">密码:</div>
              <div class="control">
                <input type="text" name="password" value="<{$database[$db_config].password}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">主机host:</div>
              <div class="control">
                <input type="text" name="host" value="<{$database[$db_config].host}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">数据库名称:</div>
              <div class="control">
                <input type="text" name="db" value="<{$database[$db_config].db}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">端口号:</div>
              <div class="control">
                <input type="text" name="port" value="<{$database[$db_config].port}>">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">字符编码:</div>
              <div class="control">
                <input type="text" name="charset" value="<{$database[$db_config].charset}>">
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button">
            <input type="submit" value="确认<{if 'add_profile' == $db_config}>添加<{else}>修改<{/if}>">
            <input type="button" value="测试连接">
            <{if 'add_profile' != $db_config}>
            <input type="button" value="删除配置">
            <{/if}> </div>
        </form>
        <div class="tips">
          <p>* 主机地址为本地，主机host一般填localhost。</p>
          <p>* 端口号一般为3306。如果是默认，请填0或者3306。</p>
          <p>* 字符编码一般国内为utf8/gb2312/gbk。英文为主或者国际化，推荐用utf8。其他情况可以用国标。</p>
        </div>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
document.getElementById('profile_form').addEventListener('submit', function(e){
	e.preventDefault();
	
	var data = $u.getFormValues(e.target);
	
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
});
document.querySelector('#profile_form .more').addEventListener('click', function(e){
	e.preventDefault();
	var nextNode = e.target.parentNode.parentNode.nextSibling;
	
	while(nextNode.nodeType != 1) {
		nextNode = nextNode.nextSibling;
	}
	if(nextNode.classList.contains('hide')) {
		nextNode.style.height = nextNode.children.length*(nextNode.firstElementChild.offsetHeight+15)+'px';
	} else
		nextNode.style.height = '';
	nextNode.classList.toggle('hide');
});
document.querySelector('#database_form select').addEventListener('change', function(e){
		location.href = '?db_config='+e.target.value;
});
document.querySelector('#database_form input[type=button]').addEventListener('click', function(e){
	var data = $u.getFormValues(e.target.parentNode.parentNode);
	$.ajax({url:'<{$admin_dir}>/index.php/setting/check_db/',
			method: 'post',
			data: data,
			dataType: 'json',
			success: function(data){
				if(data.status< 1) {
					alert(data.result);
				} else {
					alert('测试通过');
				}
			},
			error: function(xhr, data) {}
	});
});
document.querySelector('#database_form input[type=button]:last-child').addEventListener('click', function(e){
	var profileName = document.querySelector('#database_form select').value
	if(profileName == 'add_profile')
		return;
	if(!confirm('确认删除？'))
		return;
	$.ajax({url:'<{$admin_dir}>/index.php/setting/delete_db/'+profileName+'/',
			method: 'get',
			data: {},
			dataType: 'json',
			success: function(data){
				if(data.status< 1) {
					alert(data.result);
				} else {
					alert('删除成功');
					location.href = location.href;
				}
			},
			error: function(xhr, data) {}
	});
});
document.getElementById('database_form').addEventListener('submit', function(e){
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
					alert('修改成功');
					location.href = location.href;
				}
			},
			error: function(xhr, data) {}
	});
});
</script>
</body>
</html>
