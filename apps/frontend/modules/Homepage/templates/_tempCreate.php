<form method="post" action="<?php echo url_for('@ajaxCreateProgram') ?>">
  <p style="text-align: center;">Chào mừng Quý khách tới dịch vụ báo bận thông minh – Auto SMS của Viettel
  </p><br>
  <p>Vui lòng nhập các thông tin để tạo gói dịch vụ báo bận thông minh của riêng bạn:</p>
  <?php include_partial('form', ['form' => $form]) ?>
  <br>
  <div style="padding-left: 45%;">
    <button class="btn btn-success" type="submit">KHỞI TẠO</button>
    <button class="btn btn-secondary" type="reset">NHẬP LẠI</button>
  </div>
</form>