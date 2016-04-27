<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>管理员列表-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-article">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel">
      <h3 class="head">管理员列表</h3>
      <div class="body">
        <div class="select-table">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th><a href="<{$smarty.SERVER.PHP_SELF}>?orderby=<{if $smarty.get.orderby == 'id_desc'}>id_asc<{else}>id_desc<{/if}>">ID<i class="fa <{if $smarty.get.orderby == 'id_desc'}>fa-long-arrow-down<{else}>fa-long-arrow-up<{/if}>"></i></a></th>
                <th>用户名</th>
                <th>昵称</th>
                <th>电子邮箱</th>
                <th><a href="<{$smarty.SERVER.PHP_SELF}>?orderby=<{if $smarty.get.orderby == 'grade_desc'}>grade_asc<{else}>grade_desc<{/if}>">等级<i class="fa <{if $smarty.get.orderby == 'grade_desc'}>fa-long-arrow-down<{else}>fa-long-arrow-up<{/if}>"></i></a></th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
            <{section loop=$data.result name=n}>
            <tr>
              <td class="center"><label>
                  <input type="checkbox" value="<{$data.result[n].id}>">
                  <{$smarty.section.n.index+1}></label></td>
              <td class="center"><{$data.result[n].id}></td>
              <td><{$data.result[n].name}></td>
              <td><{$data.result[n].nick}></td>
              <td><{$data.result[n].email}></td>
              <td><{$data.result[n].grade}></td>
              <td class="center manage"><a href="<{$admin_dir}>/index.php/user/admin_edit/<{$data.result[n].id}>/" class="modify" title="编辑">编辑</a><a href="<{$admin_dir}>/index.php/user/admin_delete/<{$data.result[n].id}>/" class="delete" title="取消">取消</a></td>
            </tr>
            <{/section}>
              </tbody>
            
          </table>
          <div class="pagination">
            <div class="info">共<{$data.sum}>个管理员/<{$data.max}>页 <a href="#" title="选择" class="select">选择</a><a href="#" title="取消" class="unselect">取消</a><a href="<{$admin_dir}>/index.php/user/admin_delete/" title="批量取消" class="delete-more">批量取消</a></div>
            <div class="paging"><{$pagination}></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
$('.panel .body .manage a.delete').on('click', function(e){
	$.get(this.href,function(data){
									if(data.status< 1) {
										alert(data.result);
									} else {
										location.href = location.href;
									}}, 'json');
	return false;
});
document.querySelector('a.delete-more').addEventListener('click', function(e) {
	e.preventDefault();
	
	var ids = [];
	$(this).parents('.select-table').eq(0).find('input[type=checkbox]:checked').each(function(){
  	if('' != $(this).val())
			ids.push($(this).val());
  });
	
	$.ajax({url:this.href,
			method: "post",
			data: {'ids':ids.join()},
			dataType: 'json',
			success: function(data){
				if(data.status< 1) {
					alert(data.result);
				} else {
					alert('成功删除:'+data.result);
					location.href = location.href;
				}
			},
			error: function(xhr, data) {}
	});
});
</script>
</body>
</html>
