<header class="header">
  <div class="title">
    <h1>Qiezi CMS</h1>
  </div>
  <div class="control-panel"><a href="<{$homepage}>" class="fa fa-home"></a></div>
</header>
<div class="sidebar">
  <div class="wrap">
    <div class="user-info"><span><i class="fa fa-user"></i><{$smarty.cookies.u_nick}>,</span><a href="<{$dir}>/index.php/main/logout/">退出</a></div>
    <nav class="nav">
      <ul>
        <li class="parent" data-active-url="^<{$dir}>(?:/(?:index\.php(?:/(?:(?:main|setting)/)?)?)?)?$"><a href="#" title="网站设定"><i class="fa fa-dashboard"></i>网站设定<span class="fa arrow"></span></a>
          <ul>
            <li><a href="<{$dir}>/index.php/main/" title="修改密码">修改密码</a></li>
            <li><a href="<{$dir}>/index.php/setting/" title="系统设定">系统设定</a></li>
          </ul>
        </li>
        <li class="parent" data-active-url="^<{$dir}>/index\.php/user/?$"><a href="<{$dir}>/index.php/user/" title="用户管理"><i class="fa fa-users"></i>用户管理</a>
        </li>
        <li class="parent" data-active-url="^<{$dir}>/index\.php/article/?$"><a href="<{$dir}>/index.php/article/" title="文章管理"><i class="fa fa-pencil-square-o"></i>文章管理</a>
        </li>
      </ul>
    </nav>
  </div>
</div>
