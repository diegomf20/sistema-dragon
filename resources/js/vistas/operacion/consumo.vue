<template>    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    SALIDA X CONSUMO
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Colaborador</label>
                                <select v-model="consumo.colaborador_id" type="text" class="form-control">
                                    <option v-for="colaborador in colaboradores" :value="colaborador.id" >{{ colaborador.nombre_colaborador+" "+colaborador.apellido_colaborador }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="">Obra</label>
                                <select v-model="consumo.obra_id" type="text" class="form-control">
                                    <option v-for="obra in obras" :value="obra.id" >{{ obra.descripcion }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
            
                    <h5 class="card-title">Items de Consumo</h5>
                    <div class="row">
                        <div class="col-sm-6 form-group">
                            <label>Seleccionar Insumo</label>
                            <select class="form-control" v-model="itemMomentaneo.insumo">
                                <option v-for="insumo in insumos" :value="insumo" >{{ insumo.nombre_insumo }}</option>
                            </select>
                        </div>
                        <div class="col-sm-2 form-group">
                            <label for="">Cantidad</label>
                            <input v-model="itemMomentaneo.cantidad" type="number" class="form-control" min="1">
                        </div>
                        <div class="col-sm-3 form-group">
                            <button @click="agregarItem()" class="btn btn-info mt-4">Agregar al Carrito</button>
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
                                <td>{{ item.cantidad }}</td>
                                <!-- <td>
                                    <input type="text" v-model="item.cantidad" class="form-control text-center cantidad">
                                </td> -->
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
            colaboradores:[],
            insumos: [],
            obras: [],
            itemMomentaneo:{
                insumo: null,
                cantidad: 1,
            },
            consumo:{
                colaborador_id: null,
                obra_id:null,
                items: [],
            },
        }
    },
    mounted() {
        this.listarObras();
        this.listarColaborador();
        this.listarInsumos();
    },
    methods: {
        listarColaborador(){
            axios.get(url_base+'/colaborador?all=true')
            .then(response=>{
                this.colaboradores=response.data;
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
            if (this.itemMomentaneo.cantidad<=this.itemMomentaneo.insumo.stock) {
                this.consumo.items.push({
                    insumo_id: this.itemMomentaneo.insumo.id,
                    codigo: this.itemMomentaneo.insumo.codigo,
                    nombre: this.itemMomentaneo.insumo.nombre_insumo,
                    cantidad: this.itemMomentaneo.cantidad,
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
            console.log(this.consumo);
            
            // axios.post(url_base+'/consumo?api_token=',this.consumo)
            // .then(response=>{
            //     var respuesta=response.data;
            //     if(respuesta.status=="OK"){
            //         swal({title: "consumo Registrada",icon: "success",timer: "2000"});
            //         this.consumo={
            //             doc_identidad:null,
            //             nombre:null,
            //             tipo: "Boleta",
            //             proveedor_id:null,
            //             documento: null,
            //             items: [],
            //             total: 0,
            //         };
            //         $('#modal-documento').modal();
            //         this.url="comprobanteconsumo/"+respuesta.data.id;
            //         console.log(respuesta);
                    
            //     }
            //     if (respuesta.status=="ERROR") {
            //         swal({title: respuesta.data,icon: "error",timer: "4000"});
            //     }
            // });
        }
    },
}
</script>
