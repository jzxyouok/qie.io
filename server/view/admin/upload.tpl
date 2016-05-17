<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>文件管理-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-upload">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel center">
      <h3 class="head">上传文件</h3>
      <div class="body">
        <form id="upload_file" class="default-form" action="<{$admin_dir}>/index.php/upload/insert/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">选择文件</div>
              <div class="control">
                <input type="file" name="normal_file" id="normal_file" required>
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button">
            <button type="submit">添加</button>
          </div>
        </form>
      </div>
    </div>
    <div class="panel default-panel">
      <h3 class="head">文件管理</h3>
      <div class="body">
        <div class="search">
          <form id="search_form" class="inline-form search-form" action="<{$admin_dir}>/index.php/tag/?fuzzy=1" method="get">
            <fieldset>
              <div class="input-group">
                <label>关键字:
                  <input type="text" name="word" placeholder="请填写关键词">
                </label>
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
                <th><a href="<{$smarty.SERVER.PHP_SELF}>?orderby=<{if !$smarty.get.orderby || $smarty.get.orderby == 'id_desc'}>id_asc<{else}>id_desc<{/if}><{if $smarty.get.word}>&word=<{$smarty.get.word}><{/if}><{if $smarty.get.fuzzy}>&fuzzy=<{$smarty.get.fuzzy}><{/if}>">ID <i class="fa <{if !$smarty.get.orderby || $smarty.get.orderby == 'id_desc'}>fa-long-arrow-down<{else}>fa-long-arrow-up<{/if}>"></i></a></th>
                <th>名称</th>
                <th>文章数量</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
            <{section loop=$data.result name=n}>
            <tr>
              <td class="center"><{$smarty.section.n.index+1}></td>
              <td class="center"><{$data.result[n].id}></td>
              <td class="center"><{$data.result[n].word}></td>
              <td class="center"><a href="<{$admin_dir}>/index.php/article/?tag_id=<{$data.result[n].id}>"><{$data.result[n].article_sum}></a></td>
              <td class="center manage"><a href="<{$admin_dir}>/index.php/tag/delete/<{$data.result[n].id}>/" class="delete" title="删除">删除</a></td>
            </tr>
            <{/section}>
              </tbody>
            
          </table>
          <div class="pagination">
            <div class="info">共<{$data.sum}>个标签/<{$data.max}>页 <!--a href="#" title="选择" class="select">选择</a><a href="#" title="取消" class="unselect">取消</a><a href="<{$admin_dir}>/index.php/tag/delete/" title="批量删除" class="delete-more">批量删除</a--><a href="<{$admin_dir}>/index.php/tag/clean/article/" title="清理文章无效tag" class="clean">清理</a></div>
            <div class="paging"><{$pagination}></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
document.querySelector('.default-form').addEventListener('submit', function(e){
	e.preventDefault();
	var data = $u.getFormValues(this);
	
	$.ajax({url:this.action,
			method: this.method,
			data: data,
			dataType: 'json',
			success: function(data){
									if(data.status< 1) {
										alert(data.result);
									} else {
										location.href = location.href;
									}},
			error: function(xhr, data) {}
	})
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
$('.panel .body .pagination a.clean').on('click', function(e){	
	$.get(this.href,function(data){
									if(data.status< 1) {
										alert(data.result);
									} else {
										location.href = location.href;
									}}, 'json');
	return false;
});
/*
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
});*/
</script>
</body>
</html>
