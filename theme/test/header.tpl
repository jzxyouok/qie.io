<div class="header">
  <h1><a href="/" title="网站首页"><{$title}> test</a></h1>
  <p>hello, <{$backUrl = ($smarty.get.url|default:($smarty.server.REQUEST_URI|default:''))|urlencode}><{if 0 < $user.id}><!--已登录--><{$user.nick}>. <a href="/index.php/user/logout/<{if $backUrl}>?url=<{$backUrl}><{/if}>">退出</a><{else}><!--未登录--><a href="/index.php/user/<{if $backUrl}>?url=<{$backUrl}><{/if}>" title="用户登陆" class="login">登陆</a> <a href="/index.php/user/reg/<{if $backUrl}>?url=<{$backUrl}><{/if}>" title="用户注册" class="login">注册</a><{/if}></p>
</div>
