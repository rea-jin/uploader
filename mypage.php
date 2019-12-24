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

// 画面表示用データ取得
//================================
$dbFormData2 = (!empty($u_id)) ? getUsers($_SESSION['user_id']) : '';
// 新規登録画面か編集画面か判別用フラグ カードデータがnullなら新規
$edit_flg = (empty($dbFormData2)) ? false : true;
// POST送信時処理
//================================
if (!empty($_POST)) {
  debug('POST送信があります。');
  debug('POST情報：' . print_r($_POST, true));
  debug('FILE情報：' . print_r($_FILES, true));

  $title = $_POST['title'];

  // 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if (empty($dbFormData2)) {
    //未入力チェック
    validRequired($title, 'title');
  } else {
    if ($dbFormData2['title'] !== $title) {
      //最大文字数チェック
      validMaxLen($title, 'title');
    }
  }

  if (empty($err_msg)) {
  //   //例外処理
    try {
  //     // DBへ接続
      $dbh = dbConnect();
  //     // SQL文作成
  //     // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
      if ($edit_flg) {
        debug('DB更新です。');
        $sql = 'UPDATE users SET title = :title WHERE user_id = :u_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':title'=>$title);
      } else {
        debug('DB新規登録です。');
        $sql = 'insert into users (title,user_id) values (:title,:u_id)';
        $data = array(':title'=>$title,':u_id' => $_SESSION['user_id']);
      }
      debug('SQL：' . $sql);
      debug('流し込みデータ：' . print_r($data, true));
  //     // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

  //     // クエリ成功の場合
      if ($stmt) {
  //       debug('マイページへ遷移します。');
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
  <h1 class="menu">Likerd</h1>
  
<!-- 編集画面 -->
<h1>My Page</h1>
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


<div class="descript" style="color:bisque; text-shadow:2px 2px #333;">
    ＊使い方<br>
    1.「カードを追加」から、好きなものの画像をアップ（著作権の所在に注意してください）<br>
    2. 好きなものの種類、名前を記入<br>
    3. 好きなものへの思いを記入<br>
    4. 下の完成/更新をクリック！<br>
    5. １〜4枚のカード作成が終わったら、タイトルをつけて、「タイトル更新」。<br>
    6.「カードを見る」をクリックするとカードが表示されるので、<br>
      スクショを撮ってSNSにアップしよう!<br>
</div>

  <form action="" method="post" style="color:white; font-size:16px;">
    <label for="" class="input-label">
      <p class="">カードタイトルを入れて、カードを見てみよう！</p><br>
      <input type="text" name="title" value="<?php echo getFormData2('title'); ?>">
    </label>
    <input type="submit" class="btn" value="タイトル更新">
  </form>

<div class="link">
  <button class="btn">
    <!-- <a href="card.php<?= (!empty($u_id)) ? '&card=' . $u_id : '?card=' . $u_id; ?>">カードを見る</a> -->
    <a href="card.php<?php echo (!empty(appendGetParam())) ? appendGetParam().'&u_id='.$val['user_id'] : '?u_id='.$val['user_id']; ?>" target="_blank">カードを見る</a>
  </button>
  <a href="logout.php"><button class="btn">ログアウト</button></a>
  <button class="btn">
    <a href="withdraw.php">退会する</a>
  </button>
</div>


<?php
require('foot.php');
?>