<?php if ($sf_user->hasFlash('success')): ?>
  <div class="alert alert-success"><?php echo __($sf_user->getFlash('success'), array(), 'messages'); ?></div>
  <?php $sf_user->setAttribute('success', 'true', 'symfony/user/sfUser/flash/remove') ?>
<?php endif; ?>

<?php if ($sf_user->hasFlash('error')): ?>
  <div class="alert alert-warning"><?php echo __($sf_user->getFlash('error'), array(), 'messages'); ?></div>
  <?php $sf_user->setAttribute('error', 'true', 'symfony/user/sfUser/flash/remove') ?>
<?php endif; ?>