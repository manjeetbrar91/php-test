<?php 
/*
Template Name: Ticket Template
*/
$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
global $global_page_layout;
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
get_header();


$activate_result = zendesk_data("/api/v2/tickets/".$_GET['id'].".json","GET",array());

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
		$subject= $activate_result->ticket->subject;
		$created_at= $activate_result->ticket->created_at;
		$updated_at= $activate_result->ticket->updated_at;
		$description= $activate_result->ticket->description;
		
	}
?>
<?php $page_title = __($subject,'leafcolor'); ?>

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
							<article class="single-post-content single-content">
								<h2 class="blog-title title entry-title"><?php echo $subject; ?></h2>
								<div class="single-post-content-text content-pad">
									<p style="    white-space: pre-line;">
										
										<?php 
										
										echo $description; 
										
										?>
										
									</p>


								</div>
								<?php while ( have_posts() ) : the_post();
									the_content();
								endwhile; ?>
	
							</article>
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