<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    STOCK POR INSUMO
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-sm-6 form-group">
                            <input v-model="buscar" type="text" class="form-control">
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
                                <th>PRECIO P.</th>
                                <th>TOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in table.data">
                                <td>{{ item.codigo }}</td>
                                <td>{{ item.nombre_insumo }}</td>
                                <td>{{ item.stock }}</td>
                                <td>{{ (item.stock==0) ? 0 : (item.total/item.stock).toFixed(2) }}</td>
                                <td>{{ (item.total!=null) ? item.total.toFixed(2) : 0.00 }}</td>
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
            table:{
                data:[]
            },
            selectPage: 1,

            lista:[],
            buscar: ""
            // fecha: moment().format('YYYY-MM-DD')
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
        listarStocks(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/stock?paginate&buscar='+this.buscar+'&page='+n)
            .then(response=>{
                this.table=response.data;
            });
        }
    },
}
</script>