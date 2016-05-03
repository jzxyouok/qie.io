<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>分类管理-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-category">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel">
      <h3 class="head">分类管理</h3>
      <div class="body">
        <div class="search">
          <form action="<{$admin_dir}>/index.php/category/" method="get" class="inline-form search-form">
            <fieldset>
              <div class="input-group">
                <label>关键字:
                  <input type="text" name="word" placeholder="请填写关键词">
                </label>
              </div>
              <div class="input-group">
                <label><input type="checkbox" name="fuzzy" value="1"<{if $smarty.get.fuzzy}> checked<{/if}>> 模糊搜索</label>
              </div>
            </fieldset>
            <div class="form-button">
              <input type="submit" value="搜索">
            </div>
          </form>
        </div>
        <div class="select-table">
          <table>
            <thead>
              <tr>
                <th>#</th>
                <th><a href="<{$smarty.SERVER.PHP_SELF}>?orderby=<{if !$smarty.get.orderby || $smarty.get.orderby == 'id_desc'}>id_asc<{else}>id_desc<{/if}><{if $smarty.get.word}>&word=<{$smarty.get.word}><{/if}><{if $smarty.get.fuzzy}>&fuzzy=<{$smarty.get.fuzzy}><{/if}>">ID<i class="fa <{if !$smarty.get.orderby || $smarty.get.orderby == 'id_desc'}>fa-long-arrow-down<{else}>fa-long-arrow-up<{/if}>"></i></a></th>
                <th>名称</th>
                <th><a href="<{$smarty.SERVER.PHP_SELF}>?orderby=<{if !$smarty.get.orderby || $smarty.get.orderby == 'depth_desc'}>depth_asc<{else}>depth_desc<{/if}><{if $smarty.get.word}>&word=<{$smarty.get.word}><{/if}><{if $smarty.get.fuzzy}>&fuzzy=<{$smarty.get.fuzzy}><{/if}>">层级<i class="fa <{if !$smarty.get.orderby || $smarty.get.orderby == 'depth_desc'}>fa-long-arrow-down<{else}>fa-long-arrow-up<{/if}>"></i></a></th>
                <th>上一级ID</th>
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
              <td class="edit"><input data-action="<{$admin_dir}>/index.php/category/update/<{$data.result[n].id}>/" data-field="name" type="text" value="<{$data.result[n].name}>"></td>
              <td class="center"><{$data.result[n].depth}></td>
              <td class="center"><{$data.result[n].parent_id}></td>
              <td class="center manage"><a href="<{$admin_dir}>/index.php/category/edit/<{$data.result[n].id}>/" class="modify" title="编辑">编辑</a><a href="<{$admin_dir}>/index.php/category/delete/<{$data.result[n].id}>/" class="delete" title="删除">删除</a></td>
            </tr>
            <{/section}>
              </tbody>
            
          </table>
          <div class="pagination">
            <div class="info">共<{$data.sum}>个分类/<{$data.max}>页 <a href="#" title="选择" class="select">选择</a><a href="#" title="取消" class="unselect">取消</a><a href="<{$admin_dir}>/index.php/category/delete/" title="批量删除" class="delete-more">批量删除</a></div>
            <div class="paging"><{$pagination}></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
document.querySelector('form.search-form').addEventListener('submit', function(e){
	var data = $u.getFormValues(this);
	if(!data.word) {
		e.preventDefault();
		alert('请填写关键词');
	}
});
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
document.querySelector('a.delete-more').addEventListener('click', function(e) {
	e.preventDefault();
	
	if(!confirm('确认全部删除？'))
		return;
	
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
					alert('成功删除: '+data.result);
					location.href = location.href;
				}
			},
			error: function(xhr, data) {}
	});
});
</script>
</body>
</html>
