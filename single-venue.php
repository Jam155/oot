<?php get_header(); ?>
<?php require_once( ABSPATH . '/wp-admin/includes/template.php' ); ?>
<div id="container">
<section id="content" class="venue-wrapper" role="main">
	<a href="<?php echo site_url(); ?>" class="dashboard-logo"><img src="<?php echo get_template_directory_uri(); ?>/images/dashboard-logo.png" /></a>
	<?php if(is_user_logged_in()) { ?>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<section class="venue-details" data-venue-id="<?php echo get_the_ID(); ?>">
			<h3 class="col-title">Venue Details</h3>
			<div class="col-content">
				<div class="top-box">
					<div class="left">
						<div class="venue-img">
							<button class="frontend-button"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
							<?php if ( has_post_thumbnail() ) {
								echo get_the_post_thumbnail(get_the_ID(), 'thumbnail', array( 'data-image-id' => $post->ID ));
							} else { ?>						
								<img width="104" height="104" src="http://localhost/oot/wp-content/themes/oot/images/oot-placeholder-img.png" class="blank-img" data-image-id="">
							<?php } ?>
							
						</div>
					</div>
					<div class="right">					
						<label for="venue-title" class="control-label">
							<p class="text-info"><?php echo get_the_title(); ?></p>
							<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
						</label>
						<label for="venue-website" class="control-label">
							<?php $url = parse_url(get_field('website'));  
							$url = preg_replace('#^www\.(.+\.)#i', '$1', $url['host']) . $url['path'];
							?>
							<p class="text-info" data-orig-url="<?php echo get_field('website'); ?>"><?php echo $url; ?></p>
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
													<?php while( have_rows('monday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i></a><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>T</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('tuesday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>W</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('wednesday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>T</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('thursday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>F</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('friday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>S</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('saturday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
										<tr>
											<th>S</th>
											<td>
												<ul class="list-hours">
													<?php while( have_rows('sunday') ): the_row(); ?>
														<?php if( get_sub_field('open') && get_sub_field('close') ) { ?>
															<li><span class="opentime"><?php the_sub_field('open'); ?></span> - <span class="closetime"><?php the_sub_field('close'); ?></li>
														<?php } else { ?>
															<li class="closed-day">Closed</li>
														<?php } ?>
													<?php endwhile; ?></span>
													<i class="fa fa-minus-circle" aria-hidden="true"></i><i class="fa fa-plus-circle" aria-hidden="true"></i>
												</ul>
											</td>
										</tr>
									</table>
									<a href="#" class="savehours btn">Finish Editing <i class="fa fa-check" aria-hidden="true"></i></a>
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
							<li>
								<label for="venue-address-2" class="control-label no-icon">
									<p class="text-info"><?php echo get_field('address_2'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
								</label>
							</li>
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
									<?php $twitter_display = parse_url( get_field('twitter') ); ?>
									<p class="text-info" data-orig-twitter="<?php echo get_field('twitter'); ?>"><?php echo trim($twitter_display['path'], '/'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="twitter"></i>
								</label>
							</li>
							<li>
								<label for="venue-facebook" class="control-label">
									<?php $facebook_display = parse_url( get_field('facebook') ); ?>
									<p class="text-info" data-orig-facebook="<?php echo get_field('facebook'); ?>"><?php echo trim($facebook_display['path'], '/'); ?></p>
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="facebook"></i>
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
					<p class="venue-desc-title">Categories</p>
					<?php /*
					<p class="cat-select"><?php $category = get_the_category(); ?>
					<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
					*/ ?>

					
					<?php
					wp_category_checklist(get_the_ID());
					?>
				</div>
				
				<div class="venue-tag-wrapper">
					<p class="venue-desc-title">Tags</p>
					<div class="venue-active-tags">
					<?php
						$posttags = get_the_tags();
						if ($posttags) {
							foreach($posttags as $tag) {
								echo '<span class="venue-tag" data-tag-id="' . $tag->term_id . '" data-tag="' . $tag->name. '">' . htmlentities($tag->name) . '<i class="fa fa-close del" aria-hidden="true"></i></span>'; 
							}
						}
					?>
					</div>
					<input class="tag-search-field" name="tag_search_field" type="text" autocomplete="off" placeholder="&#xf067; TYPE TO SEARCH FOR TAG" style="" data-item-id="Venue <?php echo get_the_ID(); ?>"/>
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
					'meta_key' => 'date',
					'orderby' => 'meta_value',
					'order' => 'ASC',
					'meta_query' => array(
						array(
							'key'     => 'venue',
							'value'   => get_the_ID(),
							'compare' => '='
						)
					)
				) );
				 
				if ( $currentoffers ) {
					foreach ( $currentoffers as $post ) :
						setup_postdata( $post ); ?>
						<div class="offer-item" data-offer-id="<?php echo $post->ID; ?>">
							<div class="left">
								<button class="frontend-button"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
								<?php if (has_post_thumbnail( $post->ID ) ) { ?>
									<img src="<?php echo the_post_thumbnail_url(); ?>" data-image-id="<?php echo get_post_thumbnail_id( $post->ID ); ?>" >
								<?php } else { ?>
									<img src="http://localhost/oot/wp-content/themes/oot/images/oot-placeholder-img.png" class="blank-img" data-image-id="">
								<?php } ?>
							</div>
							<div class="right">
								<h2>
									<label for="offer-title" class="control-label no-icon">
										<p class="text-info"><?php the_title(); ?></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
									</label>
									<i class="editoffer fa fa-pencil" aria-hidden="true" data-edit-type="expand"></i>
								</h2>
								<div class="offer-details">
									<?php
										$date = get_field('date', false, false);
										$date = new DateTime($date);
									?>
									<label for="offer-date" class="control-label">
										<p class="text-info"><?php echo $date->format('d/m/Y'); ?></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="date"></i>
									</label>
									<label for="offer-time" class="control-label">
										<p class="text-info"><span class="starttime"><?php echo get_field('start_time'); ?></span> - <span class="endtime"><?php echo get_field('end_time'); ?></span></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="time"></i>
									</label>
									<label for="offer-quantity" class="control-label">
										<p class="text-info"><?php echo get_field('maximum_redeemable'); ?></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="number"></i>
									</label>
								</div>
							</div>
							<div class="offer-description">
								<label for="venue-description" class="control-label venue-description">
									<p class="venue-desc-title">Full Description</p><i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="textarea"></i>
									<div class="text-info-wrapper">
										<p class="text-info"><?php echo get_the_content(); ?></p>
									</div>
								</label>
								<div class="venue-cat-wrapper">
									<p class="venue-desc-title">Categories</p>
									<?php /*
									<p class="cat-select"><?php $category = get_the_category(); ?>
									<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
									*/ ?>
									<?php
									wp_category_checklist(get_the_ID());
									?>
								</div>
								
								<div class="venue-tag-wrapper">
									<p class="venue-desc-title">Tags</p>
									<div class="venue-active-tags">
									<?php
										$posttags = get_the_tags();
										if ($posttags) {
											foreach($posttags as $tag) {
												echo '<span class="venue-tag" data-tag-id="' . $tag->term_id . '" data-tag="' . $tag->name. '">' . $tag->name . '<i class="fa fa-close del" aria-hidden="true"></i></span>'; 
											}
										}
									?>
									</div>
									<input class="tag-search-field" name="tag_search_field" type="text" autocomplete="off" placeholder="&#xf067; TYPE TO SEARCH FOR TAG" style="" data-item-id="Offer <?php echo $post->ID; ?>"/>
									<div class="tag-result"></div>
								</div>
								<span class="remove" data-offer-id="<?php echo get_the_ID(); ?>">Remove Offer<i class="fa fa-trash" aria-hidden="true"></i></span>
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
							<div class="venue-cat-wrapper">
									<p class="venue-desc-title">Categories</p>
									<?php /*
									<p class="cat-select"><?php $category = get_the_category(); ?>
									<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
									*/ ?>
									<?php
									wp_category_checklist();
									?>
								</div>
								
								<div class="venue-tag-wrapper">
									<p class="venue-desc-title">Tags</p>
									<div class="venue-active-tags"></div>
									<input class="tag-search-field" name="tag_search_field" type="text" autocomplete="off" placeholder="&#xf067; TYPE TO SEARCH FOR TAG" style="" />
									<div class="tag-result"></div>
								</div>
							<span class="save new-offer-save">Save Offer<i class="fa fa-check" aria-hidden="true"></i></span>
							
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<h3 class="col-title">Add New Offer <i class="fa fa-plus" aria-hidden="true"></i></h3>
			</div>
		</section>
		
		<div class="dialog" id="dialog" title="dialog">
			<p>Unable to save offer. The following information is still missing:</p>
			<ul></ul>
		</div>
							
		<section class="upcoming-events">
			<h3 class="col-title">Upcoming Events</h3>
			<div class="col-content">
				<!-- <div id="calendar"></div> -->
				<div class="monthly" id="monthly-calendar"></div>
				<?php
				$currentevents = get_posts( array(
					'posts_per_page' => -1,
					'post_type' => 'event',
					'meta_key' => 'date',
					'orderby' => 'meta_value',
					'order' => 'ASC',
					'meta_query' => array(
						array(
							'key'     => 'venue',
							'value'   => get_the_ID(),
							'compare' => '='
						)
					)
				) );
				 
				if ( $currentevents ) {
					foreach ( $currentevents as $post ) :
						setup_postdata( $post ); ?>
						<div class="event-item" data-event-id="<?php echo $post->ID; ?>">
							<div class="left">
								<button class="frontend-button"><i class="fa fa-camera-retro" aria-hidden="true"></i></button>
								<?php if (has_post_thumbnail( $post->ID ) ) { ?>
									<img src="<?php echo the_post_thumbnail_url(); ?>" data-image-id="<?php echo get_post_thumbnail_id( $post_id ); ?>" >
								<?php } else { ?>
									<img src="http://localhost/oot/wp-content/themes/oot/images/oot-placeholder-img.png" class="blank-img" data-image-id="">
								<?php } ?>
							</div>
							<div class="right">
								<h2>
									<label for="event-title" class="control-label no-icon">
										<p class="text-info"><?php the_title(); ?></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
									</label>
									<i class="editevent fa fa-pencil" aria-hidden="true" data-edit-type="expand"></i>
								</h2>
								<div class="event-details">
									<?php
										$date = get_field('date', false, false);
										$date = new DateTime($date);
									?>
									<label for="event-date" class="control-label">
										<p class="text-info"><?php echo $date->format('d/m/Y'); ?></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="date"></i>
									</label>
									<label for="event-time" class="control-label">
										<p class="text-info"><span class="starttime"><?php echo get_field('start_time'); ?></span> - <span class="endtime"><?php echo get_field('end_time'); ?></span></p>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="time"></i>
									</label>
									<label for="event-quantity" class="control-label">
										<?php if( get_field('ticket_price_string') ) { ?>
											<p class="text-info"><?php echo get_field('ticket_price_string'); ?></p>
										<?php } else { ?>
											<p class="text-info"><?php echo '&pound;' . bcdiv(get_field('ticket_price'), '1', 2); ?></p>
										<?php } ?>
										<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
									</label>	
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
												<input type="radio" id="<?php echo get_the_ID() . $value; ?>" data-value="<?php echo $value; ?>" name="event<?php echo get_the_ID(); ?>" <?php if( get_field('repeat_event') == $value ) { echo 'checked'; } ?>/>
												<label for="<?php echo get_the_ID() . $value; ?>"><span></span><?php echo $value; ?></label>
										<?php
											}                             
										}
									?>
								</div>
								<div class="venue-cat-wrapper">
									<p class="venue-desc-title">Categories</p>
									<?php /*
									<p class="cat-select"><?php $category = get_the_category(); ?>
									<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
									*/ ?>
									<?php
									wp_category_checklist(get_the_ID());
									?>
								</div>
								
								<div class="venue-tag-wrapper">
									<p class="venue-desc-title">Tags</p>
									<div class="venue-active-tags">
									<?php
										$posttags = get_the_tags();
										if ($posttags) {
											foreach($posttags as $tag) {
												echo '<span class="venue-tag" data-tag-id="' . $tag->term_id . '" data-tag="' . $tag->name. '">' . htmlentities($tag->name) . '<i class="fa fa-close del" aria-hidden="true"></i></span>'; 
											}
										}
									?>
									</div>
									<input class="tag-search-field" name="tag_search_field" type="text" autocomplete="off" placeholder="&#xf067; TYPE TO SEARCH FOR TAG" style="" data-item-id="Event <?php echo $post->ID; ?>"/>
									<div class="tag-result"></div>
								</div>

								<span class="remove" data-event-id="<?php echo get_the_ID(); ?>">Remove Event<i class="fa fa-trash" aria-hidden="true"></i></span>
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
									<i class="edit fa fa-pencil" aria-hidden="true" data-edit-type="text"></i>
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
							<div class="venue-cat-wrapper">
									<p class="venue-desc-title">Categories</p>
									<?php /*
									<p class="cat-select"><?php $category = get_the_category(); ?>
									<?php wp_dropdown_categories(array('hide_empty' => 0, 'exclude' => '1', 'selected' => $category[0]->term_id )); ?></p>
									*/ ?>
									<?php
									wp_category_checklist();
									?>
								</div>
								
								<div class="venue-tag-wrapper">
									<p class="venue-desc-title">Tags</p>
									<div class="venue-active-tags"></div>
									<input class="tag-search-field" name="tag_search_field" type="text" autocomplete="off" placeholder="&#xf067; TYPE TO SEARCH FOR TAG" style="" />
									<div class="tag-result"></div>
								</div>
							<span class="save new-event-save">Save Event<i class="fa fa-check" aria-hidden="true"></i></span>
							<div class="clearfix"></div> 
						</div>
					</div>
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