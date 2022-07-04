<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Reporte Gasto
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
                                <th>FECHA</th>
                                <th>DESCRIPCION</th>
                                <th>MONTO</th>
                                <th>CATEGORIA</th>
                                <th>OBRA</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in lista">
                                <td>{{ item.fecha }}</td>
                                <td>{{ item.descripcion }}</td>
                                <td>{{ item.monto.toFixed(2) }}</td>
                                <td>{{ item.categoria }}</td>
                                <td>{{ (item.obra) }}</td>
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
        this.listar();
    },
    computed: {
        url(){
            return url_base+'/gasto?excel&fecha_inicio='+this.fecha_inicio+'&fecha_fin='+this.fecha_fin;
        }
    },
    methods: {
        listar(){
            axios.get(url_base+'/gasto?fecha_inicio='+this.fecha_inicio+'&fecha_fin='+this.fecha_fin)
            .then(response=>{
                this.lista=response.data;
            });
        }
    },
}
</script>