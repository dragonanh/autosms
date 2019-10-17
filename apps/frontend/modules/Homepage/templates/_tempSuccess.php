<p style="text-align: center;">Quý khách đã khởi tạo thành công lịch báo bận thông minh
</p><br>
<p><b>Chi tiết:</b></p>

<?php include_partial('form', ['form' => $form]) ?>

<p>Phí sử dụng lịch báo bận : 2000vnđ. Hết hiệu lực, lịch báo bận sẽ tự động hủy</p>
<br>
<div class="bg-success" style="width:100%; text-align: center;color: #FFF;">
  <p>Mã QR code để đăng ký sử dụng lịch báo bận quý khách đã khởi tạo
  </p>
</div>
<div style="text-align: center;">
  <img src="<?php echo $qrCodeImg ?>" style="height: 320px;">
</div>
<div>
  <b>Hướng dẫn sử dụng QR Code để chia sẻ lịch báo bận của
    Quý khách:</b><br> - Mã QR Code giúp thuê bao khác có thể sử dụng chung
  lịch báo bận mà Quý khách vừa tạo ra với nội dung báo bận và thời gian mà Quý khách vừa khởi tạo
  <br>- Khi thuê bao khác quét
  mã QR code, hệ thống sẽ hiển thị thông tin gói cước và yêu cầu xác nhận sẽ sử dụng lịch báo bận do Quý khách vừa khởi tạo
  <br>- Trong trường hợp hệ thống nhận diện được thuê bao (Dịch vụ chỉ cung cấp cho thuê bao Viettel), hệ thống hiển thị yêu cầu xác nhận. Thuê bao chỉ cần xác nhận, hệ thống sẽ đặt lịch báo bận cho thuê bao đó theo đúng nội dung và thời gian Quý khách khởi tạo.
  <br>- Phí dịch vụ: 2000vnđ
  <br>- Kết thúc thời gian báo bận theo thông số đã tạo, hệ thống sẽ tự động hủy hiệu lực của gói báo bận và thông báo cho Thuê bao đã sử dụng qua tin nhắn từ đầu số của dịch vụ.

</div>