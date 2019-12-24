<?php
require('signup.php');
require('head.php');
?>
<body>

<h1 style="text-align: right; background-color: burlywood;" class="mb-5 menu">Likerd</h1>
  

<section class="js-page container-text">
  <!-- 登録情報送信 ------------------------------------- -->
  <form method="post" action="">
    <h2 class="title" >ユーザー登録</h2>
    <div class="js-wrapper">
      <!-- 共通部分　エラーメッセージ表示 -->
    <div class="area-msg">
        <?php
        if (!empty($err_msg['common'])) echo $err_msg['common'];
        ?> 
      </div>
      <!-- ニックネーム　エラーメッセージ表示 -->
      <label class="<?php if (!empty($err_msg['username'])) echo 'err'; ?>">
        <p style="width:100px;">ニックネーム</p>
        <input type="text" name="username" value="<?php if(!empty($_POST['username'])) echo $_POST['username']; ?>">
      </label>
     <div class="area-msg">
        <?php
        if (!empty($err_msg['username'])) echo $err_msg['username'];
        ?>
      </div>
      <!-- password　エラーメッセージ表示 -->
      <label class="<?php if (!empty($err_msg['pass'])) echo 'err'; ?>">
        <p style="width:100px;">パスワード</p>
        <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
        <br>
      </label>
      <div class="area-msg">
        <?php
        if(!empty($err_msg['pass'])) echo $err_msg['pass'];
        ?>
      </div> 
      <!-- 再入力　エラーメッセージ表示 -->
      <label class="<?php if(!empty($err_msg['pass_re'])) echo 'err'; ?>">
        <p style="width:100px;">パスワード<br>（再入力）</p>
        <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
      </label>
      <div class="area-msg">
        <?php
        if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re'];
        ?>
      </div>

      <input type="submit" class="btn btn-signup" value="登録する">
  
    </div>
  </form>

  <div class="js-menu">
    <a href="login-h.php">
      <input type="button" method="post" class="btn btn-login " value="ログイン画面へ">
    </a>
    <a href="top.php">
      <input type="button" method="post" class="btn btn-toTop " value="TOPへ戻る">
    </a>
  </div>
</section>

<?php
require('foot.php');
?>