<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>添加文章-qie.io</title>
<{include file="../common/css.tpl"}>
<style>
.panel {
	width:80%;
}
</style>
</head>
<body class="manage manage-article">
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
<script src="/static/js/tinymce/tinymce.min.js"></script>
<script>
tinymce.init({
  selector: 'textarea',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table contextmenu paste code'
  ],
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_css: [
    '//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
    '//www.tinymce.com/css/codepen.min.css'
  ]
});
</script>
</body>
</html>