import Vue    from 'vue'
import fuse   from 'fuse.js'
import _      from 'underscore'
import App    from './app.v2.vue'


Vue.use(require('vue-resource'))

Vue.filter('count', function (list) {
	return _.size(list)
})

Vue.filter('numToString', function(number) {
	// regresa la version en texto de un numero
	return ['','one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve'][number]
})

Vue.filter('groupBy', function (list, iteratee){
	return _.groupBy(list, iteratee)
})

Vue.filter('replace', function (val, pattern){
	return pattern.replace(/\$1/, val);
})

Vue.filter('toMinutes',function (val) {
  return Math.round(val/60)
})

window.v = new Vue({
  el: 'body',
  data:{
  	machines : null
  },
  components: {
    app: App
  },
  methods:{
    updateFromDatabase:function (){
      this.$http.get("./machines.php", function(data){
        // console.log(data)
        // this.$set('machines', _.filter(data,{AREA:'LR4-Shim'}))
        // this.$set('machines', _.filter(data,{AREA:'4x25'}))
        this.$set('machines', data)
      });
    }
  },
  ready: function vueReady () {
    this.updateFromDatabase()
    setInterval(()=>{
      this.updateFromDatabase()
    },60000)
  }
})