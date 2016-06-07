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
</style>
</head><body class="ui">
<div class="wrap">
  <div class="panel">
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
    </div>
  </div>
  <div class="panel">
    <div class="head">forms</div>
    <div class="body">
      <div class="panel default-panel">
        <div class="head">origin form</div>
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
              <textarea class="control">textarea</textarea></label>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">default form</div>
        <div class="body"><form class="default-form">
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
              <div class="control title">input img:</div>
              <input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img class="control" src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
            <div class="row">
              <label>
              <div class="control title">input img:</div>
              <div class="control img-control"><input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required><img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div>
              </label></div>
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
              <textarea class="control">textarea</textarea></label>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
        <p>这是一个带外观的默认form表单</p>
        <p>这是表单附带的一些描述性文字。哈哈哈哈哈哈哈</p></div>
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
              <div class="control img-control"><input class="control" type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div></div>
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
              <textarea class="control">textarea</textarea></label>
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
              <button class="control" type="submit">submit</button><div class="control tips">这里是一些tips</div>
            </div>
            <div class="row">
              <label>
              <div class="control title">input password:</div>
              <input class="control" type="password" name="pwd" placeholder="请输入密码" required>
              </label>
              <div class="control tips">这里是一些tips</div>
              <a class="button" href="#" title="button">button</a>
            </div>
          </fieldset>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">table form</div>
        <form class="body default-form table-form mini-form">
          <fieldset>
            <table>
              <tr>
                <td class="title"><label>input text:</label></td>
                <td class="control"><input type="text" name="user_name" value="" placeholder="请输入用户名" required></td>
              </tr>
              <tr>
                <td class="title"><label>input password:</label></td>
                <td class="control"><input type="password" name="pwd" placeholder="请输入密码" required></td>
              <tr>
              <tr>
                <td class="title"><label> input img:</label></td>
                <td class="control img-control"><input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
                  <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></td>
              <tr>
                <td class="title"><label>select:</label></td>
                <td class="control"><select>
                    <option>option1</option>
                    <option>option2</option>
                    <option>option3</option>
                  </select></td>
              <tr>
              <tr>
                <td class="title">input checkbox:</td>
                <td class="control"><label>
                    <input type="checkbox" name="checkbox">
                    checkbox1 </label>
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox2 </label>
                  <label>
                    <input type="checkbox" name="checkbox">
                    checkbox3 </label></td>
              <tr>
              <tr>
                <td class="title">input radio:</td>
                <td class="control"><label>
                    <input type="radio" name="radio">
                    radio1 </label>
                  <label>
                    <input type="radio" name="radio">
                    radio2 </label>
                  <label>
                    <input type="radio" name="radio">
                    radio3 </label></td>
              <tr>
              <tr>
                <td class="title"><label>textarea:</label></td>
                <td class="control"><textarea>textarea</textarea></td>
              <tr>
            </table>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
    </div>
  </div>
  <div class="panel">
    <div class="head">talbes</div>
    <div class="body"> </div>
  </div>
  <div class="panel">
    <div class="head">ul/ol/dl</div>
    <div class="body"> </div>
  </div>
</div>
</body>
</html>
