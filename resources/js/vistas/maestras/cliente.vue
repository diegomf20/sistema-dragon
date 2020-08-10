<template>
    <div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Nuevo cliente</h6>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">RUC:</label>
                                <input v-model="cliente.documento" class="form-control" type="number">
                                <strong>{{ errors.documento }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Razon Social:</label>
                                <input v-model="cliente.razon_social" class="form-control" type="text">
                                <strong>{{ errors.razon_social }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Mail:</label>
                                <input v-model="cliente.mail" class="form-control" type="text">
                                <strong>{{ errors.mail }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Telefono:</label>
                                <input v-model="cliente.telefono" class="form-control" type="text">
                                <strong>{{ errors.telefono }}</strong>
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
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>RUC</th>
                                        <th>Razón Social</th>
                                        <th>Telefono</th>
                                        <th>Mail</th>
                                        <th>Editar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="cliente in table.data">
                                        <td>{{cliente.documento}}</td>
                                        <td>{{cliente.razon_social}}</td>
                                        <td>{{cliente.telefono}}</td>
                                        <td>{{cliente.mail}}</td>
                                        <td>
                                            <button @click="abrirEditar(cliente.id)" class="btn btn-sm btn-warning">
                                                <i class="fas fa-pen"></i>
                                            </button>
                                        </td>
                                        <!-- <td>
                                            <button v-if="cliente.estado=='0'" @click="actualizarEstado(cliente.id)" class="btn-link-info">
                                                <i class="material-icons">radio_button_checked</i>
                                            </button>
                                            <button v-else @click="actualizarEstado(cliente.id)" class="btn-link-gray">
                                                <i class="material-icons">radio_button_unchecked</i>
                                            </button>
                                        </td> -->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
                        <h6 class="text-primary mb-0 font-weight-bold">Editar cliente</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <div class="col-lg-12 form-group">
                                <label for="">Razon Social:</label>
                                <input v-model="cliente_editar.razon_social" class="form-control" type="text">
                                <strong>{{ errors_editar.razon_social }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Mail:</label>
                                <input v-model="cliente_editar.mail" class="form-control" type="text">
                                <strong>{{ errors_editar.mail }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Telefono:</label>
                                <input v-model="cliente_editar.telefono" class="form-control" type="text">
                                <strong>{{ errors_editar.telefono }}</strong>
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
export default {
    data() {
        return {
            areas: [],
            cliente: this.inicliente(), //datos de logeo
            cliente_editar: this.inicliente(),
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
        inicliente(){
            this.errors_editar={};
            this.errors={};
            return {
                nombre_insumpo: null,
                punto_reorden: null,
                id: null,
                codigo: null,
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/cliente?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        grabarNuevo(){
            axios.post(url_base+'/cliente',this.cliente)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.cliente=this.inicliente();
                        swal("", "cliente Registrado", "success");
                        this.listar();
                        break;
                    default:
                        swal("",respuesta.data, "error");
                        break;
                }
            });
        },
        actualizarEstado(id){
            axios.post(url_base+'/cliente/'+id+'/estado')
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
            axios.post(url_base+'/cliente/'+this.cliente_editar.id+'?_method=PUT',this.cliente_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.cliente_editar=this.inicliente();
                        this.listar();
                        swal("", "cliente Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            axios.get(url_base+'/cliente/'+id)
            .then(response => {
                this.cliente_editar = response.data;
            })
            $('#modal-editar').modal();
        }
    },
}
</script>