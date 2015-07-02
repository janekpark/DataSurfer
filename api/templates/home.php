<?php
$server = $_SERVER['HTTP_HOST'];
?>
<!DOCTYPE html>
<html>
    <head>
        <title>SANDAG Data Surfer API| Your go-to data API for the San Diego region</title>
		<link href='http://fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic' rel='stylesheet' type='text/css'>
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>

		<style>
			#body_div {
				font-family: 'Ubuntu', sans-serif;
				font-size:1em;
				font-weight:300;
				line-height:150%;
				color:#666; 	
			}
			
			#banner_head {
				width: 45%; 
				background: #194378; 
				line-height: 40px;
				font-size: 2.5em;
			    padding: 12px 20px;
				text-align: left;
				position: relative;
				color: #fff;
				font-weight: 400;
				text-decoration: none;
			}
			
			#footer_div {
				font-family: 'Ubuntu', sans-serif;
				text-align: right;
				font-weight:300;
				line-height:150%;
				font-size: .875em;
				background: #959595;
				min-height: 40px;
				letter-spacing: .05em;
				color: #fff;
				padding: 10px 15px;
			}
			
			#usage li {
				margin-top: 5px;
				font-weight: 500;
				text-decoration: underline;
				font-style: italic;
			}
		</style>
    </head>
    <body>
		<div id='body_div'>
		<div id='banner_head'>SANDAG Data Surfer API<br/>Now Available</div>
		<div id='about'>
			<h1>ABOUT</h1>
			SANDAG provides a convenient, open API to its demographic data. From this API, developers and researchers can access demographic and housing data from all of the SANDAG population datasets.
		    The application uses modern RESTful URLs to access the data. The data is returned in easy to use JSON syntax. Go ahead, build your application today.
		</div>
		<div id='usage'>
			<h1>USAGE</h1>
			http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/&lt;<i>datasource</i>&gt;/&lt;<i>year</i>&gt;/&lt;<i>geography</i>&gt;/&lt;<i>zone</i>&gt;/&lt;<i>dataset</i>&gt;
			<ul>
				<li>datasource</li>
				The data program from which to request information. Available options are census, estimate, forecast. Census data are a reflection
				of the decennial census for the San Diego region. Estimates are developed annually and are a reflection of observed conditions. The
				forecast is a most likely scenario of future events based on current demographic, economic, and policy trends in the region.
				<li>year</li>
				The year or series of the datasource requested. For a list of available years or series for each data type, use the select a datasoure
				links below.
				<br/>Examples: 
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Census =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/census'><?php echo $_SERVER['HTTP_HOST']; ?>/api/census</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Estimate =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate'><?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Forecast =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast'><?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast</a>
				<li>geograpahy</li>
				The geography to aggregate the information. For a list of geographies available, poll the API for a particular combination of 
				datasource and year / series.
				<br/>Example: <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2011'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2011</a>
				<li>zone</li>
				The available zone to aggregate for the selected datasource, year, and geography characteristics. For example, if you choose, Census 2010 by MSA,
				the list would return all available MSA names. 
				<br/>Example: <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/census/2010/msa'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/census/2010/msa</a>
				<li>dataset</li>
				The type of data that will be pulled based on the prior selections. Available options include: housing, age, ethnicity, income, and income/median. 
				For the forecast datasource, ethnicity/change and jobs are also available.
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Housing =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/region/san diego/housing'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/region/san diego/housing</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Age =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/zip/92101/age'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/zip/92101/age</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Ethnicity =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2014/tract/1.00/ethnicity'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2014/tract/1.00/ethnicity</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Income =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/census/2000/elementary/solana beach/income'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/census/2000/elementary/solana beach/income</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;Median Income =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2012/unified/san diego/income/median'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2012/unified/san diego/income/median</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;(Forecast Only) Change in Ethnicity =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/12/zip/92103/ethnicity/change'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/12/zip/92103/ethnicity/change</a>
				<br/>&nbsp;&nbsp;&nbsp;&nbsp;(Forecast Only) Jobs =&gt; <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/jurisdiction/coronado/jobs'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/jurisdiction/coronado/jobs</a>
			</ul>
			<h1>Export Usage</h1>
			<p>SANDAG also provides the Excel (XLSX) and pre-formatted PDF exports of its population and housing data through the API. Using the following syntax you can request one or more geographies to be exported
			directly to Excel or PDF.</p>
			<p>PDF Export =&gt; http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/&lt;<i>datasource</i>&gt;/&lt;<i>year</i>&gt;/&lt;<i>geography</i>&gt;/&lt;<i>zone</i>&gt;/&lt;<i>dataset+</i>&gt;/export/pdf
			<br/>Excel Export =&gt; http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/&lt;<i>datasource</i>&gt;/&lt;<i>year</i>&gt;/&lt;<i>geography</i>&gt;/&lt;<i>zone</i>&gt;/&lt;<i>dataset+</i>&gt;/export/xlsx</p>
			<ul>
			  <li>Single PDF Export</li>
			  Returns PDF<br/>
			  <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/zip/92101/export/pdf'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/zip/92101/export/pdf</a>
			  <li>Multiple PDF Export (max. 10)</li>
			  Returns ZIP<br/>
			  <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2012/jurisdiction/carlsbad/oceanside/vista/export/pdf'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2012/jurisdiction/carlsbad/oceanside/vista/export/pdf</a>
			  <li>Single Excel Export</li>
			  Returns XLSX<br/>
			  <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/zip/92101/export/xlsx'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/forecast/13/zip/92101/export/xlsx</a>
			  <li>Multiple Excel Export (max. 10)</li>
			  Returns XLSX<br/>
			  <a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2012/jurisdiction/carlsbad/oceanside/vista/export/xlsx'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2012/jurisdiction/carlsbad/oceanside/vista/export/xlsx</a>
			</ul>
			<h1>Area Map Images</h1>
			<p>In case you need a context map image for your application, the API provides static map images of all geography zones included in the API.</p>
			<a href='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2014/cpa/uptown/map'>http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2014/cpa/uptown/map</a><br/>
			<img alt='Uptown' src='http://<?php echo $_SERVER['HTTP_HOST']; ?>/api/estimate/2014/cpa/uptown/map'/>
		</div>
		<div id='footer_div'>
		&#64;2015 SANDAG
		</div>
    </body>
</html>