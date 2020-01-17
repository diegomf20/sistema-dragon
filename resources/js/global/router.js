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
        path: '/proveedor', 
        component: require('../vistas/maestras/proveedor.vue').default,
    },
    { 
        path: '/consumo', 
        component: require('../vistas/operacion/consumo.vue').default,
    },
];

export default new VueRouter({
    mode: 'history',
    routes,
    linkActiveClass: 'active'
});