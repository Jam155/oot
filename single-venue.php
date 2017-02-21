<?php get_header(); ?>
<section id="content" class="venue-wrapper" role="main">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<section class="venue-details">
		<h3 class="col-title">Venue Details</h3>
		<div class="col-content">
			
			<div class="venue-img"><?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?></div>
			
			<p><?php echo get_the_title(); ?></p>
			<p><?php echo get_field('website'); ?></p>
			<p><?php echo get_field('phone'); ?></p>
			<p>
				<?php echo get_field('address_1'); ?><br>
				<?php echo get_field('address_2'); ?><br>
				<?php echo get_field('city'); ?><br>
				<?php echo get_field('post_code'); ?>
			</p>
			<p>@<?php echo get_field('twitter'); ?></p>
			<p><?php echo get_field('facebook'); ?></p>

			<?php
			if( have_rows('opening_hours') ): ?>
				<div id="to-do-lists">
				<?php while( have_rows('opening_hours') ): the_row(); ?>
					<div>
						<ul>
							<?php while( have_rows('monday') ): the_row(); ?><li>M: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
							<?php while( have_rows('tuesday') ): the_row(); ?><li>T: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
							<?php while( have_rows('wednesday') ): the_row(); ?><li>W: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
							<?php while( have_rows('thursday') ): the_row(); ?><li>T: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
							<?php while( have_rows('friday') ): the_row(); ?><li>F: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
							<?php while( have_rows('saturday') ): the_row(); ?><li>S: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
							<?php while( have_rows('sunday') ): the_row(); ?><li>S: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?>
						</ul>
					</div>
				<?php endwhile;?>
				</div>
			<?php endif;?>
			
			<p><?php the_content(); ?></p>
			<p><?php $category = get_the_category(); ?>
			<?php wp_dropdown_categories(array('hide_empty' => 0, 'selected' => $category[0]->term_id )); ?></p>
			<p>
			<?php
				$posttags = get_the_tags();
				if ($posttags) {
				  foreach($posttags as $tag) {
					echo '<span>' . $tag->name . '</span>'; 
				  }
				}
			?>
			</p>
		</div>
	</section>
	<section class="current-offers">
		<h3 class="col-title">Current Offers</h3>
		<div class="col-content">
			<form id="offer-submission-form">
				<div>
					<label for="offer-submission-title">
						<?php _e( 'Title', 'oot' ); ?>
					</label>
					<input type="text" name="offer-submission-title" id="offer-submission-title" required aria-required="true">
				</div>
				<div>
					<label for="offer-submission-content">
						<?php _e( 'Content', 'oot' ); ?>
					</label>
					<textarea rows="10" cols="20" name="offer-submission-content" id="offer-submission-content"></textarea>
				</div>
				<div>
					<label for="offer-submission-date">
						<?php _e( 'Date', 'oot' ); ?>
					</label>
					<input type="text" name="acf_fields[date]" id="offer-submission-date" class="datepicker" required aria-required="true">
				</div>
				<div>
					<label for="offer-submission-start">
						<?php _e( 'Start Time', 'oot' ); ?>
					</label>
					<input type="text" name="acf_fields[start_time]" id="offer-submission-start" class="timepicker" required aria-required="true">
				</div>
				<div>
					<label for="offer-submission-end">
						<?php _e( 'End Time', 'oot' ); ?>
					</label>
					<input type="text" name="acf_fields[end_time]" id="offer-submission-end" class="timepicker" required aria-required="true">
				</div>
				<div>
					<label for="offer-submission-redeemable">
						<?php _e( 'Maximum Redeemable', 'oot' ); ?>
					</label>
					<input type="text" name="acf_fields[maximum_redeemable]" id="offer-submission-redeemable" required aria-required="true">
				</div>
				<input type="submit" value="<?php esc_attr_e( 'Submit', 'oot'); ?>">
			</form>
		</div>
	</section>	
	<section class="upcoming-events">
		<h3 class="col-title">Upcoming Events</h3>
		<div class="col-content">
		
		</div>
	</section>
	<?php endwhile; endif; ?>
</section>
<?php get_footer(); ?>