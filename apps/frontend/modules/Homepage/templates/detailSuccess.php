<div id="body">
  <div class="mocha-content" style="font-size:30px; line-height: 50px;font-family:Helve;">
      <?php if(empty($error)): ?>
          <form method="post" action="">
              <input type="hidden" name="id" value="<?php echo $id ?>">
              <input type="hidden" name="token" value="<?php echo $form->getCSRFToken() ?>">
              <p style="text-align: center;">Cám ơn Quý khách đã quan tâm tới dịch vụ Báo bận thông minh của Viettel</p><br>
              <p><b>Quý khách đang xem thông tin về lịch báo bận có hiệu lực theo thời gian với thông tin chi tiết như sau:</b></p><br>
              <p><b>Chi tiết:</b></p>

              <?php include_partial('form', ['form' => $form]) ?>

              <p>Phí sử dụng lịch báo bận : 2000vnđ. Hết hiệu lực, lịch báo bận sẽ tự động hủy</p>
              <br>
              <button style="width: 20%; text-align: center; margin-left: 40%;" class="btn btn-success">ĐĂNG KÝ SỬ DỤNG LỊCH BÁO BẬN</button>
          </form>
      <?php else: ?>
            <?php echo $error ?>
      <?php endif ?>
  </div>
</div>