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
              <div class="title">input text:</div>
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input password:</div>
              <input type="password" name="pwd" placeholder="请输入密码" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input img:</div>
              <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
            <div>
              <label>
              <div class="title">select:</div>
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
            <div>
              <div class="title">input checkbox:</div>
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
              <div class="title">input radio:</div>
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
              <div class="title">textarea:</div>
              <textarea>textarea</textarea>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      
      <div class="panel default-panel">
        <div class="head">default form</div>
        <form class="body default-form">
          <fieldset>
            <div>
              <label>
              <div class="title">input text:</div>
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input password:</div>
              <input type="password" name="pwd" placeholder="请输入密码" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input img:</div>
              <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
            <div>
              <label>
              <div class="title">select:</div>
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
            <div>
              <div class="title">input checkbox:</div>
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
              <div class="title">input radio:</div>
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
              <div class="title">textarea:</div>
              <textarea>textarea</textarea>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">mini form</div>
        <form class="body mini-form default-form">
          <fieldset>
            <div>
              <label>
              <div class="title">input text:</div>
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input password:</div>
              <input type="password" name="pwd" placeholder="请输入密码" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input img:</div>
              <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
            <div>
              <label>
              <div class="title">select:</div>
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
            <div>
              <div class="title">input checkbox:</div>
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
              <div class="title">input radio:</div>
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
              <div class="title">textarea:</div>
              <textarea>textarea</textarea>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">inline form</div>
        <form class="body default-form">
          <fieldset>
            <div>
              <label>
              <div class="title">input text:</div>
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input password:</div>
              <input type="password" name="pwd" placeholder="请输入密码" required>
              </label>
            </div>
            <div>
              <label>
              <div class="title">input img:</div>
              <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
            <div>
              <label>
              <div class="title">select:</div>
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
            <div>
              <div class="title">input checkbox:</div>
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
              <div class="title">input radio:</div>
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
              <div class="title">textarea:</div>
              <textarea>textarea</textarea>
            </div>
          </fieldset>
          <button type="submit">submit</button>
          <input type="reset" value="reset">
          <a class="button" href="#" title="button">button</a>
        </form>
      </div>
      <div class="panel default-panel">
        <div class="head">table form</div>
        <form class="body table-form default-form">
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
                <td class="control"><input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
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
