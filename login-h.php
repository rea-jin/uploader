<?php
require('login.php');
require('head.php');
?>
<body>
  <h1 style="text-align: right; background-color: burlywood;" class="mb-5 menu">Likerd</h1>
  
<!-- 登録成功メッセージ -->
<div id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </div>

<section class="js-page container-text">
  <!-- 登録情報送信 ------------------------------------- -->
  <form method="post" action="">
    <h2 class="title">ログインページ</h2>
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

      <input type="submit" class="btn btn-signup" value="ログインする">
      <!-- <p>すでに登録済みの方はこちら</p>
      <p>🔻</p>
      <p>
      <a href="card_login.php" style="text-decoration:none;">
        <input type="button" method="post" class="btn btn-login" value="ログイン">
      </a>
      </p> -->
    </div>
  </form>


  <div class="js-menu">
    <a href="signup-h.php">
      <p style="display:block">登録がお済みでない方はこちら</p>
      <p style="display:block">🔻</p>
      <input type="button" method="post" class="btn btn-login " value="登録画面へ">
    </a>
    <a href="top.php">
      <input type="button" method="post" class="btn btn-toTop " value="TOPへ戻る">
    </a>
  </div>
</section>

<?php
require('foot.php');
?>