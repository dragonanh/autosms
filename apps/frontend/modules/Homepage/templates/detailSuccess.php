<?php include_partial('Common/header', ['displayBanner' => false]) ?>
<div class="con_intro pv" id="intro">
    <div class="container">
        <div class="box_intro" style="margin: auto; width: auto !important;">
              <?php if(empty($error)): ?>
                <?php include_partial('flashes') ?>

                <?php if(!empty($isSub)): //truong hop la sub --> thong bao khong cho phep ap dung lich?>
                  <div class="alert alert-warning">
                    <?php echo __('Không áp dụng được lịch báo bận theo giờ do Bạn đang sử dụng gói dịch vụ theo [Ngày/Tuần/Tháng].') ?>
                  </div>
                <?php endif ?>

                <form method="post" action="">
                    <input type="hidden" name="id" value="<?php echo $id ?>">
                    <input type="hidden" name="token" value="<?php echo $form->getCSRFToken() ?>">

                    <!-- <div class="txt text-center">
                        Vui lòng truy cập bằng 3G/4G của Viettel để sử dụng lịch báo bận tự động
                    </div> -->

                    <?php include_partial('form', ['form' => $form, 'page' => 'detail']) ?>

                    <div class="txt" style="text-align: center;">
                        Phí sử dụng lịch báo bận : 0đ. Hết thời gian báo bận, lịch báo bận đã tạo sẽ tự động hủy.
                    </div>

                    <?php if(empty($isSub)): //truong hop ko phai la sub moi hien thi buuton dang ky?>
                        <div class="form-group txt" style="text-align: center;">
                            <button type="submit" style="text-decoration: none;font-size: 116%;" class="btn btn-primary">ĐĂNG KÝ LỊCH BÁO BẬN</button>
                        </div>
                    <?php endif ?>
                </form>
              <?php else: ?>
                <?php echo $error ?>
              <?php endif ?>
        </div>
    </div>
</div>