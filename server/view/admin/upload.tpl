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
                <input type="file" name="local_file" id="local_file" required>
              </div>
              </label>
            </div>
            <div class="tips center hide" id="file_status"><i class="fa fa-refresh fa-spin fa-fw"></i><span>文件处理中...</span></div>
          </fieldset>
          <input type="hidden" name="file_path" id="exists_result">
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
<script src="/static/js/spark-md5.min.js"></script> 
<script src="/static/js/ajaxfileupload.js"></script> 
<script>
document.getElementById('local_file').addEventListener('change', function(e){
	if(typeof FileReader == 'undefined')
		return;
	//获取文件md5
	var file = e.target.files[0];
	if(!file) {
		return;
	}
	
	var blobSlice = File.prototype.slice || File.prototype.mozSlice || File.prototype.webkitSlice,
			fileReader = new FileReader(),
			spark = new SparkMD5.ArrayBuffer(),
			md5 = '',
			chunkSize = 2097152,
			currentChunk = 0,
      chunks = Math.ceil(file.size / chunkSize),
			fileStatus = document.getElementById('file_status'),
			existsResult = document.getElementById('exists_result');
	
	existsResult.value = '';
	fileStatus.classList.remove('hide');
	
	fileReader.onload = function(e){
		spark.append(e.target.result);
		currentChunk++;
		if (currentChunk < chunks) {
			loadNext();
		} else {
			md5 = spark.end();
			fileStatus.querySelector('span').textContent = '处理完毕';
			window.setTimeout(function(){fileStatus.classList.remove('show');fileStatus.classList.add('hide');}, 1000);
			//console.log(md5);
			if(md5) {
				$.get("<{$admin_dir}>/index.php/upload/file_exists/"+md5+"/", function(data){
					if(data.status> 0 && data.result.exists)
						existsResult.value = data.result.path;
				},'json');
			}
			
		}
	};
	function loadNext() {
		var start = currentChunk * chunkSize,
        end = (start + chunkSize) >= file.size ? file.size : start + chunkSize;
				
    fileReader.readAsArrayBuffer(blobSlice.call(file, start, end));
	}
	loadNext();
});
document.getElementById('upload_file').addEventListener('submit', function(e){
	e.preventDefault();
	
	var file = document.getElementById('local_file');
			
	if(file.value == '') {
		alert('请选择文件');
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
					//console.log('普通上传',data.result.path);
				},
				error: function (data, status, e) {
					console.info(data)
				}
	})
});
</script>
</body>
</html>
