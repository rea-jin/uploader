<?php
 ini_set('log_errors','on');
 ini_set('error_log','php3.log');
 $err_msg = array();

//===========================================================
// デバッグ これを設定しないと、debugは使えない。というかこれ自体error_logだからそれで十分
//===========================================================
//デバッグフラグ
$debug_flg = true;
//デバッグログ関数
function debug($str){
global $debug_flg;
if(!empty($debug_flg)){
 error_log('デバッグ：'.$str);
}
}


session_start();
// ===========================================================
//  データベース接続
// ===========================================================
function dbConnect(){
    //DBへの接続準備
    $dsn = 'mysql:dbname=meshi;host=localhost;charset=utf8';
    $user = 'root';
    $password = 'root';
    $options = array(
      // SQL実行失敗時にはエラーコードのみ設定
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      // デフォルトフェッチモードを連想配列形式に設定
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      // バッファードクエリを使う(一度に結果セットをすべて取得し、サーバー負荷を軽減)
      // SELECTで得た結果に対してもrowCountメソッドを使えるようにする
      PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
    // PDOオブジェクト生成（DBへ接続）
    $dbh = new PDO($dsn, $user, $password, $options);
    return $dbh;
  }
  
  function queryPost($dbh, $sql, $data){
    //クエリー作成
    $stmt = $dbh->prepare($sql);
    //プレースホルダに値をセットし、SQL文を実行

    if(!$stmt->execute($data)){
      debug('クエリに失敗しました。');
      debug('失敗したSQL：'.print_r($stmt,true));
      $err_msg['common'] = MSG07;
      return 0;
    }
    debug('クエリ成功。');
    return $stmt;
  }

// ==============================================================
// マイページ用関数
// ==============================================================
// ユーザーテーブルから全て取ってくる ================================
  function getUsers ($u_id){
    
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT * FROM users WHERE user_id = :u_id AND delete_flg = 0';
      $data = array(':u_id' => $u_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
  
      if($stmt){
        // クエリ結果のデータを１レコード返却
        return $stmt->fetch(PDO::FETCH_ASSOC);
      }else{
        return false;
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
  }

// ============================================================
// 1つのカード情報をすべて取ってくる カードIDもあるので、新規登録後、更新するのに必要
function getCard ($u_id, $c_id){
    debug('getCardを取得します。');
    debug('ユーザーID：'.$u_id);
    debug('商品ID：'.$c_id);
    //例外処理
    try {
      // DBへ接続
      $dbh = dbConnect();
      // SQL文作成
      $sql = 'SELECT * FROM card1 WHERE user_id = :u_id AND id = :c_id AND delete_flg = 0';
      $data = array(':u_id' => $u_id, ':c_id' => $c_id);
      // クエリ実行
      $stmt = queryPost($dbh, $sql, $data);
  
      if($stmt){
        // クエリ結果のデータを１レコード返却
        return $stmt->fetch(PDO::FETCH_ASSOC);
      }else{
        return false;
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
  }

// =============================================================
// 複数のカードidを取得　ユーザーIDの合うカード情報すべてを取ってくる
// カードが1つしかないならこれだけでいいか
function getMyCard($u_id){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT id FROM card1 WHERE user_id = :u_id AND delete_flg = 0';
        $data = array(':u_id' => $u_id);
        // クエリ実行
        $stmt = queryPost($dbh, $sql, $data);
        if($stmt){
            // クエリ結果のデータを１レコード返却
            return $stmt->fetch(PDO::FETCH_COLUMN);
          }else{
            return false;
          }
      
    } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
        }
    }

// ========================================================
// 複数のカード情報全てを取ってくるÏ
    function getMyAll($u_id){
      try{
          $dbh = dbConnect();
          $sql = 'SELECT * FROM card1 WHERE user_id = :u_id AND delete_flg = 0';
          $data = array(':u_id' => $u_id);
          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          if($stmt){
              // クエリ結果のデータを１レコード返却
              return $stmt->fetchAll();
            }else{
              return false;
            }
        
      } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
          }
      }
      

//=============================================================
// その他
//=============================================================
// サニタイズ
function sanitize($str){
    return htmlspecialchars($str,ENT_QUOTES);
  }

// フォーム入力保持
  function getFormData($str, $flg = false){
$err_msg = array();

    if($flg){
      $method = $_GET;
    }else{
      $method = $_POST;
    }
    global $dbFormData;
    // ユーザーデータがある場合
    if(!empty($dbFormData)){
      //フォームのエラーがある場合
      if(!empty($err_msg[$str])){
        //POSTにデータがある場合
        if(isset($method[$str])){
          return sanitize($method[$str]);
        }else{
          //ない場合（基本ありえない）はDBの情報を表示
          return sanitize($dbFormData[$str]);
        }
      }else{
        //POSTにデータがあり、DBの情報と違う場合
        if(isset($method[$str]) && $method[$str] !== $dbFormData[$str]){
          return sanitize($method[$str]);
      debug('::::::::::::::::::::::::::');

        }else{
          debug('post送信の入力保持 ***************');
          return sanitize($dbFormData[$str]);
        }
      }
    }else{
      if(isset($method[$str])){
        return sanitize($method[$str]);
      }
    }
  }

// 画像処理
function uploadImg($file, $key){
    debug('画像アップロード処理開始');
    debug('FILE情報：'.print_r($file,true));
    
    if (isset($file['error']) && is_int($file['error'])) {
      try {
        // バリデーション
        // $file['error'] の値を確認。配列内には「UPLOAD_ERR_OK」などの定数が入っている。
        //「UPLOAD_ERR_OK」などの定数はphpでファイルアップロード時に自動的に定義される。定数には値として0や1などの数値が入っている。
        switch ($file['error']) {
            case UPLOAD_ERR_OK: // OK
                break;
            case UPLOAD_ERR_NO_FILE:   // ファイル未選択の場合
                throw new RuntimeException('ファイルが選択されていません');
            case UPLOAD_ERR_INI_SIZE:  // php.ini定義の最大サイズが超過した場合
            case UPLOAD_ERR_FORM_SIZE: // フォーム定義の最大サイズ超過した場合
                throw new RuntimeException('ファイルサイズが大きすぎます');
            default: // その他の場合
                throw new RuntimeException('その他のエラーが発生しました');
        }
        
        // $file['mime']の値はブラウザ側で偽装可能なので、MIMEタイプを自前でチェックする
        // exif_imagetype関数は「IMAGETYPE_GIF」「IMAGETYPE_JPEG」などの定数を返す
        $type = @exif_imagetype($file['tmp_name']);
        if (!in_array($type, [IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG], true)) 
        { // 第三引数にはtrueを設定すると厳密にチェックしてくれるので必ずつける
            throw new RuntimeException('画像形式が未対応です');
        }
  
        // ファイルデータからSHA-1ハッシュを取ってファイル名を決定し、ファイルを保存する
        // ハッシュ化しておかないとアップロードされたファイル名そのままで保存してしまうと同じファイル名がアップロードされる可能性があり、
        // DBにパスを保存した場合、どっちの画像のパスなのか判断つかなくなってしまう
        // image_type_to_extension関数はファイルの拡張子を取得するもの
        $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
        if (!move_uploaded_file($file['tmp_name'], $path)) { //ファイルを移動する
            throw new RuntimeException('ファイル保存時にエラーが発生しました');
        }
        // 保存したファイルパスのパーミッション（権限）を変更する
        chmod($path, 0644);
        
        debug('ファイルは正常にアップロードされました');
        debug('ファイルパス：'.$path);
        return $path;
  
      } catch (RuntimeException $e) {
  
        debug($e->getMessage());
        global $err_msg;
        $err_msg[$key] = $e->getMessage();
  
      }
    }
  }
//画像表示用関数

  function showImg($path){
    if(empty($path)){
      return 'img/sample-img.png';
    }else{
      return $path;
    }
  }

  // ========================================================
  //GETパラメータ付与
// $del_key : 付与から取り除きたいGETパラメータのキー
// function appendGetParam($arr_del_key = array()){
//   if(!empty($_GET)){
//     $str = '?';
//     $_GET as $key => $val
//       if(!in_array($key,$arr_del_key,true)){ 
//         //取り除きたいパラメータじゃない場合にurlにくっつけるパラメータを生成
//         $str .= $key.'='.$val.'&';
//       }
//     }
//     $str = mb_substr($str, 0, -1, "UTF-8");
//     return $str;
//   }

function getCardOne($c_id){
  debug('getCardOne:カード情報を取得します。');
  debug('商品ID：'.$c_id);
  //例外処理
  try {
    // DBへ接続
    $dbh = dbConnect();
    // SQL文作成 ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    $sql = 'SELECT c1.id , c1.name , c1.comment, c1.category, c1.img, c1.user_id, c1.create_date, c1.update_date, u.user_name AS card1
    -- c.name as category : nameをcategoryという名前にして、カラムを取得している
             FROM card1 AS c1 LEFT JOIN users AS u ON u.user_id = c1.user_id WHERE c1.id = :c_id AND c1.delete_flg = 0 AND u.delete_flg = 0';
             //pという名前に変えた、productテーブルから,p.category=idとマッチした
             //それぞれのカラムを取得している
              //左のproductテーブルは全て表示、カテゴリーテーブルはマッチしたものだけ表示
    $data = array(':c_id' => $c_id);
    // クエリ実行
    $stmt = queryPost($dbh, $sql, $data);

    if($stmt){
      // クエリ結果のデータを１レコード返却
      return $stmt->fetch(PDO::FETCH_ASSOC);
    }else{
      return false;
    }

  } catch (Exception $e) {
    error_log('エラー発生:' . $e->getMessage());
  }
}



// $del_key : 付与から取り除きたいGETパラメータのキー
function appendGetParam($arr_del_key = array()){
  if(!empty($_GET)){
    $str = '?';
    foreach($_GET as $key => $val){
      if(!in_array($key,$arr_del_key,true)){ 
        //取り除きたいパラメータじゃない場合にurlにくっつけるパラメータを生成
        $str .= $key.'='.$val.'&';
      }
    }
    $str = mb_substr($str, 0, 0, "UTF-8");
    return $str;
  }
}


//sessionを１回だけ取得できる
//一回取得したら消す
//マイページリロードのたびに表示されないようにするため
function getSessionFlash($key){
  if(!empty($_SESSION[$key])){
    $data = $_SESSION[$key];
    $_SESSION[$key] = '';
    return $data;
  }else{
    return null;
  }
}




 // フォーム入力保持
 function getFormData2($str, $flg = false){
  $err_msg = array();
  
      if($flg){
        $method = $_GET;
      }else{
        $method = $_POST;
      }

      global $dbFormData2;
      // ユーザーデータがある場合
      if(!empty($dbFormData2)){
        //フォームのエラーがある場合
        if(!empty($err_msg[$str])){
          //POSTにデータがある場合
          if(isset($method[$str])){
            return sanitize($method[$str]);
          }else{
            //ない場合（基本ありえない）はDBの情報を表示
            return sanitize($dbFormData2[$str]);
          }
        }else{
          //POSTにデータがあり、DBの情報と違う場合
          if(isset($method[$str]) && $method[$str] !== $dbFormData2[$str]){
            return sanitize($method[$str]);
        debug('::::::::::::::::::::::::::');
  
          }else{
            debug('post送信の入力保持 ***************');
            return sanitize($dbFormData2[$str]);
          }
        }
      }else{
        if(isset($method[$str])){
          return sanitize($method[$str]);
        }
      }
    }
  