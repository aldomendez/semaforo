import Vue    from 'vue'
import fuse   from 'fuse.js'
import _      from 'underscore'
import moment from 'moment'
import App    from './app.vue'

Vue.use(require('vue-resource'))

new Vue({
  el: 'body',
  components: {
    app: App
  }
})