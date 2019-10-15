<?php use_helper('I18N') ?>

<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <table>
    <tbody>
      <tr>
        <th><label for="signin_username"><?php echo __('Username or E-Mail', null, 'sf_guard')?></label></th>
        <td>
          <?php if ($form['username']->hasError()): ?>
            <?php echo $form['username']->renderError();?>
          <?php endif;?>
          <?php echo $form['username']->render();?>
        </td>
      </tr>
      <tr>
        <th><label for="signin_password"><?php echo __('Password', null, 'sf_guard')?></label></th>
        <td>
          <?php if ($form['password']->hasError()): ?>
            <?php echo $form['password']->renderError();?>
          <?php endif;?>
          <?php echo $form['password']->render();?>
        </td>
      </tr>

    </tbody>
    <tfoot>
      <tr>
        <td colspan="2">
          <input type="submit" value="<?php echo __('Signin', null, 'sf_guard') ?>" />
          
          <?php $routes = $sf_context->getRouting()->getRoutes() ?>
          <?php if (isset($routes['sf_guard_forgot_password'])): ?>
            <a href="<?php echo url_for('@sf_guard_forgot_password') ?>"><?php echo __('Forgot your password?', null, 'sf_guard') ?></a>
          <?php endif; ?>

          <?php if (isset($routes['sf_guard_register'])): ?>
            &nbsp; <a href="<?php echo url_for('@sf_guard_register') ?>"><?php echo __('Want to register?', null, 'sf_guard') ?></a>
          <?php endif; ?>
        </td>
      </tr>
    </tfoot>
  </table>
  <?php echo $form->renderHiddenFields(); ?>
</form>