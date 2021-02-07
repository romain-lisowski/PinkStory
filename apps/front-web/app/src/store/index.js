import Vuex from 'vuex'
import auth from './auth/index'
import site from './site/index'

export default Vuex.createStore({
  modules: {
    auth,
    site,
  },
})
