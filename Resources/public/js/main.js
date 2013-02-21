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
        dataType: 'jsonp',
        jsonpCallback: 'alertMessage',
        contentType: "application/json",
        success: function(response) {
            content.html(response)
        }
    })
})

function alertMessage(messages)
{
    console.log(messages)    
}

function alertFormError(messages)
{
    console.log(messages)
}
