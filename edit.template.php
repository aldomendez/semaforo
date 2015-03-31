<div class="column">
  <div class="ui tiered small menu">
    <div class="menu">
      <a class="item">
        <i class="home icon"></i> AvagoTech
      </a>
      <a class="item">
        <b>Equipment manager</b>
      </a>
      <a class="item" on-click="addNew">
        <i class="add icon"></i> New
      </a>
      <a class="item" on-click="addNew">
        {{saved}}
      </a>
      {{#edit === true}}
      <a class="item" on-click="backward">
        <i class="backward icon"></i> 
      </a>
      <a class="item" on-click="forward">
        <i class="forward icon"></i> 
      </a>
      {{/edit === true}}
      {{#edit === false}}

      <div class="right menu">
        <div class="item">
          <div class="ui transparent icon input">
            <input type="text" placeholder="Filter..." value="{{filter}}" class='mousetrap'>
            <i class="search link icon"></i>
          </div>
        </div>
      </div>

      {{/edit === false}}

    </div>
  </div>
</div>

{{#edit === false}}
<div class="column">
  <div class="ui grid">
    <div class="row">
      <div class="column">
        {{#if queue.running}}
        <div class="ui top attached {{queue.color}} progress active">
          <div class="bar" style="width: {{queue.percent}}%;"></div>
        </div>
        {{/if}}
        <table class="ui compact small table attached segment">
          <thead>
            <tr>
              <th>Tools</th>
              <th>Name</th>
              <th>BU Head</th>
              <th>Area</th>
              <th>Process</th>
              <th>Cicle time</th>
              <th>Connection</th>
              <th>Table</th>
              <th>Machine</th>
              <th>Device</th>
            </tr>
          </thead>
          <tbody>
          {{#each machines.data: i}}
            <tr>
              <td><a href="#{{i}}" class="ui button mini basic" on-click="edit:{{i}}">edit</a></td>
              <td>{{NAME}}</td>
              <td>{{BU}}</td>
              <td>{{AREA}}</td>
              <td>{{PROCESS}}</td>
              <td>{{min}}min</td>
              <td>{{DBCONNECTION}}</td>
              <td>{{DBTABLE}}</td>
              <td>{{DBMACHINE}}</td>
              <td>{{DBDEVICE}}</td>
            </tr>
          {{/each}}
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
{{/edit === false}}
{{#edit === true}}
<div class="column">
  <div class="ui grid">
    <div class="row">
      <div class="column">
      {{#if queue.running}}
        <div class="ui top attached {{queue.color}} progress active">
          <div class="bar" style="width: {{queue.percent}}%;"></div>
        </div>
        {{/if}}
      {{#with machines.data[editing]}}
      <form class="ui small {{#if ~message}}{{/if}} form">
        <h5 class="ui dividing header">
          <a href="" class="ui button" on-click="returnToList">
          <i class="left arrow icon"></i>regresar</a> Equipment editor</h5>
        <div class="ui segment">
          <div class="ui warning message">
            <div class="header">Action Forbidden</div>
            <ul class="list">
              <li></li>
            </ul>
          </div>
          <div class="three fields">
            <div class="field">
              <label>Name in database <i class="icon info"></i></label>
              <input placeholder="" type="text" value="{{DB_ID}}" id="machines">
            </div>
            <div class="field">
              <label>Name to show <i class="icon info"></i></label>
              <input placeholder="" type="text" value="{{NAME}}" id="machines">
            </div>
            <div class="field">
              <label>Manager</label>

                <div class="ui compact small menu">
                    <div class="ui simple dropdown item">
                      <i class="user icon"></i>
                      {{BU}}
                      <div class="menu">
                        {{#each managers: manager}}
                        <div class="item" on-click='updateManager:{{this}}'>{{this}}</div>
                        {{/each}}
                      </div>
                    </div>
                    <div class="item">
                      <a href="#" on-click="addNewManager"><i class="plus icon"></i></a>
                    </div>
                  </div>

            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label>Description</label>
              <input placeholder="" type="text" value="{{DESCRIPTION}}" id="description">
            </div>
            <div class="field">
              <label>Proceso</label>
              <input placeholder="" type="text" value="{{PROCESS}}" id="description">
            </div>
            <div class="field">
              <label>Cycle time <small>(en Minutos)</small></label>
              <input on-mousewheel="setTime:{{editing}}" type="text" value="{{min}}" id="description">
            </div>
            <div class="field">
              <label>Cycle time <small>(en segundos)</small> {{CICLETIME}}seg</label>
              <input on-mousewheel="setTime:{{editing}}" type="text" value="{{seg}}" id="description">
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label>Coneccion</label>
              <input placeholder="" type="text" value="{{DBCONNECTION}}" id="machines">
            </div>
            <div class="field">
              <label>Table</label>
              <input placeholder="" type="text" value="{{DBTABLE}}" id="location">
            </div>
            <div class="field">
              <label>Machine</label>
              <input placeholder="" type="text" value="{{DBMACHINE}}" id="area">
            </div>
            <div class="field">
              <label>Device</label>
              <input placeholder="" type="text" value="{{DBDEVICE}}" id="status">
            </div>
          </div>
          <div class="three fields">
            <div class="field">
              <a href="#" class="ui {{#edited === true}}positive{{/edited === true}} button" on-click="save:{{editing}}">
              {{#if this.ID !== undefined}} <i class="icon write"></i> Update{{else}} <i class="icon save"></i> Save{{/if}}
              </a>
            </div>
            <div class="field">
              <a href="#" class="ui button" on-click="clone:{{editing}}">
              <i class="icon copy"></i> Clone
              </a>
            </div>
            <div class="field">
              {{^deleting}}<button class="ui button" type="button" on-click="askToDelete">Delete</button>{{/deleting}}
              {{#deleting}}<button class="ui negative button" type="button" on-click="del:{{editing}}">Sure?</button>{{/deleting}}
            </div>
          </div>
        </div>
      </form>

      {{/with}}

      </div>
    </div>
  </div>
</div>
{{/edit === true}}