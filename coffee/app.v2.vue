<template>
<div class="ui grid">
  <div class="column">
    <div class="ui menu">
      <a href="#" class="item"><i class="home icon"></i>home</a>
      <div class="right menu">
        <areaselector :machines="machines"></areaselector>
      </div>
    </div>
  </div>
</div>
  <div class="ui equal width grid">
    <div class="column" v-for="(index, elements) in machines| groupBy 'BU'">
      <div class="ui reised segment">

      <h2 class="ui ribbon label"><small>Equipos de </small>{{index}}</h2>
      <div class="ui list">
        <div class="item" v-for="(index, areas) in elements |groupBy 'AREA'">
          <div class="content"><b>{{index}}</b></div>
          <div class="description">
            <div class="ui list">
              <div class="item" v-for="(index, process) in areas | groupBy 'PROCESS'">
                <div class="content"><b>{{index}}</b></div>
                <div class="description">
                  <template v-for="machine in process">
                    <tag :machine="machine"></tag>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>


<script>
import Tags from './components/tags.vue'
import AreaSelector    from './components/AreaSelector.vue'
export default {
  props:['machines'],
  ready:function appVueReady () {
    // console.log(this.machines)
  },
  components: {
    tag:Tags,
    areaselector:AreaSelector
  }
}
</script>