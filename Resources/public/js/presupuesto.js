var presupuesto = angular.module('presupuesto', []).config(function($routeProvider) {
    $routeProvider.when('/', {template: parameters.presupuestoTemplate, controller: 'editor'})
            .otherwise({redirectTo: '/'})
})
.controller('editor', function($scope, $filter) {

    $scope.descripciones = []

    $scope.reordenar = function() {
        var pos = 1;
        angular.forEach($scope.descripciones, function(d, index) {
            d.posicion = pos++
        })
    }

    $scope.add = function() {
        $scope.descripciones.push({
            descripcion: '',
            subtotal: 0,
            cantidad: 1,
            precio: 0,
            posicion: $scope.descripciones.length + 1
        })
    }

    $scope.remove = function(desc) {
        $scope.descripciones.splice($scope.descripciones.indexOf(desc), 1)
        $scope.reordenar()
    }

    $scope.total = function() {
        var total = 0;
        angular.forEach($scope.descripciones, function(d) {
            total += d.precio * d.cantidad
        })

        return $filter('number')(total, 2)
    }

    $scope.subir = function(desc) {
        var index = $scope.descripciones.indexOf(desc)
        if (index > 0) {
            var actual = $scope.descripciones[index]
            var anterior = $scope.descripciones[index - 1]
            var posTemp = actual.posicion
            actual.posicion = anterior.posicion
            anterior.posicion = posTemp
            $scope.descripciones.splice(index - 1, 2, actual, anterior)
        }
    }

    $scope.bajar = function(desc) {
        var index = $scope.descripciones.indexOf(desc)
        if ($scope.descripciones.length - 1 > index) {
            var actual = $scope.descripciones[index]
            var siguiente = $scope.descripciones[index + 1]
            var posTemp = actual.posicion
            actual.posicion = siguiente.posicion
            siguiente.posicion = posTemp
            $scope.descripciones.splice(index, 2, siguiente, actual)
        }
    }

    $scope.reordenar()
})