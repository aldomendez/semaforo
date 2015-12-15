<template>
<div class="ui grid">
  <div class="column">
    <div class="ui menu"><a href="#" class="item"><i class="home icon"></i>home</a></div>
  </div>
</div>
  <div class="ui {{machines | groupBy 'BU' | count | numToString}} column grid">
    <div class="row">
      <div class="column">
        <div class="ui {{machines | groupBy 'BU' | count | numToString}} cards">
          <div class="color card" v-for="(index, elements) in machines| groupBy 'BU'">
            <div class="column">
              <div class="ui ribbon label orange"><small>Equipos de </small>{{index}}</div>
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
      </div>
    </div>
  </div>
</template>


<script>
import Tags from './components/tags.vue'
export default {
  props:['machines'],
  ready:function appVueReady () {
    console.log(this.machines)
  },
  components: {
    tag:Tags
  }
}
</script>