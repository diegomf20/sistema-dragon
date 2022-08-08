<template>
    <div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Nuevo Colaborador</h6>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">DNI:</label>
                                <input v-model="colaborador.documento" class="form-control" type="number">
                                <strong>{{ errors.documento }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Nombres:</label>
                                <input v-model="colaborador.nombre_colaborador" class="form-control" type="text">
                                <strong>{{ errors.nombre_colaborador }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Apellidos:</label>
                                <input v-model="colaborador.apellido_colaborador" class="form-control" type="text">
                                <strong>{{ errors.apellido_colaborador }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Mail:</label>
                                <input v-model="colaborador.mail" class="form-control" type="text">
                                <strong>{{ errors.mail }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Telefono:</label>
                                <input v-model="colaborador.telefono" class="form-control" type="text">
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
                        <div class="row">
                            <div class="col-sm-2 form-group">
                                <a :href="pdf" class="btn btn-danger mb-3"><i class="far fa-file-pdf"></i> PDF</a>
                            </div>
                            <div class="col-sm-8 form-group">
                                <input type="text" class="form-control" v-model="search" @keyup="listar()">
                            </div>
                            <div class="col-sm-2 form-group">
                                <button class="btn btn-info" @click="listar()">Buscar</button>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>DNI</th>
                                    <th>Nombres</th>
                                    <th>Telefono</th>
                                    <th>Mail</th>
                                    <th>Editar</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="colaborador in table.data">
                                    <td>{{colaborador.documento}}</td>
                                    <td>{{colaborador.nombre_colaborador+" "+colaborador.apellido_colaborador}}</td>
                                    <td>{{colaborador.telefono}}</td>
                                    <td>{{colaborador.mail}}</td>
                                    <td>
                                        <button @click="abrirEditar(colaborador.id)" class="btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                    </td>
                                    <!-- <td>
                                        <button v-if="colaborador.estado=='0'" @click="actualizarEstado(colaborador.id)" class="btn-link-info">
                                            <i class="material-icons">radio_button_checked</i>
                                        </button>
                                        <button v-else @click="actualizarEstado(colaborador.id)" class="btn-link-gray">
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
                        <h6 class="text-primary mb-0 font-weight-bold">Editar colaborador</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <div class="col-lg-12 form-group">
                                <label for="">DNI:</label>
                                <input v-model="colaborador_editar.documento" class="form-control" type="number">
                                <strong>{{ errors_editar.documento }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Nombres:</label>
                                <input v-model="colaborador_editar.nombre_colaborador" class="form-control" type="text">
                                <strong>{{ errors_editar.nombre_colaborador }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Apellidos:</label>
                                <input v-model="colaborador_editar.apellido_colaborador" class="form-control" type="text">
                                <strong>{{ errors_editar.apellido_colaborador }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Mail:</label>
                                <input v-model="colaborador_editar.mail" class="form-control" type="text">
                                <strong>{{ errors_editar.mail }}</strong>
                            </div>                            
                            <div class="col-lg-12 form-group">
                                <label for="">Telefono:</label>
                                <input v-model="colaborador_editar.telefono" class="form-control" type="text">
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
            colaborador: this.inicolaborador(), //datos de logeo
            colaborador_editar: this.inicolaborador(),
            errors: {}, //datos de errores
            errors_editar: {}, //datos de errores
            //Datos de Tabla:
            table:{
                data:[]
            },
            selectPage: 1,
            url: null,
            search: ''
        }
    },
    mounted() {
        this.listar();
    },
    computed: {
        pdf(){
            return url_base+'/colaborador?pdf'
        }
    },
    methods: {
        inicolaborador(){
            this.errors_editar={};
            this.errors={};
            return {
                nombre_colaborador: null,
                apellido_colaborador: null,
                documento: null,
                telefono: null,
                mail: null,
                id: null,
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/colaborador?search='+this.search+'&page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        grabarNuevo(){
            axios.post(url_base+'/colaborador',this.colaborador)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.colaborador=this.inicolaborador();
                        swal("", "colaborador Registrado", "success");
                        this.listar();
                        break;
                    default:
                        swal("",respuesta.data, "error");
                        break;
                }
            });
        },
        actualizarEstado(id){
            axios.post(url_base+'/colaborador/'+id+'/estado')
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
            axios.post(url_base+'/colaborador/'+this.colaborador_editar.id+'?_method=PUT',this.colaborador_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.colaborador_editar=this.inicolaborador();
                        this.listar();
                        swal("", "colaborador Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            axios.get(url_base+'/colaborador/'+id)
            .then(response => {
                this.colaborador_editar = response.data;
            })
            $('#modal-editar').modal();
        }
    },
}
</script>