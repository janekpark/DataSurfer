<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/master.dwt" codeOutsideHTMLIsLocked="false" -->
<?php
require_once("lib/config.php");
$page_title = "SANDAG Data Surfer | Glossary";
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
				<div class="banner-section banner-glossary">   
					<div class="site-container">
						<div class="banner-img banner-img-glossary">
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
										<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-7">
											<h2>GLOSSARY OF TERMS BY TOPIC</h2>
										</div>
									</div>
								</div>
							</header>
						</div>
					</div>
				</div>
				
				<!-- .site-content -->
				<section class="site-content site-glossary">
					<div class="site-container">
					
						<!-- .banner-section -->
                        <div class="banner-section how-section">   
                            
                            <!-- .banner-content --> 
                            <div class="banner-content creative-content clearfix">  					
								<div class="row">
									<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left intro-section">
										<p>Not sure what all of the search and data terms mean in your report from Data Surfer? Choose a category and review the associated terms to get a better understanding.</p>
										<p><a class="creative-link view-link" title="" href="/variablelist" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
																<li><a class="isScroll" title="" href="#age" data-ajax="false">Age</a></li>
																<li><a class="isScroll" title="" href="#age-by-gender" data-ajax="false">Age by Gender</a></li>
																<li><a class="isScroll" title="" href="#disability-status" data-ajax="false">Disability Status</a></li>
																<li><a class="isScroll" title="" href="#earnings" data-ajax="false">Earnings</a></li>
																<li><a class="isScroll" title="" href="#economy" data-ajax="false">Economy</a></li>
																<li><a class="isScroll" title="" href="#education" data-ajax="false">Education</a></li>
																<li><a class="isScroll" title="" href="#ethnicity" data-ajax="false">Ethnicity</a></li>
																<li><a class="isScroll" title="" href="#household-income" data-ajax="false">Household Income</a></li>
																<li><a class="isScroll" title="" href="#household-type" data-ajax="false">Household Type</a></li>
																<li><a class="isScroll" title="" href="#households-and-families" data-ajax="false">Households and Families</a></li>
																<li><a class="isScroll" title="" href="#households-by-housing-structure-type" data-ajax="false">Households by Housing Structure Type</a></li>
																<li><a class="isScroll" title="" href="#households-by-tenure-and-occupants-per-room" data-ajax="false">Households by Tenure and Occupants per Room</a></li>
																<li><a class="isScroll" title="" href="#housing" data-ajax="false">Housing</a></li>	
																<li><a class="isScroll" title="" href="#income-detail" data-ajax="false">Income Detail</a></li>
																<li><a class="isScroll" title="" href="#labor-force" data-ajax="false">Labor Force</a></li>	
																<li><a class="isScroll" title="" href="#land-use" data-ajax="false">Land Use</a></li>	
																<li><a class="isScroll" title="" href="#language-spoken" data-ajax="false">Language Spoken</a></li>	
																<li><a class="isScroll" title="" href="#marital-status" data-ajax="false">Marital Status</a></li>	
																<li><a class="isScroll" title="" href="#means-of-transportation-to-work" data-ajax="false">Means of Transportation to Work</a></li>
																<li><a class="isScroll" title="" href="#occupation" data-ajax="false">Occupation</a></li>	
																<li><a class="isScroll" title="" href="#occupation-industry-and-earnings" data-ajax="false">Occupation, Industry and Earnings</a></li>								
																<li><a class="isScroll" title="" href="#people" data-ajax="false">people</a></li>
																<li><a class="isScroll" title="" href="#persons-by-household" data-ajax="false">PERSONS BY HOUSEHOLD</a></li>
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
												<option value="#ethnicity">ETHNICITY</option>
												<option value="#household-income">HOUSEHOLD INCOME</option>
												<option value="#household-type">HOUSEHOLD TYPE</option>
												<option value="#households-and-families">HOUSEHOLDS AND FAMILIES</option>
												<option value="#households-by-housing-structure-type">HOUSEHOLDS BY HOUSING STRUCTURE TYPE</option>
												<option value="#households-by-tenure-and-occupants-per-room">HOUSEHOLDS BY TENURE AND OCCUPANTS PER ROOM</option>
												<option value="#housing">HOUSING</option>	
												<option value="#income-detail">INCOME DETAIL</option>
												<option value="#labor-force">LABOR FORCE</option>	
												<option value="#land-use">LAND USE</option>	
												<option value="#language-spoken">LANGUAGE SPOKEN</option>	
												<option value="#marital-status">MARITAL STATUS</option>	
												<option value="#means-of-transportation-to-work">MEANS OF TRANSPORTATION TO WORK</option>
												<option value="#occupation">OCCUPATION</option>	
												<option value="#occupation-industry-and-earnings">OCCUPATION, INDUSTRY AND EARNINGS</option>								
												<option value="#people">PEOPLE</option>
												<option value="#persons-by-household">PERSONS BY HOUSEHOLD</option>
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
												<h2>general</h2>                                    
											</header>
										</div>
									</div>
									<div class="row">						
										<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
											<div class="phargagh-section">
												<h4>Census ACS</h4>
												<p>The American Community Survey (ACS) is the U.S. Census Bureau’s program for collecting and disseminating demographic, socioeconomic, and housing data through an annual nationwide survey of a sample of households. 
												Estimates representative of the entire population are reported based on the data collected from the sample. SANDAG applies these figures to our population estimates to derive data for local geographic areas every 
												five years starting with 2010.</p>
											</div>
											<div class="phargagh-section">
												<h4>Group Quarters Population</h4>
												<p>All persons not living in households. Includes both institutional (e.g., nursing homes, hospitals, and prisons) and noninstitutional (e.g., college dormitories, military barracks, and workers' dormitories) settings.</p>
											</div>
											<div class="phargagh-section">
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>
											<div class="phargagh-section">
												<h4>Household Population</h4>
												<p>All persons living in a household (an occupied housing unit).</p>
											</div>
											<div class="phargagh-section">
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Persons per Household</h4>
												<p>The average number of persons living in a housing unit, calculated by dividing the household population by the number of occupied housing units.</p>
											</div>
											<div class="phargagh-section">
												<h4>Population</h4>
												<p>Total number of persons (residents) in an area.</p>
											</div>
											<div class="phargagh-section">
												<h4>Vacant</h4>
												<p>A unit in which no one is living, unless its occupants are only temporarily absent. Units temporarily occupied entirely by people who have a usual residence elsewhere are also classified as vacant.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#general" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Median Age</h4>
												<p>Divides the age distribution into two equal parts; half of all persons are older than the median and half are younger.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Population</h4>
												<p>Total number of persons (residents) in an area.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#age" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Median Age</h4>
												<p>Divides the age distribution into two equal parts; half of all persons are older than the median and half are younger.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Population</h4>
												<p>Total number of persons (residents) in an area.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#age-by-gender" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Disability</h4>
												<p>A long-lasting physical, mental, or emotional condition making it difficult for a person to do activities such as walking, climbing stairs, dressing, bathing, learning, or remembering. May also impede a person from being able to go outside the home alone or to work at a job or business.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#disability-status" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Earnings</h4>
												<p>The sum of wage or salary income and net income from self-employment. The amount of income received regularly before deductions for personal income taxes, Social Security, bond purchases, union dues, Medicare deductions, etc.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>
											<div class="phargagh-section">
												<h4>Income</h4>
												<p>Sum of wages, salary, commissions, bonuses, tips, self-employment income, interest, dividends, net rental income, royalty income, income from estates and trusts, Social Security or Railroad Retirement income, Supplemental Security Income (SSI), any public assistance or welfare payments from the state or local welfare office, retirement, survivor, or disability pensions, and any other sources of income received regularly.</p>
											</div>
											<div class="phargagh-section">
												<h4>Interest, Dividend, or Net Rental Income</h4>
												<p>Interest on savings or bonds, dividends from stockholdings or membership in associations, net income from rental of property to others and receipts from boarders or lodgers, net royalties, and periodic payments from an estate or trust fund.</p>
											</div>
											<div class="phargagh-section">
												<h4>Public Assistance</h4>
												<p>General assistance and Temporary Assistance to Needy Families (TANF).</p>
											</div>	
											<div class="phargagh-section">
												<h4>Self-Employment</h4>
												<p>Farm (net money income from the operation of a farm by a person on his or her own account, as an owner, renter, or sharecropper) and nonfarm (net money income receipts from one’s own business, professional enterprise, or partnership) self-employment income.</p>
											</div>
											<div class="phargagh-section">
												<h4>Social Security</h4>
												<p>Social security pensions and survivors benefits, permanent disability insurance payments made by the Social Security Administration prior to deductions for medical insurance, and railroad retirement insurance checks from the U.S. government.</p>
											</div>
											<div class="phargagh-section">
												<h4>Supplemental Security Income</h4>
												<p>Income from U.S. assistance program administered by the Social Security Administration that guarantees a minimum level of income for needy aged, blind, or disabled individuals.</p>
											</div>													
											<div class="phargagh-section">
												<h4>Wage and Salary</h4>
												<p>Total money earnings received for work performed as an employee during the calendar year 1999.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#earnings" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Armed Forces</h4>
												<p>People on active duty with the United States Army, Air Force, Navy, Marine Corps, or Coast Guard.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Civilian Employment</h4>
												<p>Wage and salary employees and self-employed and domestic workers. Excludes uniformed military.</p>
											</div>
											<div class="phargagh-section">
												<h4>Civilian Population</h4>
												<p>Resident population, excluding active-duty military.</p>
											</div>
											<div class="phargagh-section">
												<h4>Employment</h4>
												<p>All jobs in the geographic area, including wage and salary employees, self-employed and domestic workers, and uniformed military.</p>
											</div>
											<div class="phargagh-section">
												<h4>Military Population</h4>
												<p>All uniformed military personnel.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#economy" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Educational Attainment</h4>
												<p>The highest level of education completed in terms of the highest degree or the highest level of schooling completed.</p>
											</div>		
											<div class="phargagh-section">
												<h4>Private School</h4>
												<p>Schools supported and controlled primarily by religious organizations or other private groups.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Public School</h4>
												<p>Schools supported and controlled primarily by a federal, state, or local government (including tribal schools).</p>
											</div>												
											<div class="phargagh-section">
												<h4>School Enrollment</h4>
												<p>Enrollment in public or private nursery school, kindergarten, elementary school, and schooling which leads to a high school diploma or college degree.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#education" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>American Indian</h4>
												<p>A person having origins in any of the original peoples of North and South America (including Central America) and who maintain tribal affiliation or community attachment.</p>
											</div>
											<div class="phargagh-section">
												<h4>Asian</h4>
												<p>A person having origins in any of the original peoples of the Far East, Southeast Asia, or the Indian subcontinent including, for example, Cambodia, China, India, Japan, Korea, Malaysia, Pakistan, the Philippine Islands, Thailand, and Vietnam.</p>
											</div>
											<div class="phargagh-section">
												<h4>Black or African American</h4>
												<p>A person having origins in any of the Black racial groups of Africa.</p>
											</div>
											<div class="phargagh-section">
												<h4>Ethnicity</h4>
												<p>Classification of population into four mutually exclusive ethnic groups (Hispanic, White, Black, and Asian) based on a combination of responses to two questions on the 1990 Census form concerning Hispanic origin and race.</p>
											</div>
											<div class="phargagh-section">
												<h4>Hispanic</h4>
												<p>Persons who identify themselves as Hispanic/Latino or of Spanish origin. People of Hispanic origin may be of any race.</p>
											</div>
											<div class="phargagh-section">
												<h4>Native Hawaiian & Other Pacific Islander</h4>
												<p>A person having origins in any of the original peoples of Hawaii, Guam, Samoa, or other Pacific Islands. It includes people who indicate their race as "Native Hawaiian," "Guamanian or Chamorro," "Samoan," and "Other Pacific Islander."</p>
											</div>	
											<div class="phargagh-section">
												<h4>Other</h4>
												<p>Includes all other responses not included in the "White," "Black or African American," and "Asian," race categories.</p>
											</div>
											<div class="phargagh-section">
												<h4>Population</h4>
												<p>Total number of persons (residents) in an area.</p>
											</div>
											<div class="phargagh-section">
												<h4>Race</h4>
												<p>Self-identification by people according to the race or races with which they most closely identify.</p>
											</div>
											<div class="phargagh-section">
												<h4>Some Other Race</h4>
												<p>Includes all other responses not included in the "White," "Black or African American," "American Indian or Alaska Native," "Asian," and "Native Hawaiian or Other Pacific Islander" race categories.</p>
											</div>
											<div class="phargagh-section">
												<h4>Tenure</h4>
												<p>Refers to owner-occupied vs. renter-occupied housing units.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Two or More Races</h4>
												<p>Refers to combinations of two or more of following race categories White, Black or African American, American Indian or Alaska Native, Asian, Native Hawaiian and Other Pacific Islander, Some other race.</p>
											</div>
											<div class="phargagh-section">
												<h4>White</h4>
												<p>A person having origins in any of the original peoples of Europe, the Middle East, or North Africa.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#ethnicity" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household Income</h4>
												<p>Total pre-tax annual income of all members of a household.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Income</h4>
												<p>Sum of wages, salary, commissions, bonuses, tips, self-employment income, interest, dividends, net rental income, royalty income, income from estates and trusts, Social Security or Railroad Retirement income, Supplemental Security Income (SSI), any public assistance or welfare payments from the state or local welfare office, retirement, survivor, or disability pensions, and any other sources of income received regularly.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#household-income" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Family</h4>
												<p>Householder and one or more other people living in the same household who are related to the householder by birth, marriage, or adoption.</p>
											</div>		
											<div class="phargagh-section">
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>												
											<div class="phargagh-section">
												<h4>Non-Family</h4>
												<p>Householder living alone or with nonrelatives only.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#household-type" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Units in structures with two or more housing units.</p>
											</div>
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>
											<div class="phargagh-section">
												<h4>Persons per Household</h4>
												<p>The average number of persons living in a housing unit, calculated by dividing the household population by the number of occupied housing units.</p>
											</div>
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>One unit detached structures (with open space on all sides) and one unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#households-and-families" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>
											<div class="phargagh-section">
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>
											<div class="phargagh-section">
												<h4>Mobile Homes</h4>
												<p>Mobile homes or trailers to which no permanent rooms have been added.</p>
											</div>
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Units in structures with two or more housing units.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>One unit detached structures (with open space on all sides) and one unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>	
											<div class="phargagh-section">
												<h4>Single Family - Attached</h4>
												<p>One unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>												
											<div class="phargagh-section">
												<h4>Single Family - Detached</h4>
												<p>One unit detached structures, with open space on all sides.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#households-by-housing-structure-type" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>
											<div class="phargagh-section">
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>
											<div class="phargagh-section">
												<h4>Occupants Per Room</h4>
												<p>The number of people in each occupied housing unit divided by the number of rooms in the unit.</p>
											</div>												
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>												
											<div class="phargagh-section">
												<h4>Tenure</h4>
												<p>Refers to owner-occupied vs. renter-occupied housing units.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#households-by-tenure-and-occupants-per-room" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>
											<div class="phargagh-section">
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>
											<div class="phargagh-section">
												<h4>Mobile Homes</h4>
												<p>Mobile homes or trailers to which no permanent rooms have been added.</p>
											</div>
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Units in structures with two or more housing units.</p>
											</div>
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>
											<div class="phargagh-section">
												<h4>Persons per Household</h4>
												<p>The average number of persons living in a housing unit, calculated by dividing the household population by the number of occupied housing units.</p>
											</div>
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>One unit detached structures (with open space on all sides) and one unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#housing" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Household Income</h4>
												<p>Total pre-tax annual income of all members of a household.</p>
											</div>		
											<div class="phargagh-section">
												<h4>Income</h4>
												<p>Sum of wages, salary, commissions, bonuses, tips, self-employment income, interest, dividends, net rental income, royalty income, income from estates and trusts, Social Security or Railroad Retirement income, Supplemental Security Income (SSI), any public assistance or welfare payments from the state or local welfare office, retirement, survivor, or disability pensions, and any other sources of income received regularly.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Median Household Income</h4>
												<p>Divides the household income distribution into two equal parts; half of all households have incomes above the median and half have incomes below the median.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#income-detail" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Armed Forces</h4>
												<p>People on active duty with the United States Army, Air Force, Navy, Marine Corps, or Coast Guard.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Civilian Employment</h4>
												<p>Wage and salary employees and self-employed and domestic workers. Excludes uniformed military.</p>
											</div>
											<div class="phargagh-section">
												<h4>Labor Force</h4>
												<p>All people classified in the civilian labor force, plus members of the U.S. Armed Forces.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Unemployed</h4>
												<p>Civilians 16 years old and over who are neither "at work" nor "with a job but not at work" during the reference period, and were actively looking for work during the last four weeks and were available to accept a job.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#labor-force" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Agricultural and Extractive</h4>
												<p>Includes agricultural uses such as orchards and vineyards, nurseries, greenhouses, flower fields, cropland and pasture, as well as mining, sand and gravel extraction, salt evaporation, landfills, recycling centers and auto wrecking yards.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Commercial/Services</h4>
												<p>Includes shopping centers, other retail, hotels, motels, public facilities such as churches and museums, hospitals, and commercial recreation facilities such as golf courses and marinas.</p>
											</div>
											<div class="phargagh-section">
												<h4>Constrained Acres</h4>
												<p>Vacant land not available for development for physical, public policy, or environmental reasons.</p>
											</div>
											<div class="phargagh-section">
												<h4>Developed Acres</h4>
												<p>Land with residential or non-residential activity.</p>
											</div>
											<div class="phargagh-section">
												<h4>Employment Density</h4>
												<p>Civilian employment per developed employment acre (industrial, retail, office, and schools).</p>
											</div>
											<div class="phargagh-section">
												<h4>Future Roads and Freeways</h4>
												<p>Land designated for future development into roads or freeways.</p>
											</div>
											<div class="phargagh-section">
												<h4>Industrial</h4>
												<p>Includes light industry, heavy industry, industrial parks, warehousing, public storage, airports, and other transportation facilities.</p>
											</div>
											<div class="phargagh-section">
												<h4>Low Density Single Family</h4>
												<p>Rural residential densities; lot sizes of one acre or more.</p>
											</div>
											<div class="phargagh-section">
												<h4>Mobile Homes</h4>
												<p>Mobile home parks with ten or more spaces that are primarily for residential (not recreational) use.</p>
											</div>
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Attached housing units, two or more units per structure, including duplexes, townhouses, condominiums, apartments, and residential hotels.</p>
											</div>
											<div class="phargagh-section">
												<h4>Office</h4>
												<p>High rise and low rise office buildings and large government office buildings or centers (outside of military reservations) and civic centers or city halls.</p>
											</div>
											<div class="phargagh-section">
												<h4>Other Residential</h4>
												<p>Group living facilities such as prisons, dormitories, military barracks, and convalescent or retirement homes.</p>
											</div>
											<div class="phargagh-section">
												<h4>Parks and Military Use</h4>
												<p>Includes parks, open space reserves, beaches, military facilities and reservations, and bodies of water including bays, lagoons, lakes, reservoirs and large ponds.</p>
											</div>
											<div class="phargagh-section">
												<h4>Residential Density</h4>
												<p>Total housing units per developed residential acre.</p>
											</div>
											<div class="phargagh-section">
												<h4>Roads and Freeways</h4>
												<p>Freeways, surface streets and railroad rights of way.</p>
											</div>
											<div class="phargagh-section">
												<h4>Schools</h4>
												<p>Universities, colleges, high schools, middle schools, elementary schools, school district offices, and other schools including adults schools, non-residential day care and nursery schools.</p>
											</div>
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>Detached housing units on lots smaller than one acre.</p>
											</div>
											<div class="phargagh-section">
												<h4>Vacant Developable Acres</h4>
												<p>Vacant land available for development, planned for specific land uses.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#land-use" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
											</div>
										</div>
									</div>
								</section>
								
								<section id="language-spoken" class="creative-section">
									<div class="row">						
										<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
											<hr />
											<header>
												<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
												<h2>Language Spoken at Home</h2>
											</header></div>
										</div>
										<div class="row">						
											<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">													
											<div class="phargagh-section">
												<h4>Ability to Speak English</h4>
												<p>For a respondent who speaks a language other than English at home, refers to his/her assessment of his ability to speak English, from "very well" to "not at all."</p>
											</div>	
											<div class="phargagh-section">
												<h4>Language Spoken at Home</h4>
												<p>The language currently used at home, either "English only" or a non-English language which is used in addition to English or in place of English.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#language-spoken-at-home" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Marital Status</h4>
												<p>For people 15 years old and over, married, never married, separated, divorced or widowed.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#marital-status" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Employed</h4>
												<p>Civilians 16 years old and over who are either "at work" or "with a job but not at work." Excludes people on active duty in the United States Armed Forces.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Means of Transportation to Work</h4>
												<p>The principal mode of travel usually used to get from home to work.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#means-of-transportation-to-work" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Industry</h4>
												<p>The kind of business conducted by a person’s employing organization.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Occupation</h4>
												<p>The kind of work the person does on the job.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#occupation" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Earnings</h4>
												<p>The sum of wage or salary income and net income from self-employment. The amount of income received regularly before deductions for personal income taxes, Social Security, bond purchases, union dues, Medicare deductions, etc.</p>
											</div>
											<div class="phargagh-section">
												<h4>Employed</h4>
												<p>Civilians 16 years old and over who are either "at work" or "with a job but not at work." Excludes people on active duty in the United States Armed Forces.</p>
											</div>												
											<div class="phargagh-section">
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Income</h4>
												<p>Sum of wages, salary, commissions, bonuses, tips, self-employment income, interest, dividends, net rental income, royalty income, income from estates and trusts, Social Security or Railroad Retirement income, Supplemental Security Income (SSI), any public assistance or welfare payments from the state or local welfare office, retirement, survivor, or disability pensions, and any other sources of income received regularly.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Industry</h4>
												<p>The kind of business conducted by a person’s employing organization.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Interest, Dividend, or Net Rental Income</h4>
												<p>Interest on savings or bonds, dividends from stockholdings or membership in associations, net income from rental of property to others and receipts from boarders or lodgers, net royalties, and periodic payments from an estate or trust fund.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Marital Status</h4>
												<p>For people 15 years old and over, married, never married, separated, divorced or widowed.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Occupation</h4>
												<p>The kind of work the person does on the job.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Public Assistance</h4>
												<p>General assistance and Temporary Assistance to Needy Families (TANF).</p>
											</div>	
											<div class="phargagh-section">
												<h4>self-employment</h4>
												<p>Farm (net money income from the operation of a farm by a person on his or her own account, as an owner, renter, or sharecropper) and nonfarm (net money income receipts from one’s own business, professional enterprise, or partnership) self-employment income.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Social Security</h4>
												<p>Social security pensions and survivors benefits, permanent disability insurance payments made by the Social Security Administration prior to deductions for medical insurance, and railroad retirement insurance checks from the U.S. government.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Supplemental Security Income</h4>
												<p>Income from U.S. assistance program administered by the Social Security Administration that guarantees a minimum level of income for needy aged, blind, or disabled individuals.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Wage and Salary</h4>
												<p>Total money earnings received for work performed as an employee during the calendar year 1999.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#occupation-industry-and-earnings" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Civilian Population</h4>
												<p>Resident population, excluding active-duty military.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Group Quarters Population</h4>
												<p>All persons not living in households. Includes both institutional (e.g., nursing homes, hospitals, and prisons) and noninstitutional (e.g., college dormitories, military barracks, and workers' dormitories) settings.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Hispanic</h4>
												<p>Persons who identify themselves as Hispanic/Latino or of Spanish origin. People of Hispanic origin may be of any race.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Household Income</h4>
												<p>Total pre-tax annual income of all members of a household.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Household Population</h4>
												<p>All persons living in a household (an occupied housing unit).</p>									
											</div>
											<div class="phargagh-section">
												<h4>Income</h4>
												<p>Sum of wages, salary, commissions, bonuses, tips, self-employment income, interest, dividends, net rental income, royalty income, income from estates and trusts, Social Security or Railroad Retirement income, Supplemental Security Income (SSI), any public assistance or welfare payments from the state or local welfare office, retirement, survivor, or disability pensions, and any other sources of income received regularly.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Median Age</h4>
												<p>Divides the age distribution into two equal parts; half of all persons are older than the median and half are younger.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Median Household Income</h4>
												<p>Divides the household income distribution into two equal parts; half of all households have incomes above the median and half have incomes below the median.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Military Population</h4>
												<p>All uniformed military personnel.</p>									
											</div>
											<div class="phargagh-section">
												<h4>Population</h4>
												<p>Total number of persons (residents) in an area.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#people" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
											</div>
										</div>
									</div>
								</section>
								
								<section id="persons-by-household" class="creative-section">
									<div class="row">						
										<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-md-7 col-left">
											<hr />
											<header>
												<a class="creative-link top-link isScroll" title="" href="#site-top" data-ajax="false">back to top <span class="glyphicon glyphicon-up"></span></a>
												<h2>PERSONS BY HOUSEHOLD</h2>
											</header>
										</div>
									</div>
									<div class="row">						
										<div class="col-xs-offset-1 col-xs-10 col-sm-8 col-md-6 col-left">
											<div class="phargagh-section">
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>		
											<div class="phargagh-section">
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>
											<div class="phargagh-section">
												<h4>Mobile Homes</h4>
												<p>Mobile homes or trailers to which no permanent rooms have been added.</p>
											</div>
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Units in structures with two or more housing units.</p>
											</div>												
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Persons per Household</h4>
												<p>The average number of persons living in a housing unit, calculated by dividing the household population by the number of occupied housing units.</p>
											</div>		
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>One unit detached structures (with open space on all sides) and one unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>		
											<div class="phargagh-section">
												<h4>Single Family - Attached</h4>
												<p>One unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>												
											<div class="phargagh-section">
												<h4>Single Family - Detached</h4>
												<p>One unit detached structures, with open space on all sides.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#persons-per-household" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Earnings</h4>
												<p>The sum of wage or salary income and net income from self-employment. The amount of income received regularly before deductions for personal income taxes, Social Security, bond purchases, union dues, Medicare deductions, etc.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Employed</h4>
												<p>Civilians 16 years old and over who are either "at work" or "with a job but not at work." Excludes people on active duty in the United States Armed Forces.</p>
											</div>
											<div class="phargagh-section">
												<h4>Occupation</h4>
												<p>The kind of work the person does on the job.</p>
											</div>													
											<div class="phargagh-section">
												<h4>Place of Work</h4>
												<p>Geographic location at which workers carried out their occupational activities.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#place-of-work" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Household Population</h4>
												<p>All persons living in a household (an occupied housing unit).</p>
											</div>
											<div class="phargagh-section">
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>
											<div class="phargagh-section">
												<h4>Mobile Homes</h4>
												<p>Mobile homes or trailers to which no permanent rooms have been added.</p>
											</div>
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Units in structures with two or more housing units.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>One unit detached structures (with open space on all sides) and one unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>
											<div class="phargagh-section">
												<h4>Single Family - Attached</h4>
												<p>One unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>											
											<div class="phargagh-section">
												<h4>Single Family - Detached</h4>
												<p>One unit detached structures, with open space on all sides.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#population-by-housing-structure-type" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Family</h4>
												<p>Householder and one or more other people living in the same household who are related to the householder by birth, marriage, or adoption.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Poverty</h4>
												<p>Families or individuals whose total income falls below the poverty threshold set by the Census Bureau, according to family size and composition.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#poverty-status" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Rent - Contract</h4>
												<p>The monthly rent agreed to or contracted for, regardless of any furnishings, utilities, fees, meals, or services that may be included. For vacant units, it is the monthly rent asked for the rental unit at the time of interview.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Rent - Gross</h4>
												<p>Contract rent plus the estimated average monthly cost of utilities and fuels if these are paid for by the renter.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#rent" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Employed</h4>
												<p>Civilians 16 years old and over who are either "at work" or "with a job but not at work." Excludes people on active duty in the United States Armed Forces.</p>
											</div>											
											<div class="phargagh-section">
												<h4>Means of Transportation to Work</h4>
												<p>The principal mode of travel usually used to get from home to work.</p>
											</div>
											<div class="phargagh-section">
												<h4>Travel Time to Work</h4>
												<p>The total number of minutes that it usually takes to get from home to work each day.</p>
											</div>
											<div class="phargagh-section">
												<h4>Vehicle Availability</h4>
												<p>Passenger cars, vans, and pickup or panel trucks of 1-ton capacity or less kept at home and available for use by household members. Excludes vehicles kept at home but used only for business purposes.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#transportation" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Employed</h4>
												<p>Civilians 16 years old and over who are either "at work" or "with a job but not at work." Excludes people on active duty in the United States Armed Forces.</p>
											</div>
											<div class="phargagh-section">
												<h4>Travel Time to Work</h4>
												<p>The total number of minutes that it usually takes to get from home to work each day. </p>
												<p><a class="creative-link view-link" title="" href="/variablelist#travel-time-to-work" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Housing Unit</h4>
												<p>A house, apartment, mobile home or trailer, group of rooms, or single room occupied as separate living quarters. If vacant, intended for occupancy as separate living quarters.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Mobile Homes</h4>
												<p>Mobile homes or trailers to which no permanent rooms have been added.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Multiple Family</h4>
												<p>Units in structures with two or more housing units.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Occupied Housing Units</h4>
												<p>Housing units that are occupied by a person or persons who do not have a primary place of residence elsewhere.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Single Family</h4>
												<p>One unit detached structures (with open space on all sides) and one unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>
											<div class="phargagh-section">
												<h4>Single Family - Attached</h4>
												<p>One unit attached structures (with one or more adjoining walls extending from ground to roof).</p>
											</div>	
											<div class="phargagh-section">
												<h4>Single Family - Detached</h4>
												<p>One unit detached structures, with open space on all sides.</p>
											</div>	
											<div class="phargagh-section">
												<h4>Vacant</h4>
												<p>A unit in which no one is living, unless its occupants are only temporarily absent. Units temporarily occupied entirely by people who have a usual residence elsewhere are also classified as vacant.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#units-by-type" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Housing Value</h4>
												<p>Respondent's estimate of how much the house and lot, mobile home and lot, or condominium unit would sell for if it were for sale.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#value" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Employed</h4>
												<p>Civilians 16 years old and over who are either "at work" or "with a job but not at work." Excludes people on active duty in the United States Armed Forces.</p>
											</div>
											<div class="phargagh-section">
												<h4>Household</h4>
												<p>A person or group of people living in a housing unit that serves as a primary place of residence.</p>
											</div>												
											<div class="phargagh-section">
												<h4>Vehicle Availability</h4>
												<p>Passenger cars, vans, and pickup or panel trucks of 1-ton capacity or less kept at home and available for use by household members. Excludes vehicles kept at home but used only for business purposes.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#vehicle-availability" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
												<h4>Year Structure Built</h4>
												<p>Year that building was first constructed.</p>
												<p><a class="creative-link view-link" title="" href="/variablelist#year-built" data-ajax="false">VIEW FULL VARIABLE LIST</a></p>
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
		<!-- .st-pusher -->
	</div>
	<!-- .st-content -->
	
    </div>
	<!-- .st-container -->
	
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
<!-- InstanceEnd -->
</html>
