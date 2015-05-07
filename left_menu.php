<div class="nav-filter clearfix">
	<div class="left-nav">  
		<button class="close"></button>
		<div class="row">
			<div class="col-xs-offset-2 col-xs-8 col-sm-offset-0 col-sm-12">	
		
				<!-- .selection-section -->   
				<div class="selection-section">
					<h5>make your selection:</h5>
					<div class="error">
						<span id="select_error" class="hide">* Make a selection for all fields</span>
					</div>
					<form id="selectionForm" name="selectionForm" action="/dataoverview" class="on-desktop" method="Post">
						<fieldset> 
							<div class="select-site">
								<select name="slt_source_type" id="slt_source_type" class="selectpicker">
									<option value="" style="display: none;">SOURCE TYPE</option>
									<option value="census">CENSUS</option>
									<option value="estimate">ESTIMATE</option>
									<option value="forecast">FORECAST</option>
								</select>
							</div>
							<div class="select-site" id="select_year">
								<select name="slt_year" id="slt_year" class="selectpicker" disabled>
									<option value="" style="display: none;">YEAR/SERIES</option>                               
								</select>
							</div>
							<div class="select-site" id="select_geography_type">
								<select name="slt_geography_type" id="slt_geography_type" class="selectpicker" disabled>
									<option value="" style="display: none;">GEOGRAPHY TYPE</option>                                
								</select>
							</div>
							<div class="select-site" id="select_location">
								<select name="slt_location" id="slt_location" class="selectpicker" disabled>
									<option value="" style="display: none;">LOCATION</option>
								</select>
							</div>
							<input id="btnViewData" class="btn btn-orange" type="submit" value="view interactive data" />
						</fieldset>
					</form>
					<form id="selectionDeviceForm" name="selectionDeviceForm" action="/dataoverview" method="Post" class="on-device"  data-ajax="false">
						<fieldset>                    
							<div class="select-site">
								<select class="select-input" id="txtType" name="txtType">
									<option value="">SOURCE TYPE</option>
									<option value="census">CENSUS</option>
									<option value="estimate">ESTIMATE</option>
									<option value="forecast">FORECAST</option>
								</select>
							</div>
							<div class="select-site">
								<select disabled="disabled" class="select-input" id="txtYear" name="txtYear">
									<option value="">YEAR/SERIES</option>
								</select>
							</div>
							<div class="select-site">
								<select disabled="disabled" class="select-input" id="txtGeography" name="txtGeography">
									<option value="">GEOGRAPHY TYPE</option> 
								</select>
							</div>
							<div class="select-site">
								<select disabled="disabled" class="select-input" id="txtLocation" name="txtLocation">
									<option value="">LOCATION</option>
								</select>
							</div>
							<input type="hidden" name="evr" value="device" />
							<div class="btn-view-data">
								<input id="btnViewDataDevice" class="btn btn-orange" type="submit" value="view interactive data" />  
							</div>
						</fieldset>
					</form>
				</div>
				<!-- .selection-section -->
				
				<!-- .report-section -->
				<div class="report-section">
					<h5>export full report:</h5>
					<form id="reportForm" class="on-desktop">
						<fieldset>  
							<div class="select-site">
								<select class="selectpicker" name="email_report" id="email_report" disabled>
									<option value="" style="display: none;">EMAIL REPORT AS</option>
                                    <?php if(isset($urlDownloadPdf)&&isset($urlDownloadElx)){?>
                                    <option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
									<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
                                    <?php }?>
								</select>
							</div>
							<div class="select-site">
								<select class="selectpicker" id="download_report" name="download_report" disabled>
									<option value="" style="display: none;">DOWNLOAD REPORT AS</option>
                                    <?php if(isset($urlDownloadPdf)&&isset($urlDownloadElx)){?>
                                    <option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
									<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
                                    <?php }?>
								</select>
							</div>
						</fieldset>
					</form>
					<form id="reportDeviceForm" class="on-device">
						<fieldset>                    
							<div class="select-site">
								<select disabled="disabled" class="select-input" id="txtEmailReport">
									<option value="">EMAIL REPORT AS</option>
                                    <?php if(isset($urlDownloadPdf)&&isset($urlDownloadElx)){?>
                                    <option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
									<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
                                    <?php }?>
								</select>
							</div>
							<div class="select-site">
								<select disabled="disabled" class="select-input" id="txtDownloadReport">
									<option value="">DOWNLOAD REPORT AS</option>
                                    <?php if(isset($urlDownloadPdf)&&isset($urlDownloadElx)){?>
                                    <option value="<?php echo $urlDownloadPdf; ?>">pdf document (pdf)</option>
									<option value="<?php echo $urlDownloadElx; ?>">microsoft excel (xls)</option>
                                    <?php }?>
								</select>
							</div>
						</fieldset>
					</form>
				</div>
				<!-- /.report-section -->
				
				<!-- .reset-section -->
				<div class="reset-section">
					<input class="btn btn-white" id="reset_form" type="button" value="reset selections" />
				</div>  
				<!-- /.reset-section -->
				<input type="hidden" id="api_url" value="<?php echo API_URL;?>"/>	
			</div>
		</div>
	</div>
</div>

