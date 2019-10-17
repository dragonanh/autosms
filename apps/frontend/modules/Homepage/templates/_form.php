<div class="bg-success" style="width:25%; text-align: center;color: #FFF;">
  <p>Nội dung báo bận</p>
</div>
<br>
<p>Nội dung báo bận vui lòng sử dụng Tiếng Việt không dấu</p>
<div>
  <?php echo $form['content']->render() ?>
  <div class="field-error"><?php echo $form['content']->getError() ?></div>
</div>
<br>
<div class="bg-success" style="width:25%;text-align: center;color: #FFF;">
  <p>Thời gian báo bận</p>
</div>
<br>
<p>Thời gian báo bận không quá 08h kể từ thời gian bắt đầu</p>
<table style="width: 100%;">
  <tr style="height: 80px;">
    <td style="width: 10%">
      Từ
    </td>
    <td style="width: 90%;">
      <?php echo $form['start_time']->render() ?>
      <div class="field-error"><?php echo $form['start_time']->getError() ?></div>
    </td>
  </tr>
  <tr>
    <td>
      Đến
    </td>
    <td>
      <?php echo $form['end_time']->render() ?>
      <div class="field-error"><?php echo $form['end_time']->getError() ?></div>
    </td>
  </tr>
</table>