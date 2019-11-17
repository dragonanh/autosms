<form id="formCreateProgram" method="post" action="<?php echo url_for('@ajaxCreateProgram') ?>">
  <?php echo $form->renderHiddenFields() ?>

    <div class="con_intro pv" id="intro">
        <div class="container">
            <div class="box_intro" style="margin: auto; width: auto !important;">
                <div class="txt text-center">
                    Chào mừng Quý khách tới dịch vụ báo bận thông minh - Auto SMS của Viettel
                    Vui lòng nhập các thông tin để tạo gói dịch vụ báo bận thông minh của riêng bạn :
                </div>

                <?php include_partial('form', ['form' => $form]) ?>

                <div class="form-group txt" style="text-align: center;">
                    <button type="submit" style="text-decoration: none;font-size: 116%;" class="btn btn-primary">Khởi
                        tạo</button>
                    <button type="reset" style="text-decoration: none;font-size: 116%; margin-left: 20%;"
                       class="btn btn-danger">Nhập lại</button>
                </div>
            </div>
        </div>
    </div>
</form>
<div id="dtBox"></div>