<?php include('header.php'); ?>
<?php
if(isset($_POST['submit']))
	{

		$wine_name=$_POST['wine_name'];
		$wine_upc=$_POST['wine_upc'];
		$wine_vintage=$_POST['wine_vintage'];
		$wine_varietal=$_POST['wine_varietal'];
		$wine_description=$_POST['wine_description'];
		$wine_currency=$_POST['wine_currency'];
		if(trim($wine_name)!=null && trim($wine_upc)!=null && trim($wine_vintage)!=null && trim($wine_varietal)!=null && trim($wine_description)!=null && trim($wine_currency)!=null)
		{
			try 
			{
				$mySforceConnection = new SforceEnterpriseClient();
				$mySoapClient = $mySforceConnection->createConnection($path.'/enterprise.wsdl.xml');
				$mylogin = $mySforceConnection->login($USERNAME, $PASSWORD);

				
				$account_id= $_SESSION['salesforce_account_id'];
				$contact_id= $_SESSION['salesforce_contact_id'];

				if($account_id!='')
				{	
					//Add Wine
					$addData = new stdClass(); 
					$addData->Name = $wine_name; 
					$addData->Producer__c = $account_id; 
					$addData->Upc_a__c = $wine_upc; 
					$addData->Vintage__c = $wine_vintage; 
					$addData->Varietal__c = $wine_varietal; 
					$addData->Description__c = $wine_description; 
					$addData->CurrencyIsoCode = $wine_currency; 

					$response = $mySforceConnection->create(array($addData),'wine__c');
					// var_dump($response);
					// die;
					$msg="Thanks for Adding Wine.";
				}
				else
				{
					echo '<script>location.href="'.home_url().'/dashboard/";</script>';
				}

			} 
			catch (Exception $e) 
			{
			  echo $e->faultstring;
			}
		}
		else
		{
			$msg="Please enter all required fields";
		}
	}

?>


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
													<h2 class="h1">	Add Wine </h2>
													<div class="clearfix"></div>
												</div>
											</div>
										</div>
									</div>
								</div>    			
							</div>
							
							<div class="container wineforms-container">
								<?php
									if(trim($msg)!=null) 
									{
										?>
											<div class="alert alert-success-theme"><?php echo $msg; ?></div>
										<?php
									}
								?>
								<h2 style="font-size: 30px;text-align: left" class="vc_custom_heading">Please fill this form</h2>
								<div class="form_wrapper">
									<form action="" method="POST" id="add-wine-form" >
										<div class="form_body">
											<div class="form-group">
												<label>Wine Name<span class="field_required">*</span></label>
												<input id="wine_name" name="wine_name" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>UPC-A<span class="field_required">*</span></label>
												<input id="wine_upc" name="wine_upc" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Vintage</label>
												<select name="wine_vintage" id="wine_vintage">
													<?php
													foreach(range((int)date("Y")+1,1900) as $year) {
														echo "<option value='".$year."'>".$year."</option>";
													}

													?>
												</select>
											</div>
											<div class="form-group">
												<label>Varietal<span class="field_required">*</span></label>
												<select id="wine_varietal" name="wine_varietal" tabindex="5">
												  <option value="Aglianico">Aglianico</option>
												  <option value="Albariño">Albariño</option>
												  <option value="Assyrtiko">Assyrtiko</option>
												  <option value="Barbera">Barbera</option>
												  <option value="Cabernet Franc">Cabernet Franc</option>
												  <option value="Cabernet Sauvignon">Cabernet Sauvignon</option>
												  <option value="Carignan">Carignan</option>
												  <option value="Chardonnay">Chardonnay</option>
												  <option value="Chenin Blanc">Chenin Blanc</option>
												  <option value="Cinsault">Cinsault</option>
												  <option value="Colombard">Colombard</option>
												  <option value="Corvina">Corvina</option>
												  <option value="Counoise">Counoise</option>
												  <option value="Dolcetto">Dolcetto</option>
												  <option value="Encruzado">Encruzado</option>
												  <option value="Falanghina">Falanghina</option>
												  <option value="Fiano">Fiano</option>
												  <option value="Friulano">Friulano</option>
												  <option value="Furmint">Furmint</option>
												  <option value="Gamay">Gamay</option>
												  <option value="Garganega">Garganega</option>
												  <option value="Gewurztraminer">Gewurztraminer</option>
												  <option value="Grenache">Grenache</option>
												  <option value="Grenache Blanc">Grenache Blanc</option>
												  <option value="Grillo">Grillo</option>
												  <option value="Grüner Veltliner">Grüner Veltliner</option>
												  <option value="Lagrein">Lagrein</option>
												  <option value="Loureiro">Loureiro</option>
												  <option value="Malbec">Malbec</option>
												  <option value="Malvasia">Malvasia</option>
												  <option value="Marsanne">Marsanne</option>
												  <option value="Mencía">Mencía</option>
												  <option value="Merlot">Merlot</option>
												  <option value="Monastrell">Monastrell</option>
												  <option value="Montepulciano">Montepulciano</option>
												  <option value="Moscato">Moscato</option>
												  <option value="Müller Thurgau">Müller Thurgau</option>
												  <option value="Nebbiolo">Nebbiolo</option>
												  <option value="Negroamaro">Negroamaro</option>
												  <option value="Nerello Mascalese">Nerello Mascalese</option>
												  <option value="Nero d’Avola">Nero d’Avola</option>
												  <option value="Pinot Blanc">Pinot Blanc</option>
												  <option value="Pinot Grigio">Pinot Grigio</option>
												  <option value="Pinot Noir">Pinot Noir</option>
												  <option value="Primitivo">Primitivo</option>
												  <option value="Riesling">Riesling</option>
												  <option value="Roussanne">Roussanne</option>
												  <option value="Sagrantino">Sagrantino</option>
												  <option value="Sangiovese">Sangiovese</option>
												  <option value="Sauvignon Blanc">Sauvignon Blanc</option>
												  <option value="Schiava">Schiava</option>
												  <option value="Sémillon">Sémillon</option>
												  <option value="Silvaner">Silvaner</option>
												  <option value="St. Laurent">St. Laurent</option>
												  <option value="Syrah">Syrah</option>
												  <option value="Tempranillo">Tempranillo</option>
												  <option value="Torrontés">Torrontés</option>
												  <option value="Touriga Franca">Touriga Franca</option>
												  <option value="Touriga Nacional">Touriga Nacional</option>
												  <option value="Trebbiano">Trebbiano</option>
												  <option value="Verdicchio">Verdicchio</option>
												  <option value="Vermentino">Vermentino</option>
												  <option value="Vinho Verde">Vinho Verde</option>
												  <option value="Viognier">Viognier</option>
												  <option value="Zinfandel">Zinfandel</option>
											   </select>
											</div>
											<div class="form-group">
												<label>Description<span class="field_required">*</span></label>
												<input id="wine_description" name="wine_description" type="text" value="" class="form-control"  >
											</div>
											<div class="form-group">
												<label>Currency<span class="field_required">*</span></label>
												<select id="wine_currency" name="wine_currency" tabindex="7">
												  <option value="CAD">CAD - Canadian Dollar</option>
												  <option value="USD">USD - U.S. Dollar</option>
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
