<div class="con_intro pv" id="intro">
    <div class="container">
        <div class="box_intro" style="margin: auto; width: auto !important;">
              <?php if(empty($error)): ?>
                <?php include_partial('flashes') ?>
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="token" value="<?php echo $form->getCSRFToken() ?>">

                    <div class="txt text-center">
                        Chào mừng Quý khách tới dịch vụ báo bận thông minh - Auto SMS của Viettel
                        Vui lòng nhập các thông tin để tạo gói dịch vụ báo bận thông minh của riêng bạn :
                    </div>

                    <?php include_partial('form', ['form' => $form]) ?>

                    <div class="txt" style="text-align: center;">
                        Phí sử dụng lịch báo bận : 0đ. Hết hiệu lực, lịch báo bận sẽ tự động hủy
                    </div>

                    <div class="form-group txt" style="text-align: center;">
                        <button type="submit" style="text-decoration: none;font-size: 116%;" class="btn btn-primary">Đăng ký sử dụng lịch báo bận</button>
                    </div>
                </form>
              <?php else: ?>
                <?php echo $error ?>
              <?php endif ?>
        </div>
    </div>
</div>