<?php if ($listActions = $this->configuration->getValue('list.batch_actions')): ?>
<div class="well pull-left margin-right">
  <div class="btn-group">
    <?php foreach ((array) $listActions as $action => $params): ?>
        <?php
          $attr = ($action == 'batchDelete') ? ' id="batchDelete"' : '';
          $attr .= ' onclick="$(this).addClass(\'clicked\')" ';
        ?>
        <?php echo $this->addCredentialCondition('<button type="submit" class="btn btn-success" name="batch_action" value="'.$action.'"'.$attr.'>[?php echo __(\''.$params['label'].'\', array(), \''.$this->getI18nCatalogue().'\') ?]</button>', $params) ?>
    <?php endforeach; ?>
  </div>
  [?php $form = new BaseForm(); if ($form->isCSRFProtected()): ?]
  <input type="hidden" name="[?php echo $form->getCSRFFieldName() ?]" value="[?php echo $form->getCSRFToken() ?]" />
  [?php endif; ?]
</div>
[?php $message = __('Bạn có chắc muốn xóa không?') ?]
<script type="text/javascript">
  $(document).ready(function () {

    $('form#list-form').submit(function (e) {
      var input = $(".clicked[type=submit]", $(this));
      input.removeClass('clicked');

      if (input.size())
      {
        if ($('.sf_admin_batch_checkbox:checked').size()) {
          if (input.attr('id') == "batchDelete") {
            var ok = confirm('[?php echo $message ?]');
            if (ok) {
              return true;
            }
            else {
              e.preventDefault();
              return false;
            }
          }
        } else {
          alert('[?php echo __('You must at least select one item.');?]');
          e.preventDefault();
          return false;
        }
      }

    });
    return;
  });
</script>
<?php endif; ?>