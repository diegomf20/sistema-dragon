<template>    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    INGRESO X COMPRA
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label for="">Fecha de Ingreso</label>
                            <input v-model="compra.fecha" type="text" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="">Documento (F/B):</label>
                                <input v-model="compra.documento" type="text" class="form-control">
                                <strong>{{ errors.documento }}</strong>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <div class="form-group">
                                <label class="">Proveedor:</label>
                                <v-select :reduce="item => item.id" :options="proveedores" label="razon_social" v-model="compra.proveedor_id">
                                    <template slot="option" slot-scope="option">
                                        <div class="d-center">
                                            {{ option.documento+" - "+option.razon_social }}
                                        </div>
                                    </template>
                                    <template slot="selected-option" slot-scope="option">
                                        <div class="selected d-center">
                                            {{ option.documento+" - "+option.razon_social }}   
                                        </div>
                                    </template>
                                </v-select>
                                <strong>{{ errors.proveedor_id }}</strong>
                            </div>
                        </div>
                    </div>
            
                    <h5 class="card-title">Items de compra</h5>
                    <div class="row">
                        <div class="col-sm-8 form-group">
                            <label>Seleccionar Insumo</label>
                            <v-select :options="insumos" v-model="itemMomentaneo.insumo" :filterable="false"  @search="onSearch">
                                <template slot="option" slot-scope="option">
                                    <div class="d-center">
                                        {{ option.nombre_insumo }}
                                    </div>
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="selected d-center">
                                        {{ option.nombre_insumo }}
                                    </div>
                                </template>
                            </v-select>
                        </div>
                        <div class="col-sm-2 form-group">
                            <label for="">Cantidad</label>
                            <input v-model="itemMomentaneo.cantidad" type="number" class="form-control" min="1">
                        </div>
                        <div class="col-sm-2 form-group">
                            <label for="">Precio</label>
                            <input v-model="itemMomentaneo.precio" type="number" class="form-control" min="1">
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
                                <th>P.U.</th>
                                <th>Quitar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item,index) in compra.items">
                                <td>{{ item.codigo}}</td>
                                <td>{{ item.nombre}}</td>
                                <td>{{ item.cantidad }}</td>
                                <td>{{ item.precio }}</td>
                                <!-- <td>
                                    <input type="text" v-model="item.cantidad" class="form-control text-center cantidad">
                                </td> -->
                                <td>
                                    <button @click="eliminarItem(index)" type="button" class="btn btn-danger btn-sm">
                                        X
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="compra.items.length==0">
                                <td class="text-center" colspan="5">Sin Items de compra</td>
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
            proveedores:[],
            insumos: [],
            obras: [],
            itemMomentaneo:{
                insumo: null,
                cantidad: 1,
                precio: 0.00,
            },
            compra: this.initcompra(),
            errors: {}
        }
    },
    mounted() {
        this.listarObras();
        this.listarProveedor();
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
        initcompra(){
            return {
                proveedor_id: null,
                documento: null,
                items: [],
                fecha: moment().format('YYYY-MM-DD')
            };
        },
        listarProveedor(){
            axios.get(url_base+'/proveedor?all=true')
            .then(response=>{
                this.proveedores=response.data;
            });
        },
        listarObras(){
            axios.get(url_base+'/obra?all=true')
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
                this.compra.items.push({
                    insumo_id: this.itemMomentaneo.insumo.id,
                    codigo: this.itemMomentaneo.insumo.codigo,
                    nombre: this.itemMomentaneo.insumo.nombre_insumo,
                    cantidad: this.itemMomentaneo.cantidad,
                    precio: Number(this.itemMomentaneo.precio)
                });
                this.itemMomentaneo={insumo: null,cantidad: 1, precio: 0.00};
        },
        eliminarItem(index){
            this.compra.items.splice(index, 1);
        },
        guardar(){
            axios.post(url_base+'/compra',this.compra)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.compra=this.initcompra();
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
