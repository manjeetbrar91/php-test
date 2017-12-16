<?php include('header.php'); ?>

    <div id="body">
    	<div class="container">
        	<div class="content-pad-4x">
                <div class="row">
                    <div id="content" class="col-md-12" role="main">
                        <article class="single-page-content">
							<div class="ia_row">
								<div class="vc_row wpb_row vc_row-fluid">
									<div class="wpb_column vc_column_container vc_col-sm-12">
										<div class="vc_column-inner ">
											<div class="wpb_wrapper"> 
												<div class="ia-heading ia-heading-heading_725 heading-align-center " data-delay="0">
													<h2 class="h1">	Add Offers </h2>
													<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>
								</div>    			
							</div>
							
							<div class="container wineforms-container">
								<h2 style="font-size: 30px;text-align: left" class="vc_custom_heading">Please fill this form</h2>
								<div class="form_wrapper">
									<form action="" method="POST" id="add-offer-form" >
										<div class="form_body">
											<div class="form-group">
												<label>Incentive<span class="field_required">*</span></label>
												<input id="txtIncentive" name="txtIncentive" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Amount<span class="field_required">*</span></label>
												<input id="txtAmount" name="txtAmount" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Member<span class="field_required">*</span></label>
												<input id="txtMember" name="txtMember" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Start Date<span class="field_required">*</span></label>
												<input id="txtStartDate" name="txtStartDate" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>End Date<span class="field_required">*</span></label>
												<input id="txtEndDate" name="txtEndDate" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Status<span class="field_required">*</span></label>
												<select id="ddlStatus" name="ddlStatus">
													<option value="">--None--</option>
													<option value="Active">Active</option>
													<option value="Inactive">Inactive</option>
												</select>
											</div>
											
											<div class="form-group" style="width:100%;float:left;">
											<input class="btn btn-primary " type="submit" value="Submit" name="submit">
											</div>
										</div>
									</form>
								</div>	
							</div>

						</article>
                    </div><!--/content-->
                </div><!--/row-->
            </div><!--/content-pad-4x-->
        </div><!--/container-->
    </div><!--/body-->
