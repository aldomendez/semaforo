<div class="column">
  <div class="ui menu">
    <a href="" class="item"><i class="home icon"></i>home</a>
    <a href="" class="item">{{lastUpdate}} {{#if machines.loadingMachines}} <i class="asterisk loading icon"></i> {{/if}}</a>
    <div class="right menu">
      <div class="item">
        <div class="ui transparent icon input">
          <input type="text" placeholder="filtrar" value="{{filter}}">
          <i class="search link icon"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">





  
  <div class="column">
  {{#each machines.grouped :groupNum}}
  <h2 class="header">Maquinas de {{groupNum}}</h2>
    <div class="">
    {{#this :i}}
      <div class="label horizontal circular tiny ui {{status}}
      {{#if status!='red'}}empty{{/if}}"
      data-content="{{NAME}}: {{humanized}}" >
        {{#if status=='red'}}
        {{NAME}}
        <div class="detail"><i class="icon warning sign"></i>{{desc}}</div>
        {{/if}}
      </div>
    {{/this}}
    </div>
  {{/each}}
  </div>





<!--   
  <div class="column">
  {{#each machines.grouped :groupNum}}
  <h2 class="header">Maquinas de {{groupNum}}</h2>
    <div class="ui five cards">
    {{#this :i}}
      <div class="{{status}} card">
        <div class="content">
          <i class="right floated star icon"></i>
          <i class="right floated hide icon"></i>
          <div class="header">{{NAME}}</div>
          <div class="meta">
            <a href="#graph">Last seen: {{humanized}}</a>
          </div>
        </div>
        <div class="ui bottom attached button {{status}}">
          <b>{{desc}}</b>
        </div>
      </div>
    {{/this}}
    </div>
  {{/each}}
  </div> -->

</div>
