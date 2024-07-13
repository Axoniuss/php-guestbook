<?php
    $HintType = '';
    $HintMsg = '';

    session_start();
    //Войдя в систему, сразу переходите на главную страницу
    if (isset($_SESSION["userid"])) {
      header('Location: index.php');
      exit();
    }

    if (!empty($_POST)) {
        define('DIRECT_VISIT_CHECK', 'IN_GUESTBOOK');
        require_once("./inc/core.class.php");


       $username = isset($_POST['username']) ? trim($_POST['username']) : '';
       $password = isset($_POST['password']) ? trim($_POST['password']) : '';
       $password_comfirm = isset($_POST['password_comfirm']) ? trim($_POST['password_comfirm']) : '';
       $captcha = isset($_POST['captcha']) ? trim($_POST['captcha']) : '';


       if (!$captcha || strtolower($captcha)!=$_SESSION['authnum_session']){
          $HintType = 'error';
          $HintMsg = 'Неверный проверочный код';
       } else if (!$username) {
          $HintType = 'error';
          $HintMsg = 'Пожалуйста, введите имя пользователя';
       } else if (!$password) {
          $HintType = 'error';
          $HintMsg = 'Пожалуйста, введите пароль';
       } else if ($password != $password_comfirm){
         $HintType = 'error';
            $HintMsg = 'Введенный дважды пароль является несогласованным';
       } else {
          //Проверьте, существует ли уже это имя пользователя
          $db = Database::getInstance();
          $Result = $db->Select("*", null, "user", "username='$username'", true);



          if ($Result){
            $HintType = 'error';
            $HintMsg = 'Имя пользователя уже существует!';
          }

          //Имя пользователя не существует, вы можете зарегистрироваться
          else {
            $password_md5 = md5($password);
            $db->Insert("user", array("username", "password"), array($username, $password_md5));
            
            $HintType = 'succeed';
            $HintMsg = 'Регистрация прошла успешно <a href="login.php">перейдите для входа в систему>></a>';
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
    <title>Регистрация пользователя</title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">


    <!-- HTML5 shim 和 Respond.js 是为了让 IE8 支持 HTML5 元素和媒体查询（media queries）功能 -->
    <!-- 警告：通过 file:// 协议（就是直接将 html 页面拖拽到浏览器中）访问页面时 Respond.js 不起作用 -->
    <!--[if lt IE 9]>
      <script src="https://cdn.bootcss.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://cdn.bootcss.com/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>

    <!-- 让表格往下一点..... -->
    <div style="margin-top: 5%"></div>
    
    <form class="form-horizontal" action="" method="post">

      <div class="form-group">

        <?php if ($HintType=="succeed") { ?>
        <!--Советы по успешной регистрации-->
        <div class="alert alert-success col-sm-offset-4 col-sm-4 fade in" role="alert">
          <?php echo $HintMsg; ?>
          <!--Кнопка закрытия-->
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
       <?php } else if ($HintType=="error") { ?>
        <!--Напоминание о сбое регистрации-->
        <div class="alert alert-danger col-sm-offset-4 col-sm-4 fade in" role="alert">
          <?php echo $HintMsg; ?>
          <!--Кнопка закрытия-->
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        </div>
        <?php } ?>

      </div>

      <div class="form-group">
        <h2 class="col-sm-offset-4 col-sm-4">Регистрация пользователя</h2>
      </div>
      
      <div class="form-group">
        <label for="inputUsername" class="col-sm-4 control-label">имя пользователя</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="inputUsername" name="username" placeholder="Пожалуйста, введите имя пользователя">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPassword" class="col-sm-4 control-label">Пароль</label>
        <div class="col-sm-3">
          <input type="password" class="form-control" id="inputPassword" name="password" placeholder="Пожалуйста, введите пароль">
        </div>
      </div>
      <div class="form-group">
        <label for="inputPasswordConfirm" class="col-sm-4 control-label">Подтвердите пароль</label>
        <div class="col-sm-3">
          <input type="password" class="form-control" id="inputPasswordConfirm" name="password_comfirm" placeholder="Пожалуйста, введите пароль еще раз">
        </div>
      </div>
      <div class="form-group">
        <label for="inputCaptcha" class="col-sm-4 control-label">Код подтверждения</label>
        <div class="col-sm-3">
          <input type="text" class="form-control" id="inputCaptcha" name="captcha" placeholder="введите проверочный код">
        </div>
        <img id="captcha_pic" title="обновить" src="./inc/getCaptcha.php" align="absbottom" onclick="this.src='./inc/getCaptcha.php?'+Math.random();"></img>
      </div>
      

      <div class="form-group">
        <div class="col-sm-offset-4 col-sm-3">
          <button type="submit" class="btn btn-primary">регистрация</button>
          <a href="login.php" class="btn btn-info">У вас уже есть учетная запись?>></a>
          <!-- <button type="submit" class="btn btn-info">У вас уже есть учетная запись?Перейдите, чтобы войти в систему>></button> -->
        </div>
      </div>
    </form>
   


    <script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
  </body>
</html>