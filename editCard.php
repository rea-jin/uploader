<?php
require('function2.php');
require('validation.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　マイページ　');
// debugLogStart();
//ログイン認証
require('auth.php');

//================================
// 画面処理
//================================

// 画面表示用データ取得
//================================
$u_id = $_SESSION['user_id'];
// DBから商品データを取得
// $c_id = getMyCard($u_id); //これはu_idのあっているものだから複数あったら最初のやつしか取れない
// $c_id = 1;
$c_all = getMyAll($u_id);// 上と同じ
// GETデータを格納
$p_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';//取れていない url煮付けて飛ばせば取れたappendgetparamが必要か
// DBからカテゴリデータを取得
$dbFormData = (!empty($u_id)&&!empty($p_id)) ? getCard($u_id, $p_id) : '';//$p_idを参照して、その時のカードが取れていないとダメだ
// 新規登録画面か編集画面か判別用フラグ カードデータがnullなら新規
$edit_flg = (!empty($dbFormData)) ? true : false;



debug('ユーザーID:::$u_id ->'.$u_id);
// debug('$c_id:::u_id ->'.$c_id);
debug('$p_id:::get[c_id] ->'.$p_id);
debug('dbformdata ->'.var_dump($dbFormData));


// パラメータ改ざんチェック
//================================
// GETパラメータはあるが、改ざんされている（URLをいじくった）場合、正しい商品データが取れないのでマイページへ遷移させる
// if(!empty($CardData) && empty($dbFormData)){
//     debug('GETパラメータの商品IDが違います。マイページへ遷移します。');
//     header("Location:"); //マイページへ
//   }
  


  // POST送信時処理
  //================================
  if(!empty($_POST)){
    debug('POST送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));
  
    //変数にユーザー情報を代入
    $category = $_POST['category'];
    $name = $_POST['name'];
    $comment = $_POST['comment'];
    //画像をアップロードし、パスを格納
    $img = ( !empty($_FILES['img']['name']) ) ? uploadImg($_FILES['img'],'img') : '';
    // 画像をPOSTしてない（登録していない）が既にDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
    $img = ( empty($img) && !empty($dbFormData['img']) ) ? $dbFormData['img'] : $img;

    
// 更新の場合はDBの情報と入力情報が異なる場合にバリデーションを行う
  if(empty($dbFormData)){
    //未入力チェック
    validRequired($category, 'category');
    validRequired($name, 'name');
    //最大文字数チェック
    validMaxLen($category, 'category',15);
    validMaxLen($name, 'name',15);
    validMaxLen($comment, 'comment',100);
   
  }else{
    if($dbFormData['category'] !== $category){
      //未入力チェック
      validRequired($name, 'name');
      //最大文字数チェック
      validMaxLen($category, 'category',15);
    validMaxLen($name, 'name',15);
    validMaxLen($comment, 'comment',100);
    }
    if($dbFormData['comment'] !== $comment){
      //最大文字数チェック
      validMaxLen($comment, 'comment', 100);
    }
   
  }

// ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
  if(empty($err_msg)){
    debug('バリデーションOKです。');

    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      // 編集画面の場合はUPDATE文、新規登録画面の場合はINSERT文を生成
      if($edit_flg){
        debug('DB更新です。');
        $sql = 'UPDATE card1 SET name = :name, category = :category, comment = :comment, img = :img WHERE user_id = :u_id AND id = :c_id';
        $data = array(':name' => $name , ':category' => $category, ':comment' => $comment, ':img' => $img,  ':u_id' => $_SESSION['user_id'], ':c_id' => $p_id);
      }else{
        debug('DB新規登録です。');
        $sql = 'insert into card1 (name, category, comment, img, user_id, create_date ) values (:name, :category,  :comment,  :img, :u_id, :date)';
        $data = array(':name' => $name , ':category' => $category, ':comment' => $comment, ':img' => $img, ':u_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
      }
      debug('SQL：'.$sql);
      debug('流し込みデータ：'.print_r($data,true));
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);

      // クエリ成功の場合
      if($stmt){
        $_SESSION['msg_success'] = SUC04;
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
<?php
// if(!empty($c_all)):foreach($c_all as $key => $val):
  ?>


<!-- カード -->
<h2><?= ($edit_flg) ? 'カード編集' : '新規作成';?></h2>


<!-- =============================================================== -->
<form action="" method="post" class="form" enctype="multipart/form-data" style="width:100%;box-sizing:border-box;">
    <!-- oya1-1 -->
    <!-- =============================================================== -->
    <div class="row">
        <!-- 画像部分 ---------------------------->
        <p></p>
        <label class="area-drop <?php if(!empty($err_msg['img'])) echo 'err'; ?>">
            <br>
            <h6 style="displaposition:relative; top:0; left:0; font-size:16px;">
            clickでファイル選択orドラッグ＆ドロップ 
            <!-- <br>  -->
           
          </h6>
            <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
            <input type="file" name="img" class="input-file" >
            <img src="<?php echo getFormData('img'); ?>" alt="" class="prev-img"
            style="<?php if(empty(getFormData('img'))) echo 'display:none'?>" >
              
        </label>
        <?php
        // var_dump(getFormData('img'));
        ?>
        <!-- カードコメント部分 -------------------->
        <div class="card-body" style="padding:0;">
            <!--グループカテゴリータイトル  ------------------------>
            <label for="" class="input-label">
                <p class="">グループ、カテゴリー名、種類</p><br>
                <input type="text" name="category" value="<?php echo getFormData('category'); ?>">
            </label>
            <!-- 名前 --------------------------------->
            <label for="" class="input-label">
                <p>名前</p><br>
                <input type="text" name="name" value="<?php echo getFormData('name'); ?>">
            </label>
            <!-- コメント 期間など ------------------------->
            <label class="input-label">
            <p>コメント、いつから好きか、どれくらい好きか、好きなものへの思いなど</p><br>
                <textarea class="textarea" name="comment" class="card-comment"><?php echo getFormData('comment'); ?></textarea>
            </label>
        </div>
    </div>

    <input type="submit" class="btn" value="<?= ($edit_flg)? '更新する': '完成' ; ?>">
</form>

<!-- モーダル ================================================== -->
<form method="post" style="position:relative; top:0; z-index:10;" action="del_card.php<?= '?c_id='.$p_id ?>">
<div class="js-modal">
    <p class="del_card">削除しますか？</p><br>
  <button type='submit' name="action" value="delete" style="font-size:20px; line-height:1em; margin:5px 10px; letter-spacing:2px;" >Yes</button>/
  <button type='submit' name='action' value='back' style="font-size:20px; line-height:1em; margin:5px 10px; letter-spacing:2px;">No</button>
</div>
</form>
<!-- ================================================== -->

<form method="post" action="del_card.php">
    <input type="button" class="js-delete_btn"  name="del-card" value="削除する">

</form>








    


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
        <a href="mypage.php"><button class="btn" style="font-size:12px;">マイページへ戻る</button></a>
        <!-- <button class="btn">
        </button>
        <button class="btn">
        <a href="withdraw.php">退会する</a>
        </button> -->
    </div>


 <?php
 require('foot.php');
 ?>