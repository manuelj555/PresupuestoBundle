var presupuesto = angular.module('presupuesto', []).config(function($routeProvider) {
    $routeProvider.when('/', {template: parameters.presupuestoTemplate, controller: 'editor'})
            .otherwise({redirectTo: '/'})
})
        .controller('editor', function($scope, presupuesto) {

    $scope.presupuesto = presupuesto
    $scope.descripciones = presupuesto.descripciones

    $scope.reordenar = presupuesto.reordenarDescripciones

    $scope.add = presupuesto.addDescripcion

    $scope.remove = presupuesto.removeDescripcion

    $scope.total = presupuesto.getTotal
    $scope.subTotal = presupuesto.subTotal

    $scope.subir = presupuesto.subirDescripcion

    $scope.bajar = presupuesto.bajarDescripcion
})
        .factory('presupuesto', function($filter) {

    var presupuesto = {
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

    presupuesto.addDescripcion = function() {
        presupuesto.descripciones.push({
            descripcion: '',
            subtotal: 0,
            cantidad: 1,
            precio: 0,
            posicion: presupuesto.descripciones.length + 1
        })
    }

    presupuesto.removeDescripcion = function(desc) {
        presupuesto.descripciones.splice(presupuesto.descripciones.indexOf(desc), 1)
        presupuesto.reordenarDescripciones()
    }

    presupuesto.subTotal = function(desc) {
        var subtotal = $filter('to_number')(desc.precio) * $filter('to_number')(desc.cantidad)
        return $filter('number')(subtotal, 2)
    }

    presupuesto.getTotal = function() {
        var total = 0;
        angular.forEach(presupuesto.descripciones, function(d) {
            total += parseFloat(presupuesto.subTotal(d))
        })
        return presupuesto.total = $filter('number')(total, 2)
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

    return presupuesto
})
.filter('to_number', function() {
    return function(input) {
        if (!isNaN(input)) {
            return input
        } else {
            if (!input) {
                return 0;
            }
            input = parseFloat(input.replace(/[^\d|\.]/g, ''));
            return isNaN(input) ? 0 : input;
        }
    }
})