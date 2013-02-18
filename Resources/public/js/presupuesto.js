//////////// UTILES //////////////////

function toFloat(num) {
    if ($.isNumeric(num))
        return formatear(num);
    if (!num)
        return 0;
    num = parseFloat(num.replace(/[^\d|\.]/g, ''));
    return isNaN(num) ? 0 : formatear(num);
}

function formatear(num) {
    num = num * 100;
    num = parseInt(num) / 100.0;
    return num;
}

$.fn.toJSON = function(form) {
    var data = {};
    form = $('input, textarea, select', form);
    form.each(function(index) {
        data[$(this).attr('id')] = $(this).val();
    });
    return data;
};

//////////// MANOS DE OBRAS /////////////////

var ManoDeObra = Backbone.RelationalModel.extend({
    urlRoot: PUBLIC_PATH + 'rest/manosdeobra',
    defaults: {
        id: null,
        descripcion: null,
        medida: null,
        precio: null,
        tipo: null
    }/*,
     relations:[{
     type: Backbone.HasOne,
     key:'descripciones',
     relatedModel:'DescripcionPresupuesto',
     collectionType:'ColeccionDescripcionPresupuesto',
     reverseRelation:{
     key:'presupuesto'
     }
     }]*/
});

var ColeccionManoDeObra = Backbone.Collection.extend({
    model: ManoDeObra,
    url: PUBLIC_PATH + 'rest/manosdeobra'
});


//////////////////////// DESCRIPCIONES ///////////////////////

var DescripcionPresupuesto = Backbone.RelationalModel.extend({
    urlRoot: PUBLIC_PATH + 'rest/descripciones/',
    defaults: {
        id: null,
        id_presupuesto: null,
        posicion: null,
        descripcion: null,
        cantidad: null,
        precio: null,
        subtotal: null,
        presupuesto: null
    }
});

var ColeccionDescripcionPresupuesto = Backbone.Collection.extend({
    model: DescripcionPresupuesto,
    url: PUBLIC_PATH + 'rest/descripciones/',
    initialize: function() {
        this.on('change', this.actualizarPresupuesto, this);
    },
    actualizarPresupuesto: function(descripcion, viejo) {
        var precioAnterior = toFloat(descripcion.previous('precio'));
        var cantidadAnterior = toFloat(descripcion.previous('cantidad'));

        var precioNuevo = toFloat(descripcion.get('precio'));
        var cantidadNueva = toFloat(descripcion.get('cantidad'));

        var totalPresupuesto = toFloat(descripcion.get('presupuesto').get('total'));

        totalPresupuesto += (precioNuevo * cantidadNueva) - (precioAnterior * cantidadAnterior);

        descripcion.get('presupuesto').set('total', totalPresupuesto);
        console.log("Actualizando el total del presupuesto")
    },
    comparator: function(descripcion) {
        return descripcion.get('posicion');
    },
    reordenar: function() {
        _.each(this.models, function(des) {
            var index = $('table.presupuesto tbody tr[data-id=' + des.get('id') + ']').index();
            des.set('posicion', index + 1);
        });
    }
});

///////////////// Presupuestos ///////////////////

var Presupuesto = Backbone.RelationalModel.extend({
    urlRoot: PUBLIC_PATH + 'rest/presupuestos/',
    defaults: {
        id: null,
        titulo: null,
        descripciones: null,
        total: null
    },
    relations: [{
            type: Backbone.HasMany,
            key: 'descripciones',
            relatedModel: 'DescripcionPresupuesto',
            collectionType: 'ColeccionDescripcionPresupuesto',
            reverseRelation: {
                key: 'presupuesto'
            }
        }],
    cargar: function(id, funcion) {
        this.set('id', id);
        this.fetch({
            success: function(modelo, res) {
                if (!res) {
                    modelo.save({}, {
                        wait: true
                    });
                }
                funcion(modelo);
            }
        });
    }
});

/////////////////// COLECCIONES //////////////////////////



////////////////// VISTAS //////////////////////////////

var VistaManoDeObra = Backbone.View.extend({
    el: '#modal_obras',
    initialize: function() {
    },
    render: function() {
        this.$('table tbody').html("");
        _.each(this.model.models, function(obra) {
            this.$('table tbody').append(new VistaDetalleManoDeObra({
                model: obra
            }).render().$el);
        }, this);
        console.log(this.$el)
        var obras = this.model.toJSON();
        $("#input-mano-de-obra").autocomplete({
            source: obras
        }).data("autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
                    .append("<a>" + item.descripcion + "</a>")
                    .appendTo(ul);
        };
        return this;
    }
});

var VistaDetalleManoDeObra = Backbone.View.extend({
    tagName: 'tr',
    initialize: function() {
        this.template = _.template($('#tpl_mano_de_obra').html());
    },
    render: function() {
        this.$el.html(this.template(this.model.toJSON()));
        if ($('table.presupuesto tbody:contains(' + this.model.get('descripcion') + ')').size() > 0) {
            this.$el.addClass('usando');
        }
        return this;
    }
});

var VistaDescripcionPresupuesto = Backbone.View.extend({
    tagName: 'tr',
    events: {
        'dblclick .quitar': 'remover',
        'keyup .precio': 'actualizar',
        'keyup .cantidad': 'actualizar',
        'change .cantidad': 'guardar',
        'change .precio': 'guardar',
        'change .descripcion': 'guardar'
    },
    initialize: function() {
        this.template = _.template($('#tpl_descripcion').html());
    },
    render: function() {
        this.$el.html(this.template(this.model.toJSON()));
        this.$el.attr('data-id', this.model.get('id'));
        console.log('agrego vista descripcion : ' + this.model.get('id'))
        return this;
    },
    remover: function() {
        this.model.destroy({
            success: function(model, res) {
                console.log(res);
            }
        });
        this.$el.unbind();
        this.$el.remove();
    },
    actualizar: function() {
        var cant = this.$('.cantidad:first').val().replace(/[^\d|\.]/g, '');
        var precio = this.$('.precio:first').val().replace(/[^\d|\.]/g, '');
        var subtotal = toFloat(cant) * toFloat(precio);
        this.model.set({
            'cantidad': cant,
            'precio': precio,
            'subtotal': subtotal,
            'descripcion': this.$(".descripcion").val()
        });
        this.$(".subtotal").html(toFloat(subtotal) + ' Bs');
        console.log("Actualizando la descripcion")
    },
    guardar: function() {
        this.actualizar();
        console.info(this.model);
        this.model.get('presupuesto').save({}, {
            success: function(model, res) {
                console.log("Actualizado el presupuesto")
            }
        }, {
            wait: true
        });
        console.log("Actualizando el presupuesto")
    }
});

var VistaPresupuesto = Backbone.View.extend({
    el: 'table.presupuesto',
    descripciones: 'table.presupuesto tbody',
    events: {
        'click .agregar-descripcion-vacia': 'agregarDescripcionVacia',
        'change .#presupuesto_titulo': 'actualizarPresupuesto',
        'click .guardar': 'actualizarPresupuesto'
    },
    initialize: function() {
        this.model.bind('change', this.actualizarVista, this);
    },
    render: function() {
        $(this.descripciones).html(""); //vaciamos el tbody
        //recorremos las descripciones que estan en la bd
        _.each(this.model.get('descripciones').models, function(des) {
            //vamos insertando cada vista (tr) en el tbody.
            $(this.descripciones).append(new VistaDescripcionPresupuesto({
                model: des
            }).render().$el);
        }, this);
        this.actualizarVista();
        return this;
    },
    /**
     * agrega una nueva descripcion a la coleccion y a al tbody
     */
    agregarDescripcionVacia: function() {
        //this.model.save({},{wait:true});
        var des = new DescripcionPresupuesto({
            id_presupuesto: this.model.get('id'), //le asignamos el id del presupuesto
            presupuesto: this.model //le asignamos el presupuesto
        });
        var este = this;
        this.model.get('descripciones').create(des, {
            success: function() {
                $(este.descripciones).append(new VistaDescripcionPresupuesto({
                    model: des
                }).render().$el);
            }
        });
    },
    actualizarVista: function() {
        this.$(".total").html(toFloat(this.model.get('total')) + ' Bs');
        this.$("#presupuesto_titulo").val(this.model.get('titulo'));
    },
    actualizarPresupuesto: function() {
        this.model.set({
            'titulo': this.$("#presupuesto_titulo").val(),
            'total': this.$(".total").html().replace(/[^\d|\.]/g, '')
        }).save();
        console.log("Se actualiza el presupuesto");
    }
});


//////////////////// Router ////////////////////////////


var MyRouter = Backbone.Router.extend({
    routes: {
        ':id/manosdeobra': 'mostrarManosDeObra',
        '': 'crear',
        ':id': 'cargar'
    },
    crear: function() {
        var pre = new Presupuesto();
        var este = this;
        console.log('nuevo presupuesto');
        pre.save({}, {
            success: function(presupuesto, res) {
                este.navigate(presupuesto.get('id'), {});
                este.mostrarData(presupuesto);
            }
        });
    },
    cargar: function(id) {
        console.log('se va a cargar el presupuesto : ', id);
        var pre = new Presupuesto();
        pre.cargar(id, this.mostrarData);
    },
    mostrarData: function(presupuesto) {
        console.log('Creando la vista para el presupuesto: ' + presupuesto.get('id'))
        new VistaPresupuesto({
            model: presupuesto
        }).render();
        $('.sortable').sortable({
            axis: 'y',
            placeholder: "fondo-sortable-elemet",
            forcePlaceholderSize: true,
            update: function() {
                presupuesto.get('descripciones').reordenar();
                presupuesto.save();
            }
        });
    },
    mostrarManosDeObra: function(event) {
        event.preventDefault();
        var obras = new ColeccionManoDeObra();
        obras.fetch({
            success: function(obj, res) {
                console.log("Cargadas las manos de obras");
                var vista = new VistaManoDeObra({
                    model: obj
                });
                vista.render().$el.modal('show');
                $('body').append(vista.$el);
            }
        });
    }
});


////////////////////////////////////////////////////////
$(function() {
    window.router = new MyRouter();

    $('.presupuesto .mostrar-manos-de-obra').click(window.router.mostrarManosDeObra);

    Backbone.history.start();

});
