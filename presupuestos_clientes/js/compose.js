$(function(){
	/*
	$('#showContent').click(function(){
		content = (tinyMCE.get('message')) ? tinyMCE.get('message').getContent(): $('#message').val();
		console.log(content);
		alert(content.length);
	});
	*/
	$("#loadTemplateDialog").dialog({
		width:400,
		maxWidth:800,
		height:500,
		autoOpen: false,
		modal:true
	});
	$("#saveTemplateDialog").dialog({
		autoOpen: false,
		modal:true
	});
	$('#loadDraftsDialog').dialog({
		autoOpen: false,
		modal:true,
		position: 'center',
		maxWidth:800,
		width: 600
	});
	$('#attachmentDialog').dialog({
		autoOpen: false,
		modal:true,
		position: 'center',
		width: 580,
		height: 130
	});
	$("#sendTestDialog").dialog({
		autoOpen: false
	});
	$('#fieldNoteDialog').dialog({
		autoOpen: false
	});

	$('#data_fields').hide();
	var leaveWarning = true;

	$('#addAttachment').click(function(){
		$('#attachmentDialog').dialog('open'); // open jQuery dialog modal
	});
	$('#field_note').live('click', function(){
		$('#fieldNoteDialog').dialog('open'); // open jQuery dialog modal
	});
	$('#send_test').click(function(event){
		event.preventDefault();
		$("#sendTestDialog").dialog('open'); // open jQuery dialog modal
		$('#sendTestAddress').focus().select();
	});
	$('#testSend').click(function(event){
		$('#testResult').html('<img src="images/ajax-loader.gif"> Sending test...');
		event.preventDefault();
		content = (tinyMCE.get('message')) ? tinyMCE.get('message').getContent(): $('#message').val();
		subject = $('#subject').val();
		to = $('#sendTestAddress').val();
		format = $('#format').val();
		attachments = '';
		$('input[name="attachment[]"]').each(function(){
			if($(this).val() != '') attachments += $(this).val() +',';
		});
		$.post('ajax/test-send.php', {subject:subject,message:content,to:to,attachments:attachments,format:format}, function(data) {
  			if(data == 1) $('#testResult').html('<img src="images/check.png"> Test message sent successfully').show().delay(5000).fadeOut(750);
  			if(data == 0) $('#testResult').html('<img src="images/delete.png"> Error sending message').show().delay(5000).fadeOut(750);
  			if(data == 3) $('#testResult').html('<img src="images/warning.png"> Error adding attachment').show().delay(5000).fadeOut(750);
		});
	});

	// Send message for processing
	$('#send').click(function(){
		// check subject and message for empty. check lists for minimum one selected.
		contentLength = (tinyMCE.get('message')) ? tinyMCE.get('message').getContent().length-200: $('#message').val().length;
		if($('input[name="lists[]"]:checked').length==0)
		{
			alert('You need to select at least one list to send your message to.');
			return false;
		}
		if($('#recipient_count').text() == '0 recipients')
		{
			alert('The list(s) you have chosen have no recipients. Please check your selections and try again.');
			return false;
		}
		if($('#subject').val().length==0 || contentLength==0)
		{
			alert('Please make sure you have filled out both the subject and message.');
			return false;
		}
		if( $('input[name=when]:checked').val() == "later" && $('#datepicker').val()=='' )
		{
			alert('Please enter a date to begin delivery');
			$('#datepicker').focus();
			return false;
		}
		if(!confirm('Send message?'))
		{
			return false;
		}
	});

	///////////////
	// TEMPLATES //
	///////////////

	$('#loadTemplate').click(function(){
		$("#loadTemplateDialog").dialog('open'); // open jQuery dialog modal
		$.get("ajax/list-templates.php", function(data){ // Call ajax/list-templates.php to generate the list of templates and populate dialog box
			$('#loadTemplateDialog').html(data);
		});
	});
	$('#saveTemplate').click(function(){
		$("#saveTemplateDialog").dialog('open'); // open jQuery dialog modal
	});
	$('#save').click(function(event){
		event.preventDefault(); // Stops the save button from submitting the save template form
		$('#save').after('<img id="template_waiting" src="images/ajax-loader.gif">');
		content = (tinyMCE.get('message')) ? tinyMCE.get('message').getContent(): $('#message').val();
		$.get("ajax/save-template.php", {filename:$('#saveTemplateName').val(), content:content}, function(data){
			$('#template_waiting').remove();
			if(data=='success')
			{
				$('#saveTemplateDialog').dialog('close'); // close jQuery dialog modal upon choosing a template
				$('#notification').html('Template successfully saved');
			}
			else if(data=='exists')
			{
				alert('The template name you entered already exists or is blank. Please choose another.');
			}
			else if(data=='unwritable')
			{
				alert('The templates folder cannot be written to. Please check the permissions and try again.');
			}
		});
	});
	$('.template').live('click', function(event) {
		event.preventDefault();
		$('#loadTemplateDialog').dialog('close'); // close jQuery dialog modal upon choosing a template
		if(confirm('Loading a template will overwrite any content currently in the message window. Continue?'))
		{
			if(tinyMCE.get('message')) tinyMCE.get('message').setProgressState(1);
			$.get("ajax/get-template.php", {filename:$(this).text()}, function(data){ // Call ajax/get-template.php to retrieve content of template and populate editor
				if(tinyMCE.get('message'))
				{
					tinyMCE.get('message').setProgressState(0);
					tinyMCE.get('message').setContent(data);
				}
				else $('#message').val(data);
			});
		}
	});
	$('.delete_template').live('click', function(event) {
		event.preventDefault();
		if(confirm('Are you sure you want to delete this template?'))
		{
			$.get("ajax/delete-template.php", {filename:$(this).attr('href')}, function(data){ // Call ajax/delete-template.php to delete template
				// possibly update notification box with message
				$('#loadTemplateDialog').dialog('close'); // close jQuery dialog modal upon success
			});
		}
	});

	///////////////////////
	// DRAFTS & AUTOSAVE //
	///////////////////////

	function autosave(){
		contentLength = (tinyMCE.get('message')) ? tinyMCE.get('message').getContent().length-200: $('#message').val().length;
		if(contentLength>0 && $('#subject').val().length > 0) // Only save a draft if the subject and message are not empty
		{
			attachments = ($('input[name="attachment[]"]').length > 1) ? $('#attachments ul').html():'';
			content = (tinyMCE.get('message')) ? tinyMCE.get('message').getContent(): $('#message').val();
			$.get("ajax/autosave.php", {firstTime:firstTime, id:messageID, subject:$('#subject').val(), content:content, format:$('#format').val(), attachments:attachments}, function(data){
				if(data != 0)
				{
					var now = new Date();
					$('#notification').html('Draft saved: '+now);
					messageID=data;
					$.get("ajax/draft-count.php", function(data){
						$('#drafts').show();
						$('#loadDrafts').html(data);
					});
					if(firstTime)
					{
						firstTime = false;
						$('form:eq(0)').append('<input type="hidden" name="draftID" value="'+messageID+'">');
					}
					return true;
				} else { $('#notification').html('Error saving draft. See log'); }
			});
		}
		else { return false; }
	}

	/////////////
	// TINYMCE //
	/////////////

	// Initialize TinyMCE
	function renderTinyMCE(){
		tinyMCE.init({
			theme : 'advanced',
			mode : 'exact',
			elements : 'message',
			content_css : "css/tinymce.css",
			height: '250',
			relative_urls : false,
			remove_script_host : false,
			setup : function(ed) {
				ed.onExecCommand.add(function(ed, cmd, ui, val) {
					if(cmd == "mceSetContent") $('#subject').val('');
				});
			},
			pdw_toggle_on : 1,
			pdw_toggle_toolbars : '2,3,4',
			plugins : 'fullpage,pdw,safari,layer,table,save,advhr,advimage,advlink,emotions,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras',
			// Theme options
			theme_advanced_buttons1 : 'newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,fontselect,fontsizeselect,formatselect,forecolor,backcolor,|,print,fullscreen,code,|,pdw_toggle',
			theme_advanced_buttons2 : 'cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,|,sub,sup',
			theme_advanced_buttons3 : 'tablecontrols,|,hr,removeformat,visualaid,|,charmap,emotions,iespell,media,advhr,|,ltr,rtl',
			theme_advanced_buttons4 : 'insertlayer,moveforward,movebackward,absolute,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,blockquote,|,insertdate,inserttime,preview',
			theme_advanced_toolbar_location : 'top',
			theme_advanced_toolbar_align : 'left',
			theme_advanced_statusbar_location : 'bottom',
			theme_advanced_resizing : true,
			theme_advanced_resize_horizontal : false
		});
	}
		renderTinyMCE();

	$('#toggleRTE').click(function(){
		if (!tinyMCE.get('message'))
		{
			renderTinyMCE(); //tinyMCE.execCommand('mceAddControl', false, 'message');
			$('#format').val('html');
		}
		else
		{
			tinyMCE.execCommand('mceRemoveControl', false, 'message');
			$('#format').val('text');
		}
	});

});