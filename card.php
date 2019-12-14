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
$dbFormData2 = (!empty($u_id)) ? getCard2($_SESSION['user_id']) : '';

// DBから連絡掲示板データを取得
debug('ユーザーID取れているか' . $u_id);
debug('カードID取れているか' . $c_id);

if (!empty($_POST)) {
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));
  debug('FILE情報：' . print_r($_FILES, true));

  //変数にユーザー情報を代入
  // for($i=1;$i<=4;$i++){
  //変数にユーザー情報を代入
  $category = $_POST['category'];
  $name = $_POST['name'];
  $comment = $_POST['comment'];
  //画像をアップロードし、パスを格納
  $img = (!empty($_FILES['img']['name'])) ? uploadImg($_FILES['img'], 'img') : '';
  // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
  $img = (empty($img) && !empty($dbFormData['img'])) ? $dbFormData['img'] : $img;
  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if (empty($dbFormData)) {
    //未入力チェック
    // validRequired($name, 'name');
    //最大文字数チェック
    validMaxLen($name, 'name');

    validMaxLen($comment, 'comment');
  
  } else {
    if ($dbFormData['name'] !== $name) {
      //未入力チェック
      //   validRequired($name, 'name');
      //最大文字数チェック
      validMaxLen($name, 'name');
    }
    if ($dbFormData['comment'] !== $comment) {
      //最大文字数チェック
      validMaxLen($comment, 'comment');
    }
  
  }


  // if (empty($err_msg)) {
  //   debug('バリデーションOKです。');

  //   //例外処理
  //   try {
  //     // DBへ接続
  //     $dbh = dbConnect();
  //     // SQL文作成
  //     // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
  //     if ($edit_flg) {
  //       debug('DB更新です。');
  //       $sql = 'UPDATE card1 SET name = :name, category = :category, comment = :comment, img = :img WHERE user_id = :u_id AND id = :c_id';
  //       $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':img' => $img,  ':u_id' => $_SESSION['user_id'], ':c_id' => $c_id);
  //     } else {
  //       debug('DB新規登録です。');
  //       $sql = 'insert into card1 (name, category, comment, img, user_id, create_date ) values (:name, :category,  :comment,  :img, :u_id, :date)';
  //       $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':img' => $img, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
  //     }
  //     debug('SQL：' . $sql);
  //     debug('流し込みデータ：' . print_r($data, true));
  //     // クエリ実行
  //     $stmt = queryPost($dbh, $sql, $data);

  //     // クエリ成功の場合
  //     if ($stmt) {
  //       $_SESSION['msg_success'] = SUC04;
  //       debug('マイページへ遷移します。');
  //       header("Location:mypage.php"); //マイページへ
  //     }
  //   } catch (Exception $e) {
  //     error_log('エラー発生:' . $e->getMessage());
  //     $err_msg['common'] = MSG07;
  //   }
  // }
}
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
<div class="title"><?= getFormData2('title'); ?></div>

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