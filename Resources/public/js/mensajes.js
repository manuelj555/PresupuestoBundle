

var Mensaje = Backbone.View.extend({
    el: 'span',
    render:function(mensaje,titulo,tipo){
        $.jGrowl(mensaje,{
            'titulo' : titulo,
            'estilo' : this.color + " " + tipo
        });
    },
    info : function(mensaje){
        this.render(mensaje, "Información", "info");
    },  
    valid : function(mensaje){
        this.render(mensaje, "Exito", "valid");
    },  
    error : function(mensaje){
        this.render(mensaje, "Error", "error");
    },  
    warning : function(mensaje){
        this.render(mensaje, "Advertencia", "warning");
    }
});


