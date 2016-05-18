<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>上传图片-qie.io</title>
<{include file="../common/css.tpl"}>
</head>
<body class="manage manage-upload upload-add">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel center">
      <h3 class="head">本地上传</h3>
      <div class="body">
        <form id="upload_file" class="default-form" action="<{$admin_dir}>/index.php/upload/insert_image/" method="post" enctype="multipart/form-data">
          <fieldset>
            <div class="input-group">
              <label>
              <div class="title">选择图片</div>
              <div class="control">
                <input type="file" name="local_file" id="local_file" required>
              </div>
              </label>
            </div>
            <div class="tips center hide" id="handle_status"><i class="fa fa-refresh fa-spin fa-fw"></i><span>文件处理中...</span></div>
          </fieldset>
          <input type="hidden" name="exists_result" id="exists_result">
          <div class="form-button">
            <button type="submit">添加</button>
          </div>
        </form>
      </div>
    </div>
    <div class="panel default-panel center">
      <h3 class="head">在线地址</h3>
      <div class="body">
        <form id="upload_file_online" class="default-form" action="<{$admin_dir}>/index.php/upload/insert_image/online/" method="post">
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
          <div class="form-button">
            <button type="submit">添加</button>
          </div>
        </form>
      </div>
    </div>
  </div>
  <{include file="./footer.tpl"}> </div>
<{include file="../common/js.tpl"}> 
<script src="/static/js/spark-md5.min.js"></script> 
<script src="/static/js/ajaxfileupload.js"></script> 
<script>
var filePaths = {};
var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice;

/*
 * input[type=file] change事件
 * 主要功能是计算md5值和查询
 */
function fileInputChange(e){
	if(typeof FileReader == 'undefined' || !blobSlice)
		return;
	//获取文件md5
	var file = e.target.files[0];
	if(!file || !/^image\//i.test(file.type)) {
		return;
	}
	
	var fileReader = new FileReader(),
			spark = new SparkMD5.ArrayBuffer(),
			chunkSize = 2097152,
			currentChunk = 0,
      chunks = Math.ceil(file.size / chunkSize),
			handleStatus = document.getElementById('handle_status'),
			existsResult = document.getElementById('exists_result');
	
	existsResult.value = '';
	handleStatus.classList.remove('hide');
	
	fileReader.onload = function(e){
		spark.append(e.target.result);
		currentChunk++;
		if (currentChunk < chunks) {
			loadNext();
		} else {
			var md5 = spark.end();
			//console.log(md5);
			if(md5) {
				if(filePaths[md5]) {
					existsResult.value = filePaths[md5];
					resultHandle();
					return;
				}
				$.get("<{$admin_dir}>/index.php/upload/file_exists/"+md5+"/", function(data){
					resultHandle();
					if(data.status> 0 && data.result.exists)
						filePaths[md5] = existsResult.value = data.result.path;
				},'json');
			}
		}
	};
	function loadNext() {
		var start = currentChunk * chunkSize,
        end = (start + chunkSize) >= file.size ? file.size : start + chunkSize;
				
    fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
	}
	function resultHandle() {
		handleStatus.querySelector('span').textContent = '处理完毕';
		handleStatus.classList.remove('show');
		handleStatus.classList.add('hide');
	}
	loadNext();
}
//上传成功
function uploadSuccess(result) {
	console.log(result);
}
document.getElementById('local_file').addEventListener('change', fileInputChange);
document.getElementById('upload_file').addEventListener('submit', function(e){
	e.preventDefault();
	
	var file = document.getElementById('local_file'),
			path = document.getElementById('exists_result').value;
			
	if(!/\.(?:jpg|jpeg|png|gif)$/i.test(file.value)) {
		alert('图片格式错误');
		return;
	}
	if(path) {
		//根据mdt
		uploadSuccess({path:path});
		return;
	}
	$.ajaxFileUpload({
				url:this.action, 
				secureuri:false,
				fileElementId:file.id,
				dataType: 'text',
				data:{},
				success: function (data, status) {
					if(typeof JSON != 'undefined')
						data = JSON.parse(data);
					else
						eval("data="+data);
					
					if(data.status < 1)
						alert(data.result);
					else {
						filePaths[data.result.md5] = data.result.path;
						uploadSuccess({md5:data.result.md5,path:data.result.path});
					}
					var file = document.getElementById('local_file');
					file.removeEventListener('change', fileInputChange);
					file.addEventListener('change', fileInputChange);
				},
				error: function (data, status, e) {
					console.info(data)
				}
	})
});
document.getElementById('file_url').addEventListener('click', function(e){e.target.select();})
document.getElementById('upload_file_online').addEventListener('submit', function(e){
  e.preventDefault();
  var data = $u.getFormValues(this);
  
  $.ajax({url:this.action,
      method: this.method,
      data: data,
      dataType: 'json',
      success: function(data){
								if(data.status < 1) {
									alert(data.result);
								} else {
									document.getElementById('file_url').value = '';
								}
              },
      error: function(xhr, data) {}
  })
});
</script>
</body>
</html>
