<?php
$attachments = get_post_meta(get_the_ID(), 'fave_attachments', false);
$documents_download = houzez_option('documents_download');
?>
<div class="property-description-wrap property-section-wrap" id="property-description-wrap" role="region">
	<div class="block-wrap">
		<div class="block-title-wrap">
			<h2><?php echo houzez_option('sps_description', 'Description'); ?></h2>	
		</div>
		<div class="block-content-wrap">
			<div class="property-description-content">
				<div class="description-content">
					<?php 
					// Get the raw post content without any filters applied
					global $post;
					$content = $post->post_content;
					
					// Check if there's a more tag
					if ( strpos( $content, '<!--more-->' ) !== false ) {
						// Split content at the first more tag only
						$more_pos = strpos( $content, '<!--more-->' );
						$content_before_more = substr( $content, 0, $more_pos );
						$content_after_more = substr( $content, $more_pos + 11 ); // 11 is length of <!--more-->
						
						// Remove any additional more tags from the after content
						$content_after_more = str_replace( '<!--more-->', '', $content_after_more );
						
						// Apply content filters to both parts
						$content_before_more = apply_filters( 'the_content', $content_before_more );
						$content_after_more = apply_filters( 'the_content', $content_after_more );
						
						// Create the more link
						$more_link_text = __( 'Read More', 'houzez' );
						$more_link = '<p><a href="#" class="houzez-read-more-link" onclick="this.style.display=\'none\'; this.parentNode.nextElementSibling.style.display=\'block\'; return false;">' . $more_link_text . '</a></p>';
						
						// Output the content with read more functionality
						echo $content_before_more;
						echo $more_link;
						echo '<div class="houzez-more-content" style="display: none;">' . $content_after_more . '</div>';
					} else {
						// No more tag, just display the content normally
						echo apply_filters( 'the_content', $content );
					}
					?>
				</div>
			</div>

			<?php 
			if(!empty($attachments) && $attachments[0] != "" ) { ?>
				<div class="block-title-wrap block-title-property-doc">
					<h3><?php echo houzez_option('sps_documents', 'Property Documents'); ?></h3>
				</div>

				<?php 
				foreach( $attachments as $attachment_id ) {
					$attachment_meta = houzez_get_attachment_metadata($attachment_id); 

					if(!empty($attachment_meta )) {
					?>
					<div class="property-documents mt-2" role="list">
                        <div class="d-flex justify-content-between" role="listitem">
							<div class="property-document-title">
                                <i class="houzez-icon icon-task-list-plain-1 me-1" aria-bs-hidden="true"></i> <?php echo esc_attr( $attachment_meta->post_title ); ?>
							</div>
							<div class="property-document-link<?php echo ($documents_download == 1 && !is_user_logged_in()) ? ' login-link' : ''; ?>">
								<?php if( $documents_download == 1 ) {
				                    if( is_user_logged_in() ) { ?>
				                    <a href="<?php echo esc_url( $attachment_meta->guid ); ?>" download="<?php echo esc_attr( $attachment_meta->post_title ); ?>" rel="noopener"><?php esc_html_e( 'Download', 'houzez' ); ?> <i class="houzez-icon icon-download-bottom ms-2" aria-bs-hidden="true"></i></a>
				                    <?php } else { ?>
				                        <a href="#" data-bs-toggle="modal" data-bs-target="#login-register-form"><?php esc_html_e( 'Download', 'houzez' ); ?> <i class="houzez-icon icon-download-bottom ms-2" aria-bs-hidden="true"></i></a>
				                    <?php } ?>
				                <?php } else { ?>
				                    <a href="<?php echo esc_url( $attachment_meta->guid ); ?>" download="<?php echo esc_attr( $attachment_meta->post_title ); ?>" rel="noopener"><?php esc_html_e( 'Download', 'houzez' ); ?> <i class="houzez-icon icon-download-bottom ms-2" aria-bs-hidden="true"></i></a>
				                <?php } ?>
							</div>
						</div>
					</div>
				<?php } }?>
				
			<?php } ?>
		</div>
	</div>
</div>