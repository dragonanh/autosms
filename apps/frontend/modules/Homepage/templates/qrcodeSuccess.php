<?php include_partial('Common/header', ['displayBanner' => false]) ?>

<div id="content">
    <!-- box intro -->

    <div class="con_intro pv" id="intro">
        <div class="container">
            <form class="box_intro" style="margin: auto; width: auto !important;">
                <?php if(!$error): ?>
                    <?php include_partial('tempDetail', [
                        'content' => $content, 'startTime' => $startTime, 'endTime' => $endTime
                    ]) ?>

                    <div class="txt" style="text-align: center;">
                        <img style="margin: 0 auto; text-align: center; height: 200px" id="qrcode_image" src="<?php echo $qrCodeImg ?>" alt="">
                    </div>
                    <div class="row sharebutton" style="justify-content: center">
                        <div id="share">
                            <!--Nút Share mã QR-->
                            <a class="click" href="<?php echo $urlShare ?>" target="_blank">
                                <i class="fa fa-share-alt-square"></i> Share
                            </a>
                        </div>
                        <div id="save">
                            <!--Nút Lưu mã QR-->
                            <a class="click1" href="<?php echo url_for('downloadQrcode', ['id' => $id]) ?>" target="_blank">
                                <i class="fa fa-download"></i> Save
                            </a>
                        </div>
                        <div id="print">
                            <!--Nút In mã QR-->
                            <a class="click2" href="" target="_blank">
                                <i class="fa fa-print"></i> In
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p><?php echo $error ?></p>
                <?php endif ?>
            </form>
        </div>
    </div>
</div>