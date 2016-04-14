<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>qie.io</title>
<{include file="../css.tpl"}>
</head>
<body class="manage manage-article">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <iframe id="phpinfo" name="phpinfo" src="<{$dir}>/index.php/phpinfoframe/" style="width:100%; border:none;"></iframe>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../js.tpl"}>
<script>
$(function(){
	document.getElementById('phpinfo').style.height = (document.documentElement.clientHeight-71-20-80-20-5)+'px';	
})
</script>
</body>
</html>
