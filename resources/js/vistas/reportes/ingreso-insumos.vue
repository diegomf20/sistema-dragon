<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Ingresos x Insumo
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 form-group">
                            <input v-model="fecha_inicio" type="date" class="form-control">
                        </div>
                        <div class="col-lg-4 col-sm-6 form-group">
                            <input v-model="fecha_fin" type="date" class="form-control">
                        </div>
                        <div class="col-lg-4 col-sm-6 form-group">
                            <button class="btn btn-primary" @click="listar()">Buscar</button>
                            <a :href="url" class="btn btn-success">Excel</a>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>CANTIDAD COMPRA</th>
                                <th>TOTAL COMPRA</th>
                                <th>PRECIO PROMEDIO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in lista">
                                <td>{{ item.codigo }}</td>
                                <td>{{ item.nombre_insumo }}</td>
                                <td>{{ item.cantidad_compra }}</td>
                                <td>{{ item.total_compra }}</td>
                                <td>{{ (item.total_compra/item.cantidad_compra).toFixed(2) }}</td>
                            </tr>
                        </tbody>
                    </table>
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
            fecha_inicio: moment().format('YYYY-MM-DD'),
            fecha_fin: moment().format('YYYY-MM-DD')
        }
    },
    mounted() {
    },
    computed: {
        url(){
            return url_base+'/ingreso-insumos?excel&fecha_inicio='+this.fecha_inicio+'&fecha_fin='+this.fecha_fin;
        }
    },
    methods: {
        listar(){
            axios.get(url_base+'/ingreso-insumos?fecha_inicio='+this.fecha_inicio+'&fecha_fin='+this.fecha_fin)
            .then(response=>{
                this.lista=response.data;
            });
        }
    },
}
</script>