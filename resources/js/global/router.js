import VueRouter from 'vue-router'
Vue.use(VueRouter)

var auth=(to, from,next)=>{
    $('.modal-backdrop').remove();
    store.state.sidebar.statusSidebar=false;
    if(store.state.cuenta===null){
        next('/login');
    }else{
        next(); 
    }
}

var routes =[
    // {
    //     path: '/',
    //     redirect: '/marcador'
    // },
    { 
        path: '/obra', 
        component: require('../vistas/maestras/obra.vue').default,
    },
    { 
        path: '/insumo', 
        component: require('../vistas/maestras/insumo.vue').default,
    },
    { 
        path: '/activo', 
        component: require('../vistas/maestras/activo.vue').default,
    },
    { 
        path: '/unidad', 
        component: require('../vistas/maestras/unidad.vue').default,
    },
    { 
        path: '/proveedor', 
        component: require('../vistas/maestras/proveedor.vue').default,
    },
    { 
        path: '/cliente', 
        component: require('../vistas/maestras/cliente.vue').default,
    },
    { 
        path: '/colaborador', 
        component: require('../vistas/maestras/colaborador.vue').default,
    },
    { 
        path: '/consumo', 
        component: require('../vistas/operacion/consumo.vue').default,
    },
    { 
        path: '/retorno', 
        component: require('../vistas/operacion/retorno.vue').default,
    },
    { 
        path: '/compra', 
        component: require('../vistas/operacion/compra.vue').default,
    },
    { 
        path: '/cuadre', 
        component: require('../vistas/operacion/cuadre.vue').default,
    },
    { 
        path: '/gasto', 
        component: require('../vistas/operacion/gasto.vue').default,
    },
    { 
        path: '/movimiento', 
        component: require('../vistas/reportes/movimiento.vue').default,
    },
    { 
        path: '/stock', 
        component: require('../vistas/reportes/stock.vue').default,
    },
    { 
        path: '/reorden', 
        component: require('../vistas/reportes/reorden.vue').default,
    },
    { 
        path: '/kardex', 
        component: require('../vistas/reportes/kardex.vue').default,
    },
    { 
        path: '/resumen-obra', 
        component: require('../vistas/reportes/resumen-obra.vue').default,
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});