<?php if (!empty($_SESSION['message'])): ?>
<div class="alert alert-warning alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<?php $message = $_SESSION['message']; ?>
<?php echo $message; ?>
<?php unset($_SESSION['message']); ?>
</div>
<?php endif; ?>
