$("#addRow").click(function () {
    console.log('AddRow')
    var html = $("#clone").clone()
    $('#multiPieza').append(html);
    $("#clone").show()
    console.log($(".clone").length)
});

// remove row
$(document).on('click', '#removeRow', function () {
    $(this).closest('#clone').remove();
     console.log($(".clone").length)
});