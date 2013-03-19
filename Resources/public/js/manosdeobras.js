function terminarEdicion() {
    $(".manos_de_obras_list tbody tr.editando").each(function() {
        var tr = $(this)
        if (tr.is('.data-modificada')) {
            tr.find('.guardar').removeClass('guardar').addClass("en-linea")
            tr.find('td:eq(0)').html(tr.find('td:eq(0) :input').val())
            tr.find('td:eq(1)').html(tr.find('td:eq(1) :selected').html())
            tr.find('td:eq(2)').html(tr.find('td:eq(2) :selected').html())
            tr.find('td:eq(3)').html(tr.find('td:eq(3) :input').val() + " Bs")
        } else {
            tr.html(tr.data('original'))
        }
        tr.removeClass("editando data-modificada")
    })
}

$(".manos_de_obras_list tbody tr").on('click', '.en-linea', function(event) {
    event.preventDefault()

    terminarEdicion()
    
    $("form.validationEngineContainer").attr("action", $(this).attr('href'))

    var tr = $(this).closest("tr").addClass('editando');
    var tbody = $(".manos_de_obras_list tbody").clone();

    tr.data("original", tr.html())

    var descripcion = $(tbody.data("descripcion"))
    var medidas = $(tbody.data("medidas"))
    var tiposdeobras = $(tbody.data("tiposdeobras"))
    var precio = $(tbody.data("precio"))

    descripcion.val(tr.find('td:eq(0)').html())
    medidas.find("option:contains(" + tr.find('td:eq(1)').html() + ")").attr("selected", "selected")
    tiposdeobras.find("option:contains(" + tr.find('td:eq(2)').html() + ")").attr("selected", "selected")
    precio.val(toFloat(tr.find('td:eq(3)').html()))

    tr.find('td:eq(0)').html(descripcion)
    tr.find('td:eq(1)').html(medidas)
    tr.find('td:eq(2)').html(tiposdeobras)
    tr.find('td:eq(3)').html(precio)

    $(this).removeClass("en-linea").addClass("guardar")
})
$(".manos_de_obras_list tbody tr").on('click', '.guardar', function(event) {
    event.preventDefault()
    $("form.validationEngineContainer").submit()
})

function EditManoDeObraSuccess(mensaje){
    jgrowlSuccess(mensaje)
    $(".validationEngineContainer :input").closest("tr").addClass('data-modificada');
    terminarEdicion()
}

$(".validationEngineContainer").validationEngine()




