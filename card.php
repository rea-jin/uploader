<?php
require('function2.php');
// require('validation.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');

//================================
// 画面処理
//================================
$u_id = $_SESSION['user_id'];
// DBからカードID取得
$c_id = getMyCard($u_id);
// DBからカードデータすべてを取得
$c_all = getMyAll($u_id);
// GETデータを格納
$p_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
$dbFormData2 = (!empty($u_id)) ? getUsers($_SESSION['user_id']) : '';


debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
require('head.php');
?>


<h6 class="head-title">Likerd
  <button class="js-change1">横スタイル</button>
  <!-- onclick="setHref('css/card.css');" -->
  <button class="js-change2">縦スタイル</button>
  <!-- onclick="setHref('css/mycard.css');" -->
  <button style=" width:50px; border:1px solid #444; " class="js-change3">伸ばす</button>
</h6>
<div class="title">『 <?= getFormData2('title'); ?> 』</div>

<div class="wrapper c-wrapper m-wrap">
<?php
if (!empty($c_all)) :
  foreach ($c_all as $key => $val) :
    ?>
      <!-- oya1 -->
    <section class="card m-card">
        <div class="img-height m-height">
          <img class="card-img m-img" src="<?= showImg(sanitize($val['img'])); ?>" alt="...">
        </div>
        <div class="card-body m-body">
          <h5 class="card-title m-title"><?php echo sanitize($val['category']); ?></h5>
          <h6 class="card-title m-title"><?php echo sanitize($val['name']); ?></h6>
         <hr>
          <p class="card-text m-text"><?php echo sanitize($val['comment']); ?></p>
        </div>
    </section>
<?php
  endforeach;
endif;
?>
</div>
<?php
require('foot.php');
?>