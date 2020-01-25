<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-header-info">
                    INGRESO X RETORNO
                </div>
                <div class="card-body">
                    <div class="row">
                        <label for=""></label>
                        <div class="col-sm-4 col-lg-3 form-group">
                            <input type="text" v-model="filtro.codigo" class="form-control" placeholder="Ingresar Código">
                        </div>
                        <div class="col-lg-8 col-sm-8 form-group">
                            <button @click="buscar()" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
                    <hr>
                    <h5>Datos de Salida</h5>
                    <div class="row" v-if="movimiento!=null">
                        <div class="col-sm-2">
                            <label for=""><b>Documento:</b></label>
                        </div>
                        <div class="col-sm-10">
                            <label for="">{{ movimiento.documento }}</label>
                        </div>
                        <div class="col-sm-2">
                            <label for=""><b>Fecha:</b></label>
                        </div>
                        <div class="col-sm-10">
                            <label for="">{{ movimiento.fecha_ingreso }}</label>
                        </div>
                        <div class="col-sm-2">
                            <label for=""><b>Obra:</b></label>
                        </div>
                        <div class="col-sm-10">
                            <label for="">{{ movimiento.titulo }}</label>
                        </div>
                    </div>
                    <table v-if="movimiento!=null" class="table">
                        <thead>
                            <tr>
                                <th>Insumo</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Retornar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="detalle in movimiento.detalles">
                                <td>{{ detalle.nombre_insumo }}</td>
                                <td>{{ detalle.cantidad }}</td>
                                <td>{{ detalle.precio }}</td>
                                <td><input type="text" class="form-control" v-model="detalle.retorno" style="width: 100px;"></td>
                            </tr>
                        </tbody>
                    </table>
                    <div v-if="movimiento!=null" class="text-center">
                        <button @click="guardar" class="btn btn-success">
                            Guardar
                        </button>
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
            filtro: {
                codigo: "",
            },
            movimiento: null
        }
    },
    methods: {
        buscar(){
            axios.get(url_base+'/consumo?codigo='+this.filtro.codigo)
            .then(response=>{
                if (response.data=="vacio") {
                    console.log(response.data);
                    this.movimiento=null;
                    swal("", "Consumo no disponible para Retornar Stock", "warning");
                }else{
                    this.movimiento=response.data;
                }
            });        
        },
        guardar(){
            axios.post(url_base+'/retorno',{
                movimiento_id: this.movimiento.id,
                items: this.movimiento.detalles})
            .then(response=>{
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.movimiento=null;
                        this.filtro={
                            codigo: "",
                        };
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