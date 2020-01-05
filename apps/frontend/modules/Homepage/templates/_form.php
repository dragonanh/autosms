<div style="text-align: center;">
    <p class="txt" style="vertical-align: middle;margin: 0 auto;font-weight: bold;">
        Nội dung báo bận
    </p>
</div>

<div class="form-group text-center">
    <?php if(!isset($page) || $page != 'detail'): ?>
        <div class="clearable-input">
            <?php echo $form['content']->render() ?>
            <span data-clear-input>&times;</span>
        </div>
    <?php else: ?>
        <?php echo $form['content']->render() ?>
    <?php endif ?>
    <datalist id="suggestions">
        <option value='Hien tai toi dang ban, toi se goi lai sau "hh:mm dd/mm". Cam on!'>
        <option value='Hien tai toi dang hop, toi se goi lai sau "hh:mm dd/mm". Cam on!'>
        <option value='Hien tai toi dang hoc, toi se goi lai sau "hh:mm dd/mm". Cam on!'>
        <option value='Hien tai toi dang lai xe, toi se goi lai sau "hh:mm dd/mm". Cam on!'>
    </datalist>
    <div class="field-error"><?php echo $form['content']->getError() ?></div>
    <div class="txt text-center">
        <i style="font-style: italic;">(Nội dung báo bận là ký tự không dấu)</i>
    </div>
</div>

<div style="text-align: center;">
        <p class="txt" style="vertical-align: middle; margin: 0 auto; font-weight: bold;">
            Thời gian báo bận
        </p>
      <?php if(!isset($page) || $page != 'detail'): ?>
        <p>
            <i class="txt" style="font-style: italic; text-align: center;">(Khung nhập thời gian bên dưới)</i>
        </p>
      <?php endif ?>
</div>

<div class="form-group row text-center">
    <label class="col-sm-2 txt col-form-label">Từ</label>
    <div class="col-sm-10 text-center">
        <?php echo $form['start_time']->render() ?>
        <div class="field-error text-left"><?php echo $form['start_time']->getError() ?></div>
    </div>
</div>
<div class="form-group row text-center">
    <label class="col-sm-2 txt col-form-label">Đến</label>
    <div class="col-sm-10 text-center">
        <?php echo $form['end_time']->render() ?>
        <div class="field-error text-left"><?php echo $form['end_time']->getError() ?></div>
    </div>
</div>
<?php if(!isset($page) || $page != 'detail'): ?>
<div class="txt text-center">
    <i style="font-style: italic;">(Thời gian báo bận không quá 08h kể từ thời gian bắt đầu)</i>
</div>
<?php endif ?>