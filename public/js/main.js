window.addEventListener('load', function() {
    FastClick.attach(document.body);
}, false);

$( document ).ready(function() {
    $('#plate input').mask("a99",{placeholder:""});
});

var nextSlide = function(that) {
    $('#main').addClass('scaled');

    $(that).parent().fadeOut(400);

    var nextDiv = '.' + $(that).attr('id');
    $(nextDiv).fadeIn(400);
    $('#main').height($(nextDiv).height());

    setTimeout(function() {
        $('#main').removeClass('scaled');
    }, 800);
}

$('#second').on('click', function() {
    var that = this;

    $.ajax({
        url: "check.php",
        type: "GET",
        dataType: "text",
        data: {code: $('#code').val()},
        timeout: 10000,
        success: function(response) {
            if (response == 'success') {
                nextSlide(that);
            } else {
                // the code has been used
                alert('Sorry. That code is not valid.');
            }
        },
        error: function() {
            alert('Could Not Connect To Server');
        }
    });
});

$('input').on('focus', function() {
    $('.error').hide();
});

$('#third').on('click', function() {
    var counter = 0;
    $('#main-form input').each(function() {
        if($(this).attr('name') == 'email') {
            if ( /^(?:\w+\.?\+?)*\w+@(?:\w+\.)+\w+$/.test($(this).val()) ) {

            } else {
                counter++;
            }
        } else {
            if (!$(this).val()) {
                counter++;
            }
        }
    });

    if (counter === 0) {
        nextSlide(this);
        $('input[name="guess"]').focus();
    } else {
        $('.error').show();
    }
});

$('#submit').on('click', function() {
    var that = this;

    if ($('#guess').val()) {
        var values = {};
        $('input').each( function() {
            values[$(this).attr('name')] = $(this).val();
        });


        $.ajax({
            url: "submit.php",
            type: "POST",
            dataType: "text",
            data: values,
            timeout: 10000,
            success: function(response) {
                if(response == 'success') {
                    $('#main').addClass('scaled');

                    $(that).parent().fadeOut(400);

                    $('#success').fadeIn(400);
                    $('#main').height($('#success').height());

                    setTimeout(function() {
                        $('#main').removeClass('scaled');
                    }, 800);
                } else {
                    console.log(response);
                    alert('Could Not Connect To Server');
                }
            },
            error: function() {

            }
        });
    } else {
        // do nothing!
    }
});
