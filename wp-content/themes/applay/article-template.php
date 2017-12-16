<?php 
/*
Template Name: Article Template
*/
$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
global $global_page_layout;
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
get_header();


$article_data = array();
$article_id=$_GET['id'];
$article_result = zendesk_data("/api/v2/help_center/articles/".$article_id.".json", "GET",$article_data);

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
		$article_name=$article_result->article->name ."<br/>";    // Article Name
		$article_title=$article_result->article->title ."<br/>";  // Article Title
		$article_body=$article_result->article->body ;          // Article Body
		$article_created_at=$article_result->results[0]->created_at;   
		$article_updated_at=$article_result->results[0]->updated_at;
		
		// echo '<pre>';
		// print_r($article_result);
		// echo '</pre>';
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
								<h2 class="blog-title title entry-title"><?php echo $article_name; ?></h2>
								<div class="single-post-content-text content-pad">
									<p style="    white-space: pre-line;">
										
										<?php 
										
										echo $article_body; 
										
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