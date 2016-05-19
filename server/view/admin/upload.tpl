<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>文件管理-qie.io</title>
<{include file="../common/css.tpl"}>
<style>
td {
	height:100px;
}
td img {
	max-width:200px;
	max-height:100px;
	border:1px solid #ccc;
	padding:2px;
}
</style>
</head>
<body class="manage manage-upload">
<{include file="./header.tpl"}>
<div class="content">
  <div class="wrap">
    <div class="panel default-panel center">
      <h3 class="head">上传文件</h3>
      <div class="body">
        <form id="upload_file" class="default-form" action="<{$admin_dir}>/index.php/upload/insert/" method="post" enctype="multipart/form-data">
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
                <th>状态</th>
                <th>预览</th>
                <th>MD5</th>
                <th>路径</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
            <{section loop=$data.result name=n}>
            <tr>
              <td class="center"><label>
                  <input type="checkbox" value="<{$data.result[n].md5}>">
                  <{$smarty.section.n.index+1}></label></td>
              <td class="center"><{if ($DOCUMENT_ROOT|cat:$data.result[n].path)|file_exists}><i class="fa fa-check color-green"></i><{else}><i class="fa fa-close color-red"></i><{/if}></td>
              <td class="center"><a href="<{$data.result[n].path}>" target="_blank"><{$extension = $data.result[n].path|substr:(($data.result[n].path|strrpos:'.')+1)}><{if $extension == 'jpg' || $extension == 'png' || $extension == 'gif' || $extension == 'jpeg' || $extension == 'bmp'}><img src="<{$data.result[n].path}>"><{else}><{$extension}><{/if}></a></td>
              <td><{$data.result[n].md5}></td>
              <td><{$data.result[n].path}></td>
              <td class="center manage"><a href="<{$admin_dir}>/index.php/upload/delete/<{$data.result[n].md5}>/" class="ajax delete" title="删除">删除</a></td>
            </tr>
            <{/section}>
              </tbody>
            
          </table>
          <div class="pagination">
            <div class="info">共<{$data.sum}>个文件/<{$data.max}>页 <a href="#" title="选择" class="select">选择</a><a href="#" title="取消" class="unselect">取消</a><a href="<{$admin_dir}>/index.php/upload/delete/" title="批量删除" class="delete-more">批量删除</a></div>
            <div class="paging"><{$pagination}></div>
          </div>
        </div>
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
	if(!file) {
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
			//继续加载
			loadNext();
		} else {
			//数据块加载结束
			var md5 = spark.end();
			//console.log(md5);
			if(md5) {
				if(typeof filePaths[md5] != 'undefined') {
					//如果md5已经存在
					if(filePaths[md5])
						existsResult.value = filePaths[md5];
					resultHandle();
					return;
				}
				$.get("<{$admin_dir}>/index.php/upload/file_exists/"+md5+"/", function(data){
					if(data.status> 0 && data.result.exists) {
						filePaths[md5] = existsResult.value = data.result.path;
					} else {
						//不存在时避免多次请求服务器
						filePaths[md5] = false;
					}
					resultHandle();
				},'json');
			}
		}
	};
	//开始加载
	loadNext();
	
	//分块加载数据
	function loadNext() {
		var start = currentChunk * chunkSize,
        end = (start + chunkSize) >= file.size ? file.size : start + chunkSize;
				
    fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
	}
	//处理结果
	function resultHandle() {
		handleStatus.querySelector('span').textContent = '处理完毕';
		handleStatus.classList.remove('show');
		handleStatus.classList.add('hide');
	}
}
/*
 * 上传成功
 * 如果包含md5，即为新上传，否则为秒传
 * 
 * result:{md5:'',path:''}
 */
function uploadSuccess(result) {
	console.log(result);
}
document.getElementById('local_file').addEventListener('change', fileInputChange);
document.getElementById('upload_file').addEventListener('submit', function(e){
	e.preventDefault();
	
	var file = document.getElementById('local_file'),
			path = document.getElementById('exists_result').value;
			
	if(!file.value) {
		alert('请选择上传文件');
		return;
	}
	
	if(path) {
		//如果文件已经存在
		uploadSuccess({path:path});
		return;
	}
	file.removeEventListener('change', fileInputChange);
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
					
					if(data.status < 1) {
						//失败处理
						alert(data.result);
					} else {
						//上传成功
						filePaths[data.result.md5] = data.result.path;
						uploadSuccess({md5:data.result.md5,path:data.result.path});
					}
					//重新绑定file change事件
					var file = document.getElementById('local_file');
					file.addEventListener('change', fileInputChange);
				},
				error: function (data, status, e) {
					console.info(data)
				}
	})
});

//全部删除
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
			data: {'md5':ids.join()},
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
