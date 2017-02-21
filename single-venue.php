<?php get_header(); ?>
<section id="content" class="venue-wrapper" role="main">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
	<section class="venue-details">
		<h3 class="col-title">Venue Details</h3>
		<div class="col-content">
			
			<div class="venue-img"><?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?></div>
			
			<label for="venue-title" class="control-label">
				<p class="text-info"><?php echo get_the_title(); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('website'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('phone'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('address_1'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			
			<?php if(get_field('address_2')) { ?>
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('address_2'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			<?php } ?>
			
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('city'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('post_code'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>
			
			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('twitter'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>

			<label for="venue-website" class="control-label">
				<p class="text-info"><?php echo get_field('facebook'); ?></p>
				<a href="#" class="edit" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
			</label>

			<?php
			if( have_rows('opening_hours') ): ?>
				<?php while( have_rows('opening_hours') ): the_row(); ?>
					<div>
						<table>
							<tr>
								<td>M:</td>
								<td>
									<ul><?php while( have_rows('monday') ): the_row(); ?><li><?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							<tr>
								<td>T:</td>
								<td>
									<ul><?php while( have_rows('tuesday') ): the_row(); ?><li>T: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							<tr>
								<td>W:</td>
								<td>
									<ul><?php while( have_rows('wednesday') ): the_row(); ?><li>W: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							<tr>
								<td>T:</td>
								<td>
									<ul><?php while( have_rows('thursday') ): the_row(); ?><li>T: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							<tr>
								<td>F:</td>
								<td>
									<ul><?php while( have_rows('friday') ): the_row(); ?><li>F: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							<tr>
								<td>S:</td>
								<td>
									<ul><?php while( have_rows('saturday') ): the_row(); ?><li>S: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							<tr>
								<td>S:</td>
								<td>
									<ul><?php while( have_rows('sunday') ): the_row(); ?><li>S: <?php the_sub_field('open'); ?> - <?php the_sub_field('close'); ?></li><?php endwhile; ?></ul>
								</td>
							</tr>
							</tr>
						</table>
					</div>
				<?php endwhile;?>
			<?php endif;?>
			
			<label for="venue-website" class="control-label venue-description">
				<p class="venue-desc-title">Venue Description</p><a href="#" class="edit-textarea" class="btn"><i class="fa fa-pencil" aria-hidden="true"></i></a>
				<div class="text-info-wrapper">
					<p class="text-info"><?php echo get_the_content(); ?></p>
				</div>
				<?php $settings = array( 'media_buttons' => false, 'quicktags' => false, 'textarea_name' => 'venue_content', ); ?>
				<div class="venue-description-editor-wrapper"><?php wp_editor(get_the_content(), 'venuedescriptioneditor', $settings); ?></div>
			</label>
			
			<p class="cat-select"><?php $category = get_the_category(); ?>
			<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
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
			<button class="save-venue-btn" type="button">Save Details</button>
		</div>
	</section>
	<section class="current-offers">
		<h3 class="col-title">Current Offers</h3>
		<div class="col-content">
			<?php
			$currentoffers = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'offer',
				'meta_key' => 'venue',
				'meta_value' => get_the_ID()
			) );
			 
			if ( $currentoffers ) {
				foreach ( $currentoffers as $post ) :
					setup_postdata( $post ); ?>
					<?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
					<h2><?php the_title(); ?></h2>
					<?php echo get_field('date'); ?><br>
					<?php echo get_field('start_time'); ?><br>
					<?php echo get_field('end_time'); ?><br>
					<?php echo get_field('maximum_redeemable'); ?>
				<?php
				endforeach; 
				wp_reset_postdata();
			}
			?>
		</div>
		<div class="col-accordion-wrapper">
			<h3 class="col-title">Add New Offer <i class="fa fa-plus" aria-hidden="true"></i></h3>
			<div class="accordion-content">
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
						<input name="acf_fields[date]" id="offer-submission-date" class="datepicker">
					</div>
					<div>
						<label for="offer-submission-start">
							<?php _e( 'Start Time', 'oot' ); ?>
						</label>
						<input name="acf_fields[start_time]" id="offer-submission-start" class="timepicker">
					</div>
					<div>
						<label for="offer-submission-end">
							<?php _e( 'End Time', 'oot' ); ?>
						</label>
						<input name="acf_fields[end_time]" id="offer-submission-end" class="timepicker">
					</div>
					<div>
						<label for="offer-submission-redeemable">
							<?php _e( 'Maximum Redeemable', 'oot' ); ?>
						</label>
						<input type="number" name="acf_fields[maximum_redeemable]" id="offer-submission-redeemable">
					</div>
					<input type="submit" value="<?php esc_attr_e( 'Submit', 'oot'); ?>">
				</form>
			</div>
		</div>
	</section>
	<section class="upcoming-events">
		<h3 class="col-title">Upcoming Events</h3>
		<div class="col-content">
			<div id="calendar"></div>
			<?php
			$currentoffers = get_posts( array(
				'posts_per_page' => -1,
				'post_type' => 'event',
				'meta_key' => 'venue',
				'meta_value' => get_the_ID()
			) );
			 
			if ( $currentoffers ) {
				foreach ( $currentoffers as $post ) :
					setup_postdata( $post ); ?>
					<?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail'); ?>
					<h2><?php the_title(); ?></h2>
					<?php echo get_field('date'); ?><br>
					<?php echo get_field('start_time'); ?><br>
					<?php echo get_field('end_time'); ?><br>
					<?php echo get_field('ticket_price'); ?>
				<?php
				endforeach; 
				wp_reset_postdata();
			}
			?>
		</div>
		<div class="col-accordion-wrapper">
			<h3 class="col-title">Add New Event <i class="fa fa-plus" aria-hidden="true"></i></h3>
			<div class="accordion-content">
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
						<input name="acf_fields[date]" id="offer-submission-date" class="datepicker">
					</div>
					<div>
						<label for="offer-submission-start">
							<?php _e( 'Start Time', 'oot' ); ?>
						</label>
						<input name="acf_fields[start_time]" id="offer-submission-start" class="timepicker">
					</div>
					<div>
						<label for="offer-submission-end">
							<?php _e( 'End Time', 'oot' ); ?>
						</label>
						<input name="acf_fields[end_time]" id="offer-submission-end" class="timepicker">
					</div>
					<div>
						<label for="offer-submission-redeemable">
							<?php _e( 'Maximum Redeemable', 'oot' ); ?>
						</label>
						<input type="number" name="acf_fields[maximum_redeemable]" id="offer-submission-redeemable">
					</div>
					<input type="submit" value="<?php esc_attr_e( 'Submit', 'oot'); ?>">
				</form>
			</div>
		</div>
	</section>
	<?php endwhile; endif; ?>
</section>

<script>
        $.ajax({
            method: "GET",
            url: 'http://localhost/oot/wp-json/wp/v2/event',
            success : function( response ) {
                console.log( response );
            },
            fail : function( response ) {
                console.log( response );
            }
        });

$.getJSON( "http://localhost/oot/wp-json/wp/v2/event", function( json ) {
	console.log( "JSON Data: " + json.id[ 3 ].name );
});

var events = [];
var len = 3;
for (var i = 0; i <= len; i++) {
    events.push({
        Title: "Event " + i,
        Date: new Date("02/0" + i + "/2017")
    });
}

/*
var events = [
	{ Title: "Event 1", Date: new Date("02/02/2017") }, 
	{ Title: "Event 2", Date: new Date("02/20/2017") }, 
	{ Title: "Event 3", Date: new Date("02/16/2017") }
];
*/

$('.venue-description-editor-wrapper').hide();

$('.edit').click(function() {
	var text = $(this).siblings('.text-info').text();
	var input = $('<input id="attribute" type="text" value="' + text + '" />')
	$(this).siblings('.text-info').text('').append(input);
	input.select();
	
	input.blur(function() {
		var text = $('#attribute').val();
		$('#attribute').parent().text(text);
		$('#attribute').remove();
	});
});

$('.edit-textarea').click(function() {
	var text = $('.venue-description .text-info-wrapper').hide();
	$('.venue-description-editor-wrapper').show();

	$(document).mouseup(function (e) {
		if($('.venue-description-editor-wrapper').is(':visible')) {		
			var container = $('.venue-description-editor-wrapper');
			if (!container.is(e.target) && container.has(e.target).length === 0)
			{
				container.hide();
				console.log( tinyMCE.get('venuedescriptioneditor').getContent() );
				$('.venue-description .text-info-wrapper').html( tinyMCE.get('venuedescriptioneditor').getContent() );
				$('.venue-description .text-info-wrapper').toggle();
			}
		}
	});
});
</script>
<?php get_footer(); ?>