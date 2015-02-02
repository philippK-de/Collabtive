<script type="text/javascript" src="include/js/timetracker_widget.js"></script>
<div class="block_in_wrapper">

  <form novalidate class="main" id="trackeradd" method="post" action="managetimetracker.php?action=add" {literal} onsubmit="return validateCompleteForm(this,'input_error'); {/literal} ">
    <fieldset>

      <input type="hidden" name="project" value="{$project.ID}" />

      <div class="row">
          <label for="ttstartday">{#startday#}:</label>
          <input type="text" class="text" style="width:80px;margin:0 67px 0 0;" id="ttstartday" name="ttstartday" realname="{#date#}" />

          <label for="ttendday">{#endday#}:</label>
          <input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ttendday" name="ttendday" realname="{#date#}" />
      </div>

      <div class="datepick">
        <div id="datepicker_addstarttt" class="picker" style="display:none;"></div>
        <div id="datepicker_addendtt" class="picker" style="display:none;"></div>
      </div>


      <script type="text/javascript">
        startCal = new calendar({$theM},{$theY});
        startCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
        startCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
        startCal.relateTo = "ttstartday";
        startCal.keepEmpty = false;
        startCal.dateFormat = "{$settings.dateformat}";
        startCal.getDatepicker("datepicker_addstarttt");
      </script>
        
      <script type="text/javascript">

        endCal = new calendar({$theM},{$theY});
        endCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
        endCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
        endCal.relateTo = "ttendday";
        endCal.keepEmpty = false;
        endCal.dateFormat = "{$settings.dateformat}";
        endCal.getDatepicker("datepicker_addendtt");        
                
      </script>

      <div class="row">
        <label for="started">{#started#}:</label>
        <input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="started" name="started" onkeyup=" populateHours();" required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#started#} ({#timeformat#}: hh:mm)" value="08:00" />
        <button class="settimebtn" onclick="getnow('started');return false;" onfocus="this.blur();" title="{#inserttime#}">hh:mm</button>
        
        <label for = "ended">{#ended#}:</label>
        <input type="text" class="text" style="width:80px;margin:0 6px 0 0;" id="ended" name="ended" onkeyup = " populateHours();" required="1" regexp="^([01]?\d|2[0123]):[012345]\d$" realname="{#started#} ({#timeformat#}: hh:mm)" value="09:00" />
        <button class="settimebtn" onclick="getnow('ended');return false;" onfocus="this.blur();" title="{#inserttime#}">hh:mm</button>
      </div>
      <div class="row">
        <label for = "workhours" >Hours:</label>
        <input type = "number" id = "workhours" name = "workhours" value = "1" min = "1" max = "10" step = "1" onkeyup = "populateEndtime();" onchange = "populateEndtime();" style="width:40px;"/>
        <label for = "repeatTT">Repeat:</label>
        <input type = "range" id = "repeatTT" name = "repeatTT" value = "0" min = "0" max = "7" style = "width:60px;margin:0;border:0px;" onchange="$('repeatShow').value=this.value;" oninput="$('repeatShow').value=this.value;" />
        <span id  = "lala" style="float:left;"><input type = "text" id = "repeatShow" disabled value = "0" style = "text-align:center;width:15px;background-color:white;"></span>

      </div>

      <input type="hidden" name="project" value="{$project.ID}" />
        <div class="row">
          <label for="trackcomm">{#comment#}:</label>
          <textarea name="comment" id="trackcomm"></textarea>
        </div>

        <div class="clear_both_b"></div>

        <div class="row">
        <label for="ttask">{#task#}:</label>
        <select name="ttask" id="ttask">
            <option value="0">{#chooseone#}</option>
            {section name=task loop=$ptasks}

              {if $ptasks[task].title != ""}
              <option value="{$ptasks[task].ID}">{$ptasks[task].title}</option>
              {else}
              <option value="{$ptasks[task].ID}">{$ptasks[task].text|truncate:30:"...":true}</option>
            {/if}

          {/section}
          </select>
        </div>

      <div class="row-butn-bottom">
        <label>&nbsp;</label>
        <button type="submit" onfocus="this.blur();">{#addbutton#}</button>
      </div>

    </fieldset>
  </form>

</div> {*block_in_wrapper end*}
