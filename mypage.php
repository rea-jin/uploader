<?php
require('function2.php');
require('validation.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');

//================================
// 画面処理
//================================
//ログイン認証
require('auth.php');

// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
// DBからカードID取得
$c_id = getMyCard($u_id);
// DBからカードデータすべてを取得
$c_all = getMyAll($u_id);
// GETデータを格納
$p_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';
// DBから連絡掲示板データを取得
debug('ユーザーID取れているか' . $u_id);
debug('カードID取れているか' . $c_id);
debug('pID取れているか' . $p_id);

// 画面表示用データ取得
//================================
// GETデータを格納　ここはいらない
// $c_id = get
// マイページに来たら、すぐにDB接続してデータを読み込むようにしたい
// $c_id = (!empty($_SESSION['user_id'])) ? $_SESSION['user_id'] : '';
// DBから商品データを取得
$dbFormData = (!empty($u_id)) ? getCard($_SESSION['user_id'], $c_id) : '';
// 新規登録画面か編集画面か判別用フラグ カードデータがnullなら新規
$edit_flg = (empty($dbFormData)) ? false : true;
// DBからカテゴリデータを取得

// POST送信時処理
//================================
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

    //最大文字数チェック
    validMaxLen($comment, 'comment', 200);
    //未入力チェック
    // validRequired($price, 'price');
    //半角数字チェック
    // validNumber($price, 'price');
  } else {
    if ($dbFormData['name'] !== $name) {
      //未入力チェック
      //   validRequired($name, 'name');
      //最大文字数チェック
      validMaxLen($name, 'name');
    }
    if ($dbFormData['comment'] !== $comment) {
      //最大文字数チェック
      validMaxLen($comment, 'comment', 200);
    }
  }

  if (empty($err_msg)) {
    debug('バリデーションOKです。');

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
      if ($edit_flg) {
        debug('DB更新です。');
        $sql = 'UPDATE card1 SET name = :name, category = :category, comment = :comment, img = :img WHERE user_id = :u_id AND id = :c_id';
        $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':img' => $img,  ':u_id' => $_SESSION['user_id'], ':c_id' => $c_id);
      } else {
        debug('DB新規登録です。');
        $sql = 'insert into card1 (name, category, comment, img, user_id, create_date ) values (:name, :category,  :comment,  :img, :u_id, :date)';
        $data = array(':name' => $name, ':category' => $category, ':comment' => $comment, ':img' => $img, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
      }
      debug('SQL：' . $sql);
      debug('流し込みデータ：' . print_r($data, true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if ($stmt) {
        debug('マイページへ遷移します。');
        header("Location:mypage.php"); //マイページへ
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
}
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
require('head.php');
?>
<body>
  <h1 style="text-align: right; background-color: burlywood;" class="mb-5 menu">Likerd</h1>
  
<!-- 編集画面 -->
<h1>My Page</h1>
<!-- <form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;"> -->
<p id="js-show-msg" style="display:none;" class="msg-slide">
      <?php echo getSessionFlash('msg_success'); ?>
    </p>

  <!-- カード -->
  <h2><?= (!$edit_flg) ? 'カードを作成しよう' : '画像をクリックでカード編集ができます'; ?></h2>

<div class="wrapper">
  <?php
  if (!empty($c_all)) :
    foreach ($c_all as $key => $val) :
      ?>
      <!-- リンクにidをいれてget送信としている -->
      <div class="mycard">
        <a href="editCard.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&c_id='.$val['id'] : '?c_id='.$val['id']; ?>" class="">
          <img src="<?php echo showImg(sanitize($val['img'])); ?>" alt="<?php echo sanitize($val['name']); ?>" class="my-img ">
        </a>
        <!-- </div> -->
        <!-- <div class="card-body"> -->
        <p class="card-title my-title"><?php echo sanitize($val['category']); ?></p>
      </div>
  <?php
    endforeach;
  endif;
  ?>
</div> 

<a href="editCard.php"><button class="btn" style="<?php echo (validCard4($u_id) >= 4)?  'display:none':'font-size:14px;' ?>">カードを追加する</button></a>


<div class="descript">
  ＊使い方<br>
  ⒈好きなものの画像をアップ（著作権の所在に注意してください）<br>
  ⒉好きなものの種類、名前を記入<br>
  ⒊好きなものへの思いを記入<br>
  ⒋下の完成/更新をクリック！<br>
  ⒌カードを見るをクリックするとカードが表示されるので、<br>
  URLやスクショを撮ってSNSにアップしよう!<br>
</div>

<div class="link">
  <a href="logout.php"><button class="btn">ログアウト</button></a>
  <button class="btn">
    <!-- <a href="card.php<?= (!empty($u_id)) ? '&card=' . $u_id : '?card=' . $u_id; ?>">カードを見る</a> -->
    <a href="card.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&u_id='.$val['user_id'] : '?u_id='.$val['user_id']; ?>" target="_blank">カードを見る</a>

  </button>
  <button class="btn">
    <a href="withdraw.php">退会する</a>
  </button>
</div>


<?php
require('foot.php');
?>