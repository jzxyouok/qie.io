<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>添加分类-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-category category-add">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-form">
      <h3 class="head">添加分类</h3>
      <div class="body">
        <form action="<{$admin_dir}>/index.php/category/insert/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">分类名称</div>
              <div class="control">
                <input type="text" name="name" placeholder="请输入分类名称" autofocus required>
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">分类介绍:</div>
              <div class="control">
                <input type="text" name="description" placeholder="请输入分类介绍">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">上级分类:</div>
              <div class="control">
                <select name="parent_id"><option value="0">├一级分类</option>
                  <{section loop=$category.result name=n}>
                  <option value="<{$category.result[n].id}>"><{section loop=$category.result[n].depth name=nn}><{if $smarty.section.n.index == 0}>┌<{else if $smarty.section.n.index == $category.sum-1 && $smarty.section.nn.index==0}>└<{else if $smarty.section.nn.index == 0}>├<{else}>─<{/if}><{/section}><{$category.result[n].name}> [<{$category.result[n].id}>,<{$category.result[n].parent_id}>,<{$category.result[n].root_id}>]</option>
                  <{/section}>
                </select>
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button"><button type="submit">添加</button></div>
        </form>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script>
document.querySelector('.default-form form').addEventListener('submit', function(e){
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
</script>
</body>
</html>
