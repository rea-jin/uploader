<?php
//共通変数・関数ファイルを読込み
require('function2.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　ログアウトページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
// debugLogStart();

debug('ログアウトします。');
// セッションを削除（ログアウトする）
session_destroy();
debug('ログインページへ遷移します。');
// ログインページへ
header("Location:login-h.php");