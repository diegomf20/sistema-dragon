<template>
    <div>
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Nuevo Activo</h6>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">Código:</label>
                                <input v-model="activo.codigo" class="form-control" type="text">
                                <strong>{{ errors.codigo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Nombre de activo:</label>
                                <input v-model="activo.nombre_activo" class="form-control" type="text">
                                <strong>{{ errors.nombre_activo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Marca:</label>
                                <input v-model="activo.marca" class="form-control">
                                <strong>{{ errors.marca }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Serie:</label>
                                <input v-model="activo.serie" class="form-control">
                                <strong>{{ errors.serie }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Fecha:</label>
                                <input v-model="activo.fecha_compra" type="date" class="form-control">
                                <strong>{{ errors.fecha_compra }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Precio Compra:</label>
                                <input v-model="activo.precio_compra" class="form-control">
                                <strong>{{ errors.precio_compra }}</strong>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">
                                <a :href="pdf" class="btn btn-danger mb-3"><i class="far fa-file-pdf"></i> PDF</a>
                            </div>
                            <div class="col-sm-8 form-group">
                                <input type="text" class="form-control" v-model="search">
                            </div>
                            <div class="col-sm-2 form-group">
                                <button class="btn btn-info" @click="listar()">Buscar</button>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Nombre</th>
                                    <th>Marca</th>
                                    <th>Serie</th>
                                    <th>Obra</th>
                                    <th>opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="activo in table.data">
                                    <td>{{activo.codigo}}</td>
                                    <td>{{activo.nombre_activo}}</td>
                                    <td>{{activo.marca}}</td>
                                    <td>{{activo.serie}}</td>
                                    <td>{{activo.titulo}}</td>
                                    <td>
                                        <button @click="abrirEditar(activo.id)" class="btn btn-sm btn-warning">
                                            <i class="fas fa-pen"></i>
                                        </button>
                                        <button @click="abrirCambiar(activo.id)" class="btn btn-sm btn-info">
                                            <i class="fas fa-building"></i>
                                        </button>
                                    </td>
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
        <div id="modal-cambiar" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Mover a obra</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarCambiar()">
                            <div class="col-lg-12 form-group">
                                <label for="">Seleccione Obra:</label>
                                <select v-model="activo_editar.obra_id" class="form-control">
                                    <option :value="null">-- En Almacen --</option>
                                    <option :value="obra.id" v-for="obra in obras">{{ obra.titulo }}</option>
                                </select>
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
        <!--Modal Editar-->
        <div id="modal-editar" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-primary mb-0 font-weight-bold">Editar Activo</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <div class="col-lg-12 form-group">
                                <label for="">Código:</label>
                                <input v-model="activo_editar.codigo" class="form-control" type="text">
                                <strong>{{ errors_editar.codigo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Nombre de Activo:</label>
                                <input v-model="activo_editar.nombre_activo" class="form-control" type="text">
                                <strong>{{ errors_editar.nombre_activo }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Marca:</label>
                                <input v-model="activo_editar.marca" class="form-control">
                                <strong>{{ errors_editar.marca }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Serie:</label>
                                <input v-model="activo_editar.serie" class="form-control">
                                <strong>{{ errors_editar.serie }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Fecha:</label>
                                <input v-model="activo_editar.fecha_compra" type="date" class="form-control">
                                <strong>{{ errors_editar.fecha_compra }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Precio Compra:</label>
                                <input v-model="activo_editar.precio_compra" class="form-control">
                                <strong>{{ errors_editar.precio_compra }}</strong>
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
            activo: this.iniActivo(), //datos de logeo
            activo_editar: this.iniActivo(),
            errors: {}, //datos de errores
            errors_editar: {}, //datos de errores
            //Datos de Tabla:
            table:{
                data:[]
            },
            selectPage: 1,
            obras: [],
            url: null,
            search: ''
        }
    },
    mounted() {
        this.listar();
        this.listarObras();
    },
    computed: {
        pdf(){
            return url_base+'/activo?pdf'
        }
    },
    methods: {
        listarObras(){
            axios.get(url_base+'/obra?all=true&estado=A')
            .then(response=>{
                this.obras=response.data;
            });
        },
        iniActivo(){
            this.errors_editar={};
            this.errors={};
            return {
                nombre_activo: null,
                marca: null,
                serie: null,
                obra_id: '',
                precio_compra: '',
                fecha_compra: ''
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/activo?search='+this.search+'&page='+n)
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
            axios.post(url_base+'/activo',this.activo)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.activo=this.iniActivo();
                        swal("", "Activo Registrado", "success");
                        this.listar();
                        break;
                    case "ERROR":
                        swal("", respuesta.data, "error");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        actualizarEstado(id){
            axios.post(url_base+'/activo/'+id+'/estado')
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
            axios.post(url_base+'/activo/'+this.activo_editar.id+'?_method=PUT',this.activo_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.activo_editar=this.iniActivo();
                        this.listar();
                        swal("", "Activo Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        grabarCambiar(){
            axios.post(url_base+'/activo/'+this.activo_editar.id+'/obra',this.activo_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.activo_editar=this.iniActivo();
                        this.listar();
                        swal("", "Activo Actualizado", "success");
                        $('#modal-cambiar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            axios.get(url_base+'/activo/'+id)
            .then(response => {
                this.activo_editar = response.data;
            })
            $('#modal-editar').modal();
        },
        abrirCambiar(id){
            axios.get(url_base+'/activo/'+id)
            .then(response => {
                this.activo_editar = response.data;
            })
            $('#modal-cambiar').modal();
        }
    },
}
</script>