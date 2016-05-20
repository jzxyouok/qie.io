<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>添加文章-qie.io</title>
<{include file="../common/css.tpl"}>
<style>
.panel {
	width: 80%;
}
</style>
</head><body class="manage manage-article article-add">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel">
      <h3 class="head">添加文章</h3>
      <div class="body">
        <form action="<{$admin_dir}>/index.php/article/insert/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">文章标题:</div>
              <div class="control">
                <input type="text" name="title" placeholder="请输入文章标题" autofocus required>
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">正文内容:</div>
              <div class="control">
                <textarea name="content" placeholder="请输入文章正文" required>请输入文章正文</textarea>
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">分类列表:</div>
              <div class="control">
                <select name="category_id">
                  <{section loop=$category.result name=n}>
                  <option value="<{$category.result[n].id}>"><{section loop=$category.result[n].depth name=nn}><{if $smarty.section.n.index == 0}>┌<{else if $smarty.section.n.index == $category.sum-1 && $smarty.section.nn.index==0}>└<{else if $smarty.section.nn.index == 0}>├<{else}>─<{/if}><{/section}><{$category.result[n].name}></option>
                  <{/section}>
                </select>
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">关键词:</div>
              <div class="control">
                <input type="text" name="keywords">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">简介:</div>
              <div class="control">
                <input type="text" name="excerpt">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">封面:</div>
              <div class="control">
                <input type="text" name="cover">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">作者:</div>
              <div class="control">
                <input type="text" name="author">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">来源:</div>
              <div class="control">
                <input type="text" name="from">
              </div>
              </label>
            </div>
            <div class="input-group">
              <label>
              <div class="title">跳转地址:</div>
              <div class="control">
                <input type="text" name="href">
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
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script src="/static/js/ajaxfileupload.js"></script> 
<script src="/static/js/tinymce/tinymce.min.js"></script> 
<script>
tinymce.init({
  selector: 'textarea',
  height: 500,
  plugins: [
    'advlist autolink lists link imageplus charmap preview anchor',
    'searchreplace visualblocks fullscreen',
    'insertdatetime table paste pagebreak code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link imageplus pagebreak | code',
});
document.querySelector('form').addEventListener('submit', function(e){
	e.preventDefault();
	
	var data = $u.getFormValues(e.target);
	data.content = tinyMCE.get(0).getContent();
	
	if(data.content.replace(/<\/?\w+>/ig, '') == document.getElementById('content').placeholder) {
		alert('请输入正文');
		return;
	}
	$.ajax({url:e.target.action,
			method: e.target.method,
			data: data,
			dataType: 'json',
			success: function(data){
									if(data.status< 1) {
										alert(data.result);
									} else {
										alert('添加成功');
										e.target.reset();
									}},
			error: function(xhr, data) {}
	})
});
</script>
</body>
</html>
