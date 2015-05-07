<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/master.dwt" codeOutsideHTMLIsLocked="false" -->
<?php
$page_title="SANDAG Data Surfer | Your go-to data warehouse for the San Diego region";
require_once("lib/config.php");
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
				
					<!-- .site-header -->
					<?php
					include("header_menu.php");
					?> 
					<!-- /.site-header -->
					
					<!-- InstanceBeginEditable name="content" -->
					<!-- .site-content -->
					<section class="site-content home-content">
						<div class="site-container">
						
							<!-- /.home-section -->
							<section class="home-section clearfix">
								<div class="row">
									<div class="col-xs-12 col-md-4 col-right">
										<div class="row">
											<ul id="photos-list" class="photos-list"></ul>
										</div>
									</div>
									<div class="col-xs-12 col-md-8 col-left">
										<div class="row">
											<div class="col-xs-offset-0 col-xs-12 col-sm-offset-2 col-sm-8 content-info">
												<h1><strong>data</strong> surfer</h1>
												<p>Your go-to data warehouse for the San Diego region. Get accurate census, estimate and forecast information and reports based on your specific needs<span class="mdash">&mdash;</span>all in just a few clicks.</p>
											</div>
										</div>
										<ul class="row">
											<li class="col-xs-12 col-sm-6 st-trigger-effects">
												<a data-effect="st-effect-4" id="btn-ready" title="" href="javascript:void(0);" class="btn btn-blue" data-ajax="false">get started now <span class="glyphicon glyphicon-next"></span></a>
											</li>
											<li class="col-xs-12 col-sm-6">
												<a title="" href="/howto" target="_self" class="btn btn-green btn-link" data-ajax="false">how to use data surfer <span class="glyphicon glyphicon-next"></span></a>
											</li>
										</ul>										
									</div>
								</div>
							</section>
							<!-- /.home-section -->
						
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
			<!-- .st-content -->
	
		</div>
        <!-- /.st-pusher -->
        
    </div>  
    <!-- /.st-container -->
	
    <noscript>
        <!-- must remain last thing before the form tag -->
        <p class="noJS">YOU NEED JAVASCRIPT TO RUN THIS SITE. PLEASE ENABLE JAVASCRIPT IN YOUR INTERNET OPTIONS.</p>
    </noscript>
    <input type="hidden" id="api_url" value="<?php echo API_URL;?>"/>
    <!-- END HTML --> 
</body>
<!-- InstanceEnd -->
</html>
