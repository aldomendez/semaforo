<template>
	<div class="label horizontal tiny ui {{color}} visible">
		<i class="icon warning" v-if="qtyLeft > 2"></i>
    {{machine.NAME}}
		<div class="detail" v-if="qtyLeft > 0">{{qtyLeft}}</div>
	</div>
  <div class="ui special popup top center">
    <div class="header">{{NAME}}</div>
    last seen {{humanized}}
    <br>Process:{{PROCESS}}
    <br>Devices not made:{{qtyLeft}}
    <br>CycleTime:{{machine.CICLETIME | toMinutes}} min
  </div>
</template>

<script>
  import m from 'moment'
	export default {
		props:['machine'],
    data () {
      return {
        color:'green',
        mmnt:null,
        humanized:null,
        diff:null,
        qtyLeft:null
      }
    },
    ready:function(){
      const colors = ['green','yellow','red']
      this.mmnt = m(this.machine.LASTTICK,'DD-MMM-YYYY HH:mm')
      this.humanized = this.mmnt.fromNow()
      this.diff = Math.round((m().diff(this.mmnt))/1000)
      this.qtyLeft = Math.round(this.diff/(this.machine.CICLETIME*1))
      if (this.qtyLeft < 2) {
        this.color = 'green'
      } else if (this.qtyLeft < 3) {
        this.color = 'yellow'
      } else{
        this.color = 'red'
      };
      // console.log(JSON.stringify(this.$data))
    }
	}
</script>