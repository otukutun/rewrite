<?php
session_start();
$rewrite_word_list = 'rewrite_word_list.csv';

// バリデーション
if (empty($_POST['rewrite'])) {
  $_SESSION['message'] = '入力してください';
  header("Location: index.php");
  exit();
}
$rewrite_words = $_POST['rewrite'];

// ファイル有無確認
if (!is_file($rewrite_word_list)) {
  $_SESSION['message'] = 'ファイルが存在しません。';
  header("Location: index.php");
}

// 空文字排除
$rewrite_words = explode(',', $rewrite_words);
$rewrite_words = array_filter($rewrite_words, function($w) {
  if (!empty($w)) return $w;
});

//既に登録されている場合は警告を出す
if (($handle = fopen($rewrite_word_list, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000,",")) !== FALSE) {
    if ($source_word == $data[0]) {
      $_SESSION['message'] = '既にその文字は登録されています。';
      fclose($handle);
      header("Location: index.php");
      exit();
    }
  }
  fclose($handle);
}
// ファイル書き込み1列目が置き換え対象文字,2列目以降が置き換え後の文字
if (isset($rewrite_words)) {
  $fp = fopen($rewrite_word_list, 'a');
  $list[] = $source_word;
  $list = array_merge($list, $rewrite_words);
  fputcsv($fp, $list);
  fclose($fp);
  $_SESSION['message'] = '登録完了しました。';
  header("Location: index.php");
  exit();
}

?>
