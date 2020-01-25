<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    INSUMO CON REORDEN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 form-group">
                            <input v-model="fecha" type="date" class="form-control">
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <button class="btn btn-primary" @click="listarStocks()">Buscar</button>
                            <!-- <a :href="url" class="btn btn-success">Excel</a> -->
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>CODIGO</th>
                                <th>NOMBRE</th>
                                <th>STOCK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in lista">
                                <td>{{ item.codigo }}</td>
                                <td>{{ item.nombre_insumo }}</td>
                                <td>{{ item.stock }}</td>
                            </tr>
                        </tbody>
                    </table>
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
            fecha: moment().format('YYYY-MM-DD')
        }
    },
    mounted() {
        this.listarStocks();
    },
    computed: {
        url(){
            return url_base+'/api/stock?fecha='+this.fecha;
        }
    },
    methods: {
        listarStocks(){
            axios.get(url_base+'/reorden?fecha='+this.fecha)
            .then(response=>{
                this.lista=response.data;
            });
        }
    },
}
</script>