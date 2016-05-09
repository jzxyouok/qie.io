<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>phpinfo-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-phpinfo">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <iframe id="phpinfo" name="phpinfo" src="<{$admin_dir}>/index.php/phpinfo/iframe/" style="width:100%; border:none; min-height:600px;"></iframe>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}>
<script>
$(function(){
	document.getElementById('phpinfo').style.height = (document.documentElement.clientHeight-51-20-60-60-30)+'px';	
})
</script>
</body>
</html>
