<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
  <?php if ($name == 'order_no'): ?>
      <td class="sf_admin_text sf_admin_list_td_order_no">
        [?php echo $orderNo; ?]
      </td>
  <?php else: ?>

  <?php

    echo $this->addCredentialCondition(sprintf(<<<EOF
<td class="sf_admin_%s sf_admin_list_td_%s" field="%s">[?php echo %s ?]</td>
EOF
, strtolower($field->getType()), $name, $name, $this->renderField($field)), $field->getConfig()) ?>
    <?php endif; ?>
<?php endforeach; ?>