<?php
session_start();
$rewrite_word_list = 'rewrite_word_list.csv';

// var_dump($_FILES);
// exit();
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

//
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

var_dump($upload_lists);
var_dump($rewrite_lists);
// var_dump($rewrite_lists, $upload_lists);
// 既存のデータに追加
array_walk($rewrite_lists, function(&$l) {
  foreach ($upload_lists as $u) {
    var_dump($u);

      if ($l[0] == $u[0]) {
        array_shift($u); 
        $l_first = array_shift($l); 
        $l = array_merge($l, $u);
        $l = array_unique($l);
        array_unshift($l, $l_first);
      }
  }
});
var_dump($rewrite_lists);
exit();

//既に登録されている場合は警告を出す
// ファイル書き込み1列目が置き換え対象文字,2列目以降が置き換え後の文字
if (isset($rewrite_words)) {
  $fp = fopen($rewrite_word_list, 'a');
  $list[] = $source_word;
  $list = array_merge($list, $rewrite_words);
  array_walk($list, function(&$l) {
    $l = mb_convert_encoding($l, "SJIS", "UTF-8");
  });
  fputcsv($fp, $list);
  fclose($fp);
  $_SESSION['message'] = '登録完了しました。';
  header("Location: index.php");
  exit();
}

?>
