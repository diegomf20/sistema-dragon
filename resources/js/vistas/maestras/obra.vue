<template>
    <div>
        <div class="row">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Nuevo Obra</h6>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">Titulo:</label>
                                <input v-model="obra.titulo" class="form-control" type="text">
                                <strong>{{ errors.titulo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Descripcion:</label>
                                <textarea v-model="obra.descripcion" class="form-control" rows="2"></textarea>
                                <strong>{{ errors.descripcion }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Fecha Inicio:</label>
                                <input v-model="obra.fecha_inicio" class="form-control" type="date">
                                <strong>{{ errors.fecha_inicio }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Total:</label>
                                <input v-model="obra.total" class="form-control" type="text">
                                <strong>{{ errors.total }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Dirección:</label>
                                <textarea v-model="obra.direccion" class="form-control" rows="4"></textarea>
                                <strong>{{ errors.direccion }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Cliente:</label>
                                <select v-model="obra.cliente_id" class="form-control">
                                    <option value=null>--Seleccionar Cliente--</option>
                                    <option v-for="cliente in clientes" :value="cliente.id">{{ cliente.razon_social }}</option>
                                </select>
                            </div>

                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <a :href="pdf" class="btn btn-danger mb-3"><i class="far fa-file-pdf"></i> PDF</a>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Fecha Inicio</th>
                                    <th>Título de Obra</th>
                                    <th>Editar</th>
                                    <th>Finalizar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="obra in table.data">
                                    <td>{{obra.fecha_inicio}}</td>
                                    <td>{{obra.titulo}}</td>
                                    <td>
                                        <button @click="abrirEditar(obra.id)" class="btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <button v-if="obra.estado=='A'" @click="finalizar(obra.id)" class="btn btn-info">
                                            <i class="fas fa-archive"></i>
                                        </button> 
                                        <button v-if="obra.estado=='I'" class="btn btn-secondary">
                                            <i class="fas fa-archive"></i>
                                        </button> 
                                    </td>
                                    <!-- <td>
                                        <button v-if="obra.estado=='0'" @click="actualizarEstado(obra.id)" class="btn-link-info">
                                            <i class="material-icons">radio_button_checked</i>
                                        </button>
                                        <button v-else @click="actualizarEstado(obra.id)" class="btn-link-gray">
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
                        <h6 class="text-primary mb-0 font-weight-bold">Editar Obra</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <div class="col-lg-12 form-group">
                                <label for="">Titulo:</label>
                                <input v-model="obra_editar.titulo" class="form-control" type="text">
                                <strong>{{ errors_editar.titulo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Cliente:</label>
                                <select v-model="obra_editar.cliente_id" class="form-control">
                                    <option value=null>-- Seleccionar Cliente --</option>
                                    <option v-for="cliente in clientes" :value="cliente.id">{{ cliente.razon_social }}</option>
                                </select>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Descripcion:</label>
                                <textarea v-model="obra_editar.descripcion" class="form-control" rows="2"></textarea>
                                <strong>{{ errors_editar.descripcion }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Fecha Inicio:</label>
                                <input v-model="obra_editar.fecha_inicio" class="form-control" type="date">
                                <strong>{{ errors_editar.fecha_inicio }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Total:</label>
                                <input v-model="obra_editar.total" class="form-control" type="text">
                                <strong>{{ errors_editar.total }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Dirección:</label>
                                <textarea v-model="obra_editar.direccion" class="form-control" rows="4"></textarea>
                                <strong>{{ errors_editar.direccion }}</strong>
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
            clientes: [],
            obra: this.iniobra(), //datos de logeo
            obra_editar: this.iniobra(),
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
        this.listarClientes();
    },
    computed: {
        pdf(){
            return url_base+'/obra?pdf'
        }
    },
    methods: {
        iniobra(){
            this.errors_editar={};
            this.errors={};
            return {
                titulo: null,
                descripcion:  null,
                fecha_inicio: null,
                fecha_fin:  null,
                direccion:  null,
                total:      null,
                cliente_id: null
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/obra?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        listarClientes(){
            axios.get(url_base+'/cliente?all=true')
            .then(response => {
                this.clientes = response.data;
            })
        },
        grabarNuevo(){
            axios.post(url_base+'/obra',this.obra)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.obra=this.iniobra();
                        swal("", "obra Registrado", "success");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        finalizar(id){
            axios.post(url_base+'/obra/'+id+'/finalizar')
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "OK":
                        swal("", "Obra Finalizada", "success");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        grabarEditar(){
            axios.post(url_base+'/obra/'+this.obra_editar.id+'?_method=PUT',this.obra_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.obra_editar=this.iniobra();
                        this.listar();
                        swal("", "obra Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            axios.get(url_base+'/obra/'+id)
            .then(response => {
                this.obra_editar = response.data;
            })
            $('#modal-editar').modal();
        }
    },
}
</script>