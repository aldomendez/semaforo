import Vue    from 'vue'
// import fuse   from 'fuse.js'
import _      from 'underscore'
// import moment from 'moment'
import App    from './app.vue'

Vue.use(require('vue-resource'))

Vue.filter('count', function (list) {
	return _.size(list)
})

Vue.filter('numToString', function(number) {
	return ['','one','two','three','four','five','six','seven','eight','nine','ten','eleven','twelve'][number]
})

Vue.filter('groupBy', function (list, iteratee){
	return _.groupBy(list, iteratee)
})

window.v = new Vue({
  el: 'body',
  data:{
  	machines : null
  },
  components: {
    app: App
  },
  ready: function vueReady () {
	this.$http.get("./filecache.txt", function(data){
		console.log(data)
		this.$set('machines', data)
	});
  }
})