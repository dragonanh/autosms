<?php include_partial('Common/header', ['displayBanner' => true]) ?>
<div class="con_intro pv" id="intro">
    <div class="container">
        <div class="box_intro" style="margin: auto; width: auto !important;">
              <?php if(empty($error)): ?>
                <?php include_partial('flashes') ?>
                <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="token" value="<?php echo $form->getCSRFToken() ?>">

                    <div class="txt text-center">
                        Vui lòng truy cập bằng 3G/4G của Viettel để sử dụng lịch báo bận tự động
                    </div>

                    <?php include_partial('form', ['form' => $form, 'page' => 'detail']) ?>

                    <div class="txt" style="text-align: center;">
                        Phí sử dụng lịch báo bận : 0đ. Hết thời gian báo bận, lịch báo bận đã tạo sẽ tự động hủy.
                    </div>

                    <div class="form-group txt" style="text-align: center;">
                        <button type="submit" style="text-decoration: none;font-size: 116%;" class="btn btn-primary">ĐĂNG KÝ LỊCH BÁO BẬN</button>
                    </div>
                </form>
              <?php else: ?>
                <?php echo $error ?>
              <?php endif ?>
        </div>
    </div>
</div>