<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/master.dwt" codeOutsideHTMLIsLocked="false" -->
<?php
require_once("lib/config.php");
$page_title = "SANDAG Data Surfer | Variable List";
include("head.php");
?> 
<body>

	<!-- .st-container -->
    <div id="st-container" class="st-container">
    
        <!-- .st-menu -->
        <nav class="st-menu st-effect-4" id="menu-4">
    
			<!-- .left-nav -->
			<?php
			include("left_menu.php");
			?>        
			<!-- /.left-nav -->
			
		</nav>
		<!-- /.st-menu -->
		
		<!-- .st-pusher -->
        <div class="st-pusher">
		
			<!-- .st-content -->
        	<div class="st-content"> 
    
				<!-- .site-wrapper -->
				<div id="site-top" class="site-wrapper">				
					
					<!-- InstanceBeginEditable name="content" -->
					<div class="banner-section banner-variables"> 
						<div class="site-container">
							<div class="banner-img banner-img-variables">
								<div class="banner-gradient">
								
									<!-- .site-header -->
									<?php
									include("header_menu.php");
									?> 
									<!-- /.site-header -->
								
								</div>
							</div>
							<div class="banner-content clearfix"> 
								<header class="banner-header clearfix">
									<div class="site-container">
										<div class="row">
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7">
												<h2>variable list</h2>
											</div>
										</div>
									</div>
								</header>  
							</div>
						</div>
					</div>
					
					<!-- .site-content -->
					<section class="site-content site-variables">
						<div class="site-container">	
						
							<!-- .banner-section -->
							<div class="banner-section how-section">   
								
								<!-- .banner-content --> 
								<div class="banner-content creative-content clearfix"> 						
									<div class="row">
										<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left intro-section">
											<p>To further understand your Data Surfer report, view the list of variables associated with a given category. Reference the full variable list below or search by category from the drop down menu.</p>
											<p><a class="creative-link view-link" title="" href="/glossary" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
										</div>
										<div class="col-xs-offset-1 col-xs-8 col-sm-4 col-md-offset-2 col-md-3 col-right category-creative">
											<h5>Choose a category  of terms:</h5>
											<nav id="nav-select" class="nav-select nav-select-site on-desktop">
												<ul>
													<li>
														<a title="" href="javascript:void(0);" class="btn btn-green" data-ajax="false">categories<span class="glyphicon glyphicon-down"></span></a>                                        
														<div  id="scroller-wrapper">
															<div id="scroller">
																<ul>															
																	<li><a class="isScroll" title="" href="#general" data-ajax="false">general</a></li>
																	<li><a class="isScroll" title="" href="#age" data-ajax="false">age</a></li>
																	<li><a class="isScroll" title="" href="#age-by-gender" data-ajax="false">age by gender</a></li>
																	<li><a class="isScroll" title="" href="#disability-status" data-ajax="false">Disability Status</a></li>
																	<li><a class="isScroll" title="" href="#earnings" data-ajax="false">Earnings</a></li>
																	<li><a class="isScroll" title="" href="#economy" data-ajax="false">Economy</a></li>
																	<li><a class="isScroll" title="" href="#education" data-ajax="false">Education</a></li>
																	<li><a class="isScroll" title="" href="#employment-by-industry" data-ajax="false">Employment by Industry</a></li>
																	<li><a class="isScroll" title="" href="#ethnicity" data-ajax="false">Ethnicity</a></li>
																	<li><a class="isScroll" title="" href="#households-and-families" data-ajax="false">Households and Families</a></li>
																	<li><a class="isScroll" title="" href="#household-income" data-ajax="false">Household Income</a></li>
																	<li><a class="isScroll" title="" href="#household-type" data-ajax="false">Household Type</a></li>
																	<li><a class="isScroll" title="" href="#households-by-housing-structure-type" data-ajax="false">Households by Housing Structure Type</a></li>
																	<li><a class="isScroll" title="" href="#households-by-tenure-and-occupants-per-room" data-ajax="false">Households by Tenure and Occupants per Room</a></li>
																	<li><a class="isScroll" title="" href="#housing" data-ajax="false">Housing</a></li>
																	<li><a class="isScroll" title="" href="#income-detail" data-ajax="false">Income Detail</a></li>
																	<li><a class="isScroll" title="" href="#labor-force" data-ajax="false">Labor Force</a></li>
																	<li><a class="isScroll" title="" href="#land-use" data-ajax="false">Land Use</a></li>
																	<li><a class="isScroll" title="" href="#language-spoken-at-home" data-ajax="false">Language Spoken at Home</a></li>
																	<li><a class="isScroll" title="" href="#marital-status" data-ajax="false">Marital Status</a></li>
																	<li><a class="isScroll" title="" href="#means-of-transportation-to-work" data-ajax="false">Means of Transportation to Work</a></li>
																	<li><a class="isScroll" title="" href="#occupation" data-ajax="false">Occupation</a></li>
																	<li><a class="isScroll" title="" href="#occupation-industry-and-earnings" data-ajax="false">Occupation, Industry and Earnings</a></li>
																	<li><a class="isScroll" title="" href="#people" data-ajax="false">People</a></li>
																	<li><a class="isScroll" title="" href="#persons-per-household" data-ajax="false">Persons per Household</a></li>
																	<li><a class="isScroll" title="" href="#place-of-work" data-ajax="false">Place of Work</a></li>
																	<li><a class="isScroll" title="" href="#population-by-housing-structure-type" data-ajax="false">Population by Housing Structure Type</a></li>
																	<li><a class="isScroll" title="" href="#poverty-status" data-ajax="false">Poverty Status</a></li>
																	<li><a class="isScroll" title="" href="#rent" data-ajax="false">Rent</a></li>
																	<li><a class="isScroll" title="" href="#transportation" data-ajax="false">Transportation</a></li>	
																	<li><a class="isScroll" title="" href="#travel-time-to-work" data-ajax="false">Travel Time to Work</a></li>
																	<li><a class="isScroll" title="" href="#units-by-type" data-ajax="false">Units by Type</a></li>	
																	<li><a class="isScroll" title="" href="#value" data-ajax="false">Value</a></li>
																	<li><a class="isScroll" title="" href="#vehicle-availability" data-ajax="false">Vehicle Availability</a></li>	
																	<li><a class="isScroll" title="" href="#year-built" data-ajax="false">Year Built</a></li>													
																</ul>
															</div>
														</div>
													</li>
												</ul>
											</nav>
											<div class="select-site on-device">													
												<select id="txtCategories">
													<option value="" style="display: none;">CATEGORIES</option>												
													<option value="#general">GENERAL</option>
													<option value="#age">AGE</option>
													<option value="#age-by-gender">AGE BY GENDER</option>
													<option value="#disability-status">DISABILITY STATUS</option>
													<option value="#earnings">EARNINGS</option>
													<option value="#economy">ECONOMY</option>
													<option value="#education">EDUCATION</option>
													<option value="#employment-by-industry">EMPLOYMENT BY INDUSTRY</option>
													<option value="#ethnicity">ETHNICITY</option>
													<option value="#households-and-families">HOUSEHOLDS AND FAMILIES</option>
													<option value="#household-income">HOUSEHOLD INCOME</option>
													<option value="#household-type">HOUSEHOLD TYPE</option>
													<option value="#households-by-housing-structure-type">HOUSEHOLDS BY HOUSING STRUCTURE TYPE</option>
													<option value="#households-by-tenure-and-occupants-per-room">HOUSEHOLDS BY TENURE AND OCCUPANTS PER ROOM</option>
													<option value="#housing">HOUSING</option>
													<option value="#income-detail">INCOME DETAIL</option>
													<option value="#labor-force">LABOR FORCE</option>
													<option value="#land-use">LAND USE</option>
													<option value="#language-spoken-at-home">LANGUAGE SPOKEN AT HOME</option>
													<option value="#marital-status">MARITAL STATUS</option>
													<option value="#means-of-transportation-to-work">MEANS OF TRANSPORTATION TO WORK</option>
													<option value="#occupation">OCCUPATION</option>
													<option value="#occupation-industry-and-earnings">OCCUPATION, INDUSTRY AND EARNINGS</option>
													<option value="#people">PEOPLE</option>
													<option value="#persons-per-household">PERSONS PER HOUSEHOLD</option>
													<option value="#place-of-work">PLACE OF WORK</option>
													<option value="#population-by-housing-structure-type">POPULATION BY HOUSING STRUCTURE TYPE</option>
													<option value="#poverty-status">POVERTY STATUS</option>
													<option value="#rent">RENT</option>
													<option value="#transportation">TRANSPORTATION</option>	
													<option value="#travel-time-to-work">TRAVEL TIME TO WORK</option>
													<option value="#units-by-type">UNITS BY TYPE</option>	
													<option value="#value">VALUE</option>
													<option value="#vehicle-availability">VEHICLE AVAILABILITY</option>	
													<option value="#year-built">YEAR BUILT</option>													
												</select>	
											</div>			
										</div> 
									</div>						
									
								
									<section id="general" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<h2>General</h2>                                    
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total population<br />                                        
														Total household population<br />
														Group quarters population<br />
														Total households (occupied housing units)<br />
														Housing stock / Total housing units<br />
														Housing stock - vacant<br />
														Persons per household - occupied housing units<br />
														Hispanic population<br />
														Non-Hispanic population
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#general" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="age" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Age</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total population<br />
														Total population age 0 to 4<br />
														Total population age 5 to 9<br />
														Total population age 10 to 14<br />
														Total population age 15 to 19<br />
														Total population age 20 to 24<br />
														Total population age 25 to 29<br />
														Total population age 30 to 34<br />
														Total population age 35 to 39<br />
														Total population age 40 to 44<br />
														Total population age 45 to 49<br />
														Total population age 50 to 54<br />
														Total population age 55 to 59<br />
														Total population age 60 to 64<br />
														Total population age 65 to 69<br />
														Total population age 70 to 74<br />
														Total population age 75 to 79<br />
														Total population age 80 to 84<br />
														Total population age 85 years and older<br />
														Total population age 65 years and older<br />
														Median age
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#age" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="age-by-gender" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Age by Gender</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total population<br />
														Total population age 0 to 4<br />
														Total population age 5 to 9<br />
														Total population age 10 to 14<br />
														Total population age 15 to 17<br />
														Total population age 18 to 19<br />
														Total population age 20 to 24<br />
														Total population age 25 to 29<br />
														Total population age 30 to 34<br />
														Total population age 35 to 39<br />
														Total population age 40 to 44<br />
														Total population age 45 to 49<br />
														Total population age 50 to 54<br />
														Total population age 55 to 59<br />
														Total population age 60 to 61<br />
														Total population age 60 to 64<br />
														Total population age 62 to 64<br />
														Total population age 65 to 69<br />
														Total population age 70 to 74<br />
														Total population age 75 to 79<br />
														Total population age 80 to 84<br />
														Median age<br />
														Male population<br />
														Male population age 0 to 4<br />
														Male population age 5 to 9<br />
														Male population age 10 to 14<br />
														Male population age 15 to 17<br />
														Male population age 18 to 19<br />
														Male population age 20 to 24<br />
														Male population age 25 to 29<br />
														Male population age 30 to 34<br />
														Male population age 35 to 39<br />
														Male population age 40 to 44<br />
														Male population age 45 to 49<br />
														Male population age 50 to 54<br />
														Male population age 55 to 59<br />
														Male population age 60 to 61<br />
														Male population age 62 to 64<br />
														Male population age 65 to 69<br />
														Male population age 70 to 74<br />
														Male population age 75 to 79<br />
														Male population age 80 to 84<br />
														Male population age 85 years and older<br />
														Male population less than 18 years of age<br />
														Male population age 65 years and older<br />
														Median age (male population)<br />
														Female population<br />
														Female population age 0 to 4<br />
														Female population age 5 to 9<br />
														Female population age 10 to 14<br />
														Female population age 15 to 17<br />
														Female population age 18 to 19<br />
														Female population age 20 to 24<br />
														Female population age 25 to 29<br />
														Female population age 30 to 34<br />
														Female population age 35 to 39<br />
														Female population age 40 to 44<br />
														Female population age 45 to 49<br />
														Female population age 50 to 54<br />
														Female population age 55 to 59<br />
														Female population age 60 to 61<br />
														Female population age 62 to 64<br />
														Female population age 65 to 69<br />
														Female population age 70 to 74<br />
														Female population age 75 to 79<br />
														Female population age 85 years and older<br />
														Female population age 80 to 84<br />
														Female population less than 18 years of age<br />
														Female population age 65 years and older<br />
														Median age (female population)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#age-by-gender" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="disability-status" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Disability Status</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Civilian non institutionalized population age 5 and older<br />
														Civilian non institutionalized population age 5 and older with disability<br />
														Civilian non institutionalized population age 5 and older with no disability
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#disability-status" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="earnings" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Earnings</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														Households with earnings<br />
														Households with wage/salary income<br />
														Households with self-employment income<br />
														Households with interest, dividend, or net rental income<br />
														Households with social security income<br />
														Households with supplemental security income<br />
														Households with public assistance income<br />
														Households with retirement income<br />
														Households with other types of income
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#earnings" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="economy" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Economy</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Employment (total)<br />
														Employment - civilian<br />
														Employment - military<br />
														Employment density (employees per employment acre)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#economy" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="education" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Education</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Population 25 and older<br />
														Less than 9th grade (total population 25 and older)<br />
														9th through 12th grade, no diploma (population 25 and older)<br />
														High school graduate (include equivalency (population 25 and older))<br />
														Some college, no diploma (population 25 and older)<br />
														Associate's degree (population 25 and older)<br />
														Bachelor's degree (population 25 and older)<br />
														Master's degree (population 25 and older)<br />
														Professional school degree (population 25 and older)<br />
														Doctorate degree (population 25 and older)<br />
														Total population age 3 and older<br />
														Nursery/preschool - total enrollment (population 3 and older)<br />
														Kindergarten to grade 4 - total enrollment (population 3 and older)<br />
														Grade 5 to grade 8 - total enrollment (population 3 and older)<br />
														Grade 9 to grade 12 - total enrollment (population 3 and older)<br />
														College undergraduate - total enrollment (population 3 and older)<br />
														Graduate or professional school - total enrollment (population 3 and older)<br />
														Not enrolled in school (population 3 and older)<br />
														Total - public school enrollment (population 3 and older)<br />
														Nursery/preschool enrollment - public school enrollment (population 3 and older)<br />
														Kindergarten to grade 4 - public school enrollment (population 3 and older)<br />
														Grade 5 to grade 8 - public school enrollment (population 3 and older)<br />
														Grade 9 to grade 12 - public school enrollment (population 3 and older)<br />
														College undergraduate - public school enrollment (population 3 and older)<br />
														Graduate or professional school enrollment - public school enrollment (population 3 and older)<br />
														Total - private school enrollment (population 3 and older)<br />
														Nursery/preschool enrollment - private school enrollment (population 3 and older)<br />
														Kindergarten to grade 4 - private school enrollment (population 3 and older)<br />
														Grade 5 to grade 8 - private school enrollment (population 3 and older)<br />
														Grade 9 to grade 12 - private school enrollment (population 3 and older)<br />
														College undergraduate - private school enrollment (population 3 and older)<br />
														Graduate or professional school enrollment - private school enrollment (population 3 and older)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#education" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="employment-by-industry" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Employment by Industry</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Occupation - total all occupations (employed civilians age 16 and older)<br />
														Industry - agriculture, forestry, mining (employed civilians age 16 and older)<br />
														Industry - utilities (employed civilians age 16 and older)<br />
														Industry - construction (employed civilians age 16 and older)<br />
														Industry - manufacturing (employed civilians age 16 and older)<br />
														Industry - wholesale trade (employed civilians age 16 and older)<br />
														Industry - retail trade (employed civilians age 16 and older)<br />
														Industry - transportation and warehousing (employed civilians age 16 and older)<br />
														Industry - information and communications (employed civilians age 16 and older)<br />
														Industry - finance, insurance, and real estate (employed civilians age 16 and older)<br />
														Industry - professional, scientific, management, administrative (employed civilians age 16 and older)<br />
														Industry - educational, social and health services (employed civilians age 16 and older)<br />
														Industry - arts, entertainment, recreation, accommodations, food services (employed civilians age 16 and older)<br />
														Industry - other services (employed civilians age 16 and older)<br />
														Industry - public administration (employed civilians age 16 and older)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="ethnicity" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Ethnicity</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total population<br />
														Hispanic population<br />
														Non-Hispanic population<br />
														Non-Hispanic white population<br />
														Non-Hispanic black population<br />
														Non-Hispanic American Indian population<br />
														Non-Hispanic Asian population<br />
														Non-Hispanic Hawaiian population<br />
														Non-Hispanic other population<br />
														Non-Hispanic 2 or more races population
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#ethnicity" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="households-and-families" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Households and Families</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														Occupied single family housing units<br />
														Occupied multi family housing units<br />
														Persons per household - occupied housing units
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#households-and-families" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="household-income" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Household Income</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														median household income (in 1999 dollars)<br />
														Household income - less than $10,000<br />
														Household income - $10,000 to $14,999<br />
														Household income - $15,000 to $24,999<br />
														Household income - $25,000 to $34,999<br />
														Household income - $35,000 to $49,999<br />
														Household income - $50,000 to $74,999<br />
														Household income - $75,000 - $99,999<br />
														Household income - $100,000 or more
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#household-income" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="household-type" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Household Type</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														Total households - with children under age 18<br />
														Total households - no children under age 18<br />
														Total family households<br />
														Family households - with children under age 18<br />
														Family households - no children under age 18<br />
														Family household - married couple - total<br />
														Family household - married couple - with children under age 18<br />
														Family household - married couple - no children under age 18<br />
														Family household - other family - total<br />
														Family household - other family - with children under age 18<br />
														Family household - other family - no children under age 18<br />
														Family household - male householder, no spouse - total<br />
														Family household - male householder, no spouse - with children under age 18<br />
														Family household - male householder, no spouse - no children under age 18<br />
														Family household - female householder, no spouse - total<br />
														Family household - female householder, no spouse - with children under age 18<br />
														Family household - female householder, no spouse - no children under age 18<br />
														Total non-family households<br />
														Non-family households - with children under age 18<br />
														Non-family household - no children under age 18<br />
														Non-family households - householder living alone<br />
														Non-family households - other non-family household
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#household-type" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="households-by-housing-structure-type" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Households by Housing Structure Type</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														Occupied single family housing units<br />
														Occupied single family housing units - detached<br />
														Occupied single family housing units - attached<br />
														Occupied multi family housing units<br />
														Occupied multi family (2 to 4 units) housing units<br />
														Occupied multi family (5 to 9 units) housing units<br />
														Occupied multi family (10 units or more) housing units<br />
														Occupied mobile homes<br />
														Occupied other housing types
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#households-by-housing-structure-type" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="households-by-tenure-and-occupants-per-room" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Households by Tenure and Occupants per Room</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														1.00 occupant per room or less (total occupied housing units)<br />
														1.01 to 1.50 occupants per room (total occupied housing units)<br />
														1.51 to 2.00 occupants per room (total occupied housing units)<br />
														2.01 or more occupants per room (total occupied housing units)<br />
														Total renter occupied housing units<br />
														1.00 occupant per room or less (renter occupied housing units)<br />
														1.01 to 1.50 occupants per room (renter occupied housing units)<br />
														1.51 to 2.00 occupants per room (renter occupied housing units)<br />
														2.01 or more occupants per room (renter occupied housing units)<br />
														Total owner occupied units<br />
														1.00 occupant per room or less (owner occupied housing units)<br />
														1.01 to 1.50 occupants per room (owner occupied housing units)<br />
														1.51 to 2.00 occupants per room (owner occupied housing units)<br />
														2.01 or more occupants per room (owner occupied housing units)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#households-by-tenure-and-occupants-per-room" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="housing" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Housing</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														Housing stock / total housing units<br />
														Housing stock - single family<br />
														Housing stock - multi family<br />
														Mobile homes & other housing stock<br />
														Occupied single family housing units<br />
														Occupied multi family housing units<br />
														Occupied mobile homes & other housing units<br />
														Persons per household - occupied housing units<br />
														Residential density (housing units per residential acre)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#housing" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="income-detail" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Income Detail</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														Household income - less than $10,000<br />
														Household income - $10,000 to $19,999<br />
														Household income - $20,000 to $29,999<br />
														Household income - $30,000 to $39,999<br />
														Household income - $40,000 to $49,999<br />
														Household income - $50,000 to $59,999<br />
														Household income - $60,000 to $74,999<br />
														Household income - $75,000 to $99,999<br />
														Household income - $100,000 to $149,999<br />
														Household income - $150,000 or more<br />
														Median household income (in 1999 dollars)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#income-detail" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="labor-force" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Labor Force</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total age 16 and older<br />
														Total in labor force (residents)<br />
														Total Armed Forces (residents)<br />
														Total civilian employed (residents)<br />
														Total civilian unemployed (residents)<br />
														Total not in labor force (residents)<br />
														Total males age 16 and older<br />
														Males in labor force<br />
														Male - Armed Forces (residents)<br />
														Male - civilian employed (residents)<br />
														Male - civilian unemployed (residents)<br />
														Male - not in labor force (residents)<br />
														Total females age 16 and older<br />
														Females in labor force (residents)<br />
														Female - Armed Forces (residents)<br />
														Female - civilian employed (residents)<br />
														Female - civilian unemployed<br />
														Female - not in labor force (residents)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#labor-force" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="land-use" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Land Use</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Acres (total)<br />
														Developed acres (total)<br />
														Developed acres - low-density single family<br />
														Developed acres - single family<br />
														Developed acres - multi family<br />
														Developed acres - mobile home<br />
														Developed acres - other<br />
														Developed acres - industrial<br />
														Developed acres - commercial<br />
														Developed acres - office<br />
														Developed acres - schools<br />
														Developed acres - roads<br />
														Developed acres - agricultural<br />
														Developed acres - parks<br />
														Developed acres - military<br />
														Vacant developable acres (total)<br />
														Vacant acres - low-density single family<br />
														Vacant acres - single family<br />
														Vacant acres - multi family<br />
														Vacant acres - industrial<br />
														Vacant acres - commercial<br />
														Vacant acres - office<br />
														Vacant acres - schools<br />
														Vacant acres - roads<br />
														Constrained acres / "unusable land"
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#land-use" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="language-spoken-at-home" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Language Spoken at Home</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Population age 5 years and older<br />
														Speak only English (population 5 years and older)<br />
														Speak Spanish - total (population 5 years and older)<br />
														Speak Spanish - speak English "well' or "very well" (population 5 years and older)<br />
														Speak Spanish - speak English "not well" or "not at all" (population 5 years and older)<br />
														Speak Asian/pacific islander language - total (population 5 years and older)<br />
														Speak Asian/pacific islander language - speak English "well' or "very well" (population 5 years and older)<br />
														Speak Asian/pacific islander language - speak English "not well" or "not at all" (population 5 years and older)<br />
														Speak other language - total (population 5 years and older)<br />
														Speak other language - speak English "well' or "very well" (population 5 years and older)<br />
														Speak other language - speak English "not well" or "not at all" (population 5 years and older)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#language-spoken" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="marital-status" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Marital Status</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Population age 15 years and older<br />
														Never married (population 15 years and older)<br />
														Married (population 15 years and older)<br />
														Separated (population 15 years and older)<br />
														Widowed (population 15 years and older)<br />
														Divorced (population 15 years and older)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#marital-status" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="means-of-transportation-to-work" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Means of Transportation to Work</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total workers (age 16 and over)<br />
														Car, truck or van - total (workers)<br />
														Car, truck or van - drove alone (workers)<br />
														Car, truck or van - carpooled (workers)<br />
														Public transportation - total (workers)<br />
														Public transportation - bus (workers)<br />
														Public transportation - trolley or streetcar (workers)<br />
														Public transportation - railroad (workers)<br />
														Public transportation - other public transportation (workers)<br />
														Motorcycle - (workers)<br />
														Bicycle (workers)<br />
														Walked (workers)<br />
														Other means - (workers)<br />
														Worked at home (workers)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#means-of-transportation-to-work" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="occupation" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Occupation</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Occupation - total all occupations (employed civilians age 16 and older)<br />
														Occupation - total - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - management, incl. Farm managers - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - business and financial - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - computer and mathematical - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - architecture and engineering - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - life, physical, and social science - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - community and social service - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - legal - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - education, training, and library - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - arts, design, entertainments, sports, and media - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - healthcare practitioners - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - total - service (employed civilians age 16 and older)<br />
														Occupation - healthcare support - service (employed civilians age 16 and older)<br />
														Occupation - protective service - service (employed civilians age 16 and older)<br />
														Occupation - food preparation and serving - service (employed civilians age 16 and older)<br />
														Occupation - building and grounds cleaning/maint - service (employed civilians age 16 and older)<br />
														Occupation - personal care and service - service (employed civilians age 16 and older)<br />
														Occupation - total - sales and office (employed civilians age 16 and older)<br />
														Occupation - total - farming, fishing, and forestry (employed civilians age 16 and older)<br />
														Occupation - total - construction, extraction, and maintenance (employed civilians age 16 and older)<br />
														Occupation - total - production, transportation, and material moving (employed civilians age 16 and older)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#occupation" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="occupation-industry-and-earnings" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Occupation, Industry and Earnings</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total workers (age 16 and over)<br />
														Worked in state of residence<br />
														Worked in county of residence<br />
														Worked outside county of residence<br />
														Worked outside state of residence<br />
														Occupation - total all occupations (employed civilians age 16 and older)<br />
														Occupation - total - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - management, incl. Farm managers - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - business and financial - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - computer and mathematical - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - architecture and engineering - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - life, physical, and social science - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - community and social service - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - legal - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - education, training, and library - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - arts, design, entertainments, sports, and media - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - healthcare practitioners - management, professional and related (employed civilians age 16 and older)<br />
														Occupation - total - service (employed civilians age 16 and older)<br />
														Occupation - healthcare support - service (employed civilians age 16 and older)<br />
														Occupation - protective service - service (employed civilians age 16 and older)<br />
														Occupation - food preparation and serving - service (employed civilians age 16 and older)<br />
														Occupation - building and grounds cleaning/maint - service (employed civilians age 16 and older)<br />
														Occupation - personal care and service - service (employed civilians age 16 and older)<br />
														Occupation - total - sales and office (employed civilians age 16 and older)<br />
														Occupation - total - farming, fishing, and forestry (employed civilians age 16 and older)<br />
														Occupation - total - construction, extraction, and maintenance (employed civilians age 16 and older)<br />
														Occupation - total - production, transportation, and material moving (employed civilians age 16 and older)<br />
														Industry - agriculture, forestry, mining (employed civilians age 16 and older)<br />
														Industry - utilities (employed civilians age 16 and older)<br />
														Industry - construction (employed civilians age 16 and older)<br />
														Industry - manufacturing (employed civilians age 16 and older)<br />
														Industry - wholesale trade (employed civilians age 16 and older)<br />
														Industry - retail trade (employed civilians age 16 and older)<br />
														Industry - transportation and warehousing (employed civilians age 16 and older)<br />
														Industry - information and communications (employed civilians age 16 and older)<br />
														Industry - finance, insurance, and real estate (employed civilians age 16 and older)<br />
														Industry - professional, scientific, management, administrative (employed civilians age 16 and older)<br />
														Industry - educational, social and health services (employed civilians age 16 and older)<br />
														Industry - arts, entertainment, recreation, accommodations, food services (employed civilians age 16 and older)<br />
														Industry - other services (employed civilians age 16 and older)<br />
														Industry - public administration (employed civilians age 16 and older)<br />
														Households with earnings<br />
														Households with wage/salary income<br />
														Households with self-employment income<br />
														Households with interest, dividend, or net rental income<br />
														Households with social security income<br />
														Households with supplemental security income<br />
														Households with public assistance income<br />
														Households with retirement income<br />
														Households with other types of income
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#occupation-industry-and-earnings" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="people" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>People</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total population<br />
														Hispanic population<br />
														Non-Hispanic population<br />
														Median age<br />
														Total household population<br />
														Group quarters population<br />
														Group quarters population - total institutionalized<br />
														Group quarters population - total non-institutionalized<br />
														Group quarters population - civilian<br />
														Group quarters population - military<br />
														Median household income (in 1999 dollars)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#people" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="persons-per-household" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Persons per Household</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Persons per household - occupied housing units<br />
														Persons per household - occupied single family - total<br />
														Persons per household - occupied single family housing units - detached<br />
														Persons per household - occupied single family housing units - attached<br />
														Persons per household - occupied multi family housing units - total<br />
														Persons per household - occupied multi family (2 to 4 units) housing units<br />
														Persons per household - occupied multi family (5 to 9 units) housing units<br />
														Persons per household - occupied multi family (10 units or more) housing units<br />
														Persons per household - occupied mobile home<br />
														Persons per household - occupied other housing units
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#persons-by-household" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="place-of-work" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Place of Work</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total workers (age 16 and over)<br />
														Worked in state of residence<br />
														Worked in county of residence<br />
														Worked outside county of residence<br />
														Worked outside state of residence
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#place-of-work" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="population-by-housing-structure-type" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Population by Housing Structure Type</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total household population<br />
														Household population - single family - total<br />
														Household population - single family - detached<br />
														Household population - single family - attached<br />
														Household population - multi family - total<br />
														Household population - multi family - 2 to 4 units<br />
														Household population - multi family - 5 to 9 units<br />
														Household population - multi family - 10+ units<br />
														Household population - mobile home<br />
														Household population - other housing type
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#population-by-housing-structure-type" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="poverty-status" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Poverty Status</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Population for whom poverty status is determined<br />
														Total population above poverty<br />
														Total population below poverty<br />
														Income 50% of poverty level (population for whom poverty status is determined)<br />
														Income 50% to 74% of poverty level (population for whom poverty status is determined)<br />
														Income 75% to 99% of poverty level (population for whom poverty status is determined)<br />
														Income 100% to 124% of poverty level (population for whom poverty status is determined)<br />
														Income 125% to 149% of poverty level (population for whom poverty status is determined)<br />
														Income 150% to 199% of poverty level (population for whom poverty status is determined)<br />
														Income 200% of poverty level or higher (population for whom poverty status is determined)<br />
														Families (total, all relationships) for whom poverty status is determined<br />
														Families (total, married couple) for whom poverty status is determined<br />
														Families (total, male householder, no wife present) for whom poverty status is determined<br />
														Families (total, female householder, no husband present) for whom poverty status is determined<br />
														Families (with children, all relationships) for whom poverty status is determined<br />
														Families (with children, married couple) for whom poverty status is determined<br />
														Families (with kids, male householder, no wife present) for whom poverty status is determined<br />
														Families (with kids, female householder, no husband present) for whom poverty status is determined<br />
														Families (without kids, all relationships) for whom poverty status is determined<br />
														Families (without kids, married couple) for whom poverty status is determined<br />
														Families (without kids, male householder, no wife present) for whom poverty status is determined<br />
														Families (without kids, female householder, no husband present) for whom poverty status is determined<br />
														Above poverty families (total, all relationships)<br />
														Above poverty families (total, married couple)<br />
														Above poverty families (total, male householder, no wife present)<br />
														Above poverty families (total, female householder, no husband present)<br />
														Above poverty families (with kids, all relationships)<br />
														Above poverty families (with kids, married couple)<br />
														Above poverty families (with kids, male householder, no wife present)<br />
														Above poverty families (with kids, female householder, no husband present)<br />
														Above poverty families (without kids, all relationships)<br />
														Above poverty families (without kids, married couple)<br />
														Above poverty families (without kids, male householder, no wife present)<br />
														Above poverty families (without kids, female householder, no husband present)<br />
														Below poverty families (total, all relationships)<br />
														Below poverty families (total, married couple)<br />
														Below poverty families (total, male householder, no wife present)<br />
														Below poverty families (total, female householder, no husband present)<br />
														Below poverty families (with kids, all relationships)<br />
														Below poverty families (with kids, married couple)<br />
														Below poverty families (with kids, male householder, no wife present)<br />
														Below poverty families (with kids, female householder, no husband present)<br />
														Below poverty families (without kids, all relationships)<br />
														Below poverty families (without kids, married couple)<br />
														Below poverty families (without kids, male householder, no wife present)<br />
														Below poverty families (without kids, female householder, no husband present)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#poverty-status" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="rent" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Rent</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total renter occupied housing units<br />
														Less than $500 rent<br />
														$500 to $599 rent<br />
														$600 to $699 rent<br />
														$700 to $799 rent<br />
														$800 to $899 rent<br />
														$900 to $999 rent<br />
														$1,000 to $1,249 rent<br />
														$1,250 to $1,499 rent<br />
														$1,500 to $1,999 rent<br />
														$2,000 or more rent<br />
														No cash rent<br />
														Median contract rent<br />
														Gross rent less than 20 percent of household income<br />
														Gross rent 20.0 to 24.9 percent of household income<br />
														Gross rent 25.0 to 29.9 percent of household income<br />
														Gross rent 30.0 to 34.9 percent of household income<br />
														Gross rent 35.0 to 39.9 percent of household income<br />
														Gross rent 40.0 to 49.9 percent of household income<br />
														Gross rent 50.0 percent or more of household income<br />
														Gross rent not computed
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#rent" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="transportation" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Transportation</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														No vehicle available<br />
														Car, truck or van - drove alone (workers)<br />
														Average travel time to work (workers who did not work at home)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#transportation" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="travel-time-to-work" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Travel Time to Work</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total workers (age 16 and over)<br />
														Total "did not work at home" employed workers<br />
														Less than 10 minute commute (workers who did not work at home)<br />
														10 to 19 minute commute (workers who did not work at home)<br />
														20 to 29 minute commute (workers who did not work at home)<br />
														30 to 44 minute commute (workers who did not work at home)<br />
														45 to 59 minute commute (workers who did not work at home)<br />
														60 to 89 minute commute (workers who did not work at home)<br />
														90 minutes or more commute (workers who did not work at home)<br />
														Average travel time to work (workers who did not work at home)
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#travel-time-to-work" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>											
									
									<section id="units-by-type" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Units by Type</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Housing stock / total housing units<br />
														Housing stock - single family<br />
														Housing stock - single family detached<br />
														Housing stock - single family attached<br />
														Housing stock - multi family<br />
														Housing stock - multi family (2 to 4 units)<br />
														Housing stock - multi family (5 to 9 units)<br />
														Housing stock - multi family (10 units or more)<br />
														Housing stock - mobile home<br />
														Other housing stock<br />
														Occupied single family housing units<br />
														Occupied single family housing units - detached<br />
														Occupied single family housing units - attached<br />
														Occupied multi family housing units<br />
														Occupied multi family (2 to 4 units) housing units<br />
														Occupied multi family (5 to 9 units) housing units<br />
														Occupied multi family (10 units or more) housing units<br />
														Occupied mobile homes<br />
														Occupied other housing types<br />
														Vacant single family housing units<br />
														Vacant single family housing units - detached<br />
														Vacant single family housing units - attached<br />
														Vacant multi family housing units<br />
														Vacant multi family (2 to 4 units) housing units<br />
														Vacant multi family (5 to 9 units) housing units<br />
														Vacant multi family (10 units or more) housing units<br />
														Vacant mobile homes housing units<br />
														Vacant other housing types
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#units-by-type" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="value" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Value</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total specified owner occupied housing units<br />
														Housing value less than $150,000<br />
														Housing value $150,000 to $199,999<br />
														Housing value $200,000 to $249,999<br />
														Housing value $250,000 to $299,999<br />
														Housing value $300,000 to $399,999<br />
														Housing value $400,000 to $499,999<br />
														Housing value $500,000 to $749,999<br />
														Housing value $750,000 to $999,999<br />
														Housing value $1,000,000 or more<br />
														Median housing value
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#value" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="vehicle-availability" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Vehicle Availability</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Total households (occupied housing units)<br />
														No vehicle available<br />
														1 vehicle available<br />
														2 vehicles available<br />
														3 vehicles available<br />
														4 or more vehicles available
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#vehicle-availability" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
									
									<section id="year-built" class="creative-section">
										<div class="row">                                
											<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
												<hr />
												<header>
													<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
													<h2>Year Built</h2>
												</header>
											</div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
												<div class="phargagh-section">
													<p>
														Housing units built 1999 to March 2000<br />
														Housing units built 1995 to 1998<br />
														Housing units built 1990 to 1994<br />
														Housing units built 1980 to 1989<br />
														Housing units built 1970 to 1979<br />
														Housing units built 1960 to 1969<br />
														Housing units built 1950 to 1959<br />
														Housing units built 1940 to 1949<br />
														Housing units built 1939 or earlier
													</p>
													<p><a class="creative-link view-link" title="" href="/glossary#year-built" data-ajax="false">VIEW GLOSSARY OF TERMS</a></p>
												</div>
											</div>
										</div>
									</section>
								
									<div class="row">                                
										<div class="col-xs-offset-1 col-xs-8 col-sm-4 col-md-3 banner-footer"> 											
											<a title="" href="#site-top" class="btn btn-green isScroll" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>                            											
										</div>							
									</div>
								</div>   
								<!-- /.banner-content --> 
								
							</div>
							<!-- /.banner-section -->
							
						</div>
					</section>
					<!-- /.site-content -->
					<!-- InstanceEndEditable -->
					
					<!-- /.site-footer -->
					<?php
					include("footer.php");
					?>
					<!-- /.site-footer -->
					
				</div> 
				<!-- /.site-wrapper -->
			
			</div>
			<!-- /.st-content -->
    
		</div>
        <!-- /.st-pusher -->
        
    </div>  
    <!-- /.st-container -->
	
    <noscript>
        <!-- must remain last thing before the form tag -->
        <p class="noJS">YOU NEED JAVASCRIPT TO RUN THIS SITE. PLEASE ENABLE JAVASCRIPT IN YOUR INTERNET OPTIONS.</p>
    </noscript>
    <!-- END HTML --> 
	<script>
	
		// Begin scroll other page
		var jump = function(e)
		{
			if (e){
			   e.preventDefault();
			   var target = $(this).attr("href");
			}else{
				var target = location.hash;
			}
			if(isMobile.any()){
				$('html, body').animate({
				   scrollTop: $(target).offset().top
				},1000,function(){
				   location.hash = target;
				});
			}else{
				$('html, body').animate({
				   scrollTop: $(target).offset().top
				},1000,function(){
				   location.hash = target;
				});
			}
		}

		$(document).ready(function(){
			// End scroll other page
			//$('a.view-link[href^=#]').bind('click', jump);
			if (location.hash){
				setTimeout(function(){
					/*if(isMobile.any()){				
						$('html, body').scrollTop(0).show();
					}else{
						$('.st-content').scrollTop(0).show();
					}*/
					jump();
				}, 0);
			}
			// End scroll other page
			
			$('.nav-select').bind('click', function(e){
				if($(this).hasClass("nav-select-site")){
					var isDisplay = $('#scroller-wrapper').attr("style");
					if(isDisplay == null || isDisplay == 'display: none;')
					{
						$('#scroller-wrapper').hide();
						$('#scroller-wrapper').css({'visibility': 'visible'});
					}
					toggleIt("#nav-select #scroller-wrapper");
				}
			});
			$(document).bind(eventMouse, function(e){	
				var objTarget = $(e.target);
				if(!objTarget.parents().hasClass("nav-select")){
					$('#scroller-wrapper').slideUp(200, 'linear');
				}
			});
			
			$('#txtCategories').change(function(){
				var valSelect = $(this).val();
				var offsetBar = $('.st-content').scrollTop(),
				offsetObj = $(valSelect).offset().top,
				offsetScroll = offsetBar + offsetObj;
				$('html, body').animate({scrollTop:offsetScroll}, 1000);
				$('#txtCategories').val("");
				$('#txtCategories').selectmenu('refresh');
			})		

			scrollCategory ();

		})

		var categoryScroll;

		function scrollCategory () {
			categoryScroll = new IScroll('#scroller-wrapper', {
				scrollbars: true,
				mouseWheel: true,
				interactiveScrollbars: true,
				shrinkScrollbars: 'scale',
				fadeScrollbars: true,
				click: true 
			});
		}
		
	</script>
</body>
<!-- InstanceEnd --></html>
