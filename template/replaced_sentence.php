<?php if (!empty($_SESSION['replaced_sentence'])): ?>
<div class="jumbotron">
<?php $replace_sentence = $_SESSION['replace_sentence']; ?>
<?php $replaced_sentence = $_SESSION['replaced_sentence']; ?>
置き換え前:<?php echo $replace_sentence; ?><br><br>
置き換え後:<?php echo $replaced_sentence; ?><br>
<?php unset($_SESSION['replaced_sentence']); ?>
<?php unset($_SESSION['replace_sentence']); ?>
</div>
<?php endif; ?>
