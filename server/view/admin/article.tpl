<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>文章管理-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-article">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel">
      <h3 class="head">文章管理</h3>
      <div class="body">
        <div class="search">
          <form id="search_form" class="inline-form search-form" action="<{$admin_dir}>/index.php/article/" method="get">
            <fieldset>
              <div class="input-group">
                <label>关键字:
                  <input type="text" name="word" placeholder="请填写关键词">
                </label>
              </div>
              <div class="input-group"> 类型:
                <label> <input type="radio" name="type" value="title"<{if !$smarty.get.type || $smarty.get.type == 'title'}> checked<{/if}>>
                  按标题</label>
                <label> <input type="radio" name="type" value="content"<{if $smarty.get.type == 'content'}> checked<{/if}>>
                  按正文</label>
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
                <th><a href="<{$smarty.SERVER.PHP_SELF}>?orderby=<{if !$smarty.get.orderby || $smarty.get.orderby == 'id_desc'}>id_asc<{else}>id_desc<{/if}><{if $smarty.get.category_id}>&category_id=<{$smarty.get.category_id}><{/if}><{if $smarty.get.tag_id}>&tag_id=<{$smarty.get.tag_id}><{/if}><{if $smarty.get.word}>&word=<{$smarty.get.word}>&type=<{$smarty.get.type}><{/if}><{if $smarty.get.fuzzy}>&fuzzy=<{$smarty.get.fuzzy}><{/if}>">ID <i class="fa <{if !$smarty.get.orderby || $smarty.get.orderby == 'id_desc'}>fa-long-arrow-down<{else}>fa-long-arrow-up<{/if}>"></i></a></th>
                <th>标题</th>
                <th>分类id</th>
                <th>浏览量</th>
                <th>排序</th>
                <th>关键词</th>
                <th>编辑时间</th>
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
              <td class="edit"><input data-action="<{$admin_dir}>/index.php/article/update/<{$data.result[n].id}>/" data-field="title" type="text" value="<{$data.result[n].title}>"></td>
              <td><a href="<{$admin_dir}>/index.php/article/?category_id=<{$data.result[n].category_id}>" title="<{$data.result[n].category_name}>[<{$data.result[n].category_id}>]"><{$data.result[n].category_name}></a></td>
              <td><{$data.result[n].counter}></td>
              <td class="edit"><input data-action="<{$admin_dir}>/index.php/article/update/<{$data.result[n].id}>/" data-field="order" type="text" value="<{$data.result[n].order}>"></td>
              <td><{$data.result[n].keywords}></td>
              <td class="center"><{$data.result[n].create_time}></td>
              <td class="center manage"><a href="<{$admin_dir}>/index.php/article/edit/<{$data.result[n].id}>/" class="modify" title="编辑">编辑</a><a href="<{$admin_dir}>/index.php/article/delete/<{$data.result[n].id}>/" class="delete" title="删除">删除</a><a href="<{$admin_dir}>/index.php/article/fix_tag/<{$data.result[n].id}>/<{$data.result[n].keywords|strtr:'，':','}>/" class="ajax" title="修复tag">修复tag</a></td>
            </tr>
            <{/section}>
              </tbody>
            
          </table>
          <div class="pagination">
            <div class="info">共<{$data.sum}>篇文章/<{$data.max}>页 <a href="#" title="选择" class="select">选择</a><a href="#" title="取消" class="unselect">取消</a><a href="<{$admin_dir}>/index.php/article/delete/" title="批量删除" class="delete-more">批量删除</a></div>
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
