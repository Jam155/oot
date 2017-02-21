<?php get_header(); ?>
<section id="content" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="header">
				<h1 class="entry-title"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
			</header>
			
			<?php /*
			<section class="entry-content">
				<?php if(is_user_logged_in()) { ?>
					<form id="post-submission-form">
						<div>
							<label for="post-submission-title">
								<?php _e( 'Title', 'oot' ); ?>
							</label>
							<input type="text" name="post-submission-title" id="post-submission-title" required aria-required="true">
						</div>
						<div>
							<label for="post-submission-excerpt">
								<?php _e( 'Excerpt', 'oot' ); ?>
							</label>
							<textarea rows="2" cols="20" name="post-submission-excerpt" id="post-submission-excerpt" required aria-required="true"></textarea>
						</div>
						<div>
							<label for="post-submission-content">
								<?php _e( 'Content', 'oot' ); ?>
							</label>
							<textarea rows="10" cols="20" name="post-submission-content" id="post-submission-content"></textarea>
						</div>
						<div>
							<label for="post-submission-address">
								<?php _e( 'Address', 'oot' ); ?>
							</label>
							<input type="text" name="acf_fields[address]" id="post-submission-address" required aria-required="true">
						</div>
						<input type="submit" value="<?php esc_attr_e( 'Submit', 'oot'); ?>">
					</form>
				<?php } ?>
			</section>
			
			*/ ?>
			
			<?php if(is_user_logged_in()) { ?>
				<div>
					<?php get_template_part( 'frontpage/frontpage', 'in' ); ?>
				</div>
			<?php } else { ?>
				<div>
					<?php get_template_part( 'frontpage/frontpage', 'out' ); ?>
				</div>
			<?php } ?>
		</article>
</section>
<?php get_footer(); ?>