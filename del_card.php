<?php
require('function2.php');
require('validation.php');

$u_id = $_SESSION['user_id'];

// DBからカードID取得
$c_id = getMyCard($u_id);
// DBからカードデータすべてを取得
$c_all = getMyAll($u_id);

// GETデータを格納
$p_id = (!empty($_GET['c_id'])) ? $_GET['c_id'] : '';

// debug('@@@@@@@@@@å'.$c_id);
debug('******************'.$p_id);
debug('******************'.var_dump($_POST));
if($_POST['action']==='delete'){
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成
    $sql = 'UPDATE card1 SET  delete_flg = 1 WHERE id = :c_id';
    // データ流し込み
    // $data = array(':p_id' => $p_id );
    // クエリ実行
    // $stmt = queryPost($dbh, $sql, $data);
    // $res = $dbh->query($sql);
    // debug($res);

    // 削除するレコードのIDは空のまま、SQL実行の準備をする
$stmt = $dbh->prepare($sql);
 
// 削除するレコードのIDを配列に格納する
$params = array(':c_id'=>$p_id);
 
// 削除するレコードのIDが入った変数をexecuteにセットしてSQLを実行
$stmt->execute($params);
 
if($stmt){
// 削除完了のメッセージ
// echo '削除完了しました';
    // クエリ実行成功の場合（最悪userテーブルのみ削除成功していれば良しとする）
     //セッション削除
    //   session_destroy();
    //   debug('セッション変数の中身：'.print_r($_SESSION,true));
    //   debug('トップページへ遷移します。');
    debug('クエリが成功しました！！！！！！！！！！！！');
      header("Location:mypage.php");
    }else{
      debug('クエリが失敗しました。');
      $err_msg['common'] = MSG07;
    }

  } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
      debug('エラー発生:' . $e->getMessage());
      $err_msg['common'] = MSG15;
      header("Location:https://yahoo.co.jp");
  }
}else{
    header("Location:editCard.php?c_id=$p_id");
}