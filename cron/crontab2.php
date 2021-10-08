<?php

?>
<html>

<head>
	<title>Editor de tareas programadas CRON</title>
		<meta name="language" content="es">
	<meta name="robots" content="all">
	<meta name="rating" content="general">	
	<meta charset="utf-8">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link rel="stylesheet" href="styles.css">
	<link rel="stylesheet" href="jquery-ui.css">	
	<script src="jquery.min.js"></script>
	<script src="jquery-ui.min.js"></script>	
	<script src="freeformatter.js"></script>
	<script type="text/javascript">

$(function () {
	$('#crontabs').tabs();
	$('#crontabs input, #crontabs select').change(FF.cron);
	FF.cron();
});

var FF = {
	cron: function () {
		$(this).parents('.cron-option').children('input[type="radio"]').attr("checked", "checked");
		FF.seconds();
		FF.minutes();
		FF.hours();
		FF.day();
		FF.month();
		FF.year();
		var seconds = $('#cronResultSecond').text();
		var minutes = $('#cronResultMinute').text();
		var hours = $('#cronResultHour').text();
		var dom = $('#cronResultDom').text();
		var month = $('#cronResultMonth').text();
		var dow = $('#cronResultDow').text();
		var year = $('#cronResultYear').text();
	},
	seconds: function () {
		var seconds = '';
		if ($('#cronEverySecond:checked').length) {
			seconds = '*';
		} else if ($('#cronSecondIncrement:checked').length) {
			seconds = $('#cronSecondIncrementStart').val();
			seconds += '/';
			seconds += $('#cronSecondIncrementIncrement').val();
		} else if ($('#cronSecondSpecific:checked').length) {
			$('[name="cronSecondSpecificSpecific"]:checked').each(function (i, chck) {
				seconds += $(chck).val();
				seconds += ',';
			});
			if (seconds === '') {
				seconds = '0';
			} else {
				seconds = seconds.slice(0, -1);
			}
		} else {
			seconds = $('#cronSecondRangeStart').val();
			seconds += '-';
			seconds += $('#cronSecondRangeEnd').val();
		}
		$('#cronResultSecond').text(seconds);
	},
	minutes: function () {
		var minutes = '';
		if ($('#cronEveryMinute:checked').length) {
			minutes = '*';
		} else if ($('#cronMinuteIncrement:checked').length) {
			minutes = $('#cronMinuteIncrementStart').val();
			minutes += '/';
			minutes += $('#cronMinuteIncrementIncrement').val();
		} else if ($('#cronMinuteSpecific:checked').length) {
			$('[name="cronMinuteSpecificSpecific"]:checked').each(function (i, chck) {
				minutes += $(chck).val();
				minutes += ',';
			});
			if (minutes === '') {
				minutes = '0';
			} else {
				minutes = minutes.slice(0, -1);
			}
		} else {
			minutes = $('#cronMinuteRangeStart').val();
			minutes += '-';
			minutes += $('#cronMinuteRangeEnd').val();
		}
		$('#cronResultMinute').text(minutes);
	},
	hours: function () {
		var hours = '';
		if ($('#cronEveryHour:checked').length) {
			hours = '*';
		} else if ($('#cronHourIncrement:checked').length) {
			hours = $('#cronHourIncrementStart').val();
			hours += '/';
			hours += $('#cronHourIncrementIncrement').val();
		} else if ($('#cronHourSpecific:checked').length) {
			$('[name="cronHourSpecificSpecific"]:checked').each(function (i, chck) {
				hours += $(chck).val();
				hours += ',';
			});
			if (hours === '') {
				hours = '0';
			} else {
				hours = hours.slice(0, -1);
			}
		} else {
			hours = $('#cronHourRangeStart').val();
			hours += '-';
			hours += $('#cronHourRangeEnd').val();
		}
		$('#cronResultHour').text(hours);
	},
	day: function () {
		var dow = '';
		var dom = '';

		if ($('#cronEveryDay:checked').length) {
			dow = '*';
			dom = '?';
		} else if ($('#cronDowIncrement:checked').length) {
			dow = $('#cronDowIncrementStart').val();
			dow += '/';
			dow += $('#cronDowIncrementIncrement').val();
			dom = '?';
		} else if ($('#cronDomIncrement:checked').length) {
			dom = $('#cronDomIncrementStart').val();
			dom += '/';
			dom += $('#cronDomIncrementIncrement').val();
			dow = '?';
		} else if ($('#cronDowSpecific:checked').length) {
			dom = '?';
			$('[name="cronDowSpecificSpecific"]:checked').each(function (i, chck) {
				dow += $(chck).val();
				dow += ',';
			});
			if (dow === '') {
				dow = 'SUN';
			} else {
				dow = dow.slice(0, -1);
			}
		} else if ($('#cronDomSpecific:checked').length) {
			dow = '?';
			$('[name="cronDomSpecificSpecific"]:checked').each(function (i, chck) {
				dom += $(chck).val();
				dom += ',';
			});
			if (dom === '') {
				dom = '1';
			} else {
				dom = dom.slice(0, -1);
			}
		} else if ($('#cronLastDayOfMonth:checked').length) {
			dow = '?';
			dom = 'L';
		} else if ($('#cronLastWeekdayOfMonth:checked').length) {
			dow = '?';
			dom = 'LW';
		} else if ($('#cronLastSpecificDom:checked').length) {
			dom = '?';
			dow = $('#cronLastSpecificDomDay').val();
			dow += 'L';
		} else if ($('#cronDaysBeforeEom:checked').length) {
			dow = '?';
			dom = 'L-';
			dom += $('#cronDaysBeforeEomMinus').val();
		} else if ($('#cronDaysNearestWeekdayEom:checked').length) {
			dow = '?';
			dom = $('#cronDaysNearestWeekday').val();
			dom += 'W';
		} else if ($('#cronNthDay:checked').length) {
			dom = '?';
			dow = $('#cronNthDayDay').val();
			dow += '#';
			dow += $('#cronNthDayNth').val();;
		}
		$('#cronResultDom').text(dom);
		$('#cronResultDow').text(dow);
	},
	month: function () {
		var month = '';
		if ($('#cronEveryMonth:checked').length) {
			month = '*';
		} else if ($('#cronMonthIncrement:checked').length) {
			month = $('#cronMonthIncrementStart').val();
			month += '/';
			month += $('#cronMonthIncrementIncrement').val();
		} else if ($('#cronMonthSpecific:checked').length) {
			$('[name="cronMonthSpecificSpecific"]:checked').each(function (i, chck) {
				month += $(chck).val();
				month += ',';
			});
			if (month === '') {
				month = '1';
			} else {
				month = month.slice(0, -1);
			}
		} else {
			month = $('#cronMonthRangeStart').val();
			month += '-';
			month += $('#cronMonthRangeEnd').val();
		}
		$('#cronResultMonth').text(month);
	},
	year: function () {
		var year = '';
		if ($('#cronEveryYear:checked').length) {
			year = '*';
		} else if ($('#cronYearIncrement:checked').length) {
			year = $('#cronYearIncrementStart').val();
			year += '/';
			year += $('#cronYearIncrementIncrement').val();
		} else if ($('#cronYearSpecific:checked').length) {
			$('[name="cronYearSpecificSpecific"]:checked').each(function (i, chck) {
				year += $(chck).val();
				year += ',';
			});
			if (year === '') {
				year = '2016';
			} else {
				year = year.slice(0, -1);
			}
		} else {
			year = $('#cronYearRangeStart').val();
			year += '-';
			year += $('#cronYearRangeEnd').val();
		}
		$('#cronResultYear').text(year);
	}
};	

	</script>

<style type="text/css">.fb_hidden{position:absolute;top:-10000px;z-index:10001}
.fb_reposition{overflow:hidden;position:relative}.fb_invisible{display:none}
.fb_reset{background:none;
border:0;border-spacing:0;
color:#000;cursor:auto;direction:ltr;font-family:"lucida grande", tahoma, verdana, arial, sans-serif;
font-size:11px;font-style:normal;font-variant:normal;font-weight:normal;
letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;
text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;
white-space:normal;word-spacing:normal}
.fb_reset>div{overflow:hidden}
.fb_link img{border:none}
@keyframes fb_transform{from{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}
.fb_animate{animation:fb_transform .3s forwards}
.fb_dialog{background:rgba(82, 82, 82, .7);position:absolute;top:-10000px;z-index:10001}
.fb_reset .fb_dialog_legacy{overflow:visible}
.fb_dialog_advanced{padding:10px;-moz-border-radius:8px;-webkit-border-radius:8px;border-radius:8px}
.fb_dialog_content{background:#fff;color:#333}
.fb_dialog_close_icon{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 0 transparent;
 cursor:pointer;display:block;height:15px;position:absolute;right:18px;top:17px;width:15px}
 .fb_dialog_mobile .fb_dialog_close_icon{top:5px;left:5px;right:auto}
 .fb_dialog_padding{background-color:transparent;position:absolute;width:1px;z-index:-1}
 .fb_dialog_close_icon:hover{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png)  no-repeat scroll 0 -15px transparent}
  .fb_dialog_close_icon:active{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yq/r/IE9JII6Z1Ys.png)   no-repeat scroll 0 -30px transparent}
   .fb_dialog_loader{background-color:#f6f7f9;border:1px solid #606060;font-size:24px;padding:20px}
   .fb_dialog_top_left,.fb_dialog_top_right, .fb_dialog_bottom_left,.fb_dialog_bottom_right{
   	height:10px;width:10px;overflow:hidden;position:absolute}
   	.fb_dialog_top_left{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/ye/r/8YeTNIlTZjm.png)
   	 no-repeat 0 0;left:-10px;top:-10px}
   	 .fb_dialog_top_right{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/ye/r/8YeTNIlTZjm.png) 
   	 no-repeat 0 -10px;right:-10px;top:-10px}
   	 .fb_dialog_bottom_left{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/ye/r/8YeTNIlTZjm.png) 
   	 no-repeat 0 -20px;bottom:-10px;left:-10px}
   	 .fb_dialog_bottom_right{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/ye/r/8YeTNIlTZjm.png) 
   	 no-repeat 0 -30px;right:-10px;bottom:-10px}
   	 .fb_dialog_vert_left,.fb_dialog_vert_right,.fb_dialog_horiz_top,.fb_dialog_horiz_bottom{position:absolute;
   	 background:#525252;filter:alpha(opacity=70);opacity:.7}
   	 .fb_dialog_vert_left,.fb_dialog_vert_right{width:10px;height:100%}
   	 .fb_dialog_vert_left{margin-left:-10px}
   	 .fb_dialog_vert_right{right:0;margin-right:-10px}.fb_dialog_horiz_top,.fb_dialog_horiz_bottom{width:100%;
   	 height:10px}.fb_dialog_horiz_top{margin-top:-10px}
   	 .fb_dialog_horiz_bottom{bottom:0;margin-bottom:-10px}
   	 .fb_dialog_iframe{line-height:0}.fb_dialog_content .dialog_title{background:#6d84b4;border:1px solid #365899;
   	 color:#fff;font-size:14px;font-weight:bold;margin:0}
   	 .fb_dialog_content .dialog_title>span{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/yd/r/Cou7n-nqK52.gif)
   	  no-repeat 5px 50%;float:left;padding:5px 0 7px 26px}
   	  body.fb_hidden{-webkit-transform:none;height:100%;margin:0;overflow:visible;position:absolute;top:-10000px;
   	  left:0;width:100%}.fb_dialog.fb_dialog_mobile.loading{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/ya/r/3rhSv5V8j3o.gif) 
   	  white no-repeat 50% 50%;min-height:100%;min-width:100%;overflow:hidden;position:absolute;top:0;
   	  z-index:10001}
   	  .fb_dialog.fb_dialog_mobile.loading.centered{width:auto;height:auto;min-height:initial;min-width:initial;
   	  background:none}.fb_dialog.fb_dialog_mobile.loading.centered 
   	  #fb_dialog_loader_spinner{width:100%}.fb_dialog.fb_dialog_mobile.loading.centered 
   	  .fb_dialog_content{background:none}.loading.centered #fb_dialog_loader_close{color:#fff;display:block;padding-top:20px;clear:both;font-size:18px}#fb-root #fb_dialog_ipad_overlay{background:rgba(0, 0, 0, .45);position:absolute;bottom:0;left:0;right:0;top:0;width:100%;min-height:100%;z-index:10000}#fb-root #fb_dialog_ipad_overlay.hidden{display:none}.fb_dialog.fb_dialog_mobile.loading iframe{visibility:hidden}.fb_dialog_content .dialog_header{-webkit-box-shadow:white 0 1px 1px -1px inset;background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#738ABA), to(#2C4987));border-bottom:1px solid;border-color:#1d4088;color:#fff;font:14px Helvetica, sans-serif;font-weight:bold;text-overflow:ellipsis;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0;vertical-align:middle;white-space:nowrap}.fb_dialog_content .dialog_header table{-webkit-font-smoothing:subpixel-antialiased;height:43px;width:100%}.fb_dialog_content .dialog_header td.header_left{font-size:12px;padding-left:5px;vertical-align:middle;width:60px}.fb_dialog_content .dialog_header td.header_right{font-size:12px;padding-right:5px;vertical-align:middle;width:60px}.fb_dialog_content .touchable_button{background:-webkit-gradient(linear, 0% 0%, 0% 100%, from(#4966A6), color-stop(.5, #355492), to(#2A4887));border:1px solid #29487d;-webkit-background-clip:padding-box;-webkit-border-radius:3px;-webkit-box-shadow:rgba(0, 0, 0, .117188) 0 1px 1px inset, rgba(255, 255, 255, .167969) 0 1px 0;display:inline-block;margin-top:3px;max-width:85px;line-height:18px;padding:4px 12px;position:relative}.fb_dialog_content .dialog_header .touchable_button input{border:none;background:none;color:#fff;font:12px Helvetica, sans-serif;font-weight:bold;margin:2px -12px;padding:2px 6px 3px 6px;text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}.fb_dialog_content .dialog_header .header_center{color:#fff;font-size:16px;font-weight:bold;line-height:18px;text-align:center;vertical-align:middle}.fb_dialog_content .dialog_content{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/y9/r/jKEcVPZFk-2.gif) no-repeat 50% 50%;border:1px solid #555;border-bottom:0;border-top:0;height:150px}.fb_dialog_content .dialog_footer{background:#f6f7f9;border:1px solid #555;border-top-color:#ccc;height:40px}#fb_dialog_loader_close{float:left}.fb_dialog.fb_dialog_mobile .fb_dialog_close_button{text-shadow:rgba(0, 30, 84, .296875) 0 -1px 0}.fb_dialog.fb_dialog_mobile .fb_dialog_close_icon{visibility:hidden}#fb_dialog_loader_spinner{animation:rotateSpinner 1.2s linear infinite;background-color:transparent;background-image:url(https://static.xx.fbcdn.net/rsrc.php/v3/yD/r/t-wz8gw1xG1.png);background-repeat:no-repeat;background-position:50% 50%;height:24px;width:24px}@keyframes rotateSpinner{0%{transform:rotate(0deg)}100%{transform:rotate(360deg)}}
.fb_iframe_widget{display:inline-block;position:relative}
.fb_iframe_widget span{display:inline-block;position:relative;text-align:justify}.fb_iframe_widget iframe{position:absolute}.fb_iframe_widget_fluid_desktop,.fb_iframe_widget_fluid_desktop span,.fb_iframe_widget_fluid_desktop iframe{max-width:100%}.fb_iframe_widget_fluid_desktop iframe{min-width:220px;position:relative}.fb_iframe_widget_lift{z-index:1}.fb_hide_iframes iframe{position:relative;left:-10000px}.fb_iframe_widget_loader{position:relative;display:inline-block}.fb_iframe_widget_fluid{display:inline}.fb_iframe_widget_fluid span{width:100%}.fb_iframe_widget_loader iframe{min-height:32px;z-index:2;zoom:1}.fb_iframe_widget_loader .FB_Loader{background:url(https://static.xx.fbcdn.net/rsrc.php/v3/y9/r/jKEcVPZFk-2.gif) no-repeat;height:32px;width:32px;margin-left:-16px;position:absolute;left:50%;z-index:4}</style>
</head>

<body>

<div id="crontabs" style="max-width: 600px;">
						<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all" role="tablist">
							<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active" role="tab" tabindex="0" aria-controls="tabs-1" aria-labelledby="ui-id-1" aria-selected="true" aria-expanded="true"><a href="#tabs-1" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-1">Segundos</a></li>
							<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-2" aria-labelledby="ui-id-2" aria-selected="false" aria-expanded="false"><a href="#tabs-2" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-2">Minutos</a></li>
							<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-3" aria-labelledby="ui-id-3" aria-selected="false" aria-expanded="false"><a href="#tabs-3" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-3">Horas</a></li>
							<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-4" aria-labelledby="ui-id-4" aria-selected="false" aria-expanded="false"><a href="#tabs-4" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-4">Día</a></li>
							<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-5" aria-labelledby="ui-id-5" aria-selected="false" aria-expanded="false"><a href="#tabs-5" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-5">Mes</a></li>
							<li class="ui-state-default ui-corner-top" role="tab" tabindex="-1" aria-controls="tabs-6" aria-labelledby="ui-id-6" aria-selected="false" aria-expanded="false"><a href="#tabs-6" class="ui-tabs-anchor" role="presentation" tabindex="-1" id="ui-id-6">Año</a></li>
						</ul>
						<div id="tabs-1" aria-labelledby="ui-id-1" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="false" style="display: block;">
							<div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronEverySecond" name="cronSecond">
									<label for="cronEverySecond" class="nofloat">Every second</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronSecondIncrement" name="cronSecond">
									<label for="cronSecondIncrement" class="nofloat">Every
										<select id="cronSecondIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option><option value="60">60</option>
										</select> second(s) starting at second 
										<select id="cronSecondIncrementStart" style="width:50px;">
											<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>
										</select>
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronSecondSpecific" checked="checked" name="cronSecond">
									<label for="cronSecondSpecific" class="nofloat">Specific second (choose one or many)</label>
									<div style="margin-left:50px;">
										<div>
											<label for="cronSecond0" class="nofloat">00</label>
											<input type="checkbox" id="cronSecond0" name="cronSecondSpecificSpecific" value="0" checked="checked">
											
											<label for="cronSecond1" class="nofloat">01</label>
											<input type="checkbox" id="cronSecond1" name="cronSecondSpecificSpecific" value="1">
											
											<label for="cronSecond2" class="nofloat">02</label>
											<input type="checkbox" id="cronSecond2" name="cronSecondSpecificSpecific" value="2">
											
											<label for="cronSecond3" class="nofloat">03</label>
											<input type="checkbox" id="cronSecond3" name="cronSecondSpecificSpecific" value="3">
											
											<label for="cronSecond4" class="nofloat">04</label>
											<input type="checkbox" id="cronSecond4" name="cronSecondSpecificSpecific" value="4">			
																																									
											<label for="cronSecond5" class="nofloat">05</label>
											<input type="checkbox" id="cronSecond5" name="cronSecondSpecificSpecific" value="5">
											
											<label for="cronSecond6" class="nofloat">06</label>
											<input type="checkbox" id="cronSecond6" name="cronSecondSpecificSpecific" value="6">
											
											<label for="cronSecond7" class="nofloat">07</label>
											<input type="checkbox" id="cronSecond7" name="cronSecondSpecificSpecific" value="7">
											
											<label for="cronSecond8" class="nofloat">08</label>
											<input type="checkbox" id="cronSecond8" name="cronSecondSpecificSpecific" value="8">
											
											<label for="cronSecond9" class="nofloat">09</label>
											<input type="checkbox" id="cronSecond9" name="cronSecondSpecificSpecific" value="9">
										</div>
										<div>
											<label for="cronSecond10" class="nofloat">10</label>
											<input type="checkbox" id="cronSecond10" name="cronSecondSpecificSpecific" value="10">
											
											<label for="cronSecond11" class="nofloat">11</label>
											<input type="checkbox" id="cronSecond11" name="cronSecondSpecificSpecific" value="11">
											
											<label for="cronSecond12" class="nofloat">12</label>
											<input type="checkbox" id="cronSecond12" name="cronSecondSpecificSpecific" value="12">
											
											<label for="cronSecond13" class="nofloat">13</label>
											<input type="checkbox" id="cronSecond13" name="cronSecondSpecificSpecific" value="13">
											
											<label for="cronSecond14" class="nofloat">14</label>
											<input type="checkbox" id="cronSecond14" name="cronSecondSpecificSpecific" value="14">			
																																									
											<label for="cronSecond15" class="nofloat">15</label>
											<input type="checkbox" id="cronSecond15" name="cronSecondSpecificSpecific" value="15">
											
											<label for="cronSecond16" class="nofloat">16</label>
											<input type="checkbox" id="cronSecond16" name="cronSecondSpecificSpecific" value="16">
											
											<label for="cronSecond17" class="nofloat">17</label>
											<input type="checkbox" id="cronSecond17" name="cronSecondSpecificSpecific" value="17">
											
											<label for="cronSecond18" class="nofloat">18</label>
											<input type="checkbox" id="cronSecond18" name="cronSecondSpecificSpecific" value="18">
											
											<label for="cronSecond19" class="nofloat">19</label>
											<input type="checkbox" id="cronSecond19" name="cronSecondSpecificSpecific" value="19">
										</div>
										<div>
											<label for="cronSecond20" class="nofloat">20</label>
											<input type="checkbox" id="cronSecond20" name="cronSecondSpecificSpecific" value="20">
											
											<label for="cronSecond21" class="nofloat">21</label>
											<input type="checkbox" id="cronSecond21" name="cronSecondSpecificSpecific" value="21">
											
											<label for="cronSecond22" class="nofloat">22</label>
											<input type="checkbox" id="cronSecond22" name="cronSecondSpecificSpecific" value="22">
											
											<label for="cronSecond23" class="nofloat">23</label>
											<input type="checkbox" id="cronSecond23" name="cronSecondSpecificSpecific" value="23">
											
											<label for="cronSecond24" class="nofloat">24</label>
											<input type="checkbox" id="cronSecond24" name="cronSecondSpecificSpecific" value="24">			
																																									
											<label for="cronSecond25" class="nofloat">25</label>
											<input type="checkbox" id="cronSecond25" name="cronSecondSpecificSpecific" value="25">
											
											<label for="cronSecond26" class="nofloat">26</label>
											<input type="checkbox" id="cronSecond26" name="cronSecondSpecificSpecific" value="26">
											
											<label for="cronSecond27" class="nofloat">27</label>
											<input type="checkbox" id="cronSecond27" name="cronSecondSpecificSpecific" value="27">
											
											<label for="cronSecond28" class="nofloat">28</label>
											<input type="checkbox" id="cronSecond28" name="cronSecondSpecificSpecific" value="28">
											
											<label for="cronSecond29" class="nofloat">29</label>
											<input type="checkbox" id="cronSecond29" name="cronSecondSpecificSpecific" value="29">
										</div>
										<div>
											<label for="cronSecond30" class="nofloat">30</label>
											<input type="checkbox" id="cronSecond30" name="cronSecondSpecificSpecific" value="30">
											
											<label for="cronSecond31" class="nofloat">31</label>
											<input type="checkbox" id="cronSecond31" name="cronSecondSpecificSpecific" value="31">
											
											<label for="cronSecond32" class="nofloat">32</label>
											<input type="checkbox" id="cronSecond32" name="cronSecondSpecificSpecific" value="32">
											
											<label for="cronSecond33" class="nofloat">33</label>
											<input type="checkbox" id="cronSecond33" name="cronSecondSpecificSpecific" value="33">
											
											<label for="cronSecond34" class="nofloat">34</label>
											<input type="checkbox" id="cronSecond34" name="cronSecondSpecificSpecific" value="34">			
																																									
											<label for="cronSecond35" class="nofloat">35</label>
											<input type="checkbox" id="cronSecond35" name="cronSecondSpecificSpecific" value="35">
											
											<label for="cronSecond36" class="nofloat">36</label>
											<input type="checkbox" id="cronSecond36" name="cronSecondSpecificSpecific" value="36">
											
											<label for="cronSecond37" class="nofloat">37</label>
											<input type="checkbox" id="cronSecond37" name="cronSecondSpecificSpecific" value="37">
											
											<label for="cronSecond38" class="nofloat">38</label>
											<input type="checkbox" id="cronSecond38" name="cronSecondSpecificSpecific" value="38">
											
											<label for="cronSecond39" class="nofloat">39</label>
											<input type="checkbox" id="cronSecond39" name="cronSecondSpecificSpecific" value="39">
										</div>	
										<div>
											<label for="cronSecond40" class="nofloat">40</label>
											<input type="checkbox" id="cronSecond40" name="cronSecondSpecificSpecific" value="40">
											
											<label for="cronSecond41" class="nofloat">41</label>
											<input type="checkbox" id="cronSecond41" name="cronSecondSpecificSpecific" value="41">
											
											<label for="cronSecond42" class="nofloat">42</label>
											<input type="checkbox" id="cronSecond42" name="cronSecondSpecificSpecific" value="42">
											
											<label for="cronSecond43" class="nofloat">43</label>
											<input type="checkbox" id="cronSecond43" name="cronSecondSpecificSpecific" value="43">
											
											<label for="cronSecond44" class="nofloat">44</label>
											<input type="checkbox" id="cronSecond44" name="cronSecondSpecificSpecific" value="44">			
																																									
											<label for="cronSecond45" class="nofloat">45</label>
											<input type="checkbox" id="cronSecond45" name="cronSecondSpecificSpecific" value="45">
											
											<label for="cronSecond46" class="nofloat">46</label>
											<input type="checkbox" id="cronSecond46" name="cronSecondSpecificSpecific" value="46">
											
											<label for="cronSecond47" class="nofloat">47</label>
											<input type="checkbox" id="cronSecond47" name="cronSecondSpecificSpecific" value="47">
											
											<label for="cronSecond48" class="nofloat">48</label>
											<input type="checkbox" id="cronSecond48" name="cronSecondSpecificSpecific" value="48">
											
											<label for="cronSecond49" class="nofloat">49</label>
											<input type="checkbox" id="cronSecond49" name="cronSecondSpecificSpecific" value="49">
										</div>	
										<div>
											<label for="cronSecond50" class="nofloat">50</label>
											<input type="checkbox" id="cronSecond50" name="cronSecondSpecificSpecific" value="50">
											
											<label for="cronSecond51" class="nofloat">51</label>
											<input type="checkbox" id="cronSecond51" name="cronSecondSpecificSpecific" value="51">
											
											<label for="cronSecond52" class="nofloat">52</label>
											<input type="checkbox" id="cronSecond52" name="cronSecondSpecificSpecific" value="52">
											
											<label for="cronSecond53" class="nofloat">53</label>
											<input type="checkbox" id="cronSecond53" name="cronSecondSpecificSpecific" value="53">
											
											<label for="cronSecond54" class="nofloat">54</label>
											<input type="checkbox" id="cronSecond54" name="cronSecondSpecificSpecific" value="54">			
																																									
											<label for="cronSecond55" class="nofloat">55</label>
											<input type="checkbox" id="cronSecond55" name="cronSecondSpecificSpecific" value="55">
											
											<label for="cronSecond56" class="nofloat">56</label>
											<input type="checkbox" id="cronSecond56" name="cronSecondSpecificSpecific" value="56">
											
											<label for="cronSecond57" class="nofloat">57</label>
											<input type="checkbox" id="cronSecond57" name="cronSecondSpecificSpecific" value="57">
											
											<label for="cronSecond58" class="nofloat">58</label>
											<input type="checkbox" id="cronSecond58" name="cronSecondSpecificSpecific" value="58">
											
											<label for="cronSecond59" class="nofloat">59</label>
											<input type="checkbox" id="cronSecond59" name="cronSecondSpecificSpecific" value="59">
										</div>																																						
									</div>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronSecondRange" name="cronSecond">
									<label for="cronSecondRange" class="nofloat">Every second between second 
									<select id="cronSecondRangeStart" style="width:50px;">
										<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>
									</select>
									and second 
									<select id="cronSecondRangeEnd" style="width:50px;">
										<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>
									</select>
									</label>
								</div>
							</div>
						</div>
						<div id="tabs-2" aria-labelledby="ui-id-2" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;">
							<div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronEveryMinute" name="cronMinute">
									<label for="cronEveryMinute" class="nofloat">Every minute</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronMinuteIncrement" name="cronMinute">
									<label for="cronMinuteIncrement" class="nofloat">Every
										<select id="cronMinuteIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option><option value="60">60</option>
										</select> minute(s) starting at minute 
										<select id="cronMinuteIncrementStart" style="width:50px;">
											<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>
										</select>
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronMinuteSpecific" checked="checked" name="cronMinute">
									<label for="cronMinuteSpecific" class="nofloat">Specific minute (choose one or many)</label>
									<div style="margin-left:50px;">
										<div>
											<label for="cronMinute0" class="nofloat">00</label>
											<input type="checkbox" id="cronMinute0" name="cronMinuteSpecificSpecific" value="0" checked="checked">
											
											<label for="cronMinute1" class="nofloat">01</label>
											<input type="checkbox" id="cronMinute1" name="cronMinuteSpecificSpecific" value="1">
											
											<label for="cronMinute2" class="nofloat">02</label>
											<input type="checkbox" id="cronMinute2" name="cronMinuteSpecificSpecific" value="2">
											
											<label for="cronMinute3" class="nofloat">03</label>
											<input type="checkbox" id="cronMinute3" name="cronMinuteSpecificSpecific" value="3">
											
											<label for="cronMinute4" class="nofloat">04</label>
											<input type="checkbox" id="cronMinute4" name="cronMinuteSpecificSpecific" value="4">			
																																									
											<label for="cronMinute5" class="nofloat">05</label>
											<input type="checkbox" id="cronMinute5" name="cronMinuteSpecificSpecific" value="5">
											
											<label for="cronMinute6" class="nofloat">06</label>
											<input type="checkbox" id="cronMinute6" name="cronMinuteSpecificSpecific" value="6">
											
											<label for="cronMinute7" class="nofloat">07</label>
											<input type="checkbox" id="cronMinute7" name="cronMinuteSpecificSpecific" value="7">
											
											<label for="cronMinute8" class="nofloat">08</label>
											<input type="checkbox" id="cronMinute8" name="cronMinuteSpecificSpecific" value="8">
											
											<label for="cronMinute9" class="nofloat">09</label>
											<input type="checkbox" id="cronMinute9" name="cronMinuteSpecificSpecific" value="9">
										</div>
										<div>
											<label for="cronMinute10" class="nofloat">10</label>
											<input type="checkbox" id="cronMinute10" name="cronMinuteSpecificSpecific" value="10">
											
											<label for="cronMinute11" class="nofloat">11</label>
											<input type="checkbox" id="cronMinute11" name="cronMinuteSpecificSpecific" value="11">
											
											<label for="cronMinute12" class="nofloat">12</label>
											<input type="checkbox" id="cronMinute12" name="cronMinuteSpecificSpecific" value="12">
											
											<label for="cronMinute13" class="nofloat">13</label>
											<input type="checkbox" id="cronMinute13" name="cronMinuteSpecificSpecific" value="13">
											
											<label for="cronMinute14" class="nofloat">14</label>
											<input type="checkbox" id="cronMinute14" name="cronMinuteSpecificSpecific" value="14">			
																																									
											<label for="cronMinute15" class="nofloat">15</label>
											<input type="checkbox" id="cronMinute15" name="cronMinuteSpecificSpecific" value="15">
											
											<label for="cronMinute16" class="nofloat">16</label>
											<input type="checkbox" id="cronMinute16" name="cronMinuteSpecificSpecific" value="16">
											
											<label for="cronMinute17" class="nofloat">17</label>
											<input type="checkbox" id="cronMinute17" name="cronMinuteSpecificSpecific" value="17">
											
											<label for="cronMinute18" class="nofloat">18</label>
											<input type="checkbox" id="cronMinute18" name="cronMinuteSpecificSpecific" value="18">
											
											<label for="cronMinute19" class="nofloat">19</label>
											<input type="checkbox" id="cronMinute19" name="cronMinuteSpecificSpecific" value="19">
										</div>
										<div>
											<label for="cronMinute20" class="nofloat">20</label>
											<input type="checkbox" id="cronMinute20" name="cronMinuteSpecificSpecific" value="20">
											
											<label for="cronMinute21" class="nofloat">21</label>
											<input type="checkbox" id="cronMinute21" name="cronMinuteSpecificSpecific" value="21">
											
											<label for="cronMinute22" class="nofloat">22</label>
											<input type="checkbox" id="cronMinute22" name="cronMinuteSpecificSpecific" value="22">
											
											<label for="cronMinute23" class="nofloat">23</label>
											<input type="checkbox" id="cronMinute23" name="cronMinuteSpecificSpecific" value="23">
											
											<label for="cronMinute24" class="nofloat">24</label>
											<input type="checkbox" id="cronMinute24" name="cronMinuteSpecificSpecific" value="24">			
																																									
											<label for="cronMinute25" class="nofloat">25</label>
											<input type="checkbox" id="cronMinute25" name="cronMinuteSpecificSpecific" value="25">
											
											<label for="cronMinute26" class="nofloat">26</label>
											<input type="checkbox" id="cronMinute26" name="cronMinuteSpecificSpecific" value="26">
											
											<label for="cronMinute27" class="nofloat">27</label>
											<input type="checkbox" id="cronMinute27" name="cronMinuteSpecificSpecific" value="27">
											
											<label for="cronMinute28" class="nofloat">28</label>
											<input type="checkbox" id="cronMinute28" name="cronMinuteSpecificSpecific" value="28">
											
											<label for="cronMinute29" class="nofloat">29</label>
											<input type="checkbox" id="cronMinute29" name="cronMinuteSpecificSpecific" value="29">
										</div>
										<div>
											<label for="cronMinute30" class="nofloat">30</label>
											<input type="checkbox" id="cronMinute30" name="cronMinuteSpecificSpecific" value="30">
											
											<label for="cronMinute31" class="nofloat">31</label>
											<input type="checkbox" id="cronMinute31" name="cronMinuteSpecificSpecific" value="31">
											
											<label for="cronMinute32" class="nofloat">32</label>
											<input type="checkbox" id="cronMinute32" name="cronMinuteSpecificSpecific" value="32">
											
											<label for="cronMinute33" class="nofloat">33</label>
											<input type="checkbox" id="cronMinute33" name="cronMinuteSpecificSpecific" value="33">
											
											<label for="cronMinute34" class="nofloat">34</label>
											<input type="checkbox" id="cronMinute34" name="cronMinuteSpecificSpecific" value="34">			
																																									
											<label for="cronMinute35" class="nofloat">35</label>
											<input type="checkbox" id="cronMinute35" name="cronMinuteSpecificSpecific" value="35">
											
											<label for="cronMinute36" class="nofloat">36</label>
											<input type="checkbox" id="cronMinute36" name="cronMinuteSpecificSpecific" value="36">
											
											<label for="cronMinute37" class="nofloat">37</label>
											<input type="checkbox" id="cronMinute37" name="cronMinuteSpecificSpecific" value="37">
											
											<label for="cronMinute38" class="nofloat">38</label>
											<input type="checkbox" id="cronMinute38" name="cronMinuteSpecificSpecific" value="38">
											
											<label for="cronMinute39" class="nofloat">39</label>
											<input type="checkbox" id="cronMinute39" name="cronMinuteSpecificSpecific" value="39">
										</div>	
										<div>
											<label for="cronMinute40" class="nofloat">40</label>
											<input type="checkbox" id="cronMinute40" name="cronMinuteSpecificSpecific" value="40">
											
											<label for="cronMinute41" class="nofloat">41</label>
											<input type="checkbox" id="cronMinute41" name="cronMinuteSpecificSpecific" value="41">
											
											<label for="cronMinute42" class="nofloat">42</label>
											<input type="checkbox" id="cronMinute42" name="cronMinuteSpecificSpecific" value="42">
											
											<label for="cronMinute43" class="nofloat">43</label>
											<input type="checkbox" id="cronMinute43" name="cronMinuteSpecificSpecific" value="43">
											
											<label for="cronMinute44" class="nofloat">44</label>
											<input type="checkbox" id="cronMinute44" name="cronMinuteSpecificSpecific" value="44">			
																																									
											<label for="cronMinute45" class="nofloat">45</label>
											<input type="checkbox" id="cronMinute45" name="cronMinuteSpecificSpecific" value="45">
											
											<label for="cronMinute46" class="nofloat">46</label>
											<input type="checkbox" id="cronMinute46" name="cronMinuteSpecificSpecific" value="46">
											
											<label for="cronMinute47" class="nofloat">47</label>
											<input type="checkbox" id="cronMinute47" name="cronMinuteSpecificSpecific" value="47">
											
											<label for="cronMinute48" class="nofloat">48</label>
											<input type="checkbox" id="cronMinute48" name="cronMinuteSpecificSpecific" value="48">
											
											<label for="cronMinute49" class="nofloat">49</label>
											<input type="checkbox" id="cronMinute49" name="cronMinuteSpecificSpecific" value="49">
										</div>	
										<div>
											<label for="cronMinute50" class="nofloat">50</label>
											<input type="checkbox" id="cronMinute50" name="cronMinuteSpecificSpecific" value="50">
											
											<label for="cronMinute51" class="nofloat">51</label>
											<input type="checkbox" id="cronMinute51" name="cronMinuteSpecificSpecific" value="51">
											
											<label for="cronMinute52" class="nofloat">52</label>
											<input type="checkbox" id="cronMinute52" name="cronMinuteSpecificSpecific" value="52">
											
											<label for="cronMinute53" class="nofloat">53</label>
											<input type="checkbox" id="cronMinute53" name="cronMinuteSpecificSpecific" value="53">
											
											<label for="cronMinute54" class="nofloat">54</label>
											<input type="checkbox" id="cronMinute54" name="cronMinuteSpecificSpecific" value="54">			
																																									
											<label for="cronMinute55" class="nofloat">55</label>
											<input type="checkbox" id="cronMinute55" name="cronMinuteSpecificSpecific" value="55">
											
											<label for="cronMinute56" class="nofloat">56</label>
											<input type="checkbox" id="cronMinute56" name="cronMinuteSpecificSpecific" value="56">
											
											<label for="cronMinute57" class="nofloat">57</label>
											<input type="checkbox" id="cronMinute57" name="cronMinuteSpecificSpecific" value="57">
											
											<label for="cronMinute58" class="nofloat">58</label>
											<input type="checkbox" id="cronMinute58" name="cronMinuteSpecificSpecific" value="58">
											
											<label for="cronMinute59" class="nofloat">59</label>
											<input type="checkbox" id="cronMinute59" name="cronMinuteSpecificSpecific" value="59">
										</div>																																						
									</div>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronMinuteRange" name="cronMinute">
									<label for="cronMinuteRange" class="nofloat">Every minute between minute 
									<select id="cronMinuteRangeStart" style="width:50px;">
										<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>
									</select>
									and minute 
									<select id="cronMinuteRangeEnd" style="width:50px;">
										<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>
									</select>
									</label>
								</div>
							</div>							
						</div>
						<div id="tabs-3" aria-labelledby="ui-id-3" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;">
							<div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronEveryHour" name="cronHour">
									<label for="cronEveryHour" class="nofloat">Every hour</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronHourIncrement" name="cronHour">
									<label for="cronHourIncrement" class="nofloat">Every
										<select id="cronHourIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option>
										</select> hour(s) starting at hour 
										<select id="cronHourIncrementStart" style="width:50px;">
											<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>
										</select>
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronHourSpecific" checked="checked" name="cronHour">
									<label for="cronHourSpecific" class="nofloat">Specific hour (choose one or many)</label>
									<div style="margin-left:50px;">
										<div>
											<label for="cronHour0" class="nofloat">00</label>
											<input type="checkbox" id="cronHour0" name="cronHourSpecificSpecific" value="0" checked="checked">
											
											<label for="cronHour1" class="nofloat">01</label>
											<input type="checkbox" id="cronHour1" name="cronHourSpecificSpecific" value="1">
											
											<label for="cronHour2" class="nofloat">02</label>
											<input type="checkbox" id="cronHour2" name="cronHourSpecificSpecific" value="2">
											
											<label for="cronHour3" class="nofloat">03</label>
											<input type="checkbox" id="cronHour3" name="cronHourSpecificSpecific" value="3">
											
											<label for="cronHour4" class="nofloat">04</label>
											<input type="checkbox" id="cronHour4" name="cronHourSpecificSpecific" value="4">			
																																									
											<label for="cronHour5" class="nofloat">05</label>
											<input type="checkbox" id="cronHour5" name="cronHourSpecificSpecific" value="5">
											
											<label for="cronHour6" class="nofloat">06</label>
											<input type="checkbox" id="cronHour6" name="cronHourSpecificSpecific" value="6">
											
											<label for="cronHour7" class="nofloat">07</label>
											<input type="checkbox" id="cronHour7" name="cronHourSpecificSpecific" value="7">
											
											<label for="cronHour8" class="nofloat">08</label>
											<input type="checkbox" id="cronHour8" name="cronHourSpecificSpecific" value="8">
											
											<label for="cronHour9" class="nofloat">09</label>
											<input type="checkbox" id="cronHour9" name="cronHourSpecificSpecific" value="9">
										</div>
										<div>
											<label for="cronHour10" class="nofloat">10</label>
											<input type="checkbox" id="cronHour10" name="cronHourSpecificSpecific" value="10">
											
											<label for="cronHour11" class="nofloat">11</label>
											<input type="checkbox" id="cronHour11" name="cronHourSpecificSpecific" value="11">
											
											<label for="cronHour12" class="nofloat">12</label>
											<input type="checkbox" id="cronHour12" name="cronHourSpecificSpecific" value="12">
											
											<label for="cronHour13" class="nofloat">13</label>
											<input type="checkbox" id="cronHour13" name="cronHourSpecificSpecific" value="13">
											
											<label for="cronHour14" class="nofloat">14</label>
											<input type="checkbox" id="cronHour14" name="cronHourSpecificSpecific" value="14">			
																																									
											<label for="cronHour15" class="nofloat">15</label>
											<input type="checkbox" id="cronHour15" name="cronHourSpecificSpecific" value="15">
											
											<label for="cronHour16" class="nofloat">16</label>
											<input type="checkbox" id="cronHour16" name="cronHourSpecificSpecific" value="16">
											
											<label for="cronHour17" class="nofloat">17</label>
											<input type="checkbox" id="cronHour17" name="cronHourSpecificSpecific" value="17">
											
											<label for="cronHour18" class="nofloat">18</label>
											<input type="checkbox" id="cronHour18" name="cronHourSpecificSpecific" value="18">
											
											<label for="cronHour19" class="nofloat">19</label>
											<input type="checkbox" id="cronHour19" name="cronHourSpecificSpecific" value="19">
										</div>
										<div>
											<label for="cronHour20" class="nofloat">20</label>
											<input type="checkbox" id="cronHour20" name="cronHourSpecificSpecific" value="20">
											
											<label for="cronHour21" class="nofloat">21</label>
											<input type="checkbox" id="cronHour21" name="cronHourSpecificSpecific" value="21">
											
											<label for="cronHour22" class="nofloat">22</label>
											<input type="checkbox" id="cronHour22" name="cronHourSpecificSpecific" value="22">
											
											<label for="cronHour23" class="nofloat">23</label>
											<input type="checkbox" id="cronHour23" name="cronHourSpecificSpecific" value="23">
										</div>																																						
									</div>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronHourRange" name="cronHour">
									<label for="cronHourRange" class="nofloat">Every hour between hour 
									<select id="cronHourRangeStart" style="width:50px;">
										<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>
									</select>
									and hour 
									<select id="cronHourRangeEnd" style="width:50px;">
										<option value="0">00</option><option value="1">01</option><option value="2">02</option><option value="3">03</option><option value="4">04</option><option value="5">05</option><option value="6">06</option><option value="7">07</option><option value="8">08</option><option value="9">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>
									</select>
									</label>
								</div>
							</div>
						</div>
						<div id="tabs-4" aria-labelledby="ui-id-4" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;">
							<div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronEveryDay" name="cronDay" checked="checked">
									<label for="cronEveryDay" class="nofloat">Every day</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronDowIncrement" name="cronDay">
									<label for="cronDowIncrement" class="nofloat">Every
										<select id="cronDowIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option>
										</select> day(s) starting on 
										<select id="cronDowIncrementStart" style="width:125px;">
											<option value="1">Sunday</option>
											<option value="2">Monday</option>
											<option value="3">Tuesday</option>
											<option value="4">Wednesday</option>
											<option value="5">Thursday</option>
											<option value="6">Friday</option>
											<option value="7">Saturday</option>
										</select>
									</label>
								</div>								
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronDomIncrement" name="cronDay">
									<label for="cronDomIncrement" class="nofloat">Every
										<select id="cronDomIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
										</select> day(s) starting on the 
										<select id="cronDomIncrementStart" style="width:50px;">
											<option value="1">1st</option>
											<option value="2">2nd</option>
											<option value="3">3rd</option>
											<option value="4">4th</option>
											<option value="5">5th</option>
											<option value="6">6th</option>
											<option value="7">7th</option>
											<option value="8">8th</option>
											<option value="9">9th</option>
											<option value="10">10th</option>																						
											<option value="11">11th</option>
											<option value="12">12th</option>
											<option value="13">13th</option>
											<option value="14">14th</option>
											<option value="15">15th</option>
											<option value="16">16th</option>
											<option value="17">17th</option>
											<option value="18">18th</option>
											<option value="19">19th</option>
											<option value="20">20th</option>
											<option value="21">21st</option>
											<option value="22">22nd</option>
											<option value="23">23rd</option>
											<option value="24">24th</option>
											<option value="25">25th</option>
											<option value="26">26th</option>
											<option value="27">27th</option>
											<option value="28">28th</option>
											<option value="29">29th</option>
											<option value="30">30th</option>																						
											<option value="31">31st</option>
										</select>
										of the month
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronDowSpecific" name="cronDay">
									<label for="cronDowSpecific" class="nofloat">Specific day of week (choose one or many)</label>
									<div style="margin-left:50px;">
										<label for="cronDowSun" class="nofloat">Sunday</label>
										<input type="checkbox" id="cronDowSun" name="cronDowSpecificSpecific" value="SUN">
										<label for="cronDowMon" class="nofloat">Monday</label>
										<input type="checkbox" id="cronDowMon" name="cronDowSpecificSpecific" value="MON">
										<label for="cronDowTue" class="nofloat">Tuesday</label>
										<input type="checkbox" id="cronDowTue" name="cronDowSpecificSpecific" value="TUE">
										<label for="cronDowWed" class="nofloat">Wednesday</label>
										<input type="checkbox" id="cronDowWed" name="cronDowSpecificSpecific" value="WED">																						
										<label for="cronDowThu" class="nofloat">Thursday</label>
										<input type="checkbox" id="cronDowThu" name="cronDowSpecificSpecific" value="THU">
										<label for="cronDowFri" class="nofloat">Friday</label>
										<input type="checkbox" id="cronDowFri" name="cronDowSpecificSpecific" value="FRI">											
										<label for="cronDowSat" class="nofloat">Saturday</label>
										<input type="checkbox" id="cronDowSat" name="cronDowSpecificSpecific" value="SAT">											
									</div>
								</div>																
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronDomSpecific" name="cronDay">
									<label for="cronDomSpecific" class="nofloat">Specific day of month (choose one or many)</label>
									<div style="margin-left:50px;">
										<div>
											<label for="cronDom1" class="nofloat">01</label>
											<input type="checkbox" id="cronDom1" name="cronDomSpecificSpecific" value="1">
											<label for="cronDom2" class="nofloat">02</label>
											<input type="checkbox" id="cronDom2" name="cronDomSpecificSpecific" value="2">
											<label for="cronDom3" class="nofloat">03</label>
											<input type="checkbox" id="cronDom3" name="cronDomSpecificSpecific" value="3">											
											<label for="cronDom4" class="nofloat">04</label>
											<input type="checkbox" id="cronDom4" name="cronDomSpecificSpecific" value="4">
											<label for="cronDom5" class="nofloat">05</label>
											<input type="checkbox" id="cronDom5" name="cronDomSpecificSpecific" value="5">											
											<label for="cronDom6" class="nofloat">06</label>
											<input type="checkbox" id="cronDom6" name="cronDomSpecificSpecific" value="6">
											<label for="cronDom7" class="nofloat">07</label>
											<input type="checkbox" id="cronDom7" name="cronDomSpecificSpecific" value="7">
											<label for="cronDom8" class="nofloat">08</label>
											<input type="checkbox" id="cronDom8" name="cronDomSpecificSpecific" value="8">											
											<label for="cronDom9" class="nofloat">09</label>
											<input type="checkbox" id="cronDom9" name="cronDomSpecificSpecific" value="9">
											<label for="cronDom10" class="nofloat">10</label>
											<input type="checkbox" id="cronDom10" name="cronDomSpecificSpecific" value="10">											
										</div>
										<div>
											<label for="cronDom11" class="nofloat">11</label>
											<input type="checkbox" id="cronDom11" name="cronDomSpecificSpecific" value="11">
											<label for="cronDom12" class="nofloat">12</label>
											<input type="checkbox" id="cronDom12" name="cronDomSpecificSpecific" value="12">
											<label for="cronDom13" class="nofloat">13</label>
											<input type="checkbox" id="cronDom13" name="cronDomSpecificSpecific" value="13">											
											<label for="cronDom14" class="nofloat">14</label>
											<input type="checkbox" id="cronDom14" name="cronDomSpecificSpecific" value="14">
											<label for="cronDom15" class="nofloat">15</label>
											<input type="checkbox" id="cronDom15" name="cronDomSpecificSpecific" value="15">											
											<label for="cronDom16" class="nofloat">16</label>
											<input type="checkbox" id="cronDom16" name="cronDomSpecificSpecific" value="16">
											<label for="cronDom17" class="nofloat">17</label>
											<input type="checkbox" id="cronDom17" name="cronDomSpecificSpecific" value="17">
											<label for="cronDom18" class="nofloat">18</label>
											<input type="checkbox" id="cronDom18" name="cronDomSpecificSpecific" value="18">											
											<label for="cronDom19" class="nofloat">19</label>
											<input type="checkbox" id="cronDom19" name="cronDomSpecificSpecific" value="19">
											<label for="cronDom20" class="nofloat">20</label>
											<input type="checkbox" id="cronDom20" name="cronDomSpecificSpecific" value="20">					
										</div>
										<div>
											<label for="cronDom21" class="nofloat">21</label>
											<input type="checkbox" id="cronDom21" name="cronDomSpecificSpecific" value="21">
											<label for="cronDom22" class="nofloat">22</label>
											<input type="checkbox" id="cronDom22" name="cronDomSpecificSpecific" value="22">
											<label for="cronDom23" class="nofloat">23</label>
											<input type="checkbox" id="cronDom23" name="cronDomSpecificSpecific" value="23">											
											<label for="cronDom24" class="nofloat">24</label>
											<input type="checkbox" id="cronDom24" name="cronDomSpecificSpecific" value="24">
											<label for="cronDom25" class="nofloat">25</label>
											<input type="checkbox" id="cronDom25" name="cronDomSpecificSpecific" value="25">											
											<label for="cronDom26" class="nofloat">26</label>
											<input type="checkbox" id="cronDom26" name="cronDomSpecificSpecific" value="26">
											<label for="cronDom27" class="nofloat">27</label>
											<input type="checkbox" id="cronDom27" name="cronDomSpecificSpecific" value="27">
											<label for="cronDom28" class="nofloat">28</label>
											<input type="checkbox" id="cronDom28" name="cronDomSpecificSpecific" value="28">											
											<label for="cronDom29" class="nofloat">29</label>
											<input type="checkbox" id="cronDom29" name="cronDomSpecificSpecific" value="29">
											<label for="cronDom30" class="nofloat">30</label>
											<input type="checkbox" id="cronDom30" name="cronDomSpecificSpecific" value="30">	
											<label for="cronDom31" class="nofloat">31</label>
											<input type="checkbox" id="cronDom31" name="cronDomSpecificSpecific" value="31">												
										</div>																																						
									</div>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronLastDayOfMonth" name="cronDay">
									<label for="cronLastDayOfMonth" class="nofloat">On the last day of the month</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronLastWeekdayOfMonth" name="cronDay">
									<label for="cronLastWeekdayOfMonth" class="nofloat">On the last weekday of the month</label>
								</div>																							
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronLastSpecificDom" name="cronDay">
									<label for="cronLastSpecificDom" class="nofloat">On the last 
										<select id="cronLastSpecificDomDay" style="width:125px;">
											<option value="1">Sunday</option>
											<option value="2">Monday</option>
											<option value="3">Tuesday</option>
											<option value="4">Wednesday</option>
											<option value="5">Thursday</option>
											<option value="6">Friday</option>
											<option value="7">Saturday</option>
										</select>									
										of the month
									</label>
								</div>	
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronDaysBeforeEom" name="cronDay">
									<label for="cronDaysBeforeEom" class="nofloat">
										<select id="cronDaysBeforeEomMinus" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option>
										</select> day(s) before the end of the month
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronDaysNearestWeekdayEom" name="cronDay">
									<label for="cronDaysNearestWeekdayEom" class="nofloat">
										Nearest weekday (Monday to Friday) to the 
										<select id="cronDaysNearestWeekday" style="width:50px;">
											<option value="1">1st</option>
											<option value="2">2nd</option>
											<option value="3">3rd</option>
											<option value="4">4th</option>
											<option value="5">5th</option>
											<option value="6">6th</option>
											<option value="7">7th</option>
											<option value="8">8th</option>
											<option value="9">9th</option>
											<option value="10">10th</option>																						
											<option value="11">11th</option>
											<option value="12">12th</option>
											<option value="13">13th</option>
											<option value="14">14th</option>
											<option value="15">15th</option>
											<option value="16">16th</option>
											<option value="17">17th</option>
											<option value="18">18th</option>
											<option value="19">19th</option>
											<option value="20">20th</option>
											<option value="21">21st</option>
											<option value="22">22nd</option>
											<option value="23">23rd</option>
											<option value="24">24th</option>
											<option value="25">25th</option>
											<option value="26">26th</option>
											<option value="27">27th</option>
											<option value="28">28th</option>
											<option value="29">29th</option>
											<option value="30">30th</option>																						
											<option value="31">31st</option>
										</select> of the month
									</label>
								</div>								

								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronNthDay" name="cronDay">
									<label for="cronNthDay" class="nofloat">
										On the 
										<select id="cronNthDayNth" style="width:50px;">
											<option value="1">1st</option>
											<option value="2">2nd</option>
											<option value="3">3rd</option>
											<option value="4">4th</option>
											<option value="5">5th</option>
										</select>
										<select id="cronNthDayDay" style="width:125px;">
											<option value="1">Sunday</option>
											<option value="2">Monday</option>
											<option value="3">Tuesday</option>
											<option value="4">Wednesday</option>
											<option value="5">Thursday</option>
											<option value="6">Friday</option>
											<option value="7">Saturday</option>
										</select>										
										of the month
									</label>
								</div>
							</div>
						</div>
						<div id="tabs-5" aria-labelledby="ui-id-5" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;">
							<div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronEveryMonth" name="cronMonth" checked="checked">
									<label for="cronEveryMonth" class="nofloat">Every month</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronMonthIncrement" name="cronMonth">
									<label for="cronMonthIncrement" class="nofloat">Every
										<select id="cronMonthIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>
										</select> month(s) starting in 
										<select id="cronMonthIncrementStart" style="width:125px;">
											<option value="1">January</option>
											<option value="2">February</option>
											<option value="3">March</option>
											<option value="4">April</option>
											<option value="5">May</option>
											<option value="6">June</option>																																	
											<option value="7">July</option>
											<option value="8">August</option>
											<option value="9">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronMonthSpecific" name="cronMonth">
									<label for="cronMonthSpecific" class="nofloat">Specific month (choose one or many)</label>
									<div style="margin-left:50px;">
										<div>
											<div>
												<label for="cronMonth1" class="nofloat">January&nbsp;&nbsp;&nbsp;&nbsp;</label>
												<input type="checkbox" id="cronMonth1" name="cronMonthSpecificSpecific" value="JAN">
												
												<label for="cronMonth2" class="nofloat">February&nbsp;&nbsp;</label>
												<input type="checkbox" id="cronMonth2" name="cronMonthSpecificSpecific" value="FEB">
			
												<label for="cronMonth3" class="nofloat">March</label>
												<input type="checkbox" id="cronMonth3" name="cronMonthSpecificSpecific" value="MAR">
			
												<label for="cronMonth4" class="nofloat">April</label>
												<input type="checkbox" id="cronMonth4" name="cronMonthSpecificSpecific" value="APR">
		
												<label for="cronMonth5" class="nofloat">May</label>
												<input type="checkbox" id="cronMonth5" name="cronMonthSpecificSpecific" value="MAY">
												
												<label for="cronMonth6" class="nofloat">June</label>
												<input type="checkbox" id="cronMonth6" name="cronMonthSpecificSpecific" value="JUN">

												<label for="cronMonth7" class="nofloat">July</label>
												<input type="checkbox" id="cronMonth7" name="cronMonthSpecificSpecific" value="JUL">
			
												<label for="cronMonth8" class="nofloat">August</label>
												<input type="checkbox" id="cronMonth8" name="cronMonthSpecificSpecific" value="AUG">
							
												<label for="cronMonth9" class="nofloat">September</label>
												<input type="checkbox" id="cronMonth0" name="cronMonthSpecificSpecific" value="SEP">
												
												<label for="cronMonth10" class="nofloat">October</label>
												<input type="checkbox" id="cronMonth10" name="cronMonthSpecificSpecific" value="OCT">
											</div>
											<div>	
												<label for="cronMonth11" class="nofloat">November</label>
												<input type="checkbox" id="cronMonth11" name="cronMonthSpecificSpecific" value="NOV">
													
												<label for="cronMonth12" class="nofloat">December</label>
												<input type="checkbox" id="cronMonth12" name="cronMonthSpecificSpecific" value="DEC">
											</div>
										</div>																																						
									</div>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronMonthRange" name="cronMonth">
									<label for="cronMonthRange" class="nofloat">
										Every month between 
										<select id="cronMonthRangeStart" style="width:125px;">
											<option value="1">January</option>
											<option value="2">February</option>
											<option value="3">March</option>
											<option value="4">April</option>
											<option value="5">May</option>
											<option value="6">June</option>																																	
											<option value="7">July</option>
											<option value="8">August</option>
											<option value="9">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
										and 
										<select id="cronMonthRangeEnd" style="width:125px;">
											<option value="1">January</option>
											<option value="2">February</option>
											<option value="3">March</option>
											<option value="4">April</option>
											<option value="5">May</option>
											<option value="6">June</option>																																	
											<option value="7">July</option>
											<option value="8">August</option>
											<option value="9">September</option>
											<option value="10">October</option>
											<option value="11">November</option>
											<option value="12">December</option>
										</select>
									</label>
								</div>
							</div>						
						</div>
						<div id="tabs-6" aria-labelledby="ui-id-6" class="ui-tabs-panel ui-widget-content ui-corner-bottom" role="tabpanel" aria-hidden="true" style="display: none;">
							<div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronEveryYear" name="cronYear" checked="checked">
									<label for="cronEveryYear" class="nofloat">Any year</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronYearIncrement" name="cronYear">
									<label for="cronYearIncrement" class="nofloat">Every
										<select id="cronYearIncrementIncrement" style="width:50px;">
											<option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option><option value="60">60</option><option value="61">61</option><option value="62">62</option><option value="63">63</option><option value="64">64</option><option value="65">65</option><option value="66">66</option><option value="67">67</option><option value="68">68</option><option value="69">69</option><option value="70">70</option><option value="71">71</option><option value="72">72</option><option value="73">73</option><option value="74">74</option><option value="75">75</option><option value="76">76</option><option value="77">77</option><option value="78">78</option><option value="79">79</option><option value="80">80</option><option value="81">81</option><option value="82">82</option><option value="83">83</option>
										</select> years(s) starting in 
										<select id="cronYearIncrementStart" style="width:80px;">
											<option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option><option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option><option value="2050">2050</option><option value="2051">2051</option><option value="2052">2052</option><option value="2053">2053</option><option value="2054">2054</option><option value="2055">2055</option><option value="2056">2056</option><option value="2057">2057</option><option value="2058">2058</option><option value="2059">2059</option><option value="2060">2060</option><option value="2061">2061</option><option value="2062">2062</option><option value="2063">2063</option><option value="2064">2064</option><option value="2065">2065</option><option value="2066">2066</option><option value="2067">2067</option><option value="2068">2068</option><option value="2069">2069</option><option value="2070">2070</option><option value="2071">2071</option><option value="2072">2072</option><option value="2073">2073</option><option value="2074">2074</option><option value="2075">2075</option><option value="2076">2076</option><option value="2077">2077</option><option value="2078">2078</option><option value="2079">2079</option><option value="2080">2080</option><option value="2081">2081</option><option value="2082">2082</option><option value="2083">2083</option><option value="2084">2084</option><option value="2085">2085</option><option value="2086">2086</option><option value="2087">2087</option><option value="2088">2088</option><option value="2089">2089</option><option value="2090">2090</option><option value="2091">2091</option><option value="2092">2092</option><option value="2093">2093</option><option value="2094">2094</option><option value="2095">2095</option><option value="2096">2096</option><option value="2097">2097</option><option value="2098">2098</option><option value="2099">2099</option>
										</select>
									</label>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronYearSpecific" name="cronYear">
									<label for="cronYearSpecific" class="nofloat">Specific year (choose one or many)</label>
									<div style="margin-left:50px;">
										<div>
											
												<label for="cronYear2016" class="nofloat">2016</label>
												<input type="checkbox" id="cronYear2016" name="cronYearSpecificSpecific" value="2016">
												
											
												<label for="cronYear2017" class="nofloat">2017</label>
												<input type="checkbox" id="cronYear2017" name="cronYearSpecificSpecific" value="2017">
												
											
												<label for="cronYear2018" class="nofloat">2018</label>
												<input type="checkbox" id="cronYear2018" name="cronYearSpecificSpecific" value="2018">
												
											
												<label for="cronYear2019" class="nofloat">2019</label>
												<input type="checkbox" id="cronYear2019" name="cronYearSpecificSpecific" value="2019">
												
											
												<label for="cronYear2020" class="nofloat">2020</label>
												<input type="checkbox" id="cronYear2020" name="cronYearSpecificSpecific" value="2020">
												
											
												<label for="cronYear2021" class="nofloat">2021</label>
												<input type="checkbox" id="cronYear2021" name="cronYearSpecificSpecific" value="2021">
												
											
												<label for="cronYear2022" class="nofloat">2022</label>
												<input type="checkbox" id="cronYear2022" name="cronYearSpecificSpecific" value="2022">
												
											
												<label for="cronYear2023" class="nofloat">2023</label>
												<input type="checkbox" id="cronYear2023" name="cronYearSpecificSpecific" value="2023">
												
											
												<label for="cronYear2024" class="nofloat">2024</label>
												<input type="checkbox" id="cronYear2024" name="cronYearSpecificSpecific" value="2024">
												
											
												<label for="cronYear2025" class="nofloat">2025</label>
												<input type="checkbox" id="cronYear2025" name="cronYearSpecificSpecific" value="2025">
												
											
												<label for="cronYear2026" class="nofloat">2026</label>
												<input type="checkbox" id="cronYear2026" name="cronYearSpecificSpecific" value="2026">
												
											
												<label for="cronYear2027" class="nofloat">2027</label>
												<input type="checkbox" id="cronYear2027" name="cronYearSpecificSpecific" value="2027">
												
										</div>
										
																													
										
										
										<div>
												
											
										</div>																																						
									</div>
								</div>
								<div class="cron-option" style="padding-bottom:10px">
									<input type="radio" id="cronYearRange" name="cronYear">
									<label for="cronYearRange" class="nofloat">
										Every year between 
										<select id="cronYearRangeStart" style="width:80px;">
											<option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option><option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option><option value="2050">2050</option><option value="2051">2051</option><option value="2052">2052</option><option value="2053">2053</option><option value="2054">2054</option><option value="2055">2055</option><option value="2056">2056</option><option value="2057">2057</option><option value="2058">2058</option><option value="2059">2059</option><option value="2060">2060</option><option value="2061">2061</option><option value="2062">2062</option><option value="2063">2063</option><option value="2064">2064</option><option value="2065">2065</option><option value="2066">2066</option><option value="2067">2067</option><option value="2068">2068</option><option value="2069">2069</option><option value="2070">2070</option><option value="2071">2071</option><option value="2072">2072</option><option value="2073">2073</option><option value="2074">2074</option><option value="2075">2075</option><option value="2076">2076</option><option value="2077">2077</option><option value="2078">2078</option><option value="2079">2079</option><option value="2080">2080</option><option value="2081">2081</option><option value="2082">2082</option><option value="2083">2083</option><option value="2084">2084</option><option value="2085">2085</option><option value="2086">2086</option><option value="2087">2087</option><option value="2088">2088</option><option value="2089">2089</option><option value="2090">2090</option><option value="2091">2091</option><option value="2092">2092</option><option value="2093">2093</option><option value="2094">2094</option><option value="2095">2095</option><option value="2096">2096</option><option value="2097">2097</option><option value="2098">2098</option><option value="2099">2099</option>
										</select>
										and 
										<select id="cronYearRangeEnd" style="width:80px;">
											<option value="2016">2016</option><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option><option value="2047">2047</option><option value="2048">2048</option><option value="2049">2049</option><option value="2050">2050</option><option value="2051">2051</option><option value="2052">2052</option><option value="2053">2053</option><option value="2054">2054</option><option value="2055">2055</option><option value="2056">2056</option><option value="2057">2057</option><option value="2058">2058</option><option value="2059">2059</option><option value="2060">2060</option><option value="2061">2061</option><option value="2062">2062</option><option value="2063">2063</option><option value="2064">2064</option><option value="2065">2065</option><option value="2066">2066</option><option value="2067">2067</option><option value="2068">2068</option><option value="2069">2069</option><option value="2070">2070</option><option value="2071">2071</option><option value="2072">2072</option><option value="2073">2073</option><option value="2074">2074</option><option value="2075">2075</option><option value="2076">2076</option><option value="2077">2077</option><option value="2078">2078</option><option value="2079">2079</option><option value="2080">2080</option><option value="2081">2081</option><option value="2082">2082</option><option value="2083">2083</option><option value="2084">2084</option><option value="2085">2085</option><option value="2086">2086</option><option value="2087">2087</option><option value="2088">2088</option><option value="2089">2089</option><option value="2090">2090</option><option value="2091">2091</option><option value="2092">2092</option><option value="2093">2093</option><option value="2094">2094</option><option value="2095">2095</option><option value="2096">2096</option><option value="2097">2097</option><option value="2098">2098</option><option value="2099">2099</option>
										</select>
									</label>
								</div>
							</div>						
						</div>
					</div>

<table style="max-width: 600px;">
							<tbody>
								<tr>
									<td>Segundos</td>
									<td>Minutos</td>
									<td>Horas</td>
									<td>Día del mes</td>
									<td>Mes</td>
									<td>Día de la semana</td>
									<td>Año</td>
								</tr>
								<tr>
						      		<td><span id="cronResultSecond">0</span></td>
						      		<td><span id="cronResultMinute">0</span></td>
						       		<td><span id="cronResultHour">0,10</span></td>
						       		<td><span id="cronResultDom">?</span></td>
						       		<td><span id="cronResultMonth">*</span></td>
						       		<td><span id="cronResultDow">*</span></td>
						       		<td><span id="cronResultYear">*</span></td>
								</tr>    
						  </tbody>
						</table>
</body>
</html>
					