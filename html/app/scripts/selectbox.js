console.log('dateupdates')
$(function () {
    //$("#datepickerEnquiry1").datepicker();
    
    $("#datepickerEnquiry").datepicker({
        dateFormat: 'dd-mm-yy',
        //maxDate: 0,
        changeMonth: true,
        changeYear: true,
        yearRange: '-99:-18'
    })
});




$('.js-example-basic-multiple').select2({
    placeholder: "Model Interested / मॉडल इच्छुक हैं",
    allowClear: true,
    maximumSelectionLength: 3
});

$("#products").change(function () {
    if (this.value.length = 1) {
        $('#state').prop("disabled", false);
    }
});



$selectElement = $('#state').select2({
    placeholder: "State / राज्य *",
   // allowClear: true
});


$('.js-example-basic-single').select2();

