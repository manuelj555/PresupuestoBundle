var presupuesto = angular.module('presupuesto',[]).config(function($routeProvider){
    $routeProvider.when('/', { template: parameters.presupuestoTemplate, controller: 'nuevo' })
})
.controller('nuevo', function(){
    alert('Hola')
})


