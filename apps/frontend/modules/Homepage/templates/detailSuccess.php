<div class="card-body">
  <?php if(empty($error)): ?>
    <?php include_partial('flashes') ?>
    <form method="post" action="">
        <input type="hidden" name="id" value="<?php echo $id ?>">
        <input type="hidden" name="token" value="<?php echo $form->getCSRFToken() ?>">

        <div class="card-text">
            Chào mừng Quý khách tới dịch vụ báo bận thông minh - Auto SMS của Viettel
        </div>
        <div class="card-text">
            Vui lòng nhập các thông tin để tạo gói dịch vụ báo bận thông minh của riêng bạn :
        </div>

        <?php include_partial('form', ['form' => $form]) ?>

        <div class="card-text">
            Phí sử dụng lịch báo bận : 2000vnđ. Hết hiệu lực, lịch báo bận sẽ tự động hủy
        </div>

        <div class="form-group">
            <button class="btn btn-secondary">Đăng ký sử dụng lịch báo bận</button>
        </div>
    </form>
  <?php else: ?>
    <?php echo $error ?>
  <?php endif ?>
</div>