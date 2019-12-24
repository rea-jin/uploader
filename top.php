<?php
require('head.php');

?>
<body>
  <h1 class="menu">Likerd</h1>
  
<!-- ここから画面を切り替える -->
  <section class="js-page container-text">
  <h2>あなたの好きなものをカードにする、Like Cardを作成！<br>
        好きな食べ物、好きな場所、好きなペット、好きな映画、好きなアニメ、などなど・・・・<br>
        <span>好きな画像で、自由にカードを作ろう！</span></h2>
    <h3><span style="color:blanchedalmond;">画像とコメントであなただけのカードを作成！</span><br>
        作成後は、スクリーンショットで<br>
      SNSなどに貼り付けよう！</h3>
    
    <!-- 画像 作成した名刺 2スタイル pcとスマホ-->
    <div class="container-img "> 
      <img src="images/" class="js-content-img1" alt="idol">
      <img src="images/" class="js-content-img2" alt="anime">
      </div>

    <h2>あなたの事なら何でもOK！これを名刺代わりにしよう！</h2>
    <h2>ニックネームだけで登録できるよ！</h2>
    <h3>まずは登録！</h3>
    
  <!-- ajax js-menu ------------------>
  <form action="" class="js-menu text-center ">
      <a href="signup-h.php">
        <input type="button" method="post" class="btn btn-signup js-signup" value="登録画面へ">
      </a>
      <a href="login-h.php">
        <input type="button" method="post" class="btn btn-login js-login" value="ログイン画面へ">
      </a>
  </form>
  </section>

<?php
require('foot.php');
?>