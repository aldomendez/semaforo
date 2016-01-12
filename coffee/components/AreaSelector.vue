<template>
<div class="ui simple dropdown item">
  BU
  <i class="dropdown icon"></i>
  <div class="menu">
    <div class="item" v-for="(name, element) in machines | groupBy 'BU'">{{name}}</div>
  </div>
</div>
</template>

<script>
	export default {
		props:['machines'],
    data () {
      return {}
    },
    ready:function(){
      
    }
	}
</script>