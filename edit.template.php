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
      {{#edit === true}}
      <a class="item" on-click="backward">
        <i class="backward icon"></i> 
      </a>
      <a class="item" on-click="forward">
        <i class="forward icon"></i> 
      </a>
      {{/edit === true}}

      <div class="right menu">
        <div class="item">
          <div class="ui transparent icon input">
            <input type="text" placeholder="Filter..." value="{{filter}}">
            <i class="search link icon"></i>
          </div>
        </div>
      </div>
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
              <td>{{CICLETIME}}</td>
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
      <form class="ui form">
        <h5 class="ui dividing header">
          <a href="" class="ui button" on-click="returnToList">
          <i class="left arrow icon"></i>regresar</a> Equipment editor</h5>
        <div class="ui segment">
          <div class="ui error message">
            <div class="header">Action Forbidden</div>
            <p>You can only sign up for an account once with a given e-mail address.</p>
          </div>
          <div class="two fields">
            <div class="field">
              <label>Name <i class="icon info"></i></label>
              <input class="mousetrap" placeholder="" type="text" value="{{NAME}}" id="machines">
            </div>
            <div class="field">
              <label>db_id</label>
              <input class="mousetrap" placeholder="" type="text" value="{{DB_ID}}" id="location">
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label>Description</label>
              <input class="mousetrap" placeholder="" type="text" value="{{DESCRIPTION}}" id="description">
            </div>
            <div class="field">
              <label>Proceso</label>
              <input class="mousetrap" placeholder="" type="text" value="{{PROCESS}}" id="description">
            </div>
            <div class="field">
              <label>Cycle time <small>(en Minutos)</small></label>
              <input class="mousetrap" on-mousewheel="setTime:{{editing}}" type="text" value="{{min}}" id="description">
            </div>
            <div class="field">
              <label>Cycle time <small>(en segundos)</small> {{duration(min,seg)}}seg</label>
              <input class="mousetrap" on-mousewheel="setTime:{{editing}}" type="text" value="{{seg}}" id="description">
            </div>
          </div>
          <div class="four fields">
            <div class="field">
              <label>Coneccion</label>
              <input class="mousetrap" placeholder="" type="text" value="{{DBCONNECTION}}" id="machines">
            </div>
            <div class="field">
              <label>Table</label>
              <input class="mousetrap" placeholder="" type="text" value="{{DBTABLE}}" id="location">
            </div>
            <div class="field">
              <label>Machine</label>
              <input class="mousetrap" placeholder="" type="text" value="{{DBMACHINE}}" id="area">
            </div>
            <div class="field">
              <label>Device</label>
              <input class="mousetrap" placeholder="" type="text" value="{{DBDEVICE}}" id="status">
            </div>
          </div>
          <div class="two fields">
            <div class="field">
              <a href="#" class="ui {{#edited === true}}positive{{/edited === true}} button" on-click="save:{{editing}}">Save</a>
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
<div class="ui styled sidebar {{sidebar ? 'active': ''}}">
  <div class="ui grid">
    <div class="column">
      <div class="ui small feed">
        <h4 class="ui header">Centro de mensajes</h4>
        <div class="event">
          <div class="label">
            <i class="circular warning icon"></i>
          </div>
          <div class="content">
            <!-- <div class="date">
              Just moments ago
            </div> -->
            <div class="summary">
               <a>Sally Poodle</a> added you as a friend
            </div>
          </div>
        </div>
        <div class="event">
          <div class="label">
            <i class="circular pencil icon"></i>
          </div>
          <div class="content">
            <!-- <div class="date">
              3 days ago
            </div> -->
            <div class="summary">
              You submitted a new post to the page
            </div>
            <div class="extra text">
              I am a dog and I do not know how to make a post
            </div>
          </div>
        </div>
        <div class="event">
          <div class="label">
            <i class="circular photo icon"></i>
          </div>
          <div class="content">
            <!-- <div class="date">
              3 days ago
            </div> -->
            <div class="summary">
              <a>Sally Poodle</a> added <a>2 new photos</a> of you
            </div>
            <!-- <div class="extra images">
              <img src="/images/demo/item1.jpg">
              <img src="/images/demo/item2.jpg">
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>
</div>