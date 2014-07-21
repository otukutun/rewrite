<?php include('template/html_top.php'); ?>  
<?php include('template/header.php'); ?>  

<div class="container">

  <div class="starter-template">
    <?php include('template/session_message.php'); ?>
    <h1>置き換えツール</h1>
    <div>
    <h2>文言登録</h2>
    <form method="POST" action="save_rewrite_word.php">
      元の単語:<input type='input' name='source_word' /><br>
      置き換え単語:<input type='input' name='rewrite_words' /><br>
      *複数置き換えたい単語がある場合は単語を「,」で区切ってください。<br>
      *例)「東京,オリンピック」<br>
      <input type='submit' value='登録'>
    </form>
    </div>
    <div>
    <h2>置き換え</h2>
  
    <form method="POST" action="rewrite.php">
      <textarea name='rewrite' cols='40' rowws='4'></textarea><br>
      <input type='checkbox' name='shuffle' value='true'>文でシャッフル<br>
      <input type='submit' value='変換'>
    </form>
    </div>
    <?php include('template/replaced_sentence.php'); ?>  
  </div>

</div><!-- /.container -->

<?php include('template/html_bottom.php'); ?>  
