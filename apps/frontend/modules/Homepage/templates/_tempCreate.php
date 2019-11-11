<form id="formCreateProgram" method="post" action="<?php echo url_for('@ajaxCreateProgram') ?>">
  <?php echo $form->renderHiddenFields() ?>

    <div class="card-body">
        <div class="card-text">
            Chào mừng Quý khách tới dịch vụ báo bận thông minh - Auto SMS của Viettel
        </div>
        <div class="card-text">
            Vui lòng nhập các thông tin để tạo gói dịch vụ báo bận thông minh của riêng bạn :
        </div>

        <?php include_partial('form', ['form' => $form]) ?>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-6 col-sm-6"><button type="submit" class="btn btn-success">Khởi tạo</button></div>
                <div class="col-md-6 col-sm-6"><button type="reset" class="btn btn-secondary">Nhập lại</button></div>
            </div>
        </div>

    </div>
</form>
<div id="dtBox"></div>