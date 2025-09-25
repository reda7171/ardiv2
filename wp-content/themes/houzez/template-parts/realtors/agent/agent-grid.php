<?php
global $post, $houzez_local;
$agent_position = get_post_meta( get_the_ID(), 'fave_agent_position', true );
$languages = get_post_meta( get_the_ID(), 'fave_agent_language', true );
$properties = Houzez_Query::agent_properties_count( $post->ID );
?>
<div class="agent-grid-container">
	<div class="agent-grid-wrap d-flex flex-column align-items-center" role="article" aria-labelledby="agent-name-<?php echo get_the_ID(); ?>">	
		<div class="agent-grid-image-wrap p-4 text-center">
			<a class="agent-grid-image mx-auto mb-4" href="<?php the_permalink(); ?>">
				<?php 
				$image_size = houzez_get_image_size_for('agent_profile');
				if( has_post_thumbnail() && get_the_post_thumbnail() != '' ) {
					the_post_thumbnail($image_size, array('class' => 'img-fluid img-circled'));
				} else {
					houzez_image_placeholder( $image_size );
				}
				?>
				<?php get_template_part('template-parts/realtors/agent/verified'); ?>
			</a>
			<h2 id="agent-name-<?php echo get_the_ID(); ?>"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
			<?php if( $agent_position != '' ) { ?>
			<div class="agent-list-position" role="text"><?php echo esc_attr($agent_position); ?></div>
			<?php } ?>
		</div>
		<div class="agent-grid-content-wrap d-flex flex-column align-items-center text-center p-3 w-100">
			<ul class="agent-list-contact list-unstyled" role="list">
				<?php if( ! empty($properties) ) { ?>
				<li class="agent-listings-count" role="listitem"><?php echo $houzez_local['properties']?>: <strong><?php echo esc_attr($properties); ?></strong></li>
				<?php } ?>

				<?php if( !empty( $languages ) ) { ?>
				<li class="agent-languages-list" role="listitem"><?php echo $houzez_local['languages']; ?>: <strong><?php echo esc_attr( $languages ); ?></strong></li>
				<?php } ?>
			</ul>
			<a class="btn btn-primary-outlined w-100" href="<?php the_permalink(); ?>">
				<?php echo $houzez_local['view_profile']; ?></a>
		</div>
	</div>
</div>