<?php
function write_csv($file, array $rewrite_lists) {
  $fp = fopen($file, 'w');
  array_walk_recursive($rewrite_lists, function (&$l) {
    $l = mb_convert_encoding($l, "SJIS", "UTF-8");
  });
  foreach($rewrite_lists as $l) {
    fputcsv($fp, $l);
  }
  return fclose($fp);
}

function sjis2utf8(array $lists) {
  array_walk_recursive($lists, function (&$l) {
    $l = mb_convert_encoding($l, "UTF-8", "SJIS");
  });
  return $lists;
}

function utf82sjis(array $lists) {
  array_walk_recursive($lists, function (&$l) {
    $l = mb_convert_encoding($l, "SJIS", "UTF-8");
  });
  return $lists;
}
?>
