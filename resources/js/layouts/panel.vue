<template>
    <div id="wrapper" :class="(statusSidebar) ? 'open':''">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
          <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon rotate-n-15">
              <i class="fas fa-laugh-wink"></i>
            </div>
            <div class="sidebar-brand-text mx-3">Casa Goza<sup>2</sup></div>
          </a>
          <!-- <hr class="sidebar-divider my-0"> -->
          <!-- <li class="nav-item active">
            <a class="nav-link" href="index.html">
              <i class="fas fa-fw fa-tachometer-alt"></i>
              <span>Dashboard</span></a>
          </li> -->
          <hr class="sidebar-divider">
          <div class="sidebar-heading">Módulo de Almacen</div>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
              <i class="fas fa-fw fa-table"></i><span>Tablas</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <h6 class="collapse-header">Tablas de Almacen:</h6>
                  <router-link to="insumo" class="collapse-item" >Insumos</router-link>
                  <router-link to="unidad" class="collapse-item" >unidad</router-link>
                  <router-link to="proveedor" class="collapse-item" >Proveedores</router-link>
                  <router-link to="obra" class="collapse-item" >Obras</router-link>
                  <router-link to="colaborador" class="collapse-item" >Colaboradores</router-link>
                  <router-link to="cliente" class="collapse-item" >Cliente</router-link>
                  <!-- <a class="collapse-item" href="buttons.html">Insumos</a> -->
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOperaciones" aria-expanded="true" aria-controls="collapseOperaciones">
              <i class="fas fa-fw fa-table"></i><span>Operaciones</span>
            </a>
            <div id="collapseOperaciones" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <h6 class="collapse-header">Tablas de Almacen:</h6>
                  <router-link to="compra" class="collapse-item" >Ingreso X Compra</router-link>
                  <router-link to="consumo" class="collapse-item" >Salida X Consumo</router-link>
                  <router-link to="retorno" class="collapse-item" >Ingreso X Retorno</router-link>
                  <router-link to="gasto" class="collapse-item" >Gasto</router-link>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseReportes" aria-expanded="true" aria-controls="collapseReportes">
              <i class="fas fa-fw fa-table"></i><span>Reportes</span>
            </a>
            <div id="collapseReportes" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
              <div class="bg-white py-2 collapse-inner rounded">
                  <h6 class="collapse-header">Reportes de Almacen:</h6>
                  <router-link to="stock" class="collapse-item" >Stock</router-link>
                  <router-link to="kardex" class="collapse-item" >Kardex</router-link>
                  <router-link to="reorden" class="collapse-item" >Reorden</router-link>
                  <router-link to="resumen-obra" class="collapse-item" >resumen-obra</router-link>
              </div>
            </div>
          </li>

          <hr class="sidebar-divider d-none d-md-block">
          <!-- Sidebar Toggler (Sidebar) -->
          <div class="text-center d-none d-md-inline">
              <button class="rounded-circle border-0" id="sidebarToggle"></button>
          </div>
        </ul>
    <div id="content-wrapper">
            <div class="content">
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">
            <!-- <div class="topbar-divider d-none d-sm-block"></div> -->

            <!-- Nav Item - User Information -->
            <!-- <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Valerie Luna</span>
                <img class="img-profile rounded-circle" src="https://source.unsplash.com/QAB-WJcbgJk/60x60">
              </a>
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="#">
                  <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                  Profile
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Settings
                </a>
                <a class="dropdown-item" href="#">
                  <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                  Activity Log
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li> -->

          </ul>

        </nav>
        <!-- <div class="background-sidebar" @click="close()"></div>
        <nav class="navbar">
            <button @click="open()" class="btn-link-success"><i class="material-icons">menu</i></button>
            {{ cuenta.fundo_id }}
            <button @click="cerrar()" class="btn btn-danger btn-sm btn-float-right">Salir</button>
        </nav> -->
        

                <div class="container-fluid">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapState,mapMutations } from 'vuex'
import administrador from './parts/administrador.vue'
import comun from './parts/comun.vue'

export default {
    components:{
        administrador,
        comun
    },
    computed: {
        ...mapState(['cuenta']),
        ...mapState('sidebar',['statusSidebar']),
    },
    mounted() {
        console.log(this.cuenta);
        
    },
    methods: {
        ...mapMutations('sidebar',['open','close']),
        url(){},
        cerrar(){
            this.$store.commit('auth_close');
            this.$router.push({path: "/login"} );
        }
    },
}
</script>