<div class="text-center">
    <p class="bg-success txt"
       style="width: 180px;height: 36px;vertical-align: middle; color: white !important; margin: 0 auto;">
        Nội dung báo bận</p>
</div>
<div class="txt text-center"></div>

<div class="form-group text-center">
    <div class="txt text-center">
        <i style="font-style: italic;">(Nội dung báo bận vui lòng sử dụng Tiếng Việt không dấu)</i>
    </div>
    <?php echo $form['content']->render() ?>
    <div class="field-error"><?php echo $form['content']->getError() ?></div>
</div>
<div class="text-center">
    <p class="bg-success txt"
       style="width: 200px;height: 36px;vertical-align: middle; color: white !important; margin: 0 auto;">
        Thời gian báo bận</p>
</div>
<div class="txt text-center"></div>
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
<div class="txt text-center">
    <i style="font-style: italic;">(Thời gian báo bận không quá 08h kể từ thời gian bắt đầu)</i>
</div>