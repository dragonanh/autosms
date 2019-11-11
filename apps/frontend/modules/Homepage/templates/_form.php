<div class="form-group">
    <a href="javascript:void(0)" class="btn btn-success">Nội dung báo bận</a>
</div>
<div class="card-text">
    Nội dung báo bận vui lòng sử dụng Tiếng Việt không dấu
</div>
<div class="form-group">
    <?php echo $form['content']->render() ?>
    <div class="field-error"><?php echo $form['content']->getError() ?></div>
</div>
<div class="form-group">
    <a href="javascript:void(0)" class="btn btn-success">Thời gian báo bận</a>
</div>
<div class="card-text">
    Thời gian báo bận không quá 08h kể từ thời gian bắt đầu
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Từ</label>
    <div class="col-sm-10">
        <?php echo $form['start_time']->render() ?>
        <div class="field-error"><?php echo $form['start_time']->getError() ?></div>
    </div>
</div>
<div class="form-group row">
    <label class="col-sm-2 col-form-label">Đến</label>
    <div class="col-sm-10">
        <?php echo $form['end_time']->render() ?>
        <div class="field-error"><?php echo $form['end_time']->getError() ?></div>
    </div>
</div>