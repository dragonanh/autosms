<?php foreach ($this->configuration->getValue('list.display') as $name => $field): ?>
    [?php slot('sf_admin.current_header') ?]
        <th <?php echo ($name == 'order_no')? ' width="3%"': ''; ?> class="sf_admin_<?php echo strtolower($field->getType()) ?> sf_admin_list_th_<?php echo $name ?>"<?php if ($name == 'is_active') echo ' style="width: 65px"' ?> style="text-align: center">
            [?php echo __('<?php echo $field->getConfig('label', '', true) ?>', array(), '<?php echo $this->getI18nCatalogue() ?>') ?]
        </th>
    [?php end_slot(); ?]
    <?php echo $this->addCredentialCondition("[?php include_slot('sf_admin.current_header') ?]", $field->getConfig()) ?>
<?php endforeach; ?>