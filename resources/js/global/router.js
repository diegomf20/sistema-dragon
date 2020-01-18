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
        path: '/proveedor', 
        component: require('../vistas/maestras/proveedor.vue').default,
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
        path: '/compra', 
        component: require('../vistas/operacion/compra.vue').default,
    },
    { 
        path: '/stock', 
        component: require('../vistas/reportes/stock.vue').default,
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});