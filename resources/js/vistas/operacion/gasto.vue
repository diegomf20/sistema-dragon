<template>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="text-primary mb-0 font-weight-bold">Registro de Gastos</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-4 form-group">
                            <label for="">Ingresar Fecha:</label>
                            <input v-model="gasto.fecha" type="date" class="form-control">
                        </div>
                        <div class="col-sm-8 form-group">
                            <label>Seleccionar Obra</label>
                            <v-select :options="obras" label="titulo" v-model="gasto.obra_id" :reduce="obra => obra.id">
                                <template slot="option" slot-scope="option">
                                    <div class="v-select d-center">
                                        {{ option.titulo }}
                                    </div>
                                </template>
                                <template slot="selected-option" slot-scope="option">
                                    <div class="v-select selected d-center">
                                        {{ option.titulo }}
                                    </div>
                                </template>
                            </v-select>
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="">Descripcion:</label>
                            <input v-model="gasto.descripcion" type="text" class="form-control">
                        </div>
                        <div class="col-sm-6 form-group">
                            <label for="">Monto:</label>
                            <input v-model="gasto.monto" type="text" class="form-control">
                        </div>
                        <div class="col-sm-2 form-group">
                            <button class="btn btn-primary mt-sm-4" @click="guardar">Registrar</button>
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
            obra_select: null,
            resumen_obra: null,
            obras: [],
            gasto: this.initGasto()
        }
    },
    mounted() {
        this.listarObras();
    },
    methods: {
        initGasto(){
            return {
                obra_id: null,
                monto: 0,
                fecha: null,
                descripcion: null
            }
        },
        listarObras(){
            axios.get(url_base+'/obra?all=true')
            .then(response=>{
                this.obras=response.data;
            });
        },
        guardar(){
            axios.post(url_base+'/gasto',this.gasto)
            .then(response => {
                var respuesta=response.data;
                switch (respuesta.status) {
                    case "VALIDATION":
                        this.errors=respuesta.data;
                        break;
                    case "OK":
                        this.gasto=this.initGasto();
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