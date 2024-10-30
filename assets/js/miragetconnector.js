function miragetPage(){

	const apSt = apiStatusHtml(apiStatus) ;

	var date = new Date( lastUpdate * 1000  );

	const lastUp = date.toString().replace('GMT+0100 (Romance Standard Time)','') ;
    if( update ) document.location = location.href ;
	html +=  `<div id="wpbody" role="main">

	<div id="wpbody-content" aria-label="Main content" tabindex="0" style="overflow: hidden;">

					<div class="miraget-apiStatus ${apiStatusHtml(apiStatus)[1]} miragetApiCalls">
					 ${apiStatusHtml(apiStatus)[0]}
					</div>

	<div class="wrap">
		<h1>Miraget Connector  Admin Panel</h1>

		<div id="dashboard-widgets-wrap">
		<div id="dashboard-widgets" class="metabox-holder">
		${rightSide()}
		<div id="postbox-container-2" class="postbox-container">
		<div id="normal-sortables" class="meta-box-sortables ui-sortable">

	<div id="dashboard_activity" class="postbox ">
	<button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Activity</span><span class="toggle-indicator" aria-hidden="true"></span></button><h2 class="hndle ui-sortable-handle"><span>Activity</span></h2>
	<div class="inside">
	<div id="activity-widget">
	<div id="published-posts" class="activity-block">

	
	 <h3>Recently Update</h3>
		<ul>
		<li><span>${lastUp}</span>

		</li>
		</ul>
	</div>
	<div id="latest-comments" class="activity-block">
    ${emailActivity()}

		<p class="community-events-footer">
			<a href="https://miraget.com/wordpress-plugin-miragetconnector/" target="_blank" >
				Miraget Connector
				<span class="screen-reader-text">(opens in a new window)</span>
			</a>
			
		</p>
		<div class="purge-history" id="purge-history">
			<form method="post" name="purge-history" action="${purgeUrl}" onsubmit="return confirm('Purge activity table?')">
					<input type="hidden" name="action" value="purge-history" />
					
					<input type="submit" value="Purge History" class="button button-primary" id="purge"/>
			</form>
		</div>
			
		<div class="hidden" id="trash-undo-holder">
			<div class="trash-undo-inside">Comment by <strong></strong> moved to the trash. <span class="undo untrash"><a href="#">Undo</a></span></div>
		</div>
		<div class="hidden" id="spam-undo-holder">
			<div class="spam-undo-inside">Comment by <strong></strong> marked as spam. <span class="undo unspam"><a href="#">Undo</a></span></div>
		</div>
	</div></div></div>
	</div>
	</div>	</div>
        <!-- left side -->
		<div id="postbox-container-3" class="postbox-container">
		<div id="column3-sortables" class="meta-box-sortables ui-sortable empty-container" data-emptystring="Drag boxes here"></div>	</div>
		<div id="postbox-container-4" class="postbox-container">
		<div id="column4-sortables" class="meta-box-sortables ui-sortable empty-container" data-emptystring="Drag boxes here"></div>	</div>
	</div>

	<input type="hidden" id="closedpostboxesnonce" name="closedpostboxesnonce" value="0a3f8185ad"><input type="hidden" id="meta-box-order-nonce" name="meta-box-order-nonce" value="fa91e513d6">	</div><!-- dashboard-widgets-wrap -->

	</div><!-- wrap -->

	<div class="clear"></div></div><!-- wpbody-content -->
	<div class="clear"></div></div>` ;
}
function errorPost(){

	if( errors_post === 1 &&  is_post )   {

		return `
		<div class="miragerErrorPost">
		  <p>Error ! </p>
		  <p>Please fill the empty values</p>
		  <p></p>
		</div>
		` ;

    }else return '';
}
function apiStatusHtml(as){

  let apiStatus_html =  '';
	let classStatud =  '';
	const codeSt = as.substring(0,3) ;
	const Msg = as.replace(/^\d+/,'') ;

    if(    codeSt <= 201 ) {

        apiStatus_html = 'Miraget service available.'
        classStatud = 'sec' ;

    } else {

        apiStatus_html = Msg
        classStatud = 'err' ;

    }
    return [apiStatus_html,classStatud] ;
}
function rightSide(){
	const currentUrl = location.href ;
	let worckabletarget = '' ;
	if(  ! isWorkable )
	worckabletarget = ` <div class="noInfotarget">
	Please choose your target and complete settings
	</div>` ;

	return ` <div id="postbox-container-1" class="postbox-container">
		<div id="side-sortables" class="meta-box-sortables ui-sortable">
			<div id="dashboard_quick_press" class="postbox closedss">
			<h2 class="hndle ui-sortable-handle " id="general-settings-title">
			<span><span class="hide-if-no-js">General Settings</span>
			<span class="hide-if-js">Your Recent Drafts</span></span>
			</h2>
				${worckabletarget}
				</br>
			<div class="inside">
     ${errorPost()}
	<form name="post" action="${currentUrl}" method="post" id="quick-press" 
	class="initial-form hide-if-no-js">

		<input type="hidden" value="${nonce}" name="m_none">
		<div class="selectOPtionBox">
			<span class="mcrmtitle">Sync On / Off </span>

			<span class="chck">
				<input ${ onOff === 1 ? 'checked' : ''} type="radio" value="1" name="on_off">
				On
		    </span>
			<span class="chck">
				<input ${ onOff === 0 ? 'checked' : ''}  type="radio" value="0" name="on_off">
				Off
		    </span>

		</div>
			
		<div class="input-text-wrap miraget-info selectOPtionBox" id="title-wrap">
			<span class=" prompt mcrmtitle" for="title" id=""> Miraget Token             </span>
			<input style="height:28px;" type="text" name="miraget_key" id="miraget-token-wp" autocomplete="off" value="${miragetKey}">
		</div>
		<div class="selectOPtionBox">
			<span class="mcrmtitle">Rows to sync 
			<div class="miraget-connect-tooltip">
			<img src="${pluginUrl}assets/image/infoicon.png" class="miraget-icon-info-img" /> 
				<span class="miraget-connect-tooltiptext">Number of rows to sync per call</span>
			</div>
			</span>
			<span class="chck"> <input ${ rowsToSync  === 1 ? 'checked' : ''} type="radio" value="1" name="rows_to_sync"> 1 row			</span>
			<span class="chck">
			<input   ${ rowsToSync  === 10 ? 'checked' : ''} type="radio" value="10" name="rows_to_sync" >			10 rows			</span>
			<span class="chck"> <input ${ rowsToSync  === 20 ? 'checked' : ''} type="radio" value="20" name="rows_to_sync"> 20 rows			</span>
			<span class="chck"> <input ${ rowsToSync  === 30 ? 'checked' : ''} type="radio" value="30" name="rows_to_sync"> 30 rows			</span>
			
		</div>

		<div class="selectOPtionBox">
			<span class="mcrmtitle">Enrich leads
			<div class="miraget-connect-tooltip">
			<img src="${pluginUrl}assets/image/infoicon.png" class="miraget-icon-info-img" /> 
				<span class="miraget-connect-tooltiptext">Yes, will add additional info, like company contacts , etc</span>
			</div>
			</span>
			
			<span class="chck">  <input  ${ enrichLeads === 1 ? 'checked' : ''} type="radio" value="1" name="miraget_enrich_leads" >	 Yes    </span>
			<span class="chck">	 <input  ${ enrichLeads === 2 ? 'checked' : ''} type="radio" value="2" name="miraget_enrich_leads" > No	</span>
			
			
		</div>

		<div class="selectOPtionBox">
		<span class="mcrmtitle">Refresh every </span>
		${optionreFreshData()}
		</div>
		
		<div class="" id="settings-2">
			<h2 class="hndle ui-sortable-handle " id="specific-settings-title">
				<span><span class="hide-if-no-js">Specific Settings</span>
				<span class="hide-if-js">Your Recent Drafts</span></span>
			</h2>
		 </br>
        


		 <div id="select-source" class="selectOPtionBox">
		 	${chooseSourcePlugin(activePlugins,true)}
	 	 </div>
          <div class="selectOPtionBox">
			
			<div id="tables-found">
			</div>
		  </div>







		
		<div class="selectOPtionBox">
			<span class="mcrmtitle" id="slc-tab" style="">Select table </span>
		
			<div class="connector-autocomplete " style="width:250px;margin-left:10px;">
				<input id="table-name-input" name="table-name-input1" type="text" value="" 
					placeholder="type here your table name" spellcheck="false"/>
				<input type="button" onclick="addTable()" id="btn-add" value="ADD">  
			</div>
			<input type="hidden" id="tables-no-source" name="table-name-input" value="" />
		</div>
		<div id="field-for-tables">
		</div>
		<div id="table-to-sync" class="selectOPtionBox">
			${chooseTableTosync(list_plugins[pluginSource-1],true)}
		</div>

		
	
		<div class="selectOPtionBox">
			<span class="mcrmtitle" id="tg-system">Target system</span>
			 <select id="mc-select"name="miraget_plugin_target" class="mc-select-opt" onChange="listOption(this,false)">
				 <option value="1"  ${pluginTarget === 1 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/zohologo.jpg') no-repeat ; width:100px; height:20px; "> Zoho</option>
				 <option value="2"  ${pluginTarget === 2 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/salesmate-logo.png') no-repeat ; width:100px; height:20px; "> Salesmate</option>
				 <option value="3"  ${pluginTarget === 3 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/insightlys.png') no-repeat ; width:100px; height:20px; "> Insightly</option>
				 <option value="4"  ${pluginTarget === 4 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/ma.png') no-repeat ; width:100px; height:20px; "> Microsof Azure </option>
				 <option value="5"  ${pluginTarget === 5 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/amazona.png') no-repeat ; width:100px; height:20px; "> Amazon Aurora</option>
				 <option value="6"  ${pluginTarget === 6 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/amazona.png') no-repeat ; width:100px; height:20px; "> Amazon MYSQL</option>
				 <option value="7"  ${pluginTarget === 7 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/amazona.png') no-repeat ; width:100px; height:20px; "> Amazon Oracle</option>
				 <option value="8"  ${pluginTarget === 8 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/salesforce2.jpg') no-repeat ; width:100px; height:20px; "> Salesforce Basic Connection</option>
				 <option value="9"  ${pluginTarget === 9 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/salesforce2.jpg') no-repeat ; width:100px; height:20px; "> Salesforce OAuth Connection</option>
				 <option value="10"  ${pluginTarget === 10 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/surveymonkey.png') no-repeat ; width:100px; height:20px; "> SurveyMonkey</option>
				 <option value="11"  ${pluginTarget === 11 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/fullcontact.png') no-repeat ; width:100px; height:20px; "> FullContact</option>
				 <option value="12"  ${pluginTarget === 12 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/Marketo.jpg') no-repeat ; width:100px; height:20px; "> Marketo </option>
				 <option value="13"  ${pluginTarget === 13 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/Splunk.png') no-repeat ; width:100px; height:20px; "> Splunk </option>
				 <option value="14"  ${pluginTarget === 14 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/netsuit.png') no-repeat ; width:100px; height:20px; "> NetSuite </option>
				 <option value="15"  ${pluginTarget === 15 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/Snowflake.png') no-repeat ; width:100px; height:20px; "> Snowflake </option>
				 <option value="16"  ${pluginTarget === 16 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/centric.png') no-repeat ; width:100px; height:20px; "> Centric CRM </option>
				 <option value="17"  ${pluginTarget === 17 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/zendesk.png') no-repeat ; width:100px; height:20px; "> Zendesk </option>
				 <option value="18"  ${pluginTarget === 18 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/servicenow.png') no-repeat ; width:100px; height:20px; "> ServiceNow </option>
				 <option value="19"  ${pluginTarget === 19 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/googlebigquery.png') no-repeat ; width:100px; height:20px; "> Google Big query </option>
				 <option value="20"  ${pluginTarget === 20 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/apachehive.jpg') no-repeat ; width:100px; height:20px; "> Apache Hive </option>
				 <option value="21"  ${pluginTarget === 21 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/jira.png') no-repeat ; width:100px; height:20px; "> Jira </option>
				 <option value="22"  ${pluginTarget === 22 ?'selected':''}
				 style="background:url('${pluginUrl}assets/image/sage.png') no-repeat ; width:100px; height:20px; "> Sage </option>
				 
			 </select>
		</div>
		
		<div class="listoptions" id="opList">
			  ${listOption(pluginTarget ,true)}
		</div>
		<div class="selectOPtionBox">
			<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
				<span class="prompt mcrmtitle" for="comments" id=""> Comments
					<div class="miraget-connect-tooltip">
					<img src="${pluginUrl}assets/image/infoicon.png" class="miraget-icon-info-img" /> 
					<span class="miraget-connect-tooltiptext">Please tell us any additional requirements you have here.</span>
					</div>
				</span>
				
				<textarea   name="comment" value="" 
				cols="50" rows="2" style="margin-left:167px; width:442px;">${userComment}
				</textarea>
			</div>
		</div>

	</div>

		<div class="textarea-wrap" id="description-wrap">
             <div class="nothing"> </div>
			<p class="submit miraget-submit">
			<input type="submit" name="save" id="save-post" class="button button-primary" value="Save All changes">
			<br class="clear">
	   </p>
		</div>
	</form>
	</div>
</div>

</div>	</div>` ;
}
//case of plugins with multiple tables

window.addEventListener('load',function(){
	
	select = document.getElementById("mc-select");
	select_s = select.style;
	switch(select.selectedIndex) {
 
		case 0 :
		select_s.background = `url('${pluginUrl}assets/image/zohologo.jpg') no-repeat 220px`;
		break;
		 
		case 1 :
		select_s.background = `url('${pluginUrl}assets/image/salesmate-logo.png') no-repeat 220px`;
		break;
		 
		case 2 :
		select_s.background = `url('${pluginUrl}assets/image/insightlys.png') no-repeat 220px`;
		break;
		 
		case 3 :
		select_s.background = `url('${pluginUrl}assets/image/ma.png') no-repeat 220px`;
		break;
		case 4 :
		select_s.background = `url('${pluginUrl}assets/image/amazona.png') no-repeat 220px`;
		break;
		case 5 :
		select_s.background = `url('${pluginUrl}assets/image/amazona.png') no-repeat 220px`;
		break;
		case 6 :
		select_s.background = `url('${pluginUrl}assets/image/amazona.png') no-repeat 220px`;
		break;
		case 7 :
		select_s.background = `url('${pluginUrl}assets/image/salesforce2.jpg') no-repeat 220px`;
		break;
		case 8 :
		select_s.background = `url('${pluginUrl}assets/image/salesforce2.jpg') no-repeat 220px`;
		break;
		case 9 :
		select_s.background = `url('${pluginUrl}assets/image/surveymonkey.png') no-repeat 220px`;
		break;
		case 10 :
		select_s.background = `url('${pluginUrl}assets/image/fullcontact.png') no-repeat 220px`;
		break;
		case 11 :
		select_s.background = `url('${pluginUrl}assets/image/Marketo.jpg') no-repeat 220px`;
		break;
		case 12 :
		select_s.background = `url('${pluginUrl}assets/image/Splunk.png') no-repeat 220px`;
		break;
		case 13 :
		select_s.background = `url('${pluginUrl}assets/image/netsuite.png') no-repeat 220px`;
		break;
		case 14 :
		select_s.background = `url('${pluginUrl}assets/image/Snowflake.png') no-repeat 220px`;
		break;
		case 15 :
		select_s.background = `url('${pluginUrl}assets/image/centric.png') no-repeat 220px`;
		break;
		case 16 :
		select_s.background = `url('${pluginUrl}assets/image/zendesk.png') no-repeat 220px`;
		break;
		case 17 :
		select_s.background = `url('${pluginUrl}assets/image/servicenow.png') no-repeat 220px`;
		break;
		case 18 :
		select_s.background = `url('${pluginUrl}assets/image/googlebigquery.png') no-repeat 220px`;
		break;
		case 19 :
		select_s.background = `url('${pluginUrl}assets/image/apachehive.jpg') no-repeat 220px`;
		break;
		case 20 :
		select_s.background = `url('${pluginUrl}assets/image/jira.png') no-repeat 220px`;
		break;
		case 21 :
		select_s.background = `url('${pluginUrl}assets/image/sage.png') no-repeat 220px`;
		break;
		  
		 
		default:
		select_s.background = "none";
		break;
		}

	select.addEventListener('change',function(){
		switch(select.selectedIndex) {
 
			case 0 :
			select_s.background = `url('${pluginUrl}assets/image/zohologo.jpg') no-repeat 220px`;
			break;
			 
			case 1 :
			select_s.background = `url('${pluginUrl}assets/image/salesmate-logo.png') no-repeat 220px`;
			break;
			 
			case 2 :
			select_s.background = `url('${pluginUrl}assets/image/insightlys.png') no-repeat 220px`;
			break;
			 
			case 3 :
			select_s.background = `url('${pluginUrl}assets/image/ma.png') no-repeat 220px`;
			break;
			case 4 :
			select_s.background = `url('${pluginUrl}assets/image/amazona.png') no-repeat 220px`;
			break;
			case 5 :
			select_s.background = `url('${pluginUrl}assets/image/amazona.png') no-repeat 220px`;
			break;
			case 6 :
			select_s.background = `url('${pluginUrl}assets/image/amazona.png') no-repeat 220px`;
			break;
			case 7 :
			select_s.background = `url('${pluginUrl}assets/image/salesforce2.jpg') no-repeat 220px`;
			break;
			case 8 :
			select_s.background = `url('${pluginUrl}assets/image/salesforce2.jpg') no-repeat 220px`;
			break;
			case 9 :
			select_s.background = `url('${pluginUrl}assets/image/surveymonkey.png') no-repeat 220px`;
			break;
			case 10 :
			select_s.background = `url('${pluginUrl}assets/image/fullcontact.png') no-repeat 220px`;
			break;
			case 11 :
			select_s.background = `url('${pluginUrl}assets/image/Marketo.jpg') no-repeat 220px`;
			break;
			case 12 :
			select_s.background = `url('${pluginUrl}assets/image/Splunk.png') no-repeat 220px`;
			break;
			case 13 :
			select_s.background = `url('${pluginUrl}assets/image/netsuite.png') no-repeat 220px`;
			break;
			case 14 :
			select_s.background = `url('${pluginUrl}assets/image/Snowflake.png') no-repeat 220px`;
			break;
			case 15 :
			select_s.background = `url('${pluginUrl}assets/image/centric.png') no-repeat 220px`;
			break;
			case 16 :
			select_s.background = `url('${pluginUrl}assets/image/zendesk.png') no-repeat 220px`;
			break;
			case 17 :
			select_s.background = `url('${pluginUrl}assets/image/servicenow.png') no-repeat 220px`;
			break;
			case 18 :
			select_s.background = `url('${pluginUrl}assets/image/googlebigquery.png') no-repeat 220px`;
			break;
			case 19 :
			select_s.background = `url('${pluginUrl}assets/image/apachehive.jpg') no-repeat 220px`;
			break;
			case 20 :
			select_s.background = `url('${pluginUrl}assets/image/jira.png') no-repeat 220px`;
			break;
			case 21 :
			select_s.background = `url('${pluginUrl}assets/image/sage.png') no-repeat 220px`;
			break;
			 
			default:
			select_s.background = "none";
			break;
			}
	})
	
})


function listOption(index,load){

	let indexed ;
	if(load) indexed =  index.toString() ;
	else indexed = index.value ;
	let html_list = '' ;
	

   //code to change image background 
   
	
   if( indexed === '1' ){
	html_list += `
	<div class="selectOPtionBox">
	  <span class="mcrmtitle">Update Target
	  <div class="miraget-connect-tooltip">
	  <img src="${pluginUrl}assets/image/infoicon.png" class="miraget-icon-info-img" /> 
			<span class="miraget-connect-tooltiptext">Yes will update if the data is existing in your target, No will insert only</span>
	   </div>
	  </span>

		<span class="chck">
		<input   ${ updCrmRecord  === 1 ? 'checked' : ''} type="radio" value="1" name="upd_crm_record" >		Yes
	   </span>
	   <span class="chck"> <input ${ updCrmRecord  === 2 ? 'checked' : ''} type="radio" value="2" name="upd_crm_record"> 		No
	   </span>
	   
	</div>
	<div class="selectOPtionBox">
	 <span class="mcrmtitle">API version  </span>
	 <span class="chck">	<input ${ pluginTargetVersion  === 1 ? 'checked' : ''} type="radio" value="1" name="miraget_plugin_zoho_vars" onchange="apiV1(this,false)"  >	API v1  </span>
	 <span class="chck">  <input ${ pluginTargetVersion  === 2 ? 'checked' : ''} type="radio" value="2" name="miraget_plugin_zoho_vars" onchange="apiV1(this,false)"  > 	API v2  </span>


	<div class="span" id="span">
		${apiV1(pluginTargetVersion,true)}
	</div>
		</div>
	`




}

	//salesmate
	else if( indexed === '2' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Access Key </span>
			<input  type="text" name="salesmate_user" value="${insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Secret Key </span>
			<input  type="text" name="salesmate_pass" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Session Key </span>
			<input  type="text" name="salesmate_session" value="${insightlyTokenApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Url </span>
			<input  type="text" name="salesmate_url" value="${insightlyTargetApi}">
		</div>
	</div>
` ;
//adding insightly 
else if(indexed === '3')
	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Access Key </span>
			<input  type="text" name="insightly_access_key" value="${insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Secret Key </span>
			<input  type="text" name="insightly_secret_key" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Session Key</span>
			<input  type="text" name="insightly_session_key" value="${insightlySessionKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Target Api</span>
			<input  type="text" name="insightly_target_api" value="${insightlyTargetApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id="">  Api Token</span>
			<input  type="text" name="insightly_token_api" value="${insightlyTokenApi}">
		</div>
	</div>
` ;
else if( indexed >= '4' &&  indexed < '8')

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Host</span>
			<input  type="text" name="host" value="${ insightlyTargetApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Port </span>
			<input  type="text" name="port" value="${codeZoho}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> User </span>
			<input  type="text" name="user" value="${ insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> PWD </span>
			<input  type="text" name="pwd" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> DataBase </span>
			<input  type="text" name="database" value="${zohoToken}">
		</div>
	</div>
` ;
else if( indexed === '8' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> User</span>
			<input  type="text" name="user" value="${salesmateUser}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> PWD</span>
			<input  type="text" name="pwd" value="${salesmatePass}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Security Token </span>
			<input  type="text" name="security_token" value="${zohoToken}">
		</div>
		
	</div>
` ;
else if( indexed === '9' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Issuer</span>
			<input  type="text" name="issuer" value="${insightlyTargetApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Subject </span>
			<input  type="text" name="subject" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Expiration Time </span>
			<input  type="text" name="expiration_time" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Key Store  </span>
			<input  type="text" name="key_store" value="${insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Keystore Password </span>
			<input  type="text" name="keystore_pwd" value="${insightlyTokenApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Security Token </span>
			<input  type="text" name="security_token" value="${clientId}">
		</div>
	</div>
` ;

else if( indexed === '10' || indexed === '11')

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> client_id</span>
			<input  type="text" name="client_id" value="${salesmateUser}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> client_secret</span>
			<input  type="text" name="client_secret" value="${salesmatePass}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Code </span>
			<input  type="text" name="fl-code" value="${zohoToken}">
		</div>
		
	</div>
` ;

else if( indexed === '12' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Endpoint</span>
			<input  type="text" name="endpoint" value="${salesmateUser}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> ClientClassID</span>
			<input  type="text" name="client-class-id" value="${salesmatePass}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> SecretKey</span>
			<input  type="text" name="secret-key" value="${zohoToken}">
		</div>
		
	</div>
` ;

else if( indexed === '13' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> URL</span>
			<input  type="text" name="splunk-url" value="${salesmateUrl}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Token</span>
			<input  type="text" name="splunk-token" value="${zohoToken}">
		</div>
	</div>
` ;

else if( indexed === '14' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Endpoint</span>
			<input  type="text" name="netsuite-endpoint" value="${insightlyAccessKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Email </span>
			<input  type="text" name="netsuite-email" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="netsuite-password" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Role  </span>
			<input  type="text" name="netsuite-role" value="${insightlyTargetApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Account </span>
			<input  type="text" name="netsuite-account" value="${insightlyTokenApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> ApplicationID </span>
			<input  type="text" name="netsuite-appid" value="${clientId}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> RecodeType </span>
			<input  type="text" name="netsuite-recordtype" value="${secretId}">
		</div>
	</div>
` ;

else if( indexed === '15' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> account</span>
			<input  type="text" name="snowflake-account" value="${insightlyAccessKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> UserID </span>
			<input  type="text" name="snowflake-userid" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="snowflake-password" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Warehouse  </span>
			<input  type="text" name="snowflake-warehouse" value="${insightlyTargetApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Schema </span>
			<input  type="text" name="snowflake-schema" value="${insightlyTokenApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Database </span>
			<input  type="text" name="snowflake-database" value="${clientId}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Table </span>
			<input  type="text" name="snowflake-table" value="${secretId}">
		</div>
	</div>
` ;

else if( indexed === '16' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Url</span>
			<input  type="text" name="centric-url" value="${insightlyAccessKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Module </span>
			<input  type="text" name="centric-module" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Server </span>
			<input  type="text" name="centric-server" value="${ insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> UserID  </span>
			<input  type="text" name="centric-userid" value="${insightlyTargetApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="centric-password" value="${insightlyTokenApi}">
		</div>
		
	</div>
` ;

else if( indexed === '17' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Url</span>
			<input  type="text" name="zendesk-url" value="${insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> UserName </span>
			<input  type="text" name="zendesk-username" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="zendesk-password" value="${insightlySessionKey }">
		</div>
		
	</div>
` ;

else if( indexed === '18' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Url</span>
			<input  type="text" name="servicenow-url" value="${insightlyAccessKey  }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> UserName </span>
			<input  type="text" name="servicenow-username" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="servicenow-password" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Table  </span>
			<input  type="text" name="servicenow-table" value="${insightlyTargetApi}">
		</div>		
	</div>
` ;

else if( indexed === '19' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> ClientId</span>
			<input  type="text" name="googlebg-clientid" value="${insightlyAccessKey  }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> ClientSecret</span>
			<input  type="text" name="googlebg-clientsecret" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> ProjectId </span>
			<input  type="text" name="googlebg-projectid" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Authorization Code </span>
			<input  type="text" name="googlebg-authcode" value="${insightlyTokenApi}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Dataset </span>
			<input  type="text" name="googlebg-dataset" value="${insightlyTargetApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Table </span>
			<input  type="text" name="googlebg-table" value="${clientId}">
		</div>
	</div>
` ;
else if( indexed === '20' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Host</span>
			<input  type="text" name="apachehive-host" value="${insightlyTargetApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> Port </span>
			<input  type="text" name="apachehive-port" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Database </span>
			<input  type="text" name="apachehive-database" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> UserName  </span>
			<input  type="text" name="apachehive-username" value="${insightlyTokenApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="apachehive-password" value="${insightlyAccessKey}">
		</div>
		
	</div>
` ;

else if( indexed === '21' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Url</span>
			<input  type="text" name="jira-url" value="${insightlyAccessKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> UserID </span>
			<input  type="text" name="jira-userid" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="jira-password" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> JiraRessource  </span>
			<select name="jira-ressource"  style="width:250px; margin-left:170px;">
				<option value="issue" ${ insightlyTargetApi=='issue' ? 'selected' :'' }>Issue</option>
				<option value="project" ${ insightlyTargetApi=='project' ? 'selected' :'' }>Project</option>
			</select>
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> JQL </span>
			<input  type="text" name="jira-jql" value="${insightlyTokenApi}">
		</div>
		
	</div>
` ;

else if( indexed === '22' )

	html_list = `
	<div class="selectOPtionBox">
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="user" id=""> Endpoint</span>
			<input  type="text" name="sage-endpoint" value="${ insightlyAccessKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Password" id=""> User </span>
			<input  type="text" name="sage-user" value="${insightlySecretKey}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Password </span>
			<input  type="text" name="sage-password" value="${insightlySessionKey }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Language  </span>
			<input  type="text" name="sage-language" value="${ insightlyTargetApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Pool alias </span>
			<input  type="text" name="sage-pollalias" value="${insightlyTokenApi }">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Request config </span>
			<input  type="text" name="sage-requestconfig" value="${clientId}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Publication name </span>
			<input  type="text" name="sage-publicationname" value="${secretId}">
		</div>
		<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
			<span class="prompt mcrmtitle" for="Salesforce" id=""> Action </span>
			<input  type="text" name="sage-action" value="${field19}">
		</div>
	</div>
` ;




	else html_list = '';
	if(load) {
		return html_list ;
	}
	  else document.getElementById('opList').innerHTML = html_list ;

}




function apiV1(index, load){

	let indexe ;
	if(load) indexe =  index.toString() ;
	else indexe = index.value ;
	
	let html_list = '' ;

	//zoho v1
	if( indexe === '1' ){
	 html_list += `
				<div class="selectOPtionBox zohoLink">
					<div class="input-text-wrap miraget-info " id="title-wrap" style="margin:10px 0;">
						<span class="prompt mcrmtitle" for="zoho_token" id=""> Zoho token </span>
						<input type="text" name="zoho_token" value="${zohoToken}">
					</div>
				</div>
				<a target="_blank" href="https://miraget.com/how-to-to-fill-zoho-api-credentials/">How to get Zoho token</a>
			`;
			
 }

	else if( indexe === '2' )
	 //zoho v2
		 html_list += `
 			</br></br>
			 <div class="m-sisforce"> 
			 	<a target="_blank" href="https://www.zoho.com/crm/help/api/v2/#oauth-request">Get Zoho Oauth credentials</a> 			
				<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px  0;">
					<span class="prompt mcrmtitle " for="title" id="">			Client Id			</span>
					<input class="zoho-apiv2" type="text" name="client_id" id="title" autocomplete="off" value="${clientId}">

				</div>
				<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
					<span class="prompt mcrmtitle " for="secret_id" id=""> 	Secret Id 	</span>
					<input class="zoho-apiv2" type="text" name="secret_id" id="title" autocomplete="off" value="${secretId}">
				</div>
				<div class="input-text-wrap miraget-info" id="title-wrap" style="margin:10px 0;">
					<span class="prompt mcrmtitle " for="title" > 	Zoho Code </span>
					<input class="zoho-apiv2" type="text" name="code" id="title" autocomplete="off" value="${codeZoho}">
				</div>
				<div class="selectOPtionBox" style="margin:10px 0;">
					<span class="prompt mcrmtitle">Data center  </span>
					<span class="chck">
						<input ${ zohoDataCenter  === 1 ? 'checked' : ''} checked="" type="radio" value="1" name="zoho_datacenter"> 	.EU			</span>
					<span class="chck">
					<input ${ zohoDataCenter  === 2 ? 'checked' : ''} type="radio" value="2" name="zoho_datacenter" > .CN			</span>
					<span class="chck">
					<input ${ zohoDataCenter  === 3 ? 'checked' : ''} type="radio" value="3" name="zoho_datacenter" > .COM		</span>
			   </div>
			</div>

   	`;
	

		else html_list = '';
		if(load) {
			return html_list ;
		}
		  else document.getElementById('span').innerHTML = html_list ;


}

function optionreFreshData(){
	let hrm = '' ;
	let selected = '' ;
	const refreshTime = [1,5,10,15]
	//for (let index = 5; index <= 15; index += 5  ) {
	for (let i = 0; i < refreshTime.length; i++  ) {
		if( refreshTime[i] === refresh_data ) selected = 'checked' ;
		else selected = '' ;
		
		hrm += `
		<span class="chck">
			<input ${ selected } type="radio" value="${refreshTime[i]}" name="miraget_plugin_refresh_data" >
			${refreshTime[i]} minutes
		</span>
		` ;
	}



	return hrm ;
}


function emailActivity(){
	return `
	<div id="community-events" class="community-events" aria-hidden="false">
	<div class="activity-block">
		<p>
			<span id="community-events-location-message" aria-hidden="false">
			Last records sent
			</span>

			<!--<button class="button-link community-events-toggle-location" aria-label="Edit city" aria-expanded="false" aria-hidden="false">
				<span class="dashicons dashicons-edit"></span>
			</button>-->
		</p>
	</div>
	<ul class="community-events-results activity-block last" aria-hidden="false">
	 ${emailRows()}

</ul>

</div>` ;
}


function emailRows(){

    if( emailes.length === 0 ) {
		
		return '<p class="event event-wordcamp wp-clearfix no-e-s">No record sent !!</p>' };
    let returnd = '' ;
	for (let index = 0; index <  emailes.length ; index++) {
		var date = new Date( emailes[index].time * 1000  );
       const t = date.toString().replace('GMT+0100 (Romance Standard Time)','') ;

		returnd += `
			<li class="event event-wordcamp wp-clearfix">
				<div class="event-info">
					<div class="dashicons event-icon" aria-hidden="true"></div>
					<div class="event-info-inner">
						 ID = ${emailes[index].id}
						 <div class="miraget-record-content">
							 ${emailes[index].content.substring(0,60)+'...'}
						 </div>
						 
						<!--<span class="event-city">Granada, Granada, Spain</span>-->
					</div>
				</div>

				<div class="event-date-time">
					<!--<span class="event-date">Saturday, Nov 17, 2018</span>-->
					<span class="event-date">${t}</span>

				</div>
			</li>
		`;
	}

return returnd ;
}
//list of all plugins used as data source 

var list_plugins = ['OptIn Panda',
					'OptIn monster',
					'leadsgen',
					'MailChimp Forms',
					'Emails Subscribers' ,
					'Google Analytic Dashboard',
					'DeliPress',
					'Smart Store Manager',
					'CRM Perks Forms',
					'WP-CRM',
					'SpeakOut',
					'Easy Digital Downloads',
					'Email Popup',
					'AWeber Forms',
					'Campaign Monitor',
					'GetResponse Forms',
					'Mad Mimi Forms',
					'WooCommerce Product Options',
					'WooCommerce Lucky Wheel',
					'FormLift Forms',
					'Jumplead Marketing Software',
					'Formidable Forms',
					'MailOptin',
					'Newsletter Subscription Form',
					'WP Popup Lite',
					'Popup by Supsystic',
					'Snappy List Builder',
					'SmartTouch NextGen Form Builder',
					'Product Catalog',
					'Store Locator WordPress',
					'Multi Rating',
					'CRM WordPress Leads',

					'WP ERP',
					'LifterLMS',
					'Ninja Forms',
					'Plugmatter Optin',
					'WP smart CRM',

					'Electronic Signature',
					'Subscribe To Comments Reloaded',
					'WooCommerce Salesforce Plugin',
					'weForms',
					'WP Optin Wheel',
					'Viral Signup',
					'BePro Listings',
					'Affiliates Manager',
					'WordPress CRM Plugin',
					'Newsletter subscription', 
					'WP Support Plus',
					'MailPoet Newsletters'];

// auto complete function
function autocomplete(inp, arr) {
	/*the autocomplete function takes two arguments,
	the text field element and an array of possible autocompleted values:*/
	var currentFocus;
	
	/*execute a function when someone writes in the text field:*/
	inp.addEventListener("input", function(e) {
		var a, b, i, val = this.value;
		/*close any already open lists of autocompleted values*/
		closeAllLists();
		if (!val) { return false;}
		currentFocus = -1;
		/*create a DIV element that will contain the items (values):*/
		a = document.createElement("DIV");
		a.setAttribute("id", this.id + "autocomplete-list");
		a.setAttribute("class", "autocomplete-items");
		/*append the DIV element as a child of the autocomplete container:*/
		this.parentNode.appendChild(a);
		/*for each item in the array...*/
		for (i = 0; i < arr.length; i++) {
		  /*check if the item starts with the same letters as the text field value:*/
		  if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {
			/*create a DIV element for each matching element:*/
			b = document.createElement("DIV");
			/*make the matching letters bold:*/
			b.innerHTML = "<strong>" + arr[i].substr(0, val.length) + "</strong>";
			b.innerHTML += arr[i].substr(val.length);
			/*insert a input field that will hold the current array item's value:*/
			b.innerHTML += "<input type='hidden' value='" + arr[i] + "'>";
			
			/*execute a function when someone clicks on the item value (DIV element):*/
			b.addEventListener("click", function(e) {
				/*insert the value for the autocomplete text field:*/
				inp.value = this.getElementsByTagName("input")[0].value;

				index_plugin = arr.findIndex(function(elm){
                    return elm == inp.value
				});
				//document.getElementById("p-source").value = index_plugin+1;
				closeAllLists();
				chooseTableTosync(list_plugins[index_plugin]);
			});
			a.appendChild(b);
		  }
		}
	});

	inp.addEventListener("keydown", function(e) {
		var x = document.getElementById(this.id + "autocomplete-list");
		if (x) x = x.getElementsByTagName("div");
		if (e.keyCode == 40) {
		 
		  currentFocus++;
		 
		  addActive(x);
		} else if (e.keyCode == 38) { //up
		  
		  currentFocus--;
		 
		  addActive(x);
		} else if (e.keyCode == 13) {
		 
		  e.preventDefault();
		  if (currentFocus > -1) {
			
			if (x) x[currentFocus].click();
		  }
		}
		
	});
	function addActive(x) {
	  
	  if (!x) return false;
	  
	  removeActive(x);
	  if (currentFocus >= x.length) currentFocus = 0;
	  if (currentFocus < 0) currentFocus = (x.length - 1);
	
	  x[currentFocus].classList.add("autocomplete-active");
	}
	function removeActive(x) {
	  
	  for (var i = 0; i < x.length; i++) {
		x[i].classList.remove("autocomplete-active");
	  }
	}
	function closeAllLists(elmnt) {
	 
	  var x = document.getElementsByClassName("autocomplete-items");
	  for (var i = 0; i < x.length; i++) {
		if (elmnt != x[i] && elmnt != inp) {
		  x[i].parentNode.removeChild(x[i]);
		}
	  }
	}
	
	document.addEventListener("click", function (e) {
		closeAllLists(e.target);
		
	});
  }

 //for plugins that have more than one table display an option box
 pluginTablesArr = [];
 // to choose the tables to send 
  function chooseTableTosync(pluginname,load){
	var html_to_add = ``;
	var index_plugin = list_plugins.findIndex(function(e){
		return e== pluginname
	});
	if(list_plugins.slice(32,37).includes(pluginname) ){
		html_to_add += `<span class="prompt mcrmtitle"> Table To Sync</span>
						<select name="plugin-selected-table" required="required" style="width:250px; margin-left:10px;">`
						for(var i=0 ; i < pluginTablesArr[index_plugin+1].length ;i++ ){
							
							html_to_add += `<option value="${i}"  ${ pluginSelectedTable===i ? 'selected' :'' }>${pluginTablesArr[index_plugin+1][i]}</option>`             
						 }					
		html_to_add +=	`</select>`;
		
	
	}
	if(load) 
		return html_to_add;
	else document.getElementById("table-to-sync").innerHTML = html_to_add;
}

  //function to add select option for plugin source field
   function chooseSourcePlugin(listPlugins,load){

	    var html_to_add = ``;
		html_to_add += `<span class="prompt mcrmtitle"> Select Plugin</span>
		<select id="mr-slc-src" name="plugin-source" required="required" style="width:250px; margin-left:10px;">`
		for(var i=0 ; i < listPlugins.length ;i++ ){
			
			html_to_add += `<option value="${listPlugins[i]}" ${ pluginSource==listPlugins[i] ? 'selected' :''} >${listPlugins[i]}</option>`             
		 }					
		html_to_add +=	`</select>`;
		if(load) 
		return html_to_add;
	    else document.getElementById("table-to-sync2").innerHTML = html_to_add;
     }
   
   /*********************************************************************** */
    
	 
	
  //function to add selecte feild for each table
function getPluginTables() {
	var arrTablesSaved = JSON.parse(pluginSelectedTable);
	document.querySelector('#table-name-input').style.visibility = 'hidden';
	document.querySelector('#slc-tab').style.visibility = 'hidden';
	document.querySelector('#btn-add').style.visibility = 'hidden';
	var tablesFiltred = [];
	//split the name of plugin by "_","-", "capital lettre", and tolowecase
	//to find the value in all tables name 
	var arrPluginName = document.querySelector('#mr-slc-src').value
	.split(/(?=[A-Z])|-|_/).map(e=>e.toLowerCase());

	for (var i = 0; i < arrPluginName.length; i++) {
		if (arrPluginName[i].length > 4) {
			var tab = allTables.filter(e => e.includes(arrPluginName[i]));

			for (j = 0; j < tab.length; j++) {
				tablesFiltred.push(tab[j]);
			}
		}
	}
	var tablesDiv = document.querySelector('#tables-found');
	tablesDiv.innerHTML = '';
	
	if (tablesFiltred.length > 0) {
		document.querySelector('#tables-no-source').value ='';
		var hiddenfield = document.createElement("input");
		hiddenfield.setAttribute("type","hidden");
		hiddenfield.setAttribute("name","hidden-field");
		hiddenfield.setAttribute("value","1");
        document.querySelector('#field-for-tables').innerHTML ='';
		for (var i = 0; i < tablesFiltred.length; i++) {
			//
            
			var label = document.createElement('div');
			var inpt = document.createElement('input');
			
			label.setAttribute("for", tablesFiltred[i]);
			label.classList.add('mr-checkbox-span');
			inpt.classList.add("mr-checkbox");
			inpt.setAttribute("type", "checkbox");
			inpt.setAttribute("value", tablesFiltred[i]);
			inpt.setAttribute("name", "multi-table-sync[]");
			
			if( arrTablesSaved && arrTablesSaved.includes( tablesFiltred[i]) ){
				inpt.setAttribute("checked","checked");
			}
		
			var txt = document.createTextNode(tablesFiltred[i]);
			label.appendChild(inpt);
			label.appendChild(document.createTextNode(' '));
			label.appendChild(txt);
			tablesDiv.appendChild(label);

			

		}
		tablesDiv.appendChild(hiddenfield);
	} else {
		tablesDiv.innerHTML = '  No Table found, please select a table ';
		//document.querySelector('#tables-no-source').value ='';
		document.querySelector('#slc-tab').style.visibility = 'initial';
		document.querySelector('#table-name-input').style.visibility = 'initial';
		document.querySelector('#btn-add').style.visibility = 'initial';
		//document.querySelector('#table-name-input').value = '';
	}
}
  
window.addEventListener('load', function () {


	 createTabesSaved();
	 
     if(emailes.length === 0){
		document.querySelector('#purge-history').style.visibility = 'hidden';
	 }
	//autocomplete(document.getElementById("plugin-name-input"), activePlugins);
	autocomplete(document.getElementById("table-name-input"), allTables);
    getPluginTables();
	document.querySelector('#mr-slc-src')
		.addEventListener('change', getPluginTables);

	let tokenValue = document.querySelector('#miraget-token-wp').value;

	if( tokenValue.includes('nocurl')){
		const encodeUri = encodeURIComponent(tokenValue.slice(6)) ;
		const apiUrl = "https://miraget.com/api/?url=" + encodeUri;

		const Http = new XMLHttpRequest();
		
		Http.open("GET", apiUrl);
		Http.send();
		Http.onreadystatechange=(e)=>{
			const obj = JSON.parse(Http.responseText);
			const tokenNoCurl = obj.Token;
			document.querySelector('#miraget-token-wp').value  = tokenNoCurl;
			console.log('get token with js call, php cur is disabled')
		}


	}


});
	
 
  function addTable(){
	  
	var tbl  = document.querySelector('#table-name-input').value;
	const tablesAlreadyExist = document.querySelector('#tables-no-source').value.split(',');
	//const tablesAlreadyExist = JSON.parse(pluginSelectedTable)
	
	if(allTables.includes(tbl) ) 
  { 
	    if(!tablesAlreadyExist.includes(tbl)){var span = document.createElement('span');
		span.setAttribute('class','elm-to-add');
		
		var txt = document.createTextNode(tbl);
		//create remove button 
		var btn = document.createElement('input');
		btn.setAttribute('type','button');
		btn.setAttribute('value','X')
		//var btn_txt = document.createTextNode('X');
		//btn.appendChild(btn_txt);
		btn.onclick  = function (){ document.querySelector('#field-for-tables').removeChild(span);
		
										document.querySelector('#tables-no-source').value = 
										document.querySelector('#tables-no-source').value.replace(span.firstChild.textContent+',','');
										
									};
		span.appendChild(txt);
		span.appendChild(btn);
		document.querySelector('#field-for-tables').appendChild(span);
		//allTables = allTables.filter(table=>table != tbl );
		document.querySelector('#table-name-input').value = '';
		document.querySelector('#tables-no-source').value += tbl+',';
		autocomplete(document.getElementById("table-name-input"), allTables);}else alert('Tables already added')
		
}
else{
	alert('Please select an existing table')
}
    
   

  }

  function createTabesSaved(){
	var pst = JSON.parse(pluginSelectedTable);
	
	
	for(var i =0 ; i < pst.length; i++){
       
		const span = document.createElement('span');
		span.setAttribute('class','elm-to-add');
		
		var tbl  = pst[i];
		var txt = document.createTextNode(pst[i]);
		//create remove button 
		var btn = document.createElement('input');
		btn.setAttribute('type','button');
		btn.setAttribute('value','X')
		span.appendChild(txt);
		span.appendChild(btn);
		
		btn.onclick  = function (){ 
										
										document.querySelector('#field-for-tables').removeChild(span);
										
										document.querySelector('#tables-no-source').value = 
										document.querySelector('#tables-no-source').value.replace(span.firstChild.textContent+',','');
									  
								 };
		
		document.querySelector('#field-for-tables').appendChild(span);
		//allTables = allTables.filter(table=>table != tbl );
		document.querySelector('#tables-no-source').value += tbl+',';
       
	}

  }
  
  

//end auto complete function


function timesAdjust( time ){

}


Date.prototype.yyyymmdd = function( ) {
	var mm = this.getMonth() + 1; // getMonth() is zero-based
	var dd = this.getDate();

	return [this.getFullYear(),
			(mm>9 ? '' : '0') + mm,
			(dd>9 ? '' : '0') + dd
		   ].join('');
  };
 
// add a client side form validation 

/* <div class="selectOPtionBox">
<span class="mcrmtitle">Target system</span>
<span class="chck">  <input onChange="listOption(this,false)" ${ pluginTarget === 1 ? 'checked' : ''} type="radio" value="1" name="miraget_plugin_target" >	 
<img src="${pluginUrl}assets/image/zohologo.jpg" class="miraget-logo-img" />  </span>
<span class="chck">	 <input onChange="listOption(this,false)"  ${ pluginTarget === 2 ? 'checked' : ''} type="radio" value="2" name="miraget_plugin_target" > 
<img src="${pluginUrl}assets/image/salesmate-logo.png" class="miraget-logo-img" />	</span>
<span class="chck">	 <input onChange="listOption(this,false)"  ${ pluginTarget === 3 ? 'checked' : ''} type="radio" value="3" name="miraget_plugin_target" > 
<img src="${pluginUrl}assets/image/insightlys.png" class="miraget-logo-img" />	</span>
</div> */
// ${pluginSelectedTable[0]!='0'?JSON.parse(pluginSelectedTable)[0]:''}