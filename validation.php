<?php
// require('function2.php');
//================================
// 定数
//================================
//エラーメッセージを定数に設定
define('MSG01','入力必須です');
define('MSG02', '');
define('MSG03','パスワード（再入力）が合っていません');
define('MSG04','半角英数字のみご利用いただけます');
define('MSG05','6文字以上で入力してください');
define('MSG06','');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08', 'そのニックネームは既に登録されています');
define('MSG09', 'ニックネームまたはパスワードが違います');

define('MSG14', '文字で入力してください');
define('MSG15', '正しくありません');
define('MSG16', '');
define('MSG17', '半角数字のみご利用いただけます');

define('SUC04', '登録しました');
define('SUC05','');

//エラーメッセージ表示
function getErrMsg($key){
    global $err_msg;
    if(!empty($err_msg[$key])){
      return $err_msg[$key];
    }
  }

  // グローバル変数
//================================
//エラーメッセージ格納用の配列
$err_msg = array();


//バリデーション関数（未入力チェック）=====================
function validRequired($str, $key){
    if($str === ''){ //金額フォームなどを考えると数値の０はOKにし、空文字はダメにする
      global $err_msg;
      $err_msg[$key] = MSG01;
    }
  }


  //バリデーション関数（同値チェック ) =========================
  function validMatch($str1, $str2, $key){
    if($str1 !== $str2){
      global $err_msg;
      $err_msg[$key] = MSG03;
    }
  }

   //バリデーション関数（最小文字数チェック） =====================
   function validMinLen($str, $key, $min = 6){
    if(mb_strlen($str) < $min){
      global $err_msg;
      $err_msg[$key] = MSG05;
    }
  }

  //バリデーション関数（最大文字数チェック） ======================
  function validMaxLen($str, $key, $max = 256){
    if(mb_strlen($str) > $max){
      global $err_msg;
      $err_msg[$key] = MSG06;
    }
  }

   //バリデーション関数（半角チェック） ===========================
   function validHalf($str, $key){
    //    正規表現
    if(!preg_match("/^[a-zA-Z0-9]+$/", $str)){
      global $err_msg;
      $err_msg[$key] = MSG04;
    }
  }

   
//パスワードチェック ======================================
function validPass($str, $key){
    //半角英数字チェック
    validHalf($str, $key);
    //最大文字数チェック
    validMaxLen($str, $key);
    //最小文字数チェック
    validMinLen($str, $key);
  }

 //バリデーション関数（username重複チェック）=====================
 function validUserDup($username){
    global $err_msg;
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT count(*) FROM users WHERE user_name = :username AND delete_flg = 0';
      $data = array(':username' => $username);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
      // クエリ結果の値を取得
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      
      
      //array_shift関数は配列の先頭を取り出す関数です。クエリ結果は配列形式で入っているので、array_shiftで1つ目だけ取り出して判定します
      if(!empty(array_shift($result))){
        $err_msg['username'] = MSG08;
  //===================== 別報 ================================================================================
        // $stmt = $this->dbh->prepare("SELECT username FROM user WHERE id = 1");
  // $stmt->execute();
  // $username = $stmt->fetchAll(PDO::FETCH_ASSOC);
  // echo $username[0]['username'];
  // 「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「」」」」」」」」」」」」」」」」」」」」
          
      }
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG07;
    }
  }
// ＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠＠
  // カード4枚までかチェック
function validCard4($u_id){
  global $err_msg;
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'SELECT count(*) FROM card1 WHERE user_id = :u_id AND delete_flg = 0';
    $data = array(':u_id' => $u_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);
    $stmt->execute();
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);
      // $count=$stmt->rowCount();
      $count=$stmt->fetch(PDO::FETCH_COLUMN);
      // $count=$stmt->fetchColumn();
      debug($count);
      return $count;
    // $colcount = $stmt->columnCount();

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
    $err_msg['common'] = MSG07;
  }
}

