var presupuesto = angular.module('presupuesto', []).config(function($locationProvider) {
    $locationProvider.html5Mode(true);
});

presupuesto.controller('mainCtrl', function($scope, presupuesto, manos_de_obra, $http, $location) {

    $scope.manos_de_obra = manos_de_obra
    $scope.$presupuesto = presupuesto
    $scope.descripciones = presupuesto.descripciones

    $scope.reordenar = presupuesto.reordenarDescripciones

    $scope.add = presupuesto.addDescripcion

    $scope.remove = presupuesto.removeDescripcion

    $scope.total = presupuesto.getTotal
    $scope.subTotal = presupuesto.subTotal

    $scope.subir = presupuesto.subirDescripcion

    $scope.bajar = presupuesto.bajarDescripcion

    $scope.activeDescription = null

    $scope.setActiveDescription = function(desc) {
        $scope.activeDescription = desc;
    };

    $scope.prepareManosDeObra = function($event) {
        var td = $($event.target);
        var input = td.find('input');
        td.append($("#manos_de_obra_list"))
        $("#manos_de_obra_list").css({
            left: td.offset().left,
            width: td.outerWidth(),
            top: td.offset().top + td.outerHeight() - 1,
            'z-index': 100
        });
    };

    $scope.hideManosDeObra = function() {
        $("#manos_de_obra_list").hide();
    };

    $scope.hideOrShowManosDeObra = function(description) {
        var div = $("#manos_de_obra_list");
        if (description.length > 3) {
            div.show();
        } else {
            div.hide();
        }

        $scope.mdo_filtro = description;
    };

    $scope.onKeyUp = function(event) {
        if (event.key == 'Down') {
            $("#manos_de_obra_list").show();
        } else if (event.key == 'Up') {
            $("#manos_de_obra_list").hide();
        }
    };

    $scope.guardar = function() {
        //se guardará la info del presupuesto
        var route = Routing.generate('presupuesto_guardar', {id: $scope.$presupuesto.id});
        $.post(route, $("#form_presupuesto").serialize()).done(function(id) {
            $scope.$presupuesto.id = id;
            $location.path(route);
        });
    };

})

presupuesto.factory('presupuesto', function($filter, $log) {

    var presupuesto = parameters.presupuesto ? parameters.presupuesto : {
        id: null,
        titulo: '',
        total: 0,
        descripciones: []
    }
    
    presupuesto.reordenarDescripciones = function() {
        var pos = 1;
        angular.forEach(presupuesto.descripciones, function(d) {
            d.posicion = pos++
        })
    }

    presupuesto.addDescripcion = function(d) {
        d = angular.isUndefined(d) ? {} : d;
        presupuesto.descripciones.push(angular.extend({
            descripcion: '',
            subtotal: 0,
            cantidad: 1,
            precio: 0,
            posicion: presupuesto.descripciones.length + 1
        }, d))
    }

    presupuesto.removeDescripcion = function(desc) {
        presupuesto.descripciones.splice(presupuesto.descripciones.indexOf(desc), 1)
        presupuesto.reordenarDescripciones()
    }

    presupuesto.subTotal = function(desc) {
        return $filter('to_number')(desc.precio) * $filter('to_number')(desc.cantidad)
    }

    presupuesto.getTotal = function() {
        var total = 0;
        angular.forEach(presupuesto.descripciones, function(d) {
            total += presupuesto.subTotal(d)
        })
        return presupuesto.total = total
    }

    presupuesto.subirDescripcion = function(desc) {
        var index = presupuesto.descripciones.indexOf(desc)
        if (index > 0) {
            var actual = presupuesto.descripciones[index]
            var anterior = presupuesto.descripciones[index - 1]
            var posTemp = actual.posicion
            actual.posicion = anterior.posicion
            anterior.posicion = posTemp
            presupuesto.descripciones.splice(index - 1, 2, actual, anterior)
        }
    }

    presupuesto.bajarDescripcion = function(desc) {
        var index = presupuesto.descripciones.indexOf(desc)
        if (presupuesto.descripciones.length - 1 > index) {
            var actual = presupuesto.descripciones[index]
            var siguiente = presupuesto.descripciones[index + 1]
            var posTemp = actual.posicion
            actual.posicion = siguiente.posicion
            siguiente.posicion = posTemp
            presupuesto.descripciones.splice(index, 2, siguiente, actual)
        }
    }

    presupuesto.reordenarDescripciones()

    return presupuesto;

})

presupuesto.filter('to_number', function() {

    return function(input) {
        if (!isNaN(input)) {
            return input
        } else {
            if (!input) {
                return 0;
            }
            input = parseFloat(input.replace(/[^\d|\.\,]/g, ''));
            return isNaN(input) ? 0 : input;
        }
    }
})

presupuesto.factory('manos_de_obra', function() {
    return parameters.manosDeObra;
})

presupuesto.controller('ManosDeObraListCtrl', function($scope, $http, presupuesto, manos_de_obra) {

    $scope.addManoDeObra = function(obra) {
        $scope.hideManosDeObra();
        if (!$scope.activeDescription ||
                $scope.activeDescription.descripcion == obra.descripcion) {
            return;//si no se modificó la descripcióm, no se hace nada
        }
        $scope.activeDescription.descripcion = obra.descripcion;
        $scope.activeDescription.cantidad = 1;
        $scope.activeDescription.precio = obra.precio + ' ' + obra.medida;
        this.loadMateriales($scope.activeDescription, obra);
    };

    $scope.loadMateriales = function(description, obra) {
        $http.get(Routing.generate('manosdeobra_get_materiales', {id: obra.id}))
                .success(function(res) {
                    description.materiales = res;
                });
    };
})

presupuesto.directive('materialesPopover', function($compile, $log) {

    $log.log("Iniciando Directiva");

    return {
        //transclude: true,
        link: function($scope, $el, $attr) {
            $log.log("Linkeando");

            var content = window.cont = angular.element('<div/>').html(parameters.materialesTemplate);
            $compile(content)($scope);

            $el.popover({
                html: true,
                trigger: 'hover',
                content: content,
            });
        }
    };
});