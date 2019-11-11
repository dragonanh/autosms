<div class="card-body">
    <div class="card-text">
        Quý khách đã khởi tạo thành công lịch báo bận thông minh
    </div>
    <div class="card-text" style="font-weight: bold">
        Chi tiết:
    </div>

    <?php include_partial('form', ['form' => $form]) ?>

    <div class="card-text">
        Phí sử dụng lịch báo bận : 2000vnđ. Hết hiệu lực, lịch báo bận sẽ tự động hủy
    </div>

    <a href="javascript:void(0)" class="btn btn-success">Mã QR code để đăng ký sử dụng lịch báo bận quý khách đã khởi tạo</a>
</div>

<div class="form-group row">
    <img style="margin: 0 auto; text-align: center; height: 200px" src="<?php echo $qrCodeImg ?>" alt="">
</div>

<div class="card-text" style="font-weight: bold;">
    Hướng dẫn sử dụng QR Code để chia sẻ lịch báo bận của Quý khách:
</div>

<div class="card-text" style="text-align: justify; padding: 10px">
    - Mã QR Code giúp thuê bao khác có thể sử dụng chung
    lịch báo bận mà Quý khách vừa tạo ra với nội dung báo
    bận và thời gian mà Quý khách vừa khởi tạo
</div>

<div class="card-text" style="text-align: justify; padding: 10px">
    - Khi thuê bao khác quét mã QR code, hệ thống sẽ hiển thị
    thông tin gói cước và yêu cầu xác nhận sẽ sử dụng lịch báo
    bận do Quý khách vừa khởi tạo
</div>

<div class="card-text" style="text-align: justify; padding: 10px">
    - Trong trường hợp hệ thống nhận diện được thuê bao
    (Dịch vụ chỉ cung cấp cho thuê bao Viettel), hệ thống hiển
    thị yêu cầu xác nhận. Thuê bao chỉ cần xác nhận, hệ thống
    sẽ đặt lịch báo bận cho thuê bao đó theo đúng nội dung và
    thời gian Quý khách khởi tạo.
</div>

<div class="card-text" style="text-align: justify; padding: 10px">
    - Phí dịch vụ: 2000vnđ
</div>

<div class="card-text" style="text-align: justify; padding: 10px">
    - Kết thúc thời gian báo bận theo thông số đã tạo, hệ thống
    sẽ tự động hủy hiệu lực của gói báo bận và thông báo cho
    Thuê bao đã sử dụng qua tin nhắn từ đầu số của dịch vụ
</div>

<div class="text-center">
    <a class="btn btn-success" href="<?php echo url_for('@createProgram') ?>">TẠO LỊCH MỚI</a>
</div>