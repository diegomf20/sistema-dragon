<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Movimientos de Activos
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 mb-3">
                           <router-link to="/activo.movimiento.new" class="btn btn-primary" >Nuevo</router-link>
                        </div>
                    </div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Obra</th>
                                <th>Colaborador</th>
                                <th>Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in table.data">
                                <td>{{ item.fecha }}</td>
                                <td>{{ item.obra }}</td>
                                <td>{{ item.colaborador }}</td>
                                <td><button @click="abrirDetalles(item.id)" class="btn btn-info"><i class="fas fa-book"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="paginate-laravel">
                        <div class="row">
                            <div class="col-9 text-left">
                                <h6>Pagina {{ selectPage }} de {{ table.last_page}} (TOTAL: {{table.total}})</h6>
                            </div>
                            <div class="col-3 text-rigth">
                                <button class="btn btn-sm btn-primary" v-if="selectPage!=1" @click="listar(Number(selectPage)-1)"><</button>
                                <select class="btn" v-model="selectPage"  v-on:change="listar()">
                                    <option v-for="n in table.last_page">{{n}}</option>
                                </select>
                                <button class="btn btn-sm btn-primary" @click="listar(Number(selectPage)+1)" v-if="!(selectPage==table.last_page||table.last_page==1)">></button>
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
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Codigo</th>
                                        <th>Activo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="insumo in movimiento.detalles">
                                        <td>{{ insumo.codigo }}</td>
                                        <td>{{ insumo.activo }}</td>
                                    </tr>
                                </tbody>
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
    methods: {
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/activo/movimiento?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        abrirDetalles(id){
            axios.get(url_base+'/activo/movimiento/'+id)
            .then(response => {
                this.movimiento.detalles = response.data;
            })
            $('#modal-detalles').modal();
        }
    },
}
</script>