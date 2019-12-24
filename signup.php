<?php
require('function2.php');
require('validation.php');


if (!empty($_POST)) {
  //変数にユーザー情報を代入
  $username = $_POST['username'];
  $pass = $_POST['pass'];
  $pass_re = $_POST['pass_re'];

 //未入力チェック
  validRequired($username,'username');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');
  
  if (empty($err_msg)){
    // ニックネーム重複チェック
    validUserDup($username);
    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
   //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
   //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');
    //パスワード（再入力）の最大文字数チェック
    validMaxLen($pass_re, 'pass_re');
    //パスワード（再入力）の最小文字数チェック
    validMinLen($pass_re, 'pass_re');
    
    if(empty($err_msg)){
      //パスワードとパスワード再入力が合っているかチェック
      validMatch($pass, $pass_re, 'pass_re');

      //DB接続関数
      if (empty($err_msg)) {
        
        //例外処理
        try { // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (user_name, password,create_date,login_time) VALUES(:username,:pass,:create_date,:login_time)';
          $data = array(
            ':username' => $username, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
            ':create_date' => date('Y-m-d H:i:s'),
            ':login_time' => date('Y-m-d H:i:s'),
          );
            // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
            
            // クエリ成功の場合
            if ($stmt) {
              //ログイン有効期限（デフォルトを１時間とする）
              // $sesLimit = 60 * 60;
              
              // debug('セッション変数の中身：'.print_r($_SESSION,true));
              $_SESSION['msg_success']=SUC04;
              header("Location:login-h.php"); //ログイン画面へ
            }
          } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG07;
          }
        }
      }
    }
  }

