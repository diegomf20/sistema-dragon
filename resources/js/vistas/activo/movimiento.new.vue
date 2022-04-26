<template>    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    Movimiento de Activo
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Obras:</label>
                                <v-select :reduce="item => item.id" :options="obras" label="titulo" v-model="movimiento.obra_id">
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            {{ `${option.titulo}`  }}
                                        </div>
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <div class="selected d-center">
                                            {{ `${option.titulo}`  }}
                                        </div>
                                    </template>
                                </v-select>
                                <strong>{{ errors.obra_id }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Colaborador:</label>
                                <v-select :reduce="item => item.id" :options="colaboradores" label="nombre_colaborador" v-model="movimiento.colaborador_id">
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            {{ `${option.documento} - ${option.nombre_colaborador} ${option.apellido_colaborador}`  }}
                                        </div>
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <div class="selected d-center">
                                            {{ `${option.documento} - ${option.nombre_colaborador} ${option.apellido_colaborador}`  }}
                                        </div>
                                    </template>
                                </v-select>
                                <strong>{{ errors.colaborador_id }}</strong>
                            </div>
                        </div>
                    </div>
            
                    <h5 class="card-title">Items de movimiento</h5>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <label class="">Activos:</label>
                            <v-select :options="activos" label="nombre_activo" v-model="activo_select">
                                <template slot="option" slot-scope="option">
                                    <div class="d-center">
                                        {{ `${option.codigo} - ${option.nombre_activo}`  }}
                                    </div>
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        {{ `${option.codigo} - ${option.nombre_activo}`  }}
                                    </div>
                                </template>
                            </v-select>
                        </div>
                        <div class="col-sm-2 form-group">
                            <button @click="agregarItem()" class="btn btn-info mt-4">Agregar</button>
                        </div>
                    </div>
                     <table class="table">
                        <thead>
                            <tr>
                                <th>Codigo</th>
                                <th>Nombre</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in movimiento.items">
                                <td>{{ item.codigo}}</td>
                                <td>{{ item.nombre_activo}}</td>
                                <td>
                                    <button @click="eliminarItem(index)" type="button" class="btn btn-danger btn-sm">
                                        X
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="movimiento.items.length==0">
                                <td class="text-center" colspan="5">Sin Items de movimiento</td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row">
                        <div class="text-center col-12">
                            <button @click="guardar()" class="btn btn-success">Guardar</button>
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
            // a: null,
            colaboradores:[],
            insumos: [],
            obras: [],
            activos: [],
            activo_select:null,
            movimiento: this.initmovimiento(),
            errors: {},
        }
    },
    mounted() {
        this.listarObras();
        this.listarcolaborador();
        this.listarActivos();
    },
    methods: {
        listarObras(){
            axios.get(url_base+'/obra?all=true&estado=A')
            .then(response=>{
                this.obras=response.data;
            });
        },
        onSearch(search, loading) {
            loading(true);
            this.search(loading, search, this);
        },
        search: _.debounce((loading, search, vm) => {
            if (search!="") {
                axios.get(url_base+'/stock?buscar='+search)
                .then(response=>{
                    vm.insumos=response.data;
                    loading(false);
                });
            }else{
                loading(false);
            }
        }, 350),
        initmovimiento(){
            return {
                colaborador_id: null,
                obra_id: null,
                documento: null,
                items: [],
            };
        },
        listarcolaborador(){
            axios.get(url_base+'/colaborador?all=true')
            .then(response=>{
                this.colaboradores=response.data;
            });
        },
        listarInsumos(){
            axios.get(url_base+'/stock')
            .then(response=>{
                this.insumos=response.data;
            });
        },
        listarActivos(){
            axios.get(url_base+'/activo?all=true&estado=A')
            .then(response=>{
                this.activos=response.data;
            });
        },
        agregarItem(){
            var encontrado=0;
            for (let i = 0; i < this.movimiento.items.length; i++) {
                const activo = this.movimiento.items[i];
                if (activo.id==this.activo_select.id) {
                    encontrado=1;
                    break;
                }
            }
            if (encontrado==1) {
                swal("","Ya esta en la lista: "+this.activo_select.codigo+" - "+this.activo_select.nombre_activo, "warning");
            }else{
                this.movimiento.items.push(this.activo_select);
                this.activo_select=null;
            }
        },
        eliminarItem(index){
            this.movimiento.items.splice(index, 1);
        },
        guardar(){
            axios.post(url_base+'/activo/movimiento',this.movimiento)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.movimiento=this.initmovimiento();
                        swal("", respuesta.data, "success");
                        break;
                    default:
                        swal("", respuesta.data, respuesta.status.toLowerCase());
                        break;
                }
            });
        }
    },
}
</script>
