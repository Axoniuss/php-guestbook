<?php
  $HintType = '';
  $HintMsg = '';

  session_start();
  //已登录直接跳到主页
  if (isset($_SESSION["userid"])) {
    header('Location: index.php');
    exit();
  }

  if (!empty($_POST)) {
      define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
      require_once("./inc/core.class.php");

      $username = isset($_POST['username']) ? trim($_POST['username']) : '';
      $password = isset($_POST['password']) ? trim($_POST['password']) : '';

      if (!$username){
         $HintType = 'error';
         $HintMsg = '请填写用户名';
      } else if (!$password) {
         $HintType = 'error';
         $HintMsg = '请填写密码';
      } else {
        $password_md5 = md5($password);

        $db = Database::getInstance();
        $Result = $db->Select("*", null, "user", "username='$username' and password='$password_md5'", true);
          if ($Result){
            //登录成功设置Session... 然后跳到主页
            $_SESSION["userid"] = $Result['id'];
            header('Location: index.php');
            exit();
          } else {
            $HintType = 'error';
            $HintMsg = 'Неверное имя пользователя или пароль!';
          }
      }
  }

?>


<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
<!--Вышеуказанные 3 мета-тега * должны* быть размещены в
 начале, а любой другой контент * должен* следовать за ними! -->    
 <title>Вход</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!--HTML5-оболочка и ответ.js предназначен для того, чтобы позволить IE8 поддерживать элементы HTML5 и медиа-запросы ->
    <!--Предупреждение: Отвечайте при доступе к странице по протоколу file:// (то есть перетаскивайте html-страницу непосредственно в браузер).js не работает...>
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <!-- Дайте форме немного опуститься..... -->
    <div style="margin-top: 5%"></div>



    <form class="form-horizontal" action="" method="post" >

      <div class="form-group">
        <?php if ($HintType=="error") { ?>
        <!--登录失败的提示-->
        <div class="alert alert-danger col-sm-offset-4 col-sm-4 fade in" role="alert">
          <?php echo $HintMsg; ?>
          <!--关闭按钮-->
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <?php } ?>
      </div>

      <div class="form-group">
        <h2 class="col-sm-offset-4 col-sm-4">用户登录</h2>
      </div>
      
      <div class="form-group">
        <label for="inputUsername" class="col-sm-4 control-label">用户名</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="inputUsername" name="username" placeholder="请输入用户名">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword" class="col-sm-4 control-label">密码</label>
        <div class="col-sm-3">
          <input type="password" class="form-control" id="inputPassword" name="password" placeholder="请输入密码">
        </div>
      </div>
      
      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-3">
          <button type="submit" class="btn btn-primary">登录</button>
          <a href="register.php" class="btn btn-info">没有账号？去注册>></a>
        </div>
      </div>
    </form>
   


    <!-- jQuery (Bootstrap 的所有 JavaScript 插件都依赖 jQuery，所以必须放在前边) -->
    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <!-- 加载 Bootstrap 的所有 JavaScript 插件。你也可以根据需要只加载单个插件。 -->
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>