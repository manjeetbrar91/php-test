<?php 
/*
Template Name: Search Template
*/
$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
global $global_page_layout;
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
get_header();

$ticket_array=array();
$post_array=array();
$total_data=array();
$article_data = array();
$article_array = array();

function zendesk ($url,$method,$data) 
{
	
	// $apiKey = "e2784ddd417026dac58e28b7c3a1c35f94f8979fd0c575a44cbfd6c236b1e4b4";
	// $baseUrl = "https://agehelp.zendesk.com";
	
	$apiKey = "445302580d23ffc9c4f4d8c905b632f28cd94574163e71ecf4a4675a0322ca5a";
	$baseUrl = "https://wine-oh.zendesk.com";

	$headers = array(
		'Authorization: Bearer ' . $apiKey,
		'Content-Type: application/json'      
	);

	$curl_url = $baseUrl. $url;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $curl_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
   
	if ($method == "POST") 
	{      
		curl_setopt($curl, CURLOPT_POST, 1);
	}      
   
	if ($method == "GET") 
	{      
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
	}
   
	if (!empty($data)) 
	{               
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	}
	
	if (($output = curl_exec($curl)) === FALSE) 
	{
		die("Curl Failed: " . curl_error($curl));
	}
	
	curl_close($curl);
	return json_decode($output);
}


function get_posts_data($str) 
	{
		$ch = curl_init();
		$timeout = 200;
		// $url='http://wine-oh.io/wp-json/wp/v2/posts?search='.$str;
		$url='http://localhost/wineoh/wp-json/wp/v2/posts?search='.$str;
		

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$data_array = json_decode($data, TRUE);
		return $data_array;
	}
$no_result= '<div class="single-content-none content-pad">
				<div class="row">
					<div class="col-md-4 col-md-offset-4" role="main">
						<a id="ia-icon-box-999" class="media ia-icon-box search-toggle" href="#" title="Search">
							<div class="text-center">
								<div class="ia-icon">
									<i class="fa fa-search"></i>
								</div>
							</div>
							<div class="media-body text-center">
								<h4 class="media-heading">No results found</h4>
								<p>Click here to try another search</p>
							</div>
							<div class="clearfix"></div>
						</a>
					</div>
				</div><!--/row-->
			</div>';
?>
<style>
.sharedaddy.sd-sharing-enabled{
	display:none;
}
</style>
<?php $page_title = __('Search Result: ','leafcolor').(isset($_POST['search_post'])?$_POST['search_post']:''); ?>
	<?php get_template_part( 'templates/header/header', 'heading' ); ?>    
    <div id="body">
    	<?php if($layout!='true-full'){ ?>
    	<div class="container">
        <?php }?>
        	<?php if($content_padding!='off'){ ?>
        	<div class="content-pad-4x">
            <?php }?>
                <div class="row">
                    <div id="content" class="<?php if($layout != 'full' && $layout != 'true-full'){ ?> col-md-9 <?php }else{?> col-md-12 <?php } if($layout == 'left'){ ?> revert-layout <?php }?>" role="main">
                       
						<div class="blog-listing">
						
							<?php
							if(isset($_POST['search_post'])!='')
							{
								
								if($_POST['search_post']==null)
								{
									echo $no_result;
								}
								
								/* Start Fetch Posts */
								$post= get_posts_data($_POST['search_post']);
								if(!empty($post) && $_POST['search_post']!=null)
								{
									foreach($post as $posts)
									{
										$post_ids[] = $posts['id'];
									}
								 
									$args = array(
									   'post_type' => 'post',
									   'post__in'      => $post_ids
									);
									// The Query
									$the_query = new WP_Query( $args );
									while ($the_query -> have_posts()) : $the_query -> the_post();

									$post_array[]=array('id'=>get_the_id(), 'title'=>get_the_title(),'description'=>get_the_excerpt(),'href'=>get_the_permalink(),'created_at'=>get_the_date('Y-m-d'),'updated_at'=>get_the_date('Y-m-d'),'tags'=>'','status'=>get_post_status(),'type'=>get_post_type(),'category_name'=>get_the_category()[0]->cat_name,'category_link'=>get_category_link(get_the_category()[0]->cat_ID),'date'=>get_the_date('d'),'month'=>get_the_date('M'),'year'=>get_the_date('Y'),'image'=>get_the_post_thumbnail() );

									endwhile;
									wp_reset_postdata();
									
								}
								/* End Fetch Posts */
								
								/* Start Fetch articles */
								
								$article_result = zendesk ("/api/v2/help_center/articles/search.json?query=".$_POST['search_post'], "GET",$article_data);

								if(isset($article_result->error))
								{
									if(isset($article_result->error->title) && isset($article_result->error->message))
									{
										echo $article_result->error->title ."<br/>";
										echo $article_result->error->message;
									}
									else
									{
										echo $article_result->error ."<br/>";
										echo $article_result->description;
									}
									
								}
								else
								{
									$article_id=$article_result->results[0]->id;      
									$article_name=$article_result->results[0]->name; 
									$article_title=$article_result->results[0]->title;      
									$article_body=$article_result->results[0]->body;        
									$article_type=$article_result->results[0]->result_type;        
									$article_created_at=$article_result->results[0]->created_at;   
									$article_updated_at=$article_result->results[0]->updated_at;   
									$article_create_date = strstr($article_created_at, 'T', true); 
									$article_update_date = strstr($article_updated_at, 'T', true); 
									// echo '<pre>';
									// print_r($article_result);
									// echo '</pre>';
									$article_array[]= array('id'=>$article_id, 'title'=>$article_title,'description'=>substr($article_body, 0, 150) . '[...]','href'=>'','created_at'=>$article_create_date,'updated_at'=>$article_update_date,'tags'=>'','status'=>'','type'=>$article_type,'category_name'=>'','category_link'=>'','date'=>date("d", strtotime( $article_created_at)),'month'=>date("M", strtotime( $article_created_at)),'year'=>date("Y", strtotime( $article_created_at)),'image'=>'' );
								}

								/* End Fetch articles */							
								
								$user_email= $_SESSION['email_id'];
								if($user_email!=null)
								{

									/* Start Fetch Tickets */
									$activate_result = zendesk ("/api/v2/search.json?query=requester:".$user_email."&sort_by=created_at&sort_order=desc","GET",array());
									if(isset($activate_result->error))
									{
										if(isset($activate_result->error->title) && isset($activate_result->error->message))
										{
											echo $activate_result->error->title ."<br/>";
											echo $activate_result->error->message;
										}
										else
										{
											echo $activate_result->error ."<br/>";
											echo $activate_result->description;
										}
										
									}
									else
									{
										if($activate_result->results!=null)
										{
											foreach($activate_result->results as $tickets)
											{
												$ticket=strtolower($tickets->subject);
												$string=$_POST['search_post'];
												if($string!=null)
												{
													if (strpos($ticket, $string) !== false) {
													
													$ticket_create_date = strstr($tickets->created_at, 'T', true); 
													$ticket_update_date = strstr($tickets->updated_at, 'T', true); 
													$ticket_description= $tickets->description;
													$ticket_array[]= array('id'=>$tickets->id, 'title'=>$tickets->subject,'description'=>substr($ticket_description, 0, 150) . '[...]','href'=>$tickets->url,'created_at'=>$ticket_create_date,'updated_at'=>$ticket_update_date,'tags'=>$tickets->tags,'status'=>$tickets->status,'type'=>'ticket','category_name'=>'','category_link'=>'','date'=>date("d", strtotime( $ticket_create_date)),'month'=>date("M", strtotime( $ticket_create_date)),'year'=>date("Y", strtotime( $ticket_create_date)),'image'=>'' );
													}
												}
											}
										}
										
									}
									/* End Fetch Tickets */
									
									$total_data= array_merge($post_array,$ticket_array,$article_array);
										
									if(!empty($total_data))
									{
										foreach ($total_data as $key => $part) 
										{
											$sort[$key] = strtotime($part['updated_at']);
										}
										array_multisort($sort, SORT_DESC, $total_data);

										foreach($total_data as $total_datas)
										{
											
										?>
										<div class="blog-item no-thumbnail post type-post status-publish format-standard hentry category-blog">
											<div class="post-item blog-post-item row">
												<div class="col-md-6 col-sm-12">
													<div class="content-pad">
														<div class="blog-thumbnail">
															<div class="item-thumbnail">
																<?php if($total_datas['type']=='ticket')
																{ ?>
																
																	<a href='<?php echo home_url() ?>/tickets?id=<?php echo $total_datas['id'] ?>'>
																	<img src="<?php bloginfo('template_url'); ?>/images/zendesk_ticket.png" width="500" height="500" >
																
																<?php 
																} 
																elseif($total_datas['type']=='article') 
																{
																	?>
																	<a href='<?php echo home_url() ?>/article?id=<?php echo $total_datas['id'] ?>'>
																	<img src="<?php bloginfo('template_url'); ?>/images/zendesk_ticket.png" width="500" height="500" >
																	<?php
																}
																else 
																{ ?>
																
																	<a href='<?php echo $total_datas['href'] ?>'>
																	<?php 
																	if($total_datas['image']==null)
																	{
																		?>
																		<img src="<?php bloginfo('template_url'); ?>/images/default-photo.png" width="500" height="400" >
																		<?php
																	}
																	else
																	{
																		echo $total_datas['image'];
																		} ?>
																
																<?php
																} ?>
																																
																	<div class="thumbnail-hoverlay main-color-1-bg"></div>
																	<div class="thumbnail-hoverlay-icon"><i class="fa fa-search"></i></div>
																	<div class="thumbnail-overflow-2">
																		<div class="date-block-2 dark-div">
																			<div class="day"><?php echo $total_datas['date'] ?></div>
																				<div class="month-year">
																					<?php echo $total_datas['month'] ?><br>
																					<?php echo $total_datas['year'] ?>               
																				</div>
																		</div>
																	</div>
																</a>
															</div>
														</div><!--/blog-thumbnail-->
													</div>
												</div>
												<div class="col-md-6 col-sm-12">
													<div class="content-pad">
														<div class="item-content">
															<?php if($total_datas['type']=='ticket')
																{ ?>
																
																	<h3 class="item-title"><a href='<?php echo home_url() ?>/tickets?id=<?php echo $total_datas['id'] ?>'><?php echo $total_datas['title'] ?></a></h3>
																	<div class="item-excerpt blog-item-excerpt"><p><?php echo $total_datas['description'] ?></p>
																	</div>
																	
																	<a class="btn btn-primary" href="<?php echo home_url() ?>/tickets?id=<?php echo $total_datas['id'] ?>" >DETAIL <i class="fa fa-angle-right"></i></a>
																
																<?php 
																} 
																elseif($total_datas['type']=='article') 
																{
																	?>
																	<h3 class="item-title"><a href='<?php echo home_url() ?>/article?id=<?php echo $total_datas['id'] ?>'><?php echo $total_datas['title'] ?></a></h3>
																	<div class="item-excerpt blog-item-excerpt"><p><?php echo $total_datas['description'] ?></p>
																	</div>
																	
																	<a class="btn btn-primary" href="<?php echo home_url() ?>/article?id=<?php echo $total_datas['id'] ?>" >DETAIL <i class="fa fa-angle-right"></i></a>
																	<?php
																}
																else 
																{ ?>
																
																	<h3 class="item-title"><a href='<?php echo $total_datas['href'] ?>'><?php echo $total_datas['title'] ?></a></h3>
																	<div class="item-excerpt blog-item-excerpt"><p><?php echo $total_datas['description'] ?></p>
																	</div>
																	<div class="item-meta blog-item-meta">
																		<span><i class="fa fa-bookmark"></i> <a href="<?php echo $total_datas['category_link'] ?>" rel="category tag"><?php echo $total_datas['category_name'] ?></a></span>
																	</div>
																	<a class="btn btn-primary" href='<?php echo $total_datas['href'] ?>'>DETAIL <i class="fa fa-angle-right"></i></a>
																
																<?php
																} ?>
															
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<?php
										}
									}
									else
									{
										echo $no_result;
									}

									
								}
								else
								{
									$str=$_POST['search_post'];	
									$post= get_posts_data($str);
									if($post!=null && $_POST['search_post']!='')
									{
										foreach($post as $posts)
										{
											$post_ids[] = $posts['id'];
										}
									 
										$args = array(
										   'post_type' => 'post',
										   'post__in'      => $post_ids
										);
										// The Query
										$the_query = new WP_Query( $args );
										while ($the_query -> have_posts()) : $the_query -> the_post();
										?>
										<div class="blog-item no-thumbnail post-<?php the_ID(); ?> post type-post status-publish format-standard hentry category-blog">
										<div class="post-item blog-post-item row">
											<div class="col-md-6 col-sm-12">
												<div class="content-pad">
													<div class="blog-thumbnail">
														<div class="item-thumbnail">
															<a href="<?php the_permalink() ?>" title="<?php the_title() ?>">
																<?php
																	if ( has_post_thumbnail() ) { 
																		the_post_thumbnail();
																	} else { 
																	?>
																	<img src="<?php bloginfo('template_url'); ?>/images/default-photo.png" width="500" height="500" title="<?php the_title() ?>" alt="<?php the_title() ?>">
																	<?php
																	}
																	?>
																<div class="thumbnail-hoverlay main-color-1-bg"></div>
																<div class="thumbnail-hoverlay-icon"><i class="fa fa-search"></i></div>
																<div class="thumbnail-overflow-2">
																	<div class="date-block-2 dark-div">
																		<div class="day"><?php echo get_the_date('d'); ?></div>
																		<div class="month-year">
																			<?php echo get_the_date('M'); ?><br>
																			<?php echo get_the_date('Y'); ?></div>
																	</div>
																</div>
															</a>
														</div>
													</div>
												</div>
											</div>
											<div class="col-md-6 col-sm-12">
												<div class="content-pad">
													<div class="item-content">
														<h3 class="item-title"><a href="<?php the_permalink() ?>" title="<?php the_title() ?>" class="main-color-1-hover"><?php the_title() ?></a></h3>
														<div class="item-excerpt blog-item-excerpt"><p><?php the_excerpt() ?></p>
														</div>
														<div class="item-meta blog-item-meta">
														
															<span><i class="fa fa-bookmark"></i>
																<?php
																	$category = get_the_category();
																?>
																<a  href="<?php echo get_category_link($category[0]->cat_ID); ?> ">
																<?php echo $category[0]->cat_name; ?> </a>
															</span>
														</div>
														<a class="btn btn-primary" href="<?php the_permalink() ?>" title="The Dream">DETAIL <i class="fa fa-angle-right"></i></a>
													</div>
												</div>
											</div>
											
											
											</div>	
										</div>	
										<?php
										endwhile;
										wp_reset_postdata();
										
									}
									else
									{
										
										echo $no_result;
										
									}
									
								}
								
								
							}
							else
							{
								
								echo $no_result;
								
							}
							?>
                        	<?php while ( have_posts() ) : the_post();
								the_content();
							endwhile; ?>
						</div>
                       
                    </div><!--/content-->
                    <?php if($layout != 'full' && $layout != 'true-full'){get_sidebar();} ?>
                </div><!--/row-->
            <?php if($content_padding!='off'){ ?>
            </div><!--/content-pad-4x-->
            <?php }?>
        <?php if($layout!='true-full'){ ?>
        </div><!--/container-->
        <?php }?>
    </div><!--/body-->

<?php get_footer(); ?>