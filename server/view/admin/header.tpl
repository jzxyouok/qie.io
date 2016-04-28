<header class="header">
  <div class="title">
    <h1>QIE.IO</h1>
  </div>
  <div class="control-panel"><a href="<{$homepage}>" class="fa fa-home"></a></div>
</header>
<div class="sidebar">
  <div class="wrap">
    <div class="user-info"><span><i class="fa fa-user"></i><{$smarty.cookies.u_nick}><sup>[<{$smarty.cookies.a_grade}>]</sup>,</span><a href="<{$admin_dir}>/index.php/main/logout/">退出</a></div>
    <nav class="nav">
      <ul>
        <li class="parent" data-active-url="^<{$admin_dir}>(?:/(?:index\.php(?:/(?:(?:main|setting|phpinfo)/)?)?)?)?$"><a href="#" title="网站设定"><i class="fa fa-dashboard"></i>网站设定<span class="fa arrow"></span></a>
          <ul>
            <li><a href="<{$admin_dir}>/index.php/main/" title="修改密码">修改密码</a></li>
            <li><a href="<{$admin_dir}>/index.php/setting/" title="系统设定">系统设定</a></li>
            <li><a href="<{$admin_dir}>/index.php/phpinfo/" title="phpinfo">phpinfo</a></li>
          </ul>
        </li>
        <li class="parent" data-active-url="^<{$admin_dir}>/index\.php/user(?:/.*)?"><a href="#" title="用户管理"><i class="fa fa-users"></i>用户管理<span class="fa arrow"></span></a>
        <ul>
            <li><a href="<{$admin_dir}>/index.php/user/" title="用户列表">用户列表</a></li>
            <li><a href="<{$admin_dir}>/index.php/user/add/" title="添加用户">添加用户</a></li>
            <{if $admin_relogin}><li><a href="<{$admin_dir}>/index.php/user/admin/" title="管理员列表">管理员列表</a></li><{/if}>
          </ul>
        </li>
        <li class="parent" data-active-url="^<{$admin_dir}>/index\.php/article(?:/.*)?"><a href="#" title="文章管理"><i class="fa fa-pencil-square-o"></i>文章管理<span class="fa arrow"></span></a>
        <ul>
            <li><a href="<{$admin_dir}>/index.php/article/" title="文章列表">文章列表</a></li>
            <li><a href="<{$admin_dir}>/index.php/article/add/" title="添加文章">添加文章</a></li>
            <li><a href="<{$admin_dir}>/index.php/article/category/" title="分类管理">分类管理</a></li>
            <li><a href="<{$admin_dir}>/index.php/article/tag/" title="标签管理">标签管理</a></li>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</div>
