<?php
session_start();
require_once('./func.php');
$rewrite_word_list = 'rewrite_word_list.csv';

// バリデーション
if (empty($_POST['source_word']) || empty($_POST['rewrite_words'])) {
  $_SESSION['message'] = '入力してください';
  header("Location: index.php");
  exit();
}
$source_word = $_POST['source_word'];
$rewrite_words = $_POST['rewrite_words'];

// ファイル有無確認
if (is_file($rewrite_word_list)) {
  touch($rewrite_word_list);
}

// 空文字排除
$rewrite_words = explode(',', $rewrite_words);
$rewrite_words = array_filter($rewrite_words, function($w) {
  if (!empty($w)) return $w;
});
// 既に登録されている場合は警告を出す

$rewrite_lists = array();
if (($handle = fopen($rewrite_word_list, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000,",")) !== FALSE) {
    $rewrite_lists[] = $data;
  }
  fclose($handle);
}
$rewrite_lists = sjis2utf8($rewrite_lists);

$flag = false;
// 既存のデータに追加
array_walk($rewrite_lists, function(&$l) use ($source_word, $rewrite_words, &$flag) {
  if ($l[0] == $source_word) {
    $flag = true;
    $l_first = array_shift($l); 
    $l = array_merge($l, $rewrite_words);
    $l = array_unique($l);
    array_unshift($l, $l_first);
  }
});
// csv書き出し
if ($flag) {
  unlink($rewrite_word_list);
  write_csv($rewrite_word_list, $rewrite_lists);
  $_SESSION['message'] = '登録完了しました。';
  header("Location: index.php");
  exit();
}

// ファイル書き込み1列目が置き換え対象文字,2列目以降が置き換え後の文字
$list[] = $source_word;
$list = array_merge($list, $rewrite_words);
$rewrite_lists[] = $list;
unlink($rewrite_word_list);
write_csv($rewrite_word_list, $rewrite_lists);
$_SESSION['message'] = '登録完了しました。';
header("Location: index.php");
exit();
?>
