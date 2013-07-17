<div id="admin-menu-wrapper">
  <div id="admin-menu" class="clearfix" data-spy="affix" data-offset-top="1">
    <ul class="toSel rendered" data-label="Admin Menu">
      <li><a href="{{ config.url }}"><img src="{{ config.url }}/favicon.ico" alt="{{ config.sitename }}" style="height:11px" /></a></li>
      

      {{#each admenu}}
        <li{{#projectMenu}} class="projectMenu"{{/projectMenu}}><a href="{{{link}}}" title="{{text}}">{{this.text}}</a>
          {{#if menu}}
          <div class="secondary">
            <ul>
            {{#each menu}}
              <li{{#if subs}} class="expandable"{{/if}}><a href="{{{link}}}" title="{{title}}">{{title}}</a>
                {{#if subs}}
                <div class="tertiary">
                  <ul>
                    {{#each subs}}
                      <li{{#if subs}} class="expandable"{{/if}}><a href="{{{link}}}" title="{{title}}">{{title}}</a>
                        {{#if subs}}
                        <div class="quadmenu">
                          <ul>
                            {{#each subs}}
                              <li{{#if subs}} class="expandable"{{/if}}><a href="{{{link}}}" title="{{title}}">{{title}}</a>
                              </li>
                            {{/each}}
                          </ul>
                        </div>              
                        {{/if}}                  
                      </li>
                    {{/each}}
                  </ul>
                </div>              
                {{/if}}
              </li>
            {{/each}}
            <ul>
          </div>
          {{/if}}
        </li>
      {{/each}}

      <li class="admin-menu-action admin-menu-tab"><a data-prefix=">" href="{{ config.url }}/user.php?op=logout">{{ labels.logout }}</a></li>
      <li class="admin-menu-action"><a data-prefix=">" href="{{ config.url }}/user.php">{{ labels.welcome }}, {{ user.uname}}</a></li>
      <li class="admin-menu-action admin-menu-users"><a data-hidden="true" href="{{ config.url }}/modules/system/admin.php?fct=findusers">{{ config.membersOnline }}/{{ config.onlineCount }}</a></li>    
    </ul>
    <select class="mobileMenu">
      <option value="false" text="Navigation">Admin Menu</option>
      {{#each admenu}}
        <option value="{{{link}}}">{{text}}</option>
        {{#each menu}}
          <option value="{{{link}}}">&ndash;&ndash; {{title}}</option>
          {{#each subs}}
            <option value="{{{link}}}">&nbsp;&nbsp;&ndash;&ndash; {{title}}</option>
            {{#each subs}}
              <option value="{{{link}}}">&nbsp;&nbsp;&nbsp;&nbsp;&ndash;&ndash; {{title}}</option>
            {{/each}}
          {{/each}}
        {{/each}}
      {{/each}}
    </select>
  </div>
</div>