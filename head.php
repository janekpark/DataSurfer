<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="x-ua-compatible" content="IE=edge">
<meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1, maximum-scale=1" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
<link rel="icon" href="favicon.ico" type="image/x-icon">
<!-- InstanceBeginEditable name="doctitle" -->
<title><?php if(isset($page_title))echo $page_title;?></title>
<meta name="author" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<!-- InstanceEndEditable --> 

<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

<!-- Bootstrap core CSS -->
<link rel="stylesheet" type="text/css" href="/content/css/bootstrap.css" />
<link rel="stylesheet" type="text/css" href="/content/css/bootstrap-select.css" />

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable --> 
<link type="text/css" rel="stylesheet" href="/content/css/jquery.mobile-1.4.5.min.css" />
<link type="text/css" rel="stylesheet" href="/content/css/component.css" />
<link type="text/css" rel="stylesheet" href="/content/css/pulldown.css" />
<link type="text/css" rel="stylesheet" href="/content/css/sandag.css" /> 
<link type="text/css" rel="stylesheet" href="/content/css/jsmobile.css" /> 
<link type="text/css" rel="stylesheet" href="/content/css/ipad.css" /> 
<link type="text/css" rel="stylesheet" href="/content/css/mobile.css" /> 
<link type="text/css" rel="stylesheet" href="/content/css/retina.css" /> 
<!-- === SCRIPTS === -->  
<script type="text/javascript" src="/scripts/libs/modernizr.custom.js"></script>
<script type="text/javascript" src="/scripts/libs/jquery-1.9.1.js"></script>    	

<script type="text/javascript">
var isMobile = {
	Android: function () {
		return navigator.userAgent.match(/Android/i);
	},
	BlackBerry: function () {
		return navigator.userAgent.match(/BlackBerry/i);
	},
	iOS: function () {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	},
	Opera: function () {
		return navigator.userAgent.match(/Opera Mini/i);
	},
	Windows: function () {
		return navigator.userAgent.match(/IEMobile/i);
	},
	any: function () {
		return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	}
};

if (isMobile.any()) {	
	var jQueryMobile = $("<script>", {
		  "type" :  "text/javascript",
		  "src" : "/scripts/libs/jquery.mobile-1.4.5.min.js"
		})[0];	
	document.getElementsByTagName("head")[0].appendChild(jQueryMobile);		
}else{
	var jQueryBootstrap = $("<script>", {
		  "type" :  "text/javascript",
		  "src" : "/scripts/libs/bootstrap.min.js"
		})[0];	
	document.getElementsByTagName("head")[0].appendChild(jQueryBootstrap);
	var jQueryBootstrapSelect = $("<script>", {
		  "type" :  "text/javascript",
		  "src" : "/scripts/libs/bootstrap-select.js"
		})[0];	
	document.getElementsByTagName("head")[0].appendChild(jQueryBootstrapSelect);
}
</script>
<script type="text/javascript" src="/scripts/libs/classie.js"></script> 
<script type="text/javascript" src="/scripts/libs/jquery.validate.js"></script>
<script type="text/javascript" src="/scripts/libs/iscroll.js"></script>
<!-- InstanceBeginEditable name="script" -->
<!-- InstanceEndEditable --> 
<script type="text/javascript" src="/scripts/general-ui.js"></script> 
<script type="text/javascript" src="/scripts/libs/sidebarEffects.js"></script> 
<script type="text/javascript" src="/scripts/general.js"></script>
<!-- InstanceBeginEditable name="script" -->
<script type="text/javascript" src="http://code.highcharts.com/highcharts.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/modules/exporting.js"></script>    
<script type="text/javascript" src="http://code.highcharts.com/modules/data.js"></script>
<script src="http://code.highcharts.com/modules/no-data-to-display.js"></script>
<script type="text/javascript" src="http://code.highcharts.com/modules/drilldown.js"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-2244852-26', 'auto');
  ga('send', 'pageview');

</script>

<!-- === END SCRIPTS === --> 
</head>
