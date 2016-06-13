<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>UI-<{$title}></title>
<{include file="./common/css.tpl"}>
<style>
.wrap {
	width: 80%;
}
.sidebar {
	text-align: left;
	position: fixed;
	left: 10px;
	top: 50%;
	padding: 1em 1em 1em 2em;
	background-color: white;
	-webkit-transform: translateY(-50%);
	-moz-transform: translateY(-50%);
	-ms-transform: translateY(-50%);
	transform: translateY(-50%);
}
.sidebar li {
	list-style: outside disc;
}
</style>
</head><body class="ui">
<div class="sidebar">
  <ul>
    <li><a href="#panels" title="panels">panels</a></li>
    <li><a href="#forms" title="forms">forms</a></li>
    <li><a href="#tables" title="tables">tables</a></li>
    <li><a href="javascript:alert('come back soon');void(0);" title="pop window">pop window</a></li>
  </ul>
</div>
<div class="wrap">
  <div class="panel" id="panels">
    <div class="head">panels</div>
    <div class="body">
      <div class="panel">
        <div class="head">panel title</div>
        <div class="body">Just a panel, No border, No background. etc...</div>
      </div>
      <div class="panel default-panel">
        <div class="head">panel title</div>
        <div class="body">I'm a default-panel.</div>
      </div>
      <div class="panel default-panel">
        <div class="head">panel title<a href="#" class="little-size more">more...</a></div>
        <div class="body">I'm a default-panel.</div>
      </div>
      <div class="panel">
        <div class="head">tab panel</div>
        <div class="body">
          <div class="tab-panel">
            <div class="head">
              <div class="tab-item active"><a href="#">tab1</a></div>
              <div class="tab-item"><a href="#">tab2</a></div>
              <div class="tab-item"><a href="#">tab3</a></div>
              <div class="tab-item"><a href="#">tab4</a></div>
              <div class="tab-item"><a href="#">tab5</a></div>
            </div>
            <div class="body"><p>这是tab的内容</p></div>
          </div>
        </div>
      </div>
      <div class="panel default-panel">
        <div class="head">tab panel</div>
        <div class="body">
          <div class="tab-panel">
            <div class="head">
              <div class="tab-item"><a href="#">tab1</a></div>
              <div class="tab-item active"><a href="#">tab2</a></div>
              <div class="tab-item"><a href="#">tab3</a></div>
              <div class="tab-item"><a href="#">tab4</a></div>
              <div class="tab-item"><a href="#">tab5</a></div>
            </div>
            <div class="body"><p>这是tab的内容</p><p>这是tab的内容</p><p>这是tab的内容</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="panel" id="forms">
    <div class="head">forms</div>
    <div class="body">
      <div class="panel default-panel">
        <div class="head">form</div>
        <form class="body">
          <fieldset>
            <div>
              <label>
              <div class="control">input text:</div>
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
            </div>
            <div>
              <label>
              <div class="control">input password:</div>
              <input type="password" name="pwd" placeholder="请输入密码" required>
              </label>
            </div>
            <div>
              <label>
              <div class="control">input img:</div>
              <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
            <div>
              <label>
              <div class="control">select:</div>
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
            <div>
              <div class="control">input checkbox:</div>
              <div class="control">
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox1 </label>
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox2 </label>
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox3 </label>
              </div>
            </div>
            <div>
              <div class="control">input radio:</div>
              <div class="control">
                <label>
                  <input type="radio" name="radio">
                  radio1 </label>
                <label>
                  <input type="radio" name="radio">
                  radio2 </label>
                <label>
                  <input type="radio" name="radio">
                  radio3 </label>
              </div>
            </div>
            <div>
              <label>
              <div class="control">textarea:</div>
              <textarea class="control">textarea</textarea>
              </label>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">default form</div>
        <div class="body">
          <form class="default-form">
            <fieldset>
              <div class="row">
                <label>
                <div class="control title">input text:</div>
                <input class="control" type="text" name="user_name" value="" placeholder="请输入用户名" required>
                </label>
                <div class="control tips">请输入用户名</div>
              </div>
              <div class="row">
                <label>
                <div class="control">input password:</div>
                <input class="control" type="password" name="pwd" placeholder="请输入密码" required>
                </label>
                <div class="control tips error">密码输入错误</div>
              </div>
              <div class="row">
                <label>
                <div class="control">input file:</div>
                <input class="control" type="file" name="file">
                </label>
              </div>
              <div class="row">
                <label>
                <div class="control title">input img:</div>
                <input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
                </label>
                <img class="control" src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
              <div class="row">
                <label>
                <div class="control title">img-control:</div>
                <div class="control img-control">
                  <input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
                  <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div>
                </label>
              </div>
              <div class="row">
                <label>
                <div class="control">select:</div>
                <select class="control">
                  <option>option1</option>
                  <option>option2</option>
                  <option>option3</option>
                </select>
                </label>
              </div>
              <div class="row">
                <div class="control title">input checkbox:</div>
                <div class="control">
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox1 </label>
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox2 </label>
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox3 </label>
                </div>
              </div>
              <div class="row">
                <div class="control">input radio:</div>
                <div class="control">
                  <label>
                    <input type="radio" name="radio">
                    radio1 </label>
                  <label>
                    <input type="radio" name="radio">
                    radio2 </label>
                  <label>
                    <input type="radio" name="radio">
                    radio3 </label>
                </div>
              </div>
              <div class="row">
                <label>
                <div class="control title">textarea:</div>
                <textarea class="control">textarea</textarea>
                </label>
              </div>
              <div class="row">
                <label>
                <div class="control title">disabled input:</div>
                <input class="control" type="text" name="user_name" value="" placeholder="请输入用户名" required disabled>
                </label>
              </div>
            </fieldset>
            <button type="submit">submit</button>
            <input type="reset" value="reset">
            <a class="button" href="#" title="button">button</a>
            <button disabled>disabled1</button>
            <a class="button disabled" href="#" title="button">disabled2</a>
          </form>
          <p>这是一个带外观的默认form表单</p>
          <p>这是表单附带的一些描述性文字。哈哈哈哈哈哈哈</p>
        </div>
      </div>
      <div class="panel default-panel">
        <div class="head">mini form</div>
        <form class="body mini-form default-form">
          <fieldset>
            <div class="row">
              <label>
              <div class="control">input text:</div>
              <input class="control" type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
            </div>
            <div class="row">
              <label>
              <div class="control">input password:</div>
              <input class="control" type="password" name="pwd" placeholder="请输入密码" required>
              </label>
            </div>
            <div class="row">
              <label>
              <div class="control">input img:</div>
              <div class="control img-control">
                <input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
                </label>
                <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div>
            </div>
            <div class="row">
              <label>
              <div class="control">select:</div>
              <select class="control">
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
            <div class="row">
              <div class="control">input checkbox:</div>
              <div class="control">
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox1 </label>
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox2 </label>
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox3 </label>
              </div>
            </div>
            <div class="row">
              <div class="control">input radio:</div>
              <div class="control">
                <label>
                  <input type="radio" name="radio">
                  radio1 </label>
                <label>
                  <input type="radio" name="radio">
                  radio2 </label>
                <label>
                  <input type="radio" name="radio">
                  radio3 </label>
              </div>
            </div>
            <div class="row">
              <label>
              <div class="control">textarea:</div>
              <textarea class="control">textarea</textarea>
              </label>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">inline form</div>
        <form class="body default-form inline-form">
          <fieldset>
            <div class="row">
              <label>
              <div class="control">input text:</div>
              <input class="control" type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
              <button class="control" type="submit">submit</button>
              <div class="control tips">这里是一些tips</div>
            </div>
            <div class="row">
              <label>
              <div class="control title">input password:</div>
              <input class="control" type="password" name="pwd" placeholder="请输入密码" required>
              </label>
              <div class="control tips">这里是一些tips</div>
              <a class="button" href="#" title="button">button</a> </div>
          </fieldset>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">column form</div>
        <form class="body default-form column-form mini-form">
          <fieldset>
            <div class="row">
              <div class="control title">
                <label>input text:</label>
              </div>
              <div class="control">
                <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </div>
            </div>
            <div class="row">
              <div class="control">
                <label>input password:</label>
              </div>
              <div class="control">
                <input type="password" name="pwd" placeholder="请输入密码" required>
              </div>
            </div>
            <div class="row">
              <div class="control title">
                <label> input img:</label>
              </div>
              <div class="control img-control">
                <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
                <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div>
            </div>
            <div class="row">
              <div class="control">
                <label>select:</label>
              </div>
              <div class="control">
                <select>
                  <option>option1</option>
                  <option>option2</option>
                  <option>option3</option>
                </select>
              </div>
            </div>
            <div class="row">
              <div class="control title">input checkbox:</div>
              <div class="control">
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox1 </label>
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox2 </label>
                <label>
                  <input type="checkbox" name="checkbox">
                  checkbox3 </label>
              </div>
            </div>
            <div class="row">
              <div class="control">input radio:</div>
              <div class="control">
                <label>
                  <input type="radio" name="radio">
                  radio1 </label>
                <label>
                  <input type="radio" name="radio">
                  radio2 </label>
                <label>
                  <input type="radio" name="radio">
                  radio3 </label>
              </div>
            </div>
            <div class="row">
              <div class="control title">
                <label>textarea:</label>
              </div>
              <div class="control">
                <textarea>textarea</textarea>
              </div>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">table form</div>
        <form class="body default-form table-form mini-form">
          <fieldset>
            <table>
              <tr>
                <td class="title"><label>input text:</label></td>
                <td><input type="text" name="user_name" value="" placeholder="请输入用户名" required></td>
              </tr>
              <tr>
                <td><label>input password:</label></td>
                <td><input type="password" name="pwd" placeholder="请输入密码" required></td>
              </tr>
              <tr>
                <td class="title"><label> input img:</label></td>
                <td><div class="img-control">
                    <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
                    <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div></td>
              </tr>
              <tr>
                <td><label>select:</label></td>
                <td><select>
                    <option>option1</option>
                    <option>option2</option>
                    <option>option3</option>
                  </select></td>
              </tr>
              <tr>
                <td class="title">input checkbox:</td>
                <td><label>
                    <input type="checkbox" name="checkbox">
                    checkbox1 </label>
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox2 </label>
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox3 </label></td>
              </tr>
              <tr>
                <td>input radio:</td>
                <td><label>
                    <input type="radio" name="radio">
                    radio1 </label>
                  <label>
                    <input type="radio" name="radio">
                    radio2 </label>
                  <label>
                    <input type="radio" name="radio">
                    radio3 </label></td>
              </tr>
              <tr>
                <td class="title"><label>textarea:</label></td>
                <td><textarea>textarea</textarea></td>
              </tr>
            </table>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">input group</div>
        <form class="body default-form">
          <fieldset>
            <div class="row">
              <label>
              <div class="control">input text:</div>
              <div class="input-group control"><span class="input-addon"><i class="fa fa-user"></i></span>
                <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </div>
              </label>
            </div>
            <div class="row">
              <label>
              <div class="control">input password:</div>
              <div class="input-group control">
                <input type="password" name="pwd" placeholder="请输入密码" required>
                <span class="input-addon"><i class="fa fa-key"></i></span></div>
              </label>
            </div>
            <div class="row">
              <label>
              <div class="control">input email:</div>
              <div class="input-group control"><span class="input-addon">@</span>
                <input type="email" name="email" placeholder="请输入邮箱" required>
              </div>
              </label>
            </div>
            <div class="row">
              <label>
              <div class="control">input number:</div>
              <div class="input-group control">
                <input type="number" name="email" placeholder="请输入数字" step="1" required>
                <span class="input-addon">.00</span></div>
              </label>
            </div>
            <div class="row">
              <label>
              <div class="control">input money:</div>
              <div class="input-group control"><span class="input-addon">$</span>
                <input type="number" name="email" placeholder="请输入数字" step="1" required>
                <span class="input-addon">.00</span></div>
              </label>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
    </div>
  </div>
  <div class="panel" id="tables">
    <div class="head">talbes</div>
    <div class="body"><div class="panel default-panel"><div class="head">table</div><div class="body"><table><thead><tr><th>column 1</th><th>column 2</th><th>column 3</th><th>column 4</th><th>column 5</th></tr></thead><tbody><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr></tbody></table></div></div><div class="panel default-panel"><div class="head">default table</div><div class="body"><table class="default-table"><thead><tr><th>column 1</th><th>column 2</th><th>column 3</th><th>column 4</th><th>column 5</th></tr></thead><tbody><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr></tbody></table></div></div><div class="panel default-panel"><div class="head">border table</div><div class="body"><table class="default-table border-table"><thead><tr><th>column 1</th><th>column 2</th><th>column 3</th><th>column 4</th><th>column 5</th></tr></thead><tbody><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr></tbody></table></div></div><div class="panel default-panel"><div class="head">stripe table</div><div class="body"><table class="default-table stripe-table"><thead><tr><th>column 1</th><th>column 2</th><th>column 3</th><th>column 4</th><th>column 5</th></tr></thead><tbody><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr><tr><td>column 1</td><td>column 2</td><td>column 3</td><td>column 4</td><td>column 5</td></tr></tbody></table></div></div></div>
  </div>
</div>
<script src="/static/js/util.js"></script>
</body>
</html>
