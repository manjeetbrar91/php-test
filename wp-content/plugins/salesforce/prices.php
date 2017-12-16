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
													<h2 class="h1">	Add Price </h2>
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
									<form action="" method="POST" id="add-price-form" >
										<div class="form_body">
											<div class="form-group">
												<label>Price Type<span class="field_required">*</span></label>
												<select id="price_type" name="price_type">
													<option value="Producer">Dollar</option>
													<option value="Seller">Euro</option>
													
												</select>
											</div>
											<div class="form-group">
												<label>Wine<span class="field_required">*</span></label>
												<input id="price_wine" name="price_wine" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Location<span class="field_required">*</span></label>
												<input id="price_location" name="price_location" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Start<span class="field_required">*</span></label>
												<input id="price_start" name="price_start" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>End<span class="field_required">*</span></label>
												<input id="price_end" name="price_end" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Scheduled<span class="field_required">*</span></label>
												<input id="price_scheduled" name="price_scheduled" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Price<span class="field_required">*</span></label>
												<input id="price_value" name="price_value" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Currency<span class="field_required">*</span></label>
												<input id="price_currency" name="price_currency" type="text" value="" class="form-control"  >
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
