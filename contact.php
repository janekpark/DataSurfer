<?php
require_once("lib/config.php");
require_once("lib/function.php");
$page_title = "SANDAG Data Surfer | Contact Us";
$isSend = false;
if (isset($_POST) && count($_POST) > 0) {
    $subject = isset($_POST['txtSubject']) ? $_POST['txtSubject'] : '';
    $name = isset($_POST['txtName']) ? $_POST['txtName'] : null;
    $email = isset($_POST['txtEmail']) ? $_POST['txtEmail'] : '';
    $message = isset($_POST['txtMessage']) ? wordwrap($_POST['txtMessage'], 70) : null;
    if(sendMail($email, $name, EMAIL_CONTACT, $subject, $message )) {
        $isSend = true;
        echo json_encode(array('result'=>1));
    }else{
        echo json_encode(array('result'=>0));
    }
    exit();
}
?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/master.dwt" codeOutsideHTMLIsLocked="false" -->
<?php
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
					<div class="banner-section banner-contact"> 
						<div class="site-container">
							<div class="banner-img banner-img-contact">
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
												<h2>contact us</h2>
											</div>
										</div>
									</div>
								</header>                                       
							</div>
						</div>
					</div>
					
					<!-- .site-content -->
					<section class="site-content site-contact">
						<div class="site-container">
						
							<!-- .banner-section -->
                            <div class="banner-section how-section"> 	
								<div class="banner-content contact-content clearfix">	
									<div class="row">
										<div class="col-xs-offset-1 col-xs-10 col-sm-9 col-left intro-section">
											<p>Feel free to contact us! Send us an email by completing the form below.</p>
										</div>
									</div> 
									<form id="contactForm" method="post" action="#">
										<fieldset class="contact-fieldset">
											<div class="row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">your name:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<input onblur="if (this.placeholder=='') this.placeholder = 'Type your name here'" onfocus="if (this.placeholder=='Type your name here') this.placeholder = ''" name="txtName" id="txtName" type="text" placeholder="Type your name here" required class="form-control" />
												</div>
											</div>
											<div class="row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">your email:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<input onblur="if (this.placeholder=='') this.placeholder = 'Type your email address here'" onfocus="if (this.placeholder=='Type your email address here') this.placeholder = ''" name="txtEmail" id="txtEmail" type="text" placeholder="Type your email address here" required class="form-control" />
												</div>
											</div>
											<div class="row contact-row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">email subject:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<input onblur="if (this.placeholder=='') this.placeholder = 'Type your subject here (optional)'" onfocus="if (this.placeholder=='Type your subject here (optional)') this.placeholder = ''" name="txtSubject" id="txtSubject" type="text" placeholder="Type your subject here (optional)" class="form-control" />
												</div>
											</div>
											<div class="row">
												<div class="col-xs-offset-1 col-xs-10 col-sm-2">
													<label class="contact-label">email message:</label>
												</div>
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-0 col-sm-5">
													<textarea onblur="if (this.placeholder=='') this.placeholder = 'Type your message here (400 character max)'" onfocus="if (this.placeholder=='Type your message here (400 character max)') this.placeholder = ''" name="txtMessage" id="txtMessage" cols="" rows="" maxlength="400" placeholder="Type your message here (400 character max)" required class="form-control"></textarea>
												</div>
											</div>
											<div class="row">
												<div id="button_change" class="col-xs-offset-1 col-xs-8 col-sm-offset-3 col-sm-4 col-md-3">
													<?php if (!$isSend) { ?>
													<input type="submit" class="btn btn-green" id="btn_send" value="send email" />
													<?php } else {?>
													<a title="" href="/" class="btn btn-green btn-link" data-ajax="false"><span class="glyphicon glyphicon-pre"></span> data surfer home</a>
													<?php } ?>
												</div>
											</div>
											<div id="completed" class="row <?php echo ($isSend)? '':'hide' ?>">
												<div class="col-xs-offset-1 col-xs-10 col-sm-offset-3 col-sm-5 intro-section">
													<h5>Thank you!</h5>
													<p>Your message has been sent! We will get back to you as soon as possible. Thank you for using Data Surfer. </p>
												</div>
											</div>
										</fieldset>
									</form>
								   
								</div>   
							</div>
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
</body>
<!-- InstanceEnd --></html>
