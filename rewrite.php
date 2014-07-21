<?php
session_start();
$rewrite_word_list = 'rewrite_word_list.csv';

// バリデーション
if (empty($_POST['rewrite'])) {
  $_SESSION['message'] = '入力してください';
  header("Location: index.php");
  exit();
}
$rewrite = $_POST['rewrite'];
$shuffle = ($_POST['shuffle'] == 'true') ? true: false;

// ファイル有無確認
if (!is_file($rewrite_word_list)) {
  $_SESSION['message'] = 'ファイルが存在しません。';
  header("Location: index.php");
}

//登録単語を配列に書き出す
$registed_words = array();
if (($handle = fopen($rewrite_word_list, "r")) !== FALSE) {
  while (($data = fgetcsv($handle, 1000,",")) !== FALSE) {
    $registed_words[] = $data;
  }
  fclose($handle);
}
// ランダムに置き換え文字抽出
$tmp_words = array_map(function($v) {
  if (count($v) == 2) {
    return array($v[0], $v[1]);
  } else if (count($v) > 2){
    $rand = mt_rand(1, count($v) -1);
    return array($v[0], $v[$rand]);
  } else {
    return array($v[0], $v[0]);
  }
}, $registed_words);
$match_words = array_map(function($v) {
  return '/' . $v[0] . '/';
}, $tmp_words);
$replace_words = array_map(function($v) {
  return $v[1];
}, $tmp_words);

// 置き換えして返す
$rewrited_sentence = preg_replace($match_words, $replace_words, $rewrite);
if (!$rewrited_sentence) {
  $_SESSION['message'] = '置き換えに失敗しました。';
  header("Location: index.php");
  exit();
} else if ($shuffle){
  $_SESSION['message'] = '置き換えに成功しました。';
  $_SESSION['replace_sentence'] = $rewrite;
  $shuffle_sentences = explode("。", $rewrited_sentence);
  $shuffle_sentences = array_filter($shuffle_sentences,'strlen');
  array_walk($shuffle_sentences, function(&$v) {
    $v = $v . '。';
  });
  shuffle($shuffle_sentences);
  $_SESSION['replaced_sentence'] = implode($shuffle_sentences);
  header("Location: index.php");
  exit();
} else {
  $_SESSION['message'] = '置き換えに成功しました。';
  $_SESSION['replace_sentence'] = $rewrite;
  $_SESSION['replaced_sentence'] = $rewrited_sentence;
  header("Location: index.php");
  exit();
}

?>
