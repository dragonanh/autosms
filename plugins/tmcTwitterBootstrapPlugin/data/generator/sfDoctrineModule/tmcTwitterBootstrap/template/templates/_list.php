<div class="sf_admin_list">
    <table class="datatable table table-bordered table-striped" id="table_<?php echo sfInflector::underscore($this->getModuleName()) ?>" style="margin-top: 5px !important;">
      <thead>
          <tr>
          <?php if ($this->configuration->getValue('list.batch_actions')): ?>
              <th id="sf_admin_list_batch_actions" style="width: 10px"><input id="sf_admin_list_batch_checkbox" type="checkbox" onclick="checkAll();" /></th>
          <?php endif; ?>

          [?php include_partial('<?php echo $this->getModuleName() ?>/list_th_<?php echo $this->configuration->getValue('list.layout') ?>', array('sort' => $sort)) ?]

          <?php if ($this->configuration->getValue('list.object_actions')): ?>
              <th id="sf_admin_list_th_actions" style="width: 170px;text-align: center">[?php echo __('Actions', array(), 'tmcTwitterBootstrapPlugin') ?]</th>
          <?php endif; ?>
          </tr>
      </thead>
      [?php if (!$pager->getNbResults()): ?]
      <tbody>
        <tr>
          <th colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) ?>">
            [?php echo __('No results', array(), 'tmcTwitterBootstrapPlugin') ?]
          </th>
        </tr>
      </tbody>
      [?php else: ?]
        [?php $results = $pager->getResults()->getRawValue() ?]
        [?php $modelname = get_class($results[0]) ?]
      <tbody>
      <!--      start - thongnq1 - 06/05/2013 - fix loi STT cua ban ghi khi thuc hien thao tac xoa-->
      [?php $currentPage  = $sf_request->getParameter('current_page', 0)?]
      [?php $currentPage = ($currentPage) ? $currentPage : $sf_request->getParameter('page', 1) ; ?]
      <!--   End thongnq1   -->
        [?php foreach ($results as $i => $<?php echo $this->getSingularName() ?>): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?]

          <tr class="sf_admin_row [?php echo $odd ?] {pk: [?php echo $<?php echo $this->getSingularName() ?>->getId() ?]}" rel="[?php echo $<?php echo $this->getSingularName() ?>->getId() ?]">

            <?php if ($this->configuration->getValue('list.batch_actions')): ?>
                [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_batch_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
            <?php endif; ?>
                [?php $orderNo = ($i) + ($currentPage - 1)*$pager->getMaxPerPage(); ?]
                [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_<?php echo $this->configuration->getValue('list.layout') ?>', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'orderNo' => $orderNo)) ?]
            <?php if ($this->configuration->getValue('list.object_actions')): ?>
                [?php include_partial('<?php echo $this->getModuleName() ?>/list_td_actions', array('<?php echo $this->getSingularName() ?>' => $<?php echo $this->getSingularName() ?>, 'helper' => $helper)) ?]
            <?php endif; ?>
          </tr>
        [?php endforeach; ?]
      </tbody>
      <tfoot>
        <tr>
          <th colspan="<?php echo count($this->configuration->getValue('list.display')) + ($this->configuration->getValue('list.object_actions') ? 1 : 0) + ($this->configuration->getValue('list.batch_actions') ? 1 : 0) ?>">
            [?php if ($pager->haveToPaginate()): ?]
            <div style="position: relative; width: auto; float:right">[?php include_partial('<?php echo $this->getModuleName() ?>/pagination', array('pager' => $pager)) ?]</div>
            [?php slot('pagination_extra') ?]
              [?php echo __('(page %%page%%/%%nb_pages%%)', array('%%page%%' => $pager->getPage(), '%%nb_pages%%' => $pager->getLastPage()), 'tmcTwitterBootstrapPlugin') ?]
            [?php end_slot() ?]
            [?php endif; ?]
            <div style="float: left;font-weight: bold;line-height: 34px;margin-left: 10px;position: relative;width: auto;">
                [?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $pager->getNbResults()), $pager->getNbResults(), 'tmcTwitterBootstrapPlugin') ?]
                [?php include_slot('pagination_extra') ?]
            </div>
          </th>
        </tr>
      </tfoot>
      [?php endif; ?]
    </table>
</div>
<script type="text/javascript">
/* <![CDATA[ */
$(function(){

// add multiple select / deselect functionality
    $("#sf_admin_list_batch_checkbox").click(function () {
        $('.sf_admin_batch_checkbox').attr('checked', this.checked);
    });

// if all checkbox are selected, check the selectall checkbox
// and viceversa
    $(".sf_admin_batch_checkbox").click(function(){

        if($(".sf_admin_batch_checkbox").length == $(".sf_admin_batch_checkbox:checked").length) {
            $("#sf_admin_list_batch_checkbox").attr("checked", "checked");
        } else {
            $("#sf_admin_list_batch_checkbox").removeAttr("checked");
        }

    });
});

/* ]]> */
</script>