<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Movimientos de Almacen
                </div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tipo Movimiento</th>
                                <th>Documento</th>
                                <th>Fecha de Ingreso</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in table.data">
                                <td>{{ item.tipo_movimiento }}</td>
                                <td>{{ item.documento }}</td>
                                <td>{{ item.fecha_ingreso }}</td>
                                <td><button @click="abrirDetalles(item.id)" class="btn btn-info"><i class="fas fa-book"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="paginate-laravel">
                        <div class="row">
                            <div class="col-9 text-left">
                                <h6>Pagina {{ selectPage }} de {{ table.last_page}} (TOTAL: {{table.total}})</h6>
                            </div>
                            <div class="col-3">
                                <button v-if="selectPage!=1" @click="listar(Number(selectPage)-1)"><</button>
                                <select v-model="selectPage"  v-on:change="listar()">
                                    <option v-for="n in table.last_page">{{n}}</option>
                                </select>
                                <a @click="listar(Number(selectPage)+1)" v-if="!(selectPage==table.last_page||table.last_page==1)">></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="modal-detalles" class="modal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="text-primary mb-0 font-weight-bold">Detalles de Movimiento</h6>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row" v-if="movimiento.entidad!=null">
                                <div class="col-12" v-if="movimiento.tipo_movimiento=='IXC'">
                                    <p><strong>Proveedor: </strong> {{ movimiento.entidad.documento }} / {{ movimiento.entidad.razon_social }}</p> 
                                </div>
                                <div class="col-12" v-if="movimiento.tipo_movimiento=='SXC'">
                                    <p><strong>Colaborador: </strong> {{ movimiento.entidad.documento }} / {{ movimiento.entidad.apellido_colaborador }}, {{ movimiento.entidad.nombre_colaborador }}</p> 
                                </div>
                            </div> 
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Insumo</th>
                                        <th>Cantidad</th>
                                        <th>Costo/unidad</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="insumo in movimiento.detalles">
                                        <td>{{ insumo.codigo }}</td>
                                        <td>{{ insumo.nombre_insumo }}</td>
                                        <td>{{ insumo.cantidad }}</td>
                                        <td class="text-rigth">{{ insumo.precio.toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-rigth">
                                            Total:
                                        </td>
                                        <td class="text-rigth">
                                            {{ total.toFixed(2)  }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            table:{
                data:[]
            },
            selectPage: 1,
            movimiento: {
                entidad: null,
                detalles: []
            },
        }
    },
    mounted() {
        this.listar()
    },
    computed: {
        total(){
            return this.movimiento.detalles.reduce(function(sum, col) {
                return sum + col.cantidad*col.precio;
            }, 0);
        }
    },  
    methods: {
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/movimiento?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        abrirDetalles(id){
            axios.get(url_base+'/movimiento/'+id)
            .then(response => {
                this.movimiento = response.data;
            })
            $('#modal-detalles').modal();
        }
    },
}
</script>