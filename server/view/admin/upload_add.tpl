<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>上传文件-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-upload upload-add">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel center">
      <h3 class="head">上传文件</h3><div class="body">
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
          <div class="form-button"><button type="submit">添加</button></div>
        </form></div>
      </div>
      <div class="panel default-panel center">
      <h3 class="head">上传图片</h3><div class="body">
        <form id="upload_image" class="default-form" action="<{$admin_dir}>/index.php/upload/insert_image/" method="post" enctype="multipart/form-data">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">选择图片</div>
              <div class="control">
                <input type="file" name="image_file" id="image_file" required>
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button"><button type="submit">添加</button></div>
        </form></div>
      </div>
      <div class="panel default-panel center">
      <h3 class="head">保存在线图片</h3><div class="body">
        <form id="upload_image_online" class="default-form" action="<{$admin_dir}>/index.php/upload/insert_image/online/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">图片地址</div>
              <div class="control">
                <input type="text" name="file_url" id="file_url" placeholder="http://" required>
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button"><button type="submit">添加</button></div>
        </form></div>
      </div>
      <div class="panel default-panel center">
      <h3 class="head">编辑图片</h3><div class="body">
        <form id="image_editor" class="default-form" action="<{$admin_dir}>/index.php/upload/insert_image/" method="post">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">选择图片</div>
              <div class="control">
                <input type="file" name="source_image" id="source_image" required>
              </div>
              </label>
            </div>
          </fieldset>
          <div class="form-button"><button type="submit">添加</button></div>
        </form></div>
      </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}>
<script src="/static/js/ajaxfileupload.js"></script>
<script>
document.getElementById('image_file').addEventListener('change', function(e){
	if(typeof FileReader == 'undefined')
		return;
	var file = e.target.files[0];
	if(!/image\/(?:jpg|jpeg|png|gif)$/i.test(file.type)) {
		return;
	}
	var fileReader = new FileReader();
	fileReader.onload = function(e){
		/*
		$.ajax({
			url:"<{$admin_dir}>/index.php/upload/insert_image/base64/",
			method:"post",
			data:{"image_file":e.target.result},
			dataType:"json",
			success: function(data){
				console.info(data);
				},
			error: function(xhr,status){}
		});*/
		
	};
	fileReader.readAsDataURL(file);
});
document.getElementById('upload_image').addEventListener('submit', function(e){
	e.preventDefault();
	
	var file = document.getElementById('image_file');
	if(!/\.(?:jpg|jpeg|png|gif)$/i.test(file.value)) {
		alert('图片格式错误');
		return;
	}
	$.ajaxFileUpload({
				url:this.action, 
				secureuri:false,
				fileElementId:file.id,
				dataType: 'text',
				data:{},
				success: function (data, status) {
					data = JSON.parse(data);
          alert(data.result);
					console.info(data);
				},
				error: function (data, status, e) {
					console.info(data)
				}
			})
});
document.getElementById('upload_image_online').addEventListener('submit', function(e){
  e.preventDefault();
  var data = $u.getFormValues(this);
  
  $.ajax({url:this.action,
      method: this.method,
      data: data,
      dataType: 'json',
      success: function(data){
                  alert(data.result);
                },
      error: function(xhr, data) {}
  })
});
</script>
</body>
</html>
