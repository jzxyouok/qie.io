<?php
/*
 * 网站配置文件
 */

//1:正常;0:关闭;-1:测试
$profile['state'] = 1;
//管理后台二次登录验证
$profile['admin_relogin'] = true;
//管理后台物理路径
$profile['admin_dir'] = '/manage';
//加密字符串(SALT)
$profile['salt'] = 'Qiezi123!';
//默认数据库配置
$profile['db_config'] = 'default';
//网站域名
$profile['domain'] = '';
//网站首页
$profile['homepage'] = '/';
//主题名称
$profile['theme'] = 'default';
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
