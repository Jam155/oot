<section id="content" role="main">
	<?php
		$current_user = wp_get_current_user();
	?>
	<p>Logged in as: <?php echo $current_user->user_login; ?> (id: <?php echo $current_user->ID; ?>)</p>
	
	<?php if( current_user_can('administrator') ) {
		$args = array(
			'posts_per_page'   => -1,
			'orderby'          => 'date',
			'order'            => 'DESC',
			'post_type'        => 'venue',
			'post_status'      => 'publish'
		);
		$posts_array = get_posts( $args ); ?>
		<?php if( $posts_array ): ?>
			<p>Admins can view all venues:</p>
			<ul class="home-venue-list">
			<?php foreach( $posts_array as $post): ?>
				<?php setup_postdata($post); ?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php wp_reset_postdata();?>
		<?php endif; ?>	
		
	<?php } else { ?>
	<?php $userVenues = get_field('users_venues', 'user_'.$current_user->ID); ?>
		<?php if( $userVenues ): ?>
			<p>You can edit the following venues:</p>
			<ul class="home-venue-list">
			<?php foreach( $userVenues as $post):?>
				<?php setup_postdata($post); ?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</li>
			<?php endforeach; ?>
			</ul>
			<?php wp_reset_postdata();?>
		<?php endif; ?>	
	<?php } ?>
</section>