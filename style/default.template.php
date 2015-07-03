<!DOCTYPE html>
<html lang="{{HTMLLANG}}">
<head profile="http://www.w3.org/2005/10/profile">
<link rel="icon" type="image/png" href="favicon.png">
<meta name="keywords" content="Quicker,BB,Bulletin Board,Board,Forum,Fast,PHP,Script,SQLite,MySQL,Admin,Software,Community">
<meta charset="UTF-8">
<title>{{TITLE}} - {{SUBTITLE}}</title>
<style>
	body            {background:#aaaaaa;font-size:1em;font-family:"Times New Roman",Georgia,Arial}
	textarea, input {background:#ffffff;font-size:1em;font-family:"Times New Roman",Georgia,Arial}
	a:link    {color:#0000FF;} /* unvisited link */
	a:visited {color:#0000FF;} /* visited link */
	a:hover   {color:#FF0000;} /* mouse over link */
	a:active  {color:#FF0000;} /* selected link */
	hr        {border:none;height:1px;background:black}
	div#wrap  {background:#f3f3f3;width:880px;margin:auto;padding:2px 5px 3px 5px;
		-webkit-border-radius:20px;-moz-border-radius:20px;border-radius:20px;border:2px solid #666666;}
	a#title       {font-size:24px;margin-left:8px;font-style:italic;text-decoration:none}
	span#subtitle {font-size:18px;margin-left:5px;font-style:italic}
	div#menu      {margin-left:50px;margin-bottom:5px}
	fieldset#breadcr {padding:6px}
	fieldset#contents{padding:0px}
	div.idxa {padding-left:6px;width:150px;float:left}
	div.idxb {font-style:italic}
	div.idxc {padding-left:16px}
	div#newtopic {text-align:center}
	span.vfma {}
	span.vfmb {font-style:italic}
	span.vfmc {font-style:italic}
	div.postbit  {border-bottom:1px solid black;}
	span.postsubj{font-weight:bold;font-style:italic}
	div.postleft {float:left;width:156px;font-style:italic;padding-left:4px;border-right:1px solid black}
	div.postright{margin-left:160px;padding:5px 5px;word-break:break-all;border-left:1px solid black}
	div.clear    {clear:both}
	div#footer   {font-size:0.85em;font-style:italic;text-align:center}
</style>
</head>
<body>
<div id="wrap">
<a id="title" href="index.php">{{TITLE}}</a>
<span id="subtitle">{{SUBTITLE}}</span>
<div id="menu">{{MENU}}</div>
<fieldset id="breadcr">
{{BREADCRUMB}}
</fieldset>
<fieldset id="contents">
{{CONTENTS}}</fieldset>
<div id="footer">Powered by <a href="http://github.com/halojoy/QuickerBB"
target="_blank">QuickerBB</a> &copy; halojoy 2015</div>
</div>
</body>
</html>