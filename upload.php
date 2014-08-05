<?php
session_start();
require_once('./func.php');
$rewrite_word_list = 'rewrite_word_list.csv';

// バリデーション
if (empty($_FILES['csv']['name'])) {
  $_SESSION['message'] = 'ファイルを指定してください';
  header("Location: index.php");
  exit();
}

$uploaddir = './';
$uploadfile = $uploaddir . mt_rand(0, 100) . basename($_FILES['csv']['name']);

if (!move_uploaded_file($_FILES['csv']['tmp_name'], $uploadfile)) {
  $_SESSION['message'] = 'ファイルアップロードに失敗しました';
  header("Location: index.php");
  exit();
}

// file_get_content
ini_set('auto_detect_line_endings', true);
$fp = fopen($uploadfile, "r");
if (!$fp) {
  $_SESSION['message'] = '登録失敗しました';
  unlink($uploadfile);
  header("Location: index.php");
  exit();
}

//uploadファイルを取り出し
$upload_lists = array();
if ($fp !== FALSE) {
  while (($data = fgetcsv($fp, 1000,",")) !== FALSE) {
    array_walk($data, function(&$d) {
      $d = mb_convert_encoding($d, "UTF-8", "SJIS");
    });
    $upload_lists[] = $data;
  }
  fclose($fp);
}
unlink($uploadfile);

// csvファイルから取り出し
$rewrite_lists = array();
if (($handle = fopen($rewrite_word_list, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000,",")) !== FALSE) {
    array_walk($data, function(&$d) {
      $d = mb_convert_encoding($d, "UTF-8", "SJIS");
    });
    $rewrite_lists[] = $data;
  }
  fclose($handle);
}

// 既存のデータに追加
array_walk($rewrite_lists, function(&$l) use ($upload_lists) {
  foreach ($upload_lists as $u) {
    if ($l[0] == $u[0]) {
      array_shift($u); 
      $l_first = array_shift($l); 
      $l = array_merge($l, $u);
      $l = array_unique($l);
      // 空文字削除
      $l = array_filter($l, 'strlen');
      array_unshift($l, $l_first);
    }
  }
});

// 新規登録のみ
$new_words = array_map(function($u) use ($rewrite_lists) {
  $flag = true;
  foreach ($rewrite_lists as $r) {
    if ($r[0] == $u[0]) {
      $flag = false;
    }
  }
  if ($flag) {
    return $u;
  }
}, $upload_lists);

// 空文字削除
foreach ($new_words as &$word) {
    $word = array_filter($word, 'strlen');
}

$rewrite_lists = array_merge($rewrite_lists, $new_words);

// csvファイル作成
unlink($rewrite_word_list);
write_csv($rewrite_word_list, $rewrite_lists);
$_SESSION['message'] = '登録完了しました。';
header("Location: index.php");
exit();

?>
