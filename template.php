<div class="row">
  <div class="column">
    <div class="ui menu">
      <a href="" class="item"><i class="home icon"></i>home</a>
      <!-- <a href="" class="item"> Last update: {{humanizeDiff(lastUpdate)}} {{#if machines.loadingMachines}} <i class="asterisk loading icon"></i> {{/if}}</a> -->
      
      <!-- <div class="right menu">
        <div class="item">
          <div class="ui transparent icon input">
            <input type="text" placeholder="filtrar" value="{{filter}}">
            <i class="search link icon"></i>
          </div>
        </div>
      </div> -->
  
    </div>
  </div>
</div>
<div class="row">
  <div class="column">
    <div class="ui {{size}} cards">
    {{#each machines.grouped :groupNum}}
      <div class="{{color[key]}} card">
        <div class="content">
          <div class="header"><small>Equipos de </small>{{key}}</div>
          <div class="ui list">
          {{#data :i}}
            <div class="item">
              <div class="content"><b>{{this.key}}</b></div>
              <div class="description">
                
<div class="ui list">
            {{#this.data :i}}
              <div class="item">
                <div class="content"><b>{{i}}</b></div>
                 <div class="description">
                  {{#each this}}
                  
                  <div class="label horizontal tiny ui {{status}}">
                    {{NAME}}
                    {{#if status=='red'}}
                    <div class="detail"><i class="icon warning sign"></i>{{desc}}</div>
                    {{/if}}
                  </div>
                  <div class="ui special popup">
                    <div class="header">{{NAME}}</div>
                    last seen: {{humanized}}
                    <br>
                    Process:{{PROCESS}}
                    {{#if status=='red'}}
                    <br>
                    Devices not made: {{desc}}
                    {{/if}}
                    <br>
                    CycleTime: {{round(CICLETIME/60)}}min
                  </div>
                  {{/each}}
                </div>
                  
              </div>
            {{/data}}
  </div>

              </div>
                
            </div>
          {{/data}}
          </div>
        </div>
      </div>
    {{/each}}
    </div>
  </div>
</div>
