<template>    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    SALIDA X CONSUMO
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Fecha de Salida</label>
                            <input v-model="consumo.fecha" type="text" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Colaborador</label>
                                <v-select :reduce="item => item.id" :options="colaboradores" label="nombre_colaborador" v-model="consumo.colaborador_id">
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            {{ option.nombre_colaborador +" " +
                                            option.apellido_colaborador}}
                                        </div>
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <div class="selected d-center">
                                            {{ option.nombre_colaborador +" " +
                                            option.apellido_colaborador}}   
                                        </div>
                                    </template>
                                </v-select>
                                <strong>{{ errors.colaborador_id }}</strong>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Obra</label>
                                <v-select :reduce="item => item.id" :options="obras" label="titulo" v-model="consumo.obra_id">
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            {{ option.titulo }}
                                        </div>
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <div class="selected d-center">
                                            {{ option.titulo }}
                                        </div>
                                    </template>
                                </v-select>
                                <strong>{{ errors.obra_id }}</strong>
                            </div>
                        </div>
                    </div>
            
                    <h5 class="card-title">Items de Consumo</h5>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <label>Seleccionar Insumo</label>
                            <v-select :options="insumos" v-model="itemMomentaneo.insumo" :filterable="false"  @search="onSearch">
                                <template slot="option" slot-scope="option">
                                    <div class="d-center">
                                        {{ `${option.nombre_insumo} - (${option.stock.toFixed(2)} ${option.unidad})` }}
                                    </div>
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        {{ `${option.nombre_insumo} - (${option.stock.toFixed(2)} ${option.unidad})` }}
                                    </div>
                                </template>
                            </v-select>
                        </div>
                        <div class="col-sm-2 form-group">
                            <label for="">Cantidad</label>
                            <input v-model="itemMomentaneo.cantidad" type="number" class="form-control" min="1">
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
                                <th>Cantidad</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in consumo.items">
                                <td>{{ item.codigo}}</td>
                                <td>{{ item.nombre}}</td>
                                <td>{{ item.cantidad }} {{ item.unidad }}</td>
                                <td>
                                    <button @click="eliminarItem(index)" type="button" class="btn btn-danger btn-sm">
                                        X
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="consumo.items.length==0">
                                <td class="text-center" colspan="5">Sin Items de consumo</td>
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
            itemMomentaneo:{
                insumo: null,
                cantidad: 1,
            },
            consumo: this.initConsumo(),
            errors: {}, //datos de errores
        }
    },
    mounted() {
        this.listarObras();
        this.listarColaborador();
    },
    methods: {
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
        initConsumo(){
            return {
                colaborador_id: null,
                obra_id:null,
                items: [],
                fecha: moment().format('YYYY-MM-DD')
            };
        },
        listarColaborador(){
            axios.get(url_base+'/colaborador?all=true')
            .then(response=>{
                this.colaboradores=response.data;
            });
        },
        listarObras(){
            axios.get(url_base+'/obra?all=true&estado=A')
            .then(response=>{
                this.obras=response.data;
            });
        },
        listarInsumos(){
            axios.get(url_base+'/stock')
            .then(response=>{
                this.insumos=response.data;
            });
        },
        agregarItem(){
            if (this.itemMomentaneo.cantidad<=this.itemMomentaneo.insumo.stock) {
                this.consumo.items.push({
                    insumo_id: this.itemMomentaneo.insumo.id,
                    codigo: this.itemMomentaneo.insumo.codigo,
                    nombre: this.itemMomentaneo.insumo.nombre_insumo,
                    cantidad: this.itemMomentaneo.cantidad,
                    unidad: this.itemMomentaneo.insumo.unidad,
                });
                this.itemMomentaneo={insumo: null,cantidad: 1};
            }else{
                swal({title: "Stock insuficiente",icon: "info",timer: "2000"});
            }
        },
        eliminarItem(index){
            this.consumo.items.splice(index, 1);
        },
        guardar(){
            this.errors={};
            axios.post(url_base+'/consumo',this.consumo)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.consumo=this.initConsumo();
                        swal("", respuesta.data, "success");
                        break;
                    // case "WARNING":
                    //     console.log(respuesta);
                    //     // this.consumo=this.initConsumo();
                    //     // swal("", respuesta.data, "success");
                    //     break;
                    default:
                        swal("", respuesta.data, respuesta.status.toLowerCase());
                        break;
                }
            });
        }
    },
}
</script>
