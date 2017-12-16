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
$user_email= $_SESSION['email_id'];

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
	
	$article_result = zendesk_data ("/api/v2/help_center/articles/search.json?query=".$_POST['search_post'], "GET",$article_data);

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
		if (strlen($article_body)>150) {
		  $article_body=substr($article_body, 0, 150) . '[...]';
		}
		// echo '<pre>';
		// print_r($article_result);
		// echo '</pre>';
		$article_array[]= array('id'=>$article_id, 'title'=>$article_title,'description'=>$article_body,'href'=>'','created_at'=>$article_create_date,'updated_at'=>$article_update_date,'tags'=>'','status'=>'','type'=>$article_type,'category_name'=>'','category_link'=>'','date'=>date("d", strtotime( $article_created_at)),'month'=>date("M", strtotime( $article_created_at)),'year'=>date("Y", strtotime( $article_created_at)),'image'=>'' );
	}

	/* End Fetch articles */							
	
	
	/* Start Fetch Tickets */
	$activate_result = zendesk_data ("/api/v2/search.json?query=requester:".$user_email."&sort_by=created_at&sort_order=desc","GET",array());
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
					if (strlen($ticket_description)>150) {
					  $ticket_description=substr($ticket_description, 0, 150) . '[...]';
					}
					
					$ticket_array[]= array('id'=>$tickets->id, 'title'=>$tickets->subject,'description'=>$ticket_description,'href'=>$tickets->url,'created_at'=>$ticket_create_date,'updated_at'=>$ticket_update_date,'tags'=>$tickets->tags,'status'=>$tickets->status,'type'=>'ticket','category_name'=>'','category_link'=>'','date'=>date("d", strtotime( $ticket_create_date)),'month'=>date("M", strtotime( $ticket_create_date)),'year'=>date("Y", strtotime( $ticket_create_date)),'image'=>'' );
					}
				}
			}
		}
		
	}
	/* End Fetch Tickets */
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
								
/********************************** Start When user logged in (Show Tickets, Posts and Articles) ***************************/

								
								if($user_email!=null)
								{
									if($article_array[0]['type']==null)
									{
										$total_data= array_merge($post_array,$ticket_array);
									}
									else
									{
										$total_data= array_merge($post_array,$ticket_array,$article_array);
									}
									//$total_data= array_merge($post_array,$ticket_array,$article_array);
	
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
																elseif($total_datas['type']!=null) 
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
																} else { }?>
																																
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
																elseif($total_datas['type']!=null) 
																{  ?>
																
																	<h3 class="item-title"><a href='<?php echo $total_datas['href'] ?>'><?php echo $total_datas['title'] ?></a></h3>
																	<div class="item-excerpt blog-item-excerpt"><p><?php echo $total_datas['description'] ?></p>
																	</div>
																	<div class="item-meta blog-item-meta">
																		<span><i class="fa fa-bookmark"></i> <a href="<?php echo $total_datas['category_link'] ?>" rel="category tag"><?php echo $total_datas['category_name'] ?></a></span>
																	</div>
																	<a class="btn btn-primary" href='<?php echo $total_datas['href'] ?>'>DETAIL <i class="fa fa-angle-right"></i></a>
																
																<?php
																} else {  } ?>
															
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
/********************************** End When user logged in (Show Tickets, Posts and Articles) ***************************/	

/********************************** Start When user not logged in (Show Posts and Articles)*******************************/	
								else
								{
if($article_array[0]['type']==null)
									{
										$total_data= $post_array;
									}
									else
									{
										$total_data= array_merge($post_array,$article_array);
									}									
//$total_data= array_merge($post_array,$article_array);
										
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
																<?php if($total_datas['type']=='article') 
																{
																	?>
																	<a href='<?php echo home_url() ?>/article?id=<?php echo $total_datas['id'] ?>'>
																	<img src="<?php bloginfo('template_url'); ?>/images/zendesk_ticket.png" width="500" height="500" >
																	<?php
																}
																elseif($total_datas['type']!=null) 
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
																} else { }?>
																																
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
															<?php if($total_datas['type']=='article') 
																{
																	?>
																	
																	<h3 class="item-title"><a href='<?php echo home_url() ?>/article?id=<?php echo $total_datas['id'] ?>'><?php echo $total_datas['title'] ?></a></h3>
																	<div class="item-excerpt blog-item-excerpt"><p><?php echo $total_datas['description'] ?></p>
																	</div>
																	
																	<a class="btn btn-primary" href="<?php echo home_url() ?>/article?id=<?php echo $total_datas['id'] ?>" >DETAIL <i class="fa fa-angle-right"></i></a>
																	<?php
																}
																elseif($total_datas['type']!=null) 
																{  ?>
																
																	<h3 class="item-title"><a href='<?php echo $total_datas['href'] ?>'><?php echo $total_datas['title'] ?></a></h3>
																	<div class="item-excerpt blog-item-excerpt"><p><?php echo $total_datas['description'] ?></p>
																	</div>
																	<div class="item-meta blog-item-meta">
																		<span><i class="fa fa-bookmark"></i> <a href="<?php echo $total_datas['category_link'] ?>" rel="category tag"><?php echo $total_datas['category_name'] ?></a></span>
																	</div>
																	<a class="btn btn-primary" href='<?php echo $total_datas['href'] ?>'>DETAIL <i class="fa fa-angle-right"></i></a>
																
																<?php
																} else {  } ?>
															
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
/********************************** End When user not logged in (Show Posts and Articles)*******************************/	
								
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