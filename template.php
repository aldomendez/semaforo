<div class="column">
  <div class="ui menu">
    <a href="" class="item"><i class="home icon"></i>home</a>

    <div class="right menu">
      <div class="item">
        <div class="ui transparent icon input">
          <input type="text" placeholder="filtrar">
          <i class="search link icon"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  
  <div class="column">
  <h2 class="header">Maquinas</h2>
    <div class="ui four cards">
    {{#machines.data :i}}
      <div class="green card">
        <div class="content">
        <i class="right floated star icon"></i>
          <div class="header">{{NAME}}</div>
          <div class="meta">
            <a href="#filter">{{AREA}}</a>
            <a href="#graph">Ultima pieza: hace 4 min</a>
          </div>
        </div>
        <div class="ui bottom attached button green">
          working correctly
        </div>
      </div>
    {{/machines.data}}
    </div>
  </div>

</div>
