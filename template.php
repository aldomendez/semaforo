<div class="column">
  <div class="ui menu">
    <a href="" class="item"><i class="home icon"></i>home</a>
    <a href="" class="item"> Last update: {{humanizeDiff(lastUpdate)}} {{#if machines.loadingMachines}} <i class="asterisk loading icon"></i> {{/if}}</a>
    
    <!-- <div class="right menu">
      <div class="item">
        <div class="ui transparent icon input">
          <input type="text" placeholder="filtrar" value="{{filter}}">
          <i class="search link icon"></i>
        </div>
      </div>
    </div>
  </div> -->

  </div>
<div class="row">





  
  <div class="column ui segment">
  {{#each machines.grouped :groupNum}}
  <h2 class="header"><small>Equipos de </small>{{groupNum}}</h2>
    <div class="">
    {{#this :i}}
      <div class="label horizontal tiny ui {{status}}">
        {{NAME}}
        {{#if status=='red'}}
        <div class="detail"><i class="icon warning sign"></i>{{desc}}</div>
        {{/if}}
      </div>
      <div class="ui special popup">
        <div class="header">{{NAME}}</div>
        last seen: {{humanized}}
        {{#if status=='red'}}
        <br>
        Piezas perdidas: {{desc}}
        {{/if}}
      </div>
    {{/this}}
    </div>
  {{/each}}
  </div>


</div>
</div>
