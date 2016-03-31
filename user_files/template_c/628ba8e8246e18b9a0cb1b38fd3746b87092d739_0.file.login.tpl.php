<?php
/* Smarty version 3.1.29, created on 2016-03-31 14:25:21
  from "E:\github\qie.io\server\view\manage\login.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.29',
  'unifunc' => 'content_56fd1731665112_50286675',
  'file_dependency' => 
  array (
    '628ba8e8246e18b9a0cb1b38fd3746b87092d739' => 
    array (
      0 => 'E:\\github\\qie.io\\server\\view\\manage\\login.tpl',
      1 => 1459337449,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_56fd1731665112_50286675 ($_smarty_tpl) {
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>后台管理系统</title>
<?php echo $_smarty_tpl->tpl_vars['css']->value;?>

</head>
<body class="login">
<div class="wrap">
  <h1>manage</h1>
  <form id="login_form" action="<?php echo $_smarty_tpl->tpl_vars['dir']->value;?>
/index.php/main/login/" method="post">
    <fieldset>
      <div class="form-row">
        <label>密&nbsp; &nbsp;码:
          <input type="password" name="pwd">
        </label>
      </div>
      <div class="form-row img">
        <label>验 证 码:
          <input type="text" name="captcha"></label>
          <img src="/index.php/captcha/" alt="验证码" id="captcha_img">
      </div>
    </fieldset>
    <input type="submit" value="登录">
    <input type="hidden" name="token" value="<?php echo $_smarty_tpl->tpl_vars['token']->value;?>
">
  </form>
  <p><?php echo $_smarty_tpl->tpl_vars['elapsed_time']->value;?>
&<?php echo $_smarty_tpl->tpl_vars['memory_usage']->value;?>
</p>
</div>
<?php echo $_smarty_tpl->tpl_vars['js']->value;?>
 
<?php echo '<script'; ?>
>
function refreshImg() {
	$('#captcha_img').removeAttr('src').attr('src', '/index.php/captcha/?v='+new Date().getTime());
}

$('#captcha_img').on('click', refreshImg);
$('#login_form').on('submit', function(){
	var data = $u.getFormValues(this);
	
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
	return false;
});
<?php echo '</script'; ?>
>
</body>
</html>
<?php }
}
