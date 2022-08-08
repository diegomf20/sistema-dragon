<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Reporte Flujo Diario
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 form-group">
                            <input v-model="fecha" type="date" class="form-control">
                        </div>
                        <div class="col-lg-4 col-sm-6 form-group">
                            <button class="btn btn-primary" @click="listar()">Buscar</button>
                            <a :href="url" class="btn btn-success">Excel</a>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>DESCRIPCION</th>
                                <th>MONTO</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in lista">
                                <td>{{ item.descripcion }}</td>
                                <td>{{ item.monto.toFixed(3) }}</td>
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
            fecha: moment().format('YYYY-MM-DD'),
        }
    },
    mounted() {
        this.listar();
    },
    computed: {
        url(){
            return url_base+'/flujo-diario?excel&fecha='+this.fecha;
        }
    },
    methods: {
        listar(){
            axios.get(url_base+'/flujo-diario?fecha='+this.fecha)
            .then(response=>{
                this.lista=response.data;
            });
        }
    },
}
</script>