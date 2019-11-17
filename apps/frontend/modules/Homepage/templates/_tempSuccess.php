<div class="con_intro pv" id="intro">
    <div class="container">
        <div class="box_intro" style="margin: auto; width: auto !important;">
            <div class="txt" style="text-align: center;">
                Quý khách đã khởi tạo thành công lịch báo bận thông minh
            </div>
            <div class="txt" style="text-align: center;">
                Chi tiết
            </div>

            <?php include_partial('form', ['form' => $form]) ?>

            <div class="txt" style="text-align: center;">
                Phí sử dụng lịch báo bận phí DV: 0đ. Hết hiệu lực, lịch báo bận sẽ tự động hủy
            </div>

            <div class="form-group txt" style="text-align: center;">
                <a href="#" style="text-decoration: none;font-size: 116%;" class="btn btn-primary">Mã QR
                    code để đăng ký sử dụng lịch báo bận quý khách đã khởi tạo</a>
            </div>

            <div id="qrcode" class="txt text-center">
                <img style="margin: 0 auto; text-align: center; height: 200px" id="qrcode_image" src="<?php echo $qrCodeImg ?>" alt="">
            </div>

            <div class="text-center">
                <a href="<?php echo url_for('downloadQrcode', ['id' => $id]) ?>"> Tải QRCode </a>
            </div>
            <div class="txt" style="text-align: center;">
                Hướng dẫn sử dụng QR Code để chia sẻ lịch báo bận của Quý khách:
            </div>
            <div class="txt" style="text-align: center;">
                - Mã QR Code giúp thuê bao khác có thể sử dụng chung
                lịch báo bận mà Quý khách vừa tạo ra với nội dung báo
                bận và thời gian mà Quý khách vừa khởi tạo
            </div>
            <div class="txt" style="text-align: center;">
                - Khi thuê bao khác quét mã QR code, hệ thống sẽ hiển thị
                thông tin gói cước và yêu cầu xác nhận sẽ sử dụng lịch báo
                bận do Quý khách vừa khởi tạo
            </div>
            <div class="txt" style="text-align: center;">
                - Trong trường hợp hệ thống nhận diện được thuê bao
                (Dịch vụ chỉ cung cấp cho thuê bao Viettel), hệ thống hiển
                thị yêu cầu xác nhận. Thuê bao chỉ cần xác nhận, hệ thống
                sẽ đặt lịch báo bận cho thuê bao đó theo đúng nội dung và
                thời gian Quý khách khởi tạo.
            </div>
            <div class="txt" style="text-align: center;">
                - Phí dịch vụ: 0đ
            </div>
            <div class="txt" style="text-align: center;">
                - Kết thúc thời gian báo bận theo thông số đã tạo, hệ thống
                sẽ tự động hủy hiệu lực của gói báo bận và thông báo cho
                Thuê bao đã sử dụng qua tin nhắn từ đầu số của dịch vụ
            </div>

            <div class="text-center">
                <a class="btn btn-success" href="<?php echo url_for('@createProgram') ?>" style="text-decoration: none">TẠO LỊCH MỚI</a>
            </div>
        </div>
    </div>
</div>


