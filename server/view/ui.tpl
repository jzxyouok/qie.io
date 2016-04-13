<!DOCTYPE html>
<html lang="zh-cn">
<head>
<meta charset="utf-8">
<title>UI-<{$title}></title>
<{include file="./css.tpl"}>
<style>
.default-form {
	width: 500px;
}
</style>
</head><body class="ui">
<div class="wrap panel" id="form">
  <h2 class="head">form</h2>
  <div class="body">
    <div class="panel">
      <h3 class="head">form</h3>
      <form class="body" >
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
        <button type="submit">登录</button>
        <a class="button" href="/index.php/user/reg/" title="注册">注册</a>
      </form>
    </div>
    <div class="panel">
      <h3 class="head">form form-button</h3>
      <form class="body" >
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
        <div class="form-button"><button type="submit">登录</button>
        <a class="button" href="/index.php/user/reg/" title="注册">注册</a></div>
      </form>
    </div>
    <div class="panel default-form">
      <h3 class="head">default-form form-group</h3>
      <form class="body" >
        <fieldset>
          <div class="form-group">
            <label>
            <div class="title">input text:</div>
            <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">input password:</div>
            <input type="password" name="pwd" placeholder="请输入密码" required>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">input img:</div>
            <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
            </label>
            <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
          <div class="form-group">
            <label>
            <div class="title">select:</div>
            <select>
              <option>option1</option>
              <option>option2</option>
              <option>option3</option>
            </select>
            </label>
          </div>
          <div class="form-group">
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
          <div class="form-group">
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
          <div class="form-group">
            <label>
            <div class="title">textarea:</div>
            <textarea>textarea</textarea>
          </div>
        </fieldset>
        <div class="form-button"><button type="submit">登录</button>
        <a class="button" href="/index.php/user/reg/" title="注册">注册</a></div>
      </form>
    </div>
    <div class="panel default-form">
      <h3 class="head">default-form two-collumn</h3>
      <form class="body two-collumn" >
        <fieldset>
          <div class="form-group">
            <label>
            <div class="title">input text:</div>
            <div class="control">
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
            </div>
            </label>
          </div>
          <div class="form-group">
            <label>
            <div class="title">input password:</div>
            <div class="control">
              <input type="password" name="pwd" placeholder="请输入密码" required>
            </div>
            </label>
          </div>
          <div class="form-group has-img">
            <label>
            <div class="title">input img:</div>
            <div class="control">
              <input type="text" maxlength="4" name="captcha" placeholder="请输入验证码" required>
              </label>
              <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"></div>
          </div>
          <div class="form-group">
            <label>
            <div class="title">select:</div>
            <div class="control">
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
              </label>
            </div>
          </div>
          <div class="form-group">
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
          <div class="form-group">
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
          <div class="form-group">
            <label>
            <div class="title">textarea:</div>
            <div class="control">
              <textarea>textarea</textarea>
            </div>
          </div>
        </fieldset>
        <div class="form-button"><button type="submit">登录</button>
        <a class="button" href="/index.php/user/reg/" title="注册">注册</a></div>
      </form>
    </div>
    <div class="panel default-form">
      <h3 class="head">default-form has-addon</h3>
      <form class="body" >
        <fieldset>
          <div class="form-group has-addon">
            <label> <span class="input-addon"><i class="icon fa fa-flag-checkered"></i></span>
              <input type="text" name="user_name" value="" placeholder="请输入用户名" required>
            </label>
          </div>
          <div class="form-group has-addon">
            <label><span class="input-addon"><i class="icon fa fa-key"></i></span>
              <input type="password" name="pwd" required>
            </label>
          </div>
          <div class="form-group has-addon has-img">
            <label><span class="input-addon"><i class="icon fa fa fa-list"></i></span>
              <input type="text" name="captcha" maxlength="4" required>
            </label>
            <img src="/index.php/captcha/?w=80&h=32" alt="验证码" id="captcha_img"> </div>
          <div class="form-group has-addon">
            <label><span class="input-addon"><i class="icon fa fa-image"></i></span>
              <select>
                <option>option1</option>
                <option>option2</option>
                <option>option3</option>
              </select>
            </label>
          </div>
          <div class="form-group">
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
          <div class="form-group">
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
          <div class="form-group">
            <label>
            <div class="title">textarea:</div>
            <textarea>textarea</textarea>
          </div>
        </fieldset>
        <div class="form-button"><button type="submit">登录</button>
        <a class="button" href="/index.php/user/reg/" title="注册">注册</a></div>
      </form>
    </div>
  </div>
</div>
</body>
</html>
