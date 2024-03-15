function show_album() {
    $('#dialog').fadeIn();

    var popMargTop = ($('#dialog').height()) / 2;
    var popMargLeft = ($('#dialog').width()) / 2;

    $('#dialog').css({
        'margin-top': -popMargTop,
        'margin-left': -popMargLeft
    });

    $('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
    $('#fade').css({
        'filter': 'alpha(opacity=80)'
    }).fadeIn();
}

function hide_album() {
    $('#fade , #dialog').fadeOut(function () {
        $('#fade').remove(); //fade them both out
    });
    return false;
}

function show_album_new() {
    $('#dialog_new').fadeIn();

    var popMargTop = ($('#dialog_new').height()) / 2;
    var popMargLeft = ($('#dialog_new').width()) / 2;

    $('#dialog_new').css({
        'margin-top': -popMargTop,
        'margin-left': -popMargLeft
    });

    $('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
    $('#fade').css({
        'filter': 'alpha(opacity=80)'
    }).fadeIn();
}

function hide_album_new() {
    $('#fade , #dialog_new').fadeOut(function () {
        $('#fade').remove(); //fade them both out
    });
    return false;
}

//Call back Function
function abc() {
    $('#dialog').css('display', 'none');
    document.getElementById('dialog').className = 'popupBox2';
}
jQuery(document).ready(function () {
    setTimeout('abc()', 10);
});
$(document).ready(function () {
    $('.dropdown-mul-2').dropdown({
        limitCount: 3,
        //multipleMode: 'label',
        minCountErrorMessage: 'dfdsfsaf',
        limitCountErrorMessage: 'Up to 3 selection maximum',
        searchable: false
    });
    $('.close-dropdown').click(function(){
        $('.dropdown-mul-2').removeClass('active');
    })
});
