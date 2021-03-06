<div class="con_intro pv" id="intro">
    <div class="container">
        <form class="box_intro" style="margin: auto; width: auto !important;">
            <div class="txt" style="text-align: center;">
                Quý khách đã khởi tạo thành công lịch báo bận thông minh
            </div>

            <?php include_partial('tempDetail', [
                    'content' => $content, 'startTime' => $startTime, 'endTime' => $endTime
            ]) ?>
        </form>

        <div class="box_intro" style="margin: auto; width: auto !important;">
            <div class="txt" style="text-align: center;">
                Phí sử dụng lịch báo bận phí DV: 0đ. Hết hiệu lực, lịch báo bận sẽ tự động hủy
            </div>

            <div id="qrcode" class="txt text-center">
                <a href="<?php echo url_for('qrcode', ['id' => $id]) ?>">
                    <img style="margin: 0 auto; text-align: center; height: 200px" id="qrcode_image" src="<?php echo $qrCodeImg ?>" alt="">
                </a>
            </div>

            <div class="txt" style="text-align: center;">
                <!--Khi click đường link thì sẽ chuyển sang trang qrcode2-->
                <b>Link chia sẻ: <a href="<?php echo url_for('qrcode', ['id' => $id]) ?>"><?php echo url_for('qrcode', ['id' => $id], true) ?></a></b>
            </div>

            <div class="txt" style="text-align: center;">
                <b>Hướng dẫn sử dụng QR Code:</b>
            </div>
            <div class="txt" style="text-align: center;">
                - Mã QR Code giúp thuê bao khác có thể sử dụng chung lịch báo bận mà Quý khách vừa tạo ra
                với nội dung báo bận và thời gian mà Quý khách vừa khởi tạo
            </div>
            <div class="txt" style="text-align: center;">
                - Khi thuê bao khác quét mã QR code, hệ thống sẽ hiển thị thông tin gói cước và yêu cầu xác
                nhận sẽ sử dụng lịch báo bận do Quý khách vừa khởi tạo
            </div>
            <div class="txt" style="text-align: center;">
                - Trong trường hợp hệ thống nhận diện được thuê bao (Dịch vụ chỉ cung cấp cho thuê bao
                Viettel), hệ thống hiển thị yêu cầu xác nhận. Thuê bao chỉ cần xác nhận, hệ thống sẽ đặt
                lịch báo bận cho thuê bao đó theo đúng nội dung và thời gian Quý khách khởi tạo.
            </div>
            <div class="txt" style="text-align: center;">
                - Kết thúc thời gian báo bận theo thông số đã tạo, hệ thống sẽ tự động hủy hiệu lực của gói
                báo bận và thông báo cho Thuê bao đã sử dụng qua tin nhắn từ đầu số của dịch vụ
            </div>
            <div class="txt" style="text-align: center;">
                - Phí dịch vụ: 0đ
            </div>
        </div>
    </div>
</div>


