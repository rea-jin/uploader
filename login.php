<?php
require('function2.php');
require('validation.php');
//ログイン認証
require('auth.php');

// post送信されていた場合
if (!empty($_POST)) {
  //変数にユーザー情報を代入
  $username = $_POST['username'];
  $pass = $_POST['pass'];

 //未入力チェック
  validRequired($username,'username');
  validRequired($pass, 'pass');
  
  if (empty($err_msg)){
    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
   //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
   //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');

      //DB接続関数
      if (empty($err_msg)) {
        
        //例外処理
        try { // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'SELECT user_id,password FROM users WHERE user_name = :username AND delete_flg = 0'; 
          $data = array(
            ':username' => $username // Post送信された
          );
            // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          // クエリ結果の値を取得
          $result = $stmt->fetch(PDO::FETCH_ASSOC);
          debug(print_r($result));
          // パスワードの照合 パスワードがハッシュにマッチするかどうかを調べるpassword_verify
          if($result!=null){ 
          // && password_verify($pass,array_shift($result))){
            //ログイン有効期限（デフォルトを１時間とする）
            $sesLimit = 60*60;
            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time(); //time関数は1970年1月1日 00:00:00 を0として、1秒経過するごとに1ずつ増加させた値が入る

            // ログイン保持にチェックがある場合
            // if($pass_save){
            //     debug('ログイン保持にチェックがあります。');
            //       // ログイン有効期限を1日にしてセット
                  $_SESSION['login_limit'] = $sesLimit * 24 * 1;
            // }else{
            //       debug('ログイン保持にチェックはありません。');
            //       // 次回からログイン保持しないので、ログイン有効期限を1時間後にセット
            //       $_SESSION['login_limit'] = $sesLimit;
            //   }
              // ユーザーIDを格納
            $_SESSION['user_id'] = $result['user_id'];
            $_SESSION['msg_success']=SUC04;
            
            header("Location:mypage.php"); //マイページへ

          }else{
            debug('パスワードがアンマッチです。');
            $err_msg['common'] = MSG09;
          }
        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
    debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
 

            // クエリ成功の場合
            // if ($stmt) {
              //ログイン有効期限（デフォルトを１時間とする）
              // $sesLimit = 60 * 60;
              // 最終ログイン日時を現在日時に
              // $_SESSION['login_date'] = time();
              // $_SESSION['login_limit'] = $sesLimit;
              // ユーザーIDを格納
              // $_SESSION['user_id'] = $dbh->lastInsertId();
         
          }
        


