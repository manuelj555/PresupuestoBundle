$("body").on('click', "a.ajax", function(event) {
    event.preventDefault()
    $($(this).data('res')).load($(this).attr("href"));
})
$("body").on('submit', "form.ajax", function(event) {
    event.preventDefault()
    var content = $($(this).data("res"))
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        type: $(this).attr("method"),
        success: function(response){
            content.html(response)
        }
    })
})

