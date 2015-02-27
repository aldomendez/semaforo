<div class="column">
  <div class="ui menu">
    <a href="" class="item"><i class="home icon"></i>home {{machines.queryCount}}</a>
    <a href="" class="item">{{lastUpdate}} {{#if loadingMachines}} <i class="asterisk loading icon"></i> {{/if}}</a>
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
  <h2 class="header">Maquinas{{filter.length>0?': ':''}}{{filter}}</h2>
    <div class="ui six cards">
    {{#machines.data :i}}
      <div class="{{status}} card">
        <div class="content">
          <i class="right floated star icon"></i>
          <!-- <i class="right floated hide icon"></i> -->
          <div class="header">{{NAME}}</div>
          <div class="meta">
            <a href="#id/{{ID}}">{{ID}}</a>
            <a href="#filter">{{AREA}}</a>
            <a href="#graph">Last seen: {{humanized}}</a>
          </div>
        </div>
        <div class="ui bottom attached button {{status}}">
          <b>{{desc}}</b>
        </div>
      </div>
    {{/machines.data}}
    </div>
  </div>

</div>
