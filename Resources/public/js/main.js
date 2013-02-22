var contentRes = null;

$("body").on('click', "a.ajax", function(event) {
    event.preventDefault()
    $($(this).data('res')).load($(this).attr("href"));
})
$("body").on('submit', "form.ajax", function(event) {
    event.preventDefault()
    window.contentRes = $($(this).data("res"))
    $.ajax({
        url: $(this).attr("action"),
        data: $(this).serialize(),
        type: $(this).attr("method"),
        dataType: 'jsonp',
        jsonpCallback: 'alertMessage',
        contentType: "application/json",
        success: function(response) {
        }
    })
})

function alertMessage(messages)
{
    console.log(messages)
}

function jgrowlSucess(message)
{
    new Mensaje.info(message);
}

function alertFormError(messages)
{
    console.log(messages)
    var alert = "<div class=\"alert alert-error\"><%= message %></div>";
    window.contentRes.html("")
    $.each(messages, function() {
        window.contentRes.append(_.template(alert, this))
    })
}
