<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>用户列表-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-article">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel">
      <h3 class="head">用户列表</h3>
      <div class="body">
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>ID</th>
              <th>用户名</th>
              <th>昵称</th>
              <th>电子邮箱</th>
              <th>注册时间</th>
              <th>操作</th>
            </tr>
          </thead>
          <tbody>
          <{section loop=$data.result name=n}>
          <tr>
            <td class="center"><{$smarty.section.n.index+1}></td>
            <td class="center"><{$data.result[n].id}></td>
            <td><{$data.result[n].name}></td>
            <td><{$data.result[n].nick}></td>
            <td><{$data.result[n].email}></td>
            <td class="center"><{$data.result[n].create_time}></td>
            <td class="center manage"><a href="<{$admin_dir}>/index.php/user/edit/<{$data.result[n].id}>/" class="modify" title="编辑">编辑</a><a href="<{$admin_dir}>/index.php/user/delete/<{$data.result[n].id}>/" class="delete" title="删除">删除</a></td>
          </tr>
          <{/section}>
            </tbody>
        </table>
        <div class="pagination"><div class="info">共<{$data.sum}>个用户/<{$data.max}>页</div><div class="paging"><{$pagination}></div></div>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
$('.panel .body .manage a.delete').on('click', function(e){
	if(!confirm('确认删除？'))
		return false;
		
	$.get(this.href,function(data){
									if(data.status< 1) {
										alert(data.result);
									} else {
										location.href = location.href;
									}}, 'json');
	return false;
});
</script>
</body>
</html>
