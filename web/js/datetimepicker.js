$(document).ready(function()
{

  initDatetimepicker();

});

function initDatetimepicker() {
  // $("#dtBox").DateTimePicker({
  //   dateTimeFormat: 'dd-MM-yyyy HH:mm:ss',
  //   // shortDayNames: ["Chủ nhật", "Thứ 2", "Thứ 3", "Thứ 4", "Thứ 5", "Thứ 6", "Thứ 7"]
  //   titleContentDateTime: 'Chọn thời gian',
  //   setButtonContent: 'Lưu',
  //   clearButtonContent: 'Xoá'
  // });
  // $('.datepicker-here').data('datepicker');
  // $('.datepicker-here').datepicker({
  //   language: 'en',
  //   timepicker: true,
  //   autoClose: true
  // });

  $.datetimepicker.setLocale("vi");
  $(".datepicker-here").datetimepicker({
    step: 15,
    format:'d-m-Y H:i'
  });
}