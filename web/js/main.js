$(document).ready(function() {

  $('.slider_video').slick({
    dots: false,
    slidesToShow: 3,
    slidesToScroll: 1,
    autoplay: true,
    speed: 2200,
    autoplaySpeed: 5000,
    prevArrow:'<div type="button" class="slick-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div>',
    nextArrow:'<div type="button" class="slick-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>',
    responsive: [
      {
        breakpoint: 600,
        settings: {
          slidesToShow: 2,
          slidesToScroll: 2
        }
      },
      {
        breakpoint: 480,
        settings: {
          slidesToShow: 1,
          slidesToScroll: 1
        }
      }
    ]
  });

  if($(window).width() < 768){
    $( ".pv" ).each(function( index ) {
      var title=$(this).find('.title');
      var img=$(this).find('.img');
      img.insertAfter(title);
    });
  }

  $("a").on('click', function(event) {
    var offset = 0;
    if($(window).width() > 768){
      var x = 0;
    }else{
      var x = 70;
    }
    // Make sure this.hash has a value before overriding default behavior
    if (this.hash !== "") {
      // Prevent default anchor click behavior
      event.preventDefault();

      // Store hash
      var hash = this.hash;

      // Using jQuery's animate() method to add smooth page scroll
      // The optional number (800) specifies the number of milliseconds it takes to scroll to the specified area
      $('html, body').animate({
        scrollTop: $(hash).offset().top - x
      }, 800, function(){

        // Add hash (#) to URL when done scrolling (default click behavior)
        window.location.hash = hash;
      });
    } // End if
  });
});

$(document).on('focus', 'input[type=text]', function () {
  $(this).removeAttr('placeholder')
}).on('blur', 'input[type=text]', function () {
  $(this).attr('placeholder', $(this).attr('data-placeholder'))
});

Array.prototype.forEach.call(document.querySelectorAll('.clearable-input'), function(el) {
  var input = el.querySelector('input');

  conditionallyHideClearIcon();
  input.addEventListener('input', conditionallyHideClearIcon);
  el.querySelector('[data-clear-input]').addEventListener('click', function(e) {
    input.value = '';
    conditionallyHideClearIcon();
  });

  function conditionallyHideClearIcon(e) {
    var target = (e && e.target) || input;
    target.nextElementSibling.style.display = target.value ? 'block' : 'none';
  }
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
        $('.section-create').html(response.template);
        initDatetimepicker();
        var scrollId;
        if(response.errorCode === 0)
          scrollId = $('#qrcode');
        else
          scrollId = $('.field-error:first').parents('.form-group');
        $('html, body').animate({
          scrollTop: scrollId.offset().top - 70
        }, 800);
      },
      error: function (request, status, err) {
        isSubmit = 0;
        button.removeClass('btn-disabled');
        $('#loading').remove();
      }
    });
  }
}