<template>
    <div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Nueva Categoria Activo</h6>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">Código:</label>
                                <input v-model="categoria_activo.codigo" class="form-control" type="text">
                                <strong>{{ errors.codigo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Nombre de Categoria:</label>
                                <input v-model="categoria_activo.nombre_categoria" class="form-control" type="text">
                                <strong>{{ errors.nombre_categoria }}</strong>
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
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="categoria_activo in table.data">
                                    <td>{{categoria_activo.codigo}}</td>
                                    <td>{{categoria_activo.nombre_categoria}}</td>
                                    <td>
                                        <button @click="abrirEditar(categoria_activo.id)" class="btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row pagination">
                            <div class="col-sm-6 text-left">
                                <h6>Pagina {{ selectPage }} de {{ table.last_page}} (TOTAL: {{table.total}})</h6>
                            </div>
                            <div class="col-sm-6 text-right">
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
                        <h6 class="text-primary mb-0 font-weight-bold">Editar Categoria Activo</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <div class="col-lg-12 form-group">
                                <label for="">Código:</label>
                                <input v-model="categoria_activo_editar.codigo" class="form-control" type="text">
                                <strong>{{ errors_editar.codigo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Nombre de categoria:</label>
                                <input v-model="categoria_activo_editar.nombre_categoria" class="form-control" type="text">
                                <strong>{{ errors_editar.nombre_categoria }}</strong>
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
            categoria_activo: this.inicategoria_activo(), //datos de logeo
            categoria_activo_editar: this.inicategoria_activo(),
            errors: {}, //datos de errores
            errors_editar: {}, //datos de errores
            //Datos de Tabla:
            table:{
                data:[]
            },
            selectPage: 1,

            url: null
        }
    },
    mounted() {
        this.listar();
    },
    methods: {
        inicategoria_activo(){
            this.errors_editar={};
            this.errors={};
            return {
                codigo: null,
                nombre_categoria: null
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/categoria-activo?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        grabarNuevo(){
            axios.post(url_base+'/categoria-activo',this.categoria_activo)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.categoria_activo=this.inicategoria_activo();
                        swal("", "Categoria Activo Registrado", "success");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        actualizarEstado(id){
            axios.post(url_base+'/categoria-activo/'+id+'/estado')
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
            axios.post(url_base+'/categoria-activo/'+this.categoria_activo_editar.id+'?_method=PUT',this.categoria_activo_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.categoria_activo_editar=this.inicategoria_activo();
                        this.listar();
                        swal("", "categoria_activo Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            this.errors_editar={};
            axios.get(url_base+'/categoria-activo/'+id)
            .then(response => {
                this.categoria_activo_editar = response.data;
            })
            $('#modal-editar').modal();
        }
    },
}
</script>