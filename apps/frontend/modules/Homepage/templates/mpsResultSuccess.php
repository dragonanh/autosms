<?php include_partial('Common/header', ['displayBanner' => true]) ?>
<div id="content">
    <div class="con_intro pv" id="intro">
        <div class="container">
            <div class="box_intro" style="margin: auto; width: auto !important;">
                <?php if($errorCode == 1): ?>
                    <div class="txt" style="text-align: center;vertical-align: middle;">
                        <?php echo $message ?>
                    </div>
                <?php else: ?>
                    <div class="txt" style="text-align: center;">
                        <b>Cảm ơn Quý khách đã sử dụng dịch vụ Báo bận thông minh của Viettel với gói dịch vụ báo
                            bận theo thời gian Lịch báo bận của Quý khách sẽ có hiệu lực</b>
                    </div>
                    <?php if($schedule): ?>
                        <div class="form-group row" style="text-align: center;">
                            <label class="col-sm-2 txt col-form-label">Từ</label>
                            <div class="col-sm-10 txt" style="text-align: center;">
                                <b><?php echo date('d-m-Y H:i:s', strtotime($schedule['start_time'])) ?></b>
                            </div>
                        </div>
                        <div class="form-group row" style="text-align: center;">
                            <label class="col-sm-2 txt col-form-label">Đến</label>
                            <div class="col-sm-10 txt" style="text-align: center;">
                                <b><?php echo date('d-m-Y H:i:s', strtotime($schedule['end_time'])) ?></b>
                            </div>
                        </div>
                        <div class="txt" style="text-align: center;">
                            <b>Với nội dung bận là:</b>
                        </div>
                        <div class="form-group txt" style="text-align: center; ">
                            <b><?php echo $schedule['content'] ?></b>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            </div>
        </div>
    </div>
</div>