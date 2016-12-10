<template>
    <tr ng-repeat="desc in descripciones">
        <td class="td-posicion">
            <span><i class="icon-chevron-up" ng-click="subir(desc)"></i>
                <i class="icon-chevron-down" ng-click="bajar(desc)"></i>
            </span>
            <!--<span>{{'{{desc.posicion}}'}}</span>-->
        </td>
        <td ng-mouseenter="setActiveDescription(desc);prepareManosDeObra($event);"
            ng-mouseleave="hideManosDeObra()">
            <input type="text" ng-model="data.descripcion" style="width: 92%"
                   ng-change="hideOrShowManosDeObra(desc.descripcion)"
                   ng-focus="hideOrShowManosDeObra(desc.descripcion)"
                   @change="changeData"/>
            <!--<a href="#" data-container="body" data-toggle="popover" data-placement="bottom"-->
            <!--materiales-popover>-->
            <!--<span class="glyphicon glyphicon-list"-->
            <!--style="vertical-align: top; font-size: 20px"></span>-->
            <!--</a>-->
        </td>
        <td>
            <input type="text" v-model="data.precio" style="width: 88%"
                   @change="changeData"/>
        </td>
        <td>
            <input type="text" v-model="data.cantidad" style="width: 88%"
                   @change="changeData"/>
        </td>
        <td style="text-align: right;padding: 5px 10px 0 0">
            {{ data.total }} Bs
        </td>
        <td class="link-imagen" style="padding-top: 3px;cursor: pointer"
            ng-dblclick="remove(desc)">
            <!--<img src="{{ asset(" bundles/presupuesto/img/figuras/Error.png") }}" />-->
        </td>
    </tr>
</template>

<script>
    import _ from 'lodash'

    export default {
        props: {
            index: {required: true, type: Number},
            item: {required: true, type: Object},
        },

        data () {
            return {
            }
        },

        updated() {
            console.debug('Actualizado componente de item', this.item, new Date())
        },

        computed: {
            data () {
                return _.clone(this.item)
            },
        },

        methods: {
            changeData () {
                this.data.total = this.getTotal()
                this.$emit('change', this.index, this.data)
            },
            getTotal () {
                return _.toString(this.data.precio).replace(/\D+/g, '')
                        * _.toString(this.data.cantidad).replace(/\D+/g, '')
            },
        },
    }
</script>