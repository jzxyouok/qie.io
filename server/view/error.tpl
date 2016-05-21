<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>error-<{$title}></title>
<{include file="./common/css.tpl"}>
<style>
.msg {text-align:center;}
.msg i {font-size:1.5em; margin-right:.2em;}
</style>
</head>
<body class="error">
<{include file="`$smarty.const.DOCUMENT_ROOT`/theme/`$theme`/header.tpl"}>
<div class="middle">
  <div class="wrap panel default-form">
    <h2 class="head">出错了</h2>
    <div class="body">
      <div class="msg"><i class="fa fa-frown-o"></i><{$msg}></div>
      <div class="ggad"><script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- parked -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-2828706807674059"
     data-ad-slot="8957450803"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script></div>
    </div>
  </div></div>
  <{include file="`$smarty.const.DOCUMENT_ROOT`/theme/`$theme`/footer.tpl"}>
<{include file="./common/js.tpl"}>
</body>
</html>
