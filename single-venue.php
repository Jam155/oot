<?php get_header(); ?>
<div id="container">
<section id="content" class="venue-wrapper" role="main">
	<a href="<?php echo site_url(); ?>" class="dashboard-logo"><img src="<?php echo get_template_directory_uri(); ?>/images/dashboard-logo.png" /></a>
	<?php if(is_user_logged_in()) { ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<section class="venue-details">
			<h3 class="col-title">Venue Details</h3>
			<div class="col-content">
				<div class="top-box">
					<div class="left">
						<div class="venue-img">
							<button class="frontend-button"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
							<?php echo get_the_post_thumbnail(get_the_ID(), 'thumbnail', array( 'data-image-id' => '' )); ?>
						</div>
					</div>
					<div class="right">					
						<label for="venue-title" class="control-label">
							<p class="text-info"><?php echo get_the_title(); ?></p>
							<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
						</label>
						<label for="venue-website" class="control-label">
							<p class="text-info"><?php echo get_field('website'); ?></p>
							<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="url"></i>
						</label>
					</div>
				</div>
				
				<div class="venue-detail-columns">
					<div class="left">
						<?php if( have_rows('opening_hours') ): ?>
							<?php while( have_rows('opening_hours') ): the_row(); ?>
								<div class="opening-hours">
									<p class="venue-desc-title">Opening Hours<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="hours"></i></p>
									<table>
										<tr>
											<th>M</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('monday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i></a><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>T</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('tuesday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>W</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('wednesday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>T</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('thursday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>F</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('friday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>S</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('saturday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>S</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('sunday') ): the_row(); ?><li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li><?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
									</table>
									<a href="#" class="savehours btn">Update Hours <i class="fa fa-check" aria-hidden="true"></i></a>
								</div>
							<?php endwhile;?>
						<?php endif;?>
					</div>
					<div class="right">
						<ul>
							<li>
								<label for="venue-phone" class="control-label">
									<p class="text-info"><?php echo get_field('phone'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="tel"></i>
								</label>
							</li>
							<li>
								<label for="venue-address-1" class="control-label">
									<p class="text-info"><?php echo get_field('address_1'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
							<?php if(get_field('address_2')) { ?>
							<li>
								<label for="venue-address-2" class="control-label no-icon">
									<p class="text-info"><?php echo get_field('address_2'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
							<?php } ?>
							<li>
								<label for="venue-city" class="control-label no-icon">
									<p class="text-info"><?php echo get_field('city'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
							<li>
								<label for="venue-post-code" class="control-label no-icon">
									<p class="text-info"><?php echo get_field('post_code'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
							<li>
								<label for="venue-twitter" class="control-label">
									<p class="text-info"><?php echo get_field('twitter'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
							<li>
								<label for="venue-facebook" class="control-label">
									<p class="text-info"><?php echo get_field('facebook'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
						</ul>
					</div>
					<div class="clearfix"></div>
				</div>
				
				<label for="venue-description" class="control-label venue-description">
					<p class="venue-desc-title">Venue Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i></a>
					<div class="text-info-wrapper">
						<p class="text-info"><?php echo get_the_content(); ?></p>
					</div>
				</label>
				
				<div class="venue-cat-wrapper">
					<p class="venue-desc-title">Category</p>
					<p class="cat-select"><?php $category = get_the_category(); ?>
					<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
				</div>
				
				<div class="venue-tag-wrapper">
					<p class="venue-desc-title">Tags</p>
					<div class="venue-active-tags">
					<?php
						$posttags = get_the_tags();
						if ($posttags) {
							foreach($posttags as $tag) {
								echo '<span class="venue-tag" data-tag="' . $tag->name. '">' . $tag->name . '<i class="fa fa-close del" aria-hidden="true"></i></span>'; 
							}
						}
					?>
					</div>
					<input class="tag-search-field" name="tag_search_field" type="text" autocomplete="off" placeholder="&#xf067; TYPE TO SEARCH FOR TAG" style="" />
					<div class="tag-result"></div>
				</div>
				<button class="save-venue-btn" type="button" data-post-id="<?php echo get_the_ID(); ?>">Save Details</button>
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
						<div class="offer-item">
							<div class="left">
								<?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
							</div>
							<div class="right">
								<h2>
									<?php the_title(); ?>
									<i class="editoffer fa fa-pencil" aria-hidden="true" data-edit-type="expand"></i>
								</h2>
								<div class="offer-details">
									<?php
										$date = get_field('date', false, false);
										$date = new DateTime($date);
									?>
									
									<i class="fa fa-calendar" aria-hidden="true"></i><?php echo $date->format('d/m/Y'); ?><br>
									<i class="fa fa-clock-o" aria-hidden="true"></i><span class="starttime"><?php echo get_field('start_time'); ?></span> - <span class="endtime"><?php echo get_field('end_time'); ?></span><br>
									<i class="fa fa-hashtag" aria-hidden="true"></i><?php echo get_field('maximum_redeemable'); ?>
								</div>
							</div>
							<div class="offer-description">
								<label for="venue-description" class="control-label venue-description">
									<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
									<div class="text-info-wrapper">
										<p class="text-info"><?php echo get_the_content(); ?></p>
									</div>
								</label>
								<span class="remove">Remove Offer<i class="fa fa-trash" aria-hidden="true"></i></span>
								<span class="save">Save Offer<i class="fa fa-check" aria-hidden="true"></i></span>
							</div>
						</div>
					<?php
					endforeach; 
					wp_reset_postdata();
				}
				?>
			</div>
			<div class="col-accordion-wrapper">
				<div class="accordion-content">
					<div class="offer-item">
						<div class="left">
							<button class="frontend-button"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
							<img src="<?php echo get_template_directory_uri(); ?>/images/oot-placeholder-img.png" class="blank-img" data-image-id=""/>
						</div>
						<div class="right">
							<h2>
								<label for="offer-title" class="control-label no-icon">
									<p class="text-info">Offer Title</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</h2>
							<div class="offer-details">
								<label for="offer-date" class="control-label">
									<p class="text-info">Select offer date</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="date"></i>
								</label>
								<label for="offer-time" class="control-label">
									<p class="text-info">Select offer time</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="time"></i>
								</label>
								<label for="offer-quantity" class="control-label">
									<p class="text-info">Enter redeem amount</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="number"></i>
								</label>
							</div>
						</div>
						<div class="offer-description">
							<label for="venue-description" class="control-label venue-description">
								<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
								<div class="text-info-wrapper">
									<p class="text-info">Full Description &plus; T&amp;C</p>
								</div>
							</label>
							<span class="remove">Remove Offer<i class="fa fa-trash" aria-hidden="true"></i></span>
							<span class="save new-offer-save">Save Offer<i class="fa fa-check" aria-hidden="true"></i></span>
						</div>
					</div>
					<?php /*
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
					*/ ?>
				</div>
				<h3 class="col-title">Add New Offer <i class="fa fa-plus" aria-hidden="true"></i></h3>
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
						<div class="event-item">
							<div class="left">
								<?php echo get_the_post_thumbnail(get_the_ID(), 'full'); ?>
							</div>
							<div class="right">
								<h2>
									<?php the_title(); ?>
									<i class="editevent fa fa-pencil" aria-hidden="true"></i>
								</h2>
								<div class="event-details">
									<i class="fa fa-calendar" aria-hidden="true"></i><?php echo get_field('date'); ?><br>
									<i class="fa fa-clock-o" aria-hidden="true"></i><span class="starttime"><?php echo get_field('start_time'); ?></span> - <span class="endtime"><?php echo get_field('end_time'); ?></span><br>
									<i class="fa fa-ticket" aria-hidden="true"></i><?php echo '&pound;' . bcdiv(get_field('ticket_price'), '1', 2); ?>
								</div>
							</div>
							<div class="event-description">
								<label for="venue-description" class="control-label venue-description">
									<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
									<div class="text-info-wrapper">
										<p class="text-info"><?php echo get_the_content(); ?></p>
									</div>
								</label>
								<div class="repeat-event-wrapper">
									<p class="venue-desc-title">Repeat Event?</p>
									<?php
										$key = 'field_58ac60bee9d5c';
										$field = get_field_object($key);
										if ($field) {
											foreach ($field['choices'] as $key => $value) {
										?>
												<input type="radio" id="<?php echo get_the_ID() . $value; ?>" name="event<?php echo get_the_ID(); ?>" <?php if( get_field('repeat_event') == $value ) { echo 'checked'; } ?>/>
												<label for="<?php echo get_the_ID() . $value; ?>"><span></span><?php echo $value; ?></label>
										<?php
											}                             
										}
									?>
								</div>

								<span class="remove">Remove Event<i class="fa fa-trash" aria-hidden="true"></i></span>
								<span class="save">Save Event<i class="fa fa-check" aria-hidden="true"></i></span>
							</div>
						</div>
					<?php
					endforeach; 
					wp_reset_postdata();
				}
				?>
			</div>
			<div class="col-accordion-wrapper">
				<div class="accordion-content">
					<div class="event-item">
						<div class="left">
							<button class="frontend-button"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
							<img src="<?php echo get_template_directory_uri(); ?>/images/oot-placeholder-img.png" class="blank-img" data-image-id=""/>
						</div>
						<div class="right">
							<h2>
								<label for="event-title" class="control-label no-icon">
									<p class="text-info">Event Title</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</h2>
							<div class="event-details">
								<label for="event-date" class="control-label">
									<p class="text-info">Select event date</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="date"></i>
								</label>
								<label for="event-time" class="control-label">
									<p class="text-info">Select event time</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="time"></i>
								</label>
								<label for="event-quantity" class="control-label">
									<p class="text-info">Enter ticket price</p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="currency"></i>
								</label>
							</div>
						</div>
						<div class="event-description">
							<label for="venue-description" class="control-label venue-description">
								<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
								<div class="text-info-wrapper">
									<p class="text-info">Full Description &plus; T&amp;C</p>
								</div>
							</label>
							<div class="repeat-event-wrapper">
								<p class="venue-desc-title">Repeat Event?</p>
								<?php
									$key = 'field_58ac60bee9d5c';
									$field = get_field_object($key);
									if ($field) {
										foreach ($field['choices'] as $key => $value) {
									?>
											<input type="radio" data-value="<?php echo $value; ?>" id="<?php echo get_the_ID() . $value; ?>" name="event<?php echo get_the_ID(); ?>" <?php if( get_field('repeat_event') == $value ) { echo 'checked'; } ?>/>
											<label for="<?php echo get_the_ID() . $value; ?>"><span></span><?php echo $value; ?></label>
									<?php
										}                             
									}
								?>
							</div>
							<span class="remove">Remove Event<i class="fa fa-trash" aria-hidden="true"></i></span>
							<span class="save new-event-save">Save Event<i class="fa fa-check" aria-hidden="true"></i></span>
						</div>
					</div>
					<?php /*
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
					*/ ?>
				</div>
				<h3 class="col-title">Add New Event <i class="fa fa-plus" aria-hidden="true"></i></h3>
			</div>
		</section>
		<?php endwhile; endif; ?>
	<?php } else { ?>
		<p>You are not logged in</p>
	<?php } ?>
</section>
<?php get_footer(); ?>