<template>
<div class="ui grid">
  <div class="column">
    <div class="ui menu"><a href="#" class="item"><i class="home icon"></i>home</a></div>
  </div>
</div>
  <div class="row">
    <div class="column">
      <div class="ui {{machines | groupBy 'BU' | count | numToString}} cards">
        <div class="color card" v-for="elements in machines| groupBy 'BU'">
          <div class="column">
            <div class="header"><small>Equipos de</small>{{elements[0].BU}}</div>
            <div class="ui list">
              <div class="item" v-for="areas in elements |groupBy 'AREA'">
                <div class="content"><b>{{areas[0].AREA}}</b></div>
                <div class="description">
                  <div class="ui list">
                    <div class="item" v-for="process in areas | groupBy 'PROCESS'">
                      <div class="content"><b>{{process[0].PROCESS}}</b></div>
                      <div class="description">
                        <div class="label horizontal tiny ui status" v-for="machine in process">{{machine.NAME}}</div>
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

export default {
  props:['machines'],
  ready:function appVueReady () {
    console.log(this.machines)
  },
  components: {}
}
