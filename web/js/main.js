$(document).on('focus', 'input[type=text]', function () {
  $(this).removeAttr('placeholder')
}).on('blur', 'input[type=text]', function () {
  $(this).attr('placeholder', $(this).attr('data-placeholder'))
});

var isSubmit = 0;
$(document).on('submit', '#formCreateProgram', function (e) {
  e.preventDefault();
  createProgram($(this));
});

function createProgram(obj) {
  if(isSubmit === 0) {
    isSubmit = 1;
    var button = obj.find('button[type=submit]');
    button
      .addClass('btn-disabled')
      .before('<img style="height: auto" id="loading" src="/images/loading20x20.gif" >');
    $.ajax({
      url: obj.attr('action'),
      type: 'post',
      data: obj.serialize(),
      success: function (response) {
        isSubmit = 0;
        obj.removeClass('btn-disabled');
        $('#loading').remove();
        $('.mocha-content').html(response.template);
        initDatetimepicker();
      },
      error: function (request, status, err) {
        isSubmit = 0;
        button.removeClass('btn-disabled');
        $('#loading').remove();
      }
    });
  }
}