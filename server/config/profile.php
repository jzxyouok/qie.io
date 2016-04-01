<?php
/*
 * 网站配置
 */
//网站域名
$profile['domain'] = '';
//管理后台二次登录
$profile['manage_relogin'] = true;
//主题名称
$profile['theme'] = 'default';
//前端静态文件
$profile['css'] = array('<link type="text/css" rel="stylesheet" href="/theme/default/css/style.css">');
$profile['js'] =  array('<script src="/theme/default/js/common.js"></script>');
//网站meta
$profile['title'] = '网站标题';
$profile['meta']['keywords'] = 'keyword1,keyword23';
$profile['meta']['description'] = '网站介绍';
//前端监测代码
$profile['analytics'] = <<<EOT
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?ec3d789892f55f0c1a634b2106d68f90";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
EOT;
//ICP
$profile['icp'] = '粤icp5';
