{if $showhtml|default == "yes"}

{include file="header.tpl" jsload="ajax" jsload1="tinymce"}
{include file="tabsmenue-project.tpl" milestab="active"}

<div id="content-left">
	<div id="content-left-in">
		<div class="miles">

			<div class="breadcrumb">
				<a href="managemilestone.php?action=showproject&amp;id={$project.ID}">
					<img src="./templates/{$settings.template}/theme/{$settings.theme}/images/symbols/projects.png" alt="" />
					{$projectname|truncate:40:"...":true}
				</a>
			</div>

			<h1>{#addmilestone#}</h1>

{/if}

			<div class="block_in_wrapper">
				<h2>{#addmilestone#}</h2>

				<form id="addmilestoneform" class="main" method="post" action="managemilestone.php?action=add&amp;id={$project.ID}">
					<fieldset>

						<div class="row">
							<label for="name">{#name#}:</label>
							<input type="text" class="text" name="name" id="name"
                                   onkeyup="cssId('tasklist[]').value = this.value" required />
						</div>

						<div class="row">
							<label for="desc">{#description#}:</label>
							<div class="editor">
								<textarea name="desc" id="desc" rows="3" cols="1" ></textarea>
							</div>
						</div>

						<div class="clear_both_b"></div>

						<div class="row">
							<label for="end">{#start#}:</label>
							<input type="text" class="text" name="start" id="start" required />
						</div>

						<div class="datepick">
							<div id="datepicker_miles_start" class="picker display-none"></div>
						</div>

						<script type="text/javascript">
							theCal = new calendar({$theM},{$theY});
							theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
							theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
							theCal.relateTo = "start";
							theCal.dateFormat = "{$settings.dateformat}";
							theCal.getDatepicker("datepicker_miles_start");
						</script>

                        <div class="row">
                            <label for="end">{#due#}:</label>
                            <input type="text" class="text" name="end" id="end" required value="{$project.endstring}" />
                        </div>


                        <div class="datepick">
							<div id="datepicker_miles" class="picker display-none" ></div>
						</div>

                        <div class="row">
                            <label for="assigned">{#assignto#}:</label>
                            <select name="assigned[]" multiple="multiple" style="height:80px;" id="assigned" required>
                                <option value="">{#chooseone#}</option>
                                {section name=user loop=$assignable_users}
                                    <option value="{$assignable_users[user].ID}" {if $assignable_users[user].ID == $userid} selected {/if} >{$assignable_users[user].name}</option>
                                {/section}
                            </select>
                        </div>

                        <div id="tasklistsContainer">
                            <div class="row">
                                <label for="name">{#tasklist#}:</label>
                                <input type="text" class="text" name="tasklist[]" id="tasklist[]"  required />
                            </div>
                        </div>
                        <div class="row">
                            <button class="text" title="Taskliste hinzufügen" type="button" onclick="copyTasklist()">+</button>
                        </div>

						<script type="text/javascript">
							theCal = new calendar({$theM},{$theY});
							theCal.dayNames = ["{#monday#}","{#tuesday#}","{#wednesday#}","{#thursday#}","{#friday#}","{#saturday#}","{#sunday#}"];
							theCal.monthNames = ["{#january#}","{#february#}","{#march#}","{#april#}","{#may#}","{#june#}","{#july#}","{#august#}","{#september#}","{#october#}","{#november#}","{#december#}"];
							theCal.relateTo = "end";
							theCal.dateFormat = "{$settings.dateformat}";
							theCal.getDatepicker("datepicker_miles");
						</script>

						<div class="row-butn-bottom">
							<label>&nbsp;</label>
							<button type="submit" onfocus="this.blur();">{#addbutton#}</button>
							<button onclick="blindtoggle('addstone');toggleClass('add','add-active','add');toggleClass('add_butn','butn_link_active','butn_link');toggleClass('sm_miles','smooth','nosmooth');return false;" onfocus="this.blur();">{#cancel#}</button>
						</div>

					</fieldset>
				</form>

			</div> {*block_in_wrapper end*}

{if $showhtml|default == "yes"}

			<div class="content-spacer"></div>

		</div> {*Miles END*}
	</div> {*content-left-in END*}
</div> {*content-left END*}

{include file="sidebar-a.tpl"}
{include file="footer.tpl"}

{/if}
