var numDescriptions = $(".listado-descripciones tr").size()

function addManoDeObra(data) {
    data = $.extend({
        posicion: '',
        subtotal: 0,
        cantidad: '',
        precio: '',
        descripcion: '',
        medidas: { medida : '' }
    }, data)
    var container = $(".listado-descripciones")
    var element = container.data("prototype")
    element = $(element.replace(/__name__/g, ++numDescriptions))
    element.find(".descripcion input").val(data['descripcion'])
    element.find(".precio input").val(data['precio'] + " " + data['medidas']['medida'])
    element.find(".cantidad input").val(data['cantidad'])
    element.find(".subtotal").html(data['subtotal'] + ' Bs')

    $(container).append(element)
    colocarNumOrden()
}

function colocarNumOrden()
{
    $(".listado-descripciones tr").each(function(index) {
        $(this).find('.orden span').html(index + 1)
        $(this).find('.orden input').val(index + 1)
    })
}

function calcularTotal()
{
    var total = 0;
    $(".listado-descripciones .subtotal").each(function() {
        total += toFloat($(this).html())
    })
    $(".total").html(formatear(total) + ' Bs')
}

function calcularSubtotal() {
    var tr = $(this).closest('tr');
    var cantidad = toFloat(tr.find('.cantidad input').val())
    var precio = toFloat(tr.find('.precio input').val())
    tr.find(".subtotal").html(formatear(cantidad * precio) + ' Bs')
    calcularTotal()
}

function filtrarListado() {
    var content = $("#manos_de_obra_autocomplete").val().trim()
    $(".presupuesto-manos-de-obra tbody tr").hide(0).filter(function() {
        return $(this).find(":contains(" + content + ")").size() > 0
    }).show(0);
}

$(".agregar-descripcion-vacia").on('click', function(event) {
    event.preventDefault()
    addManoDeObra()
})

$(".listado-descripciones").on('dblclick', ".quitar-descripcion", function(event) {
    event.preventDefault()
    $(this).closest("tr").remove()
    colocarNumOrden()
}).sortable({
    update: colocarNumOrden
}).on('keyup change', '.cantidad input, .precio input', calcularSubtotal)

$("body").on('click', ".presupuesto-manos-de-obra tbody tr", function(event) {
    event.preventDefault()
    var data = $(this).data("json")
    data['posicion'] = null
    data['subtotal'] = data['precio']
    data['cantidad'] = 1
    addManoDeObra(data)
    $("#modal_manodeobra").modal("hide")
})

