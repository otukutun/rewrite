<?php
session_start();
$rewrite_word_list = 'rewrite_word_list.csv';

// バリデーション
if (empty($_FILES['csv']['name'])) {
  $_SESSION['message'] = 'ファイルを指定してください';
  header("Location: index.php");
  exit();
}

$uploaddir = './';
$uploadfile = $uploaddir . basename($_FILES['csv']['name']);

if (!move_uploaded_file($_FILES['csv']['tmp_name'], $uploadfile)) {
  $_SESSION['message'] = 'ファイルアップロードに失敗しました';
  header("Location: index.php");
  exit();
}

// ファイル有無確認
if (!is_file($rewrite_word_list)) {
  touch($rewrite_word_list);
}

// file_get_content
$buf = file_get_contents($uploadfile);
$buf = ereg_replace("\r\n|\r|\n","\n",$buf);
$fp = tmpfile();
fwrite($fp, $buf);
rewind($fp);

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
      array_unshift($l, $l_first);
      }
  }
});

// 新規登録のみ
$new_words = array_map(function($l) use ($upload_lists) {
  $flag = false;
  foreach ($upload_lists as $u) {
      if ($l[0] == $u[0]) {
        $flag = true;
      }
  }

  if ($flag == false) {
    return $u;
  }
}, $rewrite_lists);

$rewrite_lists = array_merge($rewrite_lists, $new_words);

// 既に登録されている場合は警告を出す
// ファイル書き込み1列目が置き換え対象文字,2列目以降が置き換え後の文字
unlink($rewrite_word_list);
$fp = fopen($rewrite_word_list, 'w');
array_walk_recursive($rewrite_lists, function (&$l) {
  $l = mb_convert_encoding($l, "SJIS", "UTF-8");
});
foreach($rewrite_lists as $l) {
  fputcsv($fp, $l);
}
fclose($fp);
$_SESSION['message'] = '登録完了しました。';
header("Location: index.php");

?>
