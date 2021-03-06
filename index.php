<?php include('template/html_top.php'); ?>  
<?php include('template/header.php'); ?>  

<div class="container">

  <div class="starter-template">
    <?php include('template/session_message.php'); ?>
    <div>
    <h2>文言登録</h2>
    <form method="POST" class="form-horizontal" role="form" action="save_rewrite_word.php">
      <div class="form-group">
        <label for="inputSourceWord" class="col-sm-2 control-label">元の単語</label>
        <div class="col-sm-10">
          <input type='input' name='source_word' class="form-control" id="inputWourceWord" />
        </div>
      </div>
      <div class="form-group">
        <label for="inputRewriteWords" class="col-sm-2 control-label">置き換え単語</label>
        <div class="col-sm-10">
          <input type="input" class="form-control" id="inputRewriteWords" name='rewrite_words'>
          <br>*複数置き換えたい単語がある場合は単語を「,」で区切ってください。*例)「東京,オリンピック」<br>
        </div>
      </div>
     <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">登録</button>
        </div>
      </div>
    </form>
    </div>
    <br><br>
    <hr>
    <div>
    <h2>CSVファイルをダウンロード</h2>
      <input type="button" value="ダウンロード" class="btn btn-primary" onClick="location.href='./download.php'">
    </div>
    <br><br>
    <hr>
    <div>
    <h2>CSVファイルをアップロード</h2>
     <form method="POST" class="form-horizontal" role="form" action="upload.php" enctype="multipart/form-data">
       <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
              <label>
                <input type='file' name='csv'><br>
                <br>*csvファイルは置き換え前文字列,置き換え後文字列のフォーマットにしてください。*例)「東京,オリンピック,ワールドカップ」<br>
              </label>
            </div>
          </div>
        </div>
       <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">アップロード</button>
          </div>
        </div>
    </form>
    </div>
    <br><br>
    <hr>

    <div>
    <h2>置き換え</h2>
    <form method="POST" class="form-horizontal" role="form" action="rewrite.php">
      <div class="form-group">
        <label for="inputRewrite" class="col-sm-2 control-label">元の単語</label>
        <div class="col-sm-10">
          <textarea name='rewrite' class="form-control" rows="3" id='inputRewrite'></textarea><br>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
              <label>
                <input type='checkbox' name='shuffle' value='true'>文でシャッフル<br>
              </label>
            </div>
          </div>
        </div>
       <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-primary">変換</button>
          </div>
        </div>
    </form>
    </div>
    <?php include('template/replaced_sentence.php'); ?>  
  </div>

</div><!-- /.container -->

<?php include('template/html_bottom.php'); ?>  
