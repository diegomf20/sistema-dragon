<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-primary mb-0 font-weight-bold">Resumen por Obra</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-10 form-group">
                            <label>Seleccionar Obra</label>
                            <v-select :options="obras" label="titulo" v-model="obra_select">
                                <template slot="option" slot-scope="option">
                                    <div class="v-select d-center">
                                        {{ option.titulo }}
                                    </div>
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="v-select selected d-center">
                                        {{ option.titulo }}
                                    </div>
                                </template>
                            </v-select>
                            <!-- <input v-model="fecha" type="text" class="form-control"> -->
                        </div>
                        <div class="col-sm-2 form-group">
                            <button class="btn btn-primary mt-sm-4" @click="consultarObra">Consultar</button>
                        </div>
                    </div>
                    <div class="row" v-if="resumen_obra!=null">
                        <div class="col-12">
                            
                            <h5>Resumen de Obra <a :href="url" class="btn btn-danger">PDF</a></h5>
                            <hr>
                            <p><strong>Titulo: </strong> {{ resumen_obra.obra.titulo }}</p> 
                            <p><strong>Descripción: </strong> {{ resumen_obra.obra.descripcion }}</p> 
                            <p><strong>Fecha Inicio: </strong> {{ resumen_obra.obra.fecha_inicio }}</p> 
                            <p><strong>Fecha Fin: </strong> {{ resumen_obra.obra.fecha_fin }}</p> 
                        </div>
                        <div class="col-12">
                            <h5>Resumen de Insumos</h5>
                            <hr>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Insumo</th>
                                        <th>SXC</th>
                                        <th>Cantidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="insumo in resumen_obra.insumos">
                                        <td>{{ insumo.nombre_insumo }}</td>
                                        <td>{{ insumo.documentos }}</td>
                                        <td>{{ insumo.cantidad }}</td>
                                        <td>{{ insumo.total.toFixed(2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">Total:</td>
                                        <td>{{ totalInsumos.toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-12">
                            <h5>Gastos Otros</h5>
                            <hr>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Descripcion</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gasto in resumen_obra.gastos">
                                        <td>{{ gasto.fecha }}</td>
                                        <td>{{ gasto.descripcion }}</td>
                                        <td>{{ gasto.monto.toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"><b>Total:</b></td>
                                        <td><b>{{ totalGastos.toFixed(2) }}</b></td>
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
            obra_select: null,
            resumen_obra: null,
            obras: []
        }
    },
    mounted() {
        this.listarObras();
    },
    computed: {
        totalInsumos(){
            var total=0;

            if (this.resumen_obra!=null) {
                for (let i = 0; i < this.resumen_obra.insumos.length; i++) {
                    var insumo = this.resumen_obra.insumos[i];
                    total+=insumo.total;
                }
            }
            return total;
        },
        totalGastos(){
            var total=0;

            if (this.resumen_obra!=null) {
                for (let i = 0; i < this.resumen_obra.gastos.length; i++) {
                    var gasto = this.resumen_obra.gastos[i];
                    total+=gasto.monto;
                }
            }
            return total;
        },
        url(){
            return url_base+'/resumen-obra?pdf&obra_id='+this.obra_select.id;
        }
    },
    methods: {
        listarObras(){
            axios.get(url_base+'/obra?all=true')
            .then(response=>{
                this.obras=response.data;
            });
        },
        consultarObra(){
            axios.get(url_base+'/resumen-obra?obra_id='+this.obra_select.id)
            .then(response=>{
                this.resumen_obra=response.data;
            });
        }
    },
}
</script>