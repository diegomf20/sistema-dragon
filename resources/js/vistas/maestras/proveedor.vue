<template>
    <div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Nueva proveedor</h4>
                    </div>
                    <div class="card-body">
                        <form action="" v-on:submit.prevent="grabarNuevo()" class="row">
                            <div class="col-lg-12 form-group">
                                <label for="">RUC:</label>
                                <input v-model="proveedor.documento" class="form-control" type="text">
                                <strong>{{ errors.documento }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Razón Social:</label>
                                <input v-model="proveedor.razon_social" class="form-control" type="text" >
                                <strong>{{ errors.razon_social }}</strong>
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">E-Mail:</label>
                                <input v-model="proveedor.mail" class="form-control" type="text" >
                            </div>
                            <div class="col-lg-12 form-group">
                                <label for="">Teléfono:</label>
                                <input v-model="proveedor.telefono" class="form-control" type="text" >
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Lista de proveedores</h4>
                        <!-- <button class="btn btn-sm btn-danger" @click="sincronizar()">Sincronizar</button> -->
                    </div>
                    <div class="card-body">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Código</th>
                                    <th>Descripcion</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="proveedor in table.data">
                                    <td>{{proveedor.id}}</td>
                                    <td>{{proveedor.nom_proveedor}}</td>
                                    <!-- <td>
                                        <button @click="abrirEditar(proveedor.id)" class="btn-link-info">
                                            <i class="material-icons">create</i>
                                        </button>
                                    </td> -->
                                    <td>
                                        <button v-if="proveedor.estado=='0'" @click="actualizarEstado(proveedor.id)" class="btn-link-info">
                                            <i class="material-icons">radio_button_checked</i>
                                        </button>
                                        <button v-else @click="actualizarEstado(proveedor.id)" class="btn-link-gray">
                                            <i class="material-icons">radio_button_unchecked</i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="pagination">
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
                    </div>
                </div>
            </div>
        </div>
        <!--Modal Editar-->
        <div id="modal-editar" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Editar proveedor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="" v-on:submit.prevent="grabarEditar()">
                            <Input title="Codigo:" v-model="proveedor_editar.codigo" :error="errors_editar.codigo"></Input>
                            <Input title="Nombre:" v-model="proveedor_editar.nom_proveedor" :error="errors_editar.nom_proveedor"></Input>
                            <Select title="Area:" v-model="proveedor_editar.area_id" :error="errors_editar.area_id">
                                <option v-for="area in areas" :value="area.id">{{ area.nom_area }}</option>
                            </Select>
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
            proveedor: this.iniProveedor(), //datos de logeo
            proveedor_editar: this.iniProveedor(),
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
        iniProveedor(){
            this.errors={};
            return {
                documento: null,
                razon_social: null,
                mail: null,
                telefono: null,
            }
        },
        listar(n=this.selectPage){
            this.selectPage=n;
            axios.get(url_base+'/proveedor?page='+n)
            .then(response => {
                this.table = response.data;
            })
        },
        grabarNuevo(){
            axios.post(url_base+'/proveedor',this.proveedor)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.proveedor=this.iniProveedor();
                        swal("", "proveedor Registrado", "success");
                        this.listar();
                        break;
                    default:
                        break;
                }
            });
        },
        actualizarEstado(id){
            axios.post(url_base+'/proveedor/'+id+'/estado')
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
            axios.post(url_base+'/proveedor/'+this.proveedor_editar.id+'?_method=PUT',this.proveedor_editar)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors_editar=respuesta.data;
                        break;
                    case "OK":
                        this.proveedor_editar=this.iniProveedor();
                        this.listar();
                        swal("", "proveedor Actualizado", "success");
                        $('#modal-editar').modal('hide');
                        break;
                    default:
                        break;
                }
            });
        },
        abrirEditar(id){
            axios.get(url_base+'/proveedor/'+id)
            .then(response => {
                this.proveedor_editar = response.data;
            })
            $('#modal-editar').modal();
        }
    },
}
</script>