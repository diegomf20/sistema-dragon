<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-primary mb-0 font-weight-bold">Resumen por Obra</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-8 form-group">
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
                            <br>
                            <input type="checkbox" v-model="resumido" >Reporte Consolidado
                        </div>
                        <div class="col-sm-2 form-group">
                            
                            <button class="btn btn-primary mt-sm-4" @click="consultarObra">Consultar</button>
                        </div>
                    </div>
                    <div class="row" v-if="resumen_obra!=null">
                        <div class="col-12">
                            
                            <h5>Resumen de Obra 
                                <a :href="url" class="btn btn-danger">PDF</a>
                                <a :href="url_excel" class="btn btn-success">Excel</a>
                            </h5>
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
                                        <th>Fecha</th>
                                        <th>Categoria</th>
                                        <th>Insumo</th>
                                        <th>Detalles</th>
                                        <th>Cantidad</th>
                                        <th>Unidad</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="insumo in resumen_obra.insumos">
                                        <td>{{ insumo.fecha }}</td>
                                        <td>{{ insumo.categoria }}</td>
                                        <td>{{ insumo.insumo }}</td>
                                        <td>{{ insumo.documento }} - {{ insumo.colaborador }} </td>
                                        <td>{{ insumo.cantidad }}</td>
                                        <td>{{ insumo.unidad }}</td>
                                        <td>{{ insumo.total.toFixed(3) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6">Total:</td>
                                        <td>{{ totalInsumos.toFixed(3) }}</td>
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
                                        <th>Categoria</th>
                                        <th>Descripcion</th>
                                        <th>Monto</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="gasto in resumen_obra.gastos">
                                        <td>{{ gasto.fecha }}</td>
                                        <td>{{ gasto.categoria }}</td>
                                        <td>{{ gasto.descripcion }}</td>
                                        <td>{{ gasto.monto.toFixed(2) }}</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3"><b>Total:</b></td>
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
            obras: [],
            resumido: false
        }
    },
    mounted() {
        this.listarObras();
    },
    computed: {
        // optionsCategoriaInsumos(){
        //     return this.resumen_obra.insumos.map(item => item.categoria)
        //             .filter((value, index, self) => self.indexOf(value) === index)
        // },
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
            return url_base+'/resumen-obra?pdf&obra_id='+this.obra_select.id+this.sResumido;
        },
        url_excel(){
            return url_base+'/resumen-obra?excel&obra_id='+this.obra_select.id+this.sResumido;
        },
        sResumido(){
            return this.resumido ? '&resumido':'';
        },
    },
    methods: {
        listarObras(){
            axios.get(url_base+'/obra?all=true')
            .then(response=>{
                this.obras=response.data;
            });
        },
        consultarObra(){
            axios.get(url_base+'/resumen-obra?obra_id='+this.obra_select.id+this.sResumido)
            .then(response=>{
                this.resumen_obra=response.data;
            });
        }
    },
}
</script>