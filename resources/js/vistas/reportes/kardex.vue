<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Kardex x Producto
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-3 form-group">
                            <label for="">Fecha:</label>
                            <input v-model="filtros.fecha" type="text" class="form-control">
                        </div>
                        <div class="col-lg-6 col-sm-9 form-group">
                            <label>Seleccionar Insumo</label>
                            <v-select :options="insumos" label="nombre_insumo" v-model="filtros.insumo">
                                <template slot="option" slot-scope="option">
                                    <div class="d-center">
                                        {{ option.nombre_insumo }}
                                    </div>
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        {{ option.nombre_insumo }}
                                    </div>
                                </template>
                            </v-select>
                            <!-- <input v-model="fecha" type="text" class="form-control"> -->
                        </div>
                        <div class="col-lg-3 col-sm-6 form-group mt-lg-4">
                            <button class="btn btn-primary" @click="listarStocks()">Buscar</button>
                            <a :href="url" class="btn btn-success">Excel</a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered ">
                            <thead>
                                <tr>
                                    <th rowspan="2">Fecha</th>
                                    <th rowspan="2">Motivo</th>
                                    <th class="text-center" colspan="3">Ingreso</th>
                                    <th class="text-center" colspan="3">Salida</th>
                                    <th class="text-center" colspan="2">Saldo</th>
                                </tr>
                                <tr>
                                    <th colspan="1">Cantidad</th>
                                    <th colspan="1">Costo Unitario</th>
                                    <th colspan="1">Total</th>
                                    <th colspan="1">Cantidad</th>
                                    <th colspan="1">Costo Unitario</th>
                                    <th colspan="1">Total</th>
                                    <th colspan="1">Cantidad</th>
                                    <th colspan="1">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="item in lista" class="text-rigth">
                                    <td>{{ item.fecha }}</td>
                                    <td>{{ item.documento}}</td>
                                    <td v-if="item.tipo=='Inicial'">{{ item.cantidad }}</td>
                                    <td v-if="item.tipo=='Inicial'"></td>
                                    <td v-if="item.tipo=='Inicial'">{{ (item.total).toFixed(2) }}</td>
                                    <td v-if="item.tipo=='Ingreso'">{{ item.cantidad }}</td>
                                    <td v-if="item.tipo=='Ingreso'">{{ item.precio.toFixed(2) }}</td>
                                    <td v-if="item.tipo=='Ingreso'">{{ (item.cantidad*item.precio).toFixed(2) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td v-if="item.tipo=='Salida'">{{ item.cantidad }}</td>
                                    <td v-if="item.tipo=='Salida'">{{ item.precio.toFixed(2) }}</td>
                                    <td v-if="item.tipo=='Salida'">{{ (item.cantidad*item.precio).toFixed(2) }}</td>
                                    <td>{{ item.stock }}</td>
                                    <td>{{ item.total.toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- {{lista}} -->
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            lista:[],
            filtros: {
                fecha: moment().format('YYYY-MM'),
                insumo: null
            },
            insumos: []
        }
    },
    mounted() {
        // this.listarStocks();
        this.listarInsumos();
    },
    computed: {
        url(insumo,fecha){
            return url_base+'/exports/kardex?fecha='+this.filtros.fecha+'&codigo='+((this.filtros.insumo==null) ? '0000':this.filtros.insumo.codigo);
        }
    },
    methods: {
        listarStocks(){
            axios.get(url_base+'/kardex_unitario?fecha='+this.filtros.fecha+'&codigo='+this.filtros.insumo.codigo)
            .then(response=>{
                this.lista=response.data;
            });
        },
        listarInsumos(){
            axios.get(url_base+'/insumo?all=true')
            .then(response=>{
                this.insumos=response.data;
            });
        },
    },
}
</script>