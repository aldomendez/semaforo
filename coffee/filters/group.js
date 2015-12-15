import _ from 'underscore'

module.exports = Vue.filter('groupBy', function (list, aggregator){
	return _.groupBy(list, aggregator)
})