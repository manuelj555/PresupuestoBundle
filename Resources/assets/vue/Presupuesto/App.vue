<script src="../../../../../../../../../../../optime/hp/hpe_roe/src/Optime/FormTemplateBundle/Resources/js/formTemplateBuilder/question/dateQuestionView.js"></script>
<template>
    <div class="overflow" style="height:auto;">
        <table class="presupuesto table table-striped">
            <thead>
                <tr>
                    <th colspan="1" style="border-bottom: 1px solid #DDD;">
                        Título&nbsp;&nbsp;&nbsp;
                    </th>
                    <th colspan="5" style="border-bottom: 1px solid #DDD;">
                        <!--{#<input type="text" ng-model="o" class="span7"/>#}{{ form_widget(form.titulo, {-->
                        <!--attr: { 'ng-model': '$presupuesto.titulo', class: 'form-control input-sm' } }) }}-->
                    </th>
                </tr>
                <tr>
                    <th style="width: 30px;">Orden</th>
                    <th style="width: 530px;">Mano de Obra</th>
                    <th style="width: 70px;">Bs</th>
                    <th style="width: 70px;font-size: 13px;">Cant</th>
                    <th style="width: 90px;">SubTotal Bs</th>
                    <th style="width: 40px;">Remover</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="2">
                        <a class="btn" @click="addItem">+ Linea</a>
                        <!--{% if presupuesto.id is not null %}-->
                        <!--<a href="{{ path(" presupuesto_export",{id : presupuesto.id}) }}" target="_blank"-->
                        <!--class="btn">Imprimir</a>-->
                        <!--{% else %}-->
                        <!--<a href="#" target="_blank" class="btn disabled">Imprimir</a>-->
                        <!--{% endif %}-->
                        <!--<input class="btn pull-right guardar" value="Guardar" type="submit"/>-->
                    </td>
                    <td colspan="2" style="text-align: right;padding-right: 20px;vertical-align: middle">
                        <b>TOTAL :</b>
                    </td>
                    <td colspan="2" style="text-align: center;font-size: 18px;vertical-align: middle"
                        class="total">
                        {{ total }} Bs
                    </td>
                </tr>
            </tfoot>
            <tbody class="listado-descripciones">

                    <ManoDeObra v-for="(item, index) in items"
                                :item="item"
                                :key="index"
                                :index="index"
                                @change="changeItemData"
                                v-sortable
                    />

                <!--{{ form_row(form.descripciones.vars.prototype)|replace({'__name__': '{{$index}}' })|raw }}-->
            </tbody>
        </table>
        <!--<div class="panel panel-default" id="manos_de_obra_list" ng-controller="ManosDeObraListCtrl">-->
        <!--<div class="panel-body">-->
        <!--<table class="table table-condensed table-striped">-->
        <!--<tr ng-repeat="m in manos_de_obra | filter:mdo_filtro">-->
        <!--<td ng-click="addManoDeObra(m)">{{ '{{ m.descripcion }}' }}</td>-->
        <!--<td>{{ '{{ m.precio }} {{ m.medida }}' }}</td>-->
        <!--</tr>-->
        <!--</table>-->
        <!--</div>-->
        <!--</div>-->
    </div>
</template>

<script>
    import Vue from 'vue'
    import VueResource from 'vue-resource'
    import Sortable from 'vue-sortable'
    import ManoDeObra from 'Presupuesto/ManoDeObra.vue'
    import _ from 'lodash'

    Vue.use(VueResource)
    Vue.use(Sortable)

    export default {
        props: {
            apiUrl: {required: true, type: String},
        },

        data () {
            return {
                items: [],
            }
        },

        created () {
            this.resource = this.$resource(this.apiUrl + '{/id}')
            this.getManosDeObra()
        },

        computed: {
            total () {
                return _.sumBy(this.items, 'total')
            }
        },

        methods: {
            getManosDeObra () {
                let item = {
                    precio: 0,
                    cantidad: 0,
                    total: 0,
                };

                this.items = [
                    item,
                    _.clone(item),
                    _.clone(item),
                    _.clone(item),
                    _.clone(item),
                ]
            },
            addItem () {
                this.items.push({})
            },
            changeItemData(index, data) {
                Vue.set(this.items, index, Object.assign({}, this.items[index], data))
            },
        },

        components: {ManoDeObra},
    }
</script>