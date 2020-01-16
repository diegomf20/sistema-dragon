import Vuex from 'vuex'
Vue.use(Vuex)

const moduleSidebar={
    namespaced: true,
    state:{
        statusSidebar: false
    },
    mutations: {
        open(state){
            state.statusSidebar=true;
        },
        close(state){
            state.statusSidebar=false;
        }
    }
}

window.store=new Vuex.Store({
    state: {
        cuenta: JSON.parse(localStorage.getItem('cuenta_sistema'))||null,
    },
    modules:{
        'sidebar': moduleSidebar
    },
    mutations: {        
      auth_success(state,cuenta){
        state.cuenta=cuenta;
        localStorage.setItem('cuenta_sistema',JSON.stringify(state.cuenta));
        axios.defaults.headers.common['Authorization'] = state.cuenta.api_token;
      },
      auth_close(state){
        state.cuenta=null;
        localStorage.removeItem('cuenta_sistema');
      }
    },
    actions: {}
});

if (store.state.cuenta!=null) {
    axios.defaults.headers.common['Authorization'] = store.state.cuenta.api_token;
}
export default store;