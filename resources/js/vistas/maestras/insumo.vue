<template>
    <div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Nuevo Insumo</h6>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">Nombre de Insumo:</label>
                                <input v-model="insumo.nombre_insumo" class="form-control" type="text">
                                <strong>{{ errors.nombre_insumo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Unidad:</label>
                                <select v-model="insumo.unidad_id" class="form-control">
                                    <option value=null>--Seleccionar Unidad--</option>
                                    <option v-for="unidad in unidades" :value="unidad.id">{{ unidad.nombre_unidad }}</option>
                                </select>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Punto de Reorden:</label>
                                <input v-model="insumo.punto_reorden" class="form-control" type="number">
                                <strong>{{ errors.punto_reorden }}</strong>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-9 form-group">
                                <input type="text" class="form-control" v-model="search">
                            </div>
                            <div class="col-sm-3 form-group">
                                <button class="btn btn-info" @click="listar()">Buscar</button>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Unidad</th>
                                    <th>Descripcion</th>
                                    <th>P.R.</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="insumo in table.data">
                                    <td>{{insumo.codigo}}</td>
                                    <td>{{insumo.nombre_unidad}}</td>
                                    <td>{{insumo.nombre_insumo}}</td>
                                    <td>{{insumo.punto_reorden}}</td>
                                    <td>
                                        <button @click="abrirEditar(insumo.id)" class="btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                    <!-- <td>
                                        <button v-if="insumo.estado=='0'" @click="actualizarEstado(insumo.id)" class="btn-link-info">
                                            <i class="material-icons">radio_button_checked</i>
                                        </button>
                                        <button v-else @click="actualizarEstado(insumo.id)" class="btn-link-gray">
                                            <i class="material-icons">radio_button_unchecked</i>
                                        </button>
                                    </td> -->
                                </tr>
                            </tbody>
                        </table>
                        <div class="row pagination">
                            <div class="col-9 text-left">
                                <h6>Pagina {{ selectPage }} de {{ table.last_page}} (TOTAL: {{table.total}})</h6>
                            </div>
                            <div class="col-3 text-right">
                                <button v-if="selectPage!=1" @click="listar(Number(selectPage)-1)" class="btn btn-primary"><</button>
                                <select v-model="selectPage"  v-on:change="listar()" class="form-control" style="width: 60px; display: inline;">
                                    <option v-for="n in table.last_page">{{n}}</option>
                                </select>
                                <button class="btn btn-primary" @click="listar(Number(selectPage)+1)" v-if="!(selectPage==table.last_page||table.last_page==1)">></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--Modal Editar-->
        <div id="modal-editar" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Editar Insumo</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <div class="col-lg-12 form-group">
                                <label for="">Nombre de Insumo:</label>
                                <input v-model="insumo_editar.nombre_insumo" class="form-control" type="text">
                                <strong>{{ errors_editar.nombre_insumo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Unidad:</label>
                                <select v-model="insumo_editar.unidad_id" class="form-control">
                                    <option value=null>--Seleccionar Unidad--</option>
                                    <option v-for="unidad in unidades" :value="unidad.id">{{ unidad.nombre_unidad }}</option>
                                </select>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Punto de Reorden:</label>
                                <input v-model="insumo_editar.punto_reorden" class="form-control" type="number" >
                                <strong>{{ errors_editar.punto_reorden }}</strong>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--Fin Modal Editar-->
    </div>
</template>
<script>
// import Input from '../../dragon-desing/dg-input.vue'
// import Select from '../../dragon-desing/dg-select.vue'
export default {
    // components:{
    //     Input,
    //     Select
    // },
    data() {
        return {
            areas: [],
            insumo: this.iniInsumo(), //datos de logeo
            insumo_editar: this.iniInsumo(),
            errors: {}, //datos de errores
            errors_editar: {}, //datos de errores
            //Datos de Tabla:
            table:{
                data:[]
            },
            selectPage: 1,
            unidades: [],
            url: null,
            search: ''
        }
    },
    mounted() {
        this.listar();
        this.listarUnidades();
    },
    methods: {
        iniInsumo(){
            this.errors_editar={};
            this.errors={};
            return {
                nombre_insumo: null,
                punto_reorden: null,
                unidad_id: null,
                id: null,
                codigo: null,
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/insumo?search='+this.search+'&page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        listarUnidades(){
            axios.get(url_base+'/unidad?all=true')
            .then(response => {
                this.unidades = response.data;
            })
        },
        grabarNuevo(){
            axios.post(url_base+'/insumo',this.insumo)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.insumo=this.iniInsumo();
                        swal("", "insumo Registrado", "success");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        actualizarEstado(id){
            axios.post(url_base+'/insumo/'+id+'/estado')
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "OK":
                        swal("", "Estado Actualizado", "success");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        grabarEditar(){
            axios.post(url_base+'/insumo/'+this.insumo_editar.id+'?_method=PUT',this.insumo_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.insumo_editar=this.iniInsumo();
                        this.listar();
                        swal("", "insumo Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            axios.get(url_base+'/insumo/'+id)
            .then(response => {
                this.insumo_editar = response.data;
            })
            $('#modal-editar').modal();
        }
    },
}
</script>