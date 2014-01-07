<?php if ( $data instanceof stdClass ): ?>

<div>
    <input type="radio" id="_template1" name="_template" value="image" <?php checked( $data->template, 'image' ); ?> />
    <label for="_template1"><?php _e( 'Image', 'eisma_subscriptions' ); ?></label><br />
    <input type="radio" id="_template2" name="_template" value="text" <?php checked( $data->template, 'text' ); ?> />
    <label for="_template2"><?php _e( 'Text', 'eisma_subscriptions' ); ?></label><br />
    <input type="radio" id="_template3" name="_template" value="video" <?php checked( $data->template, 'video' ); ?> />
    <label for="_template3"><?php _e( 'Video', 'eisma_subscriptions' ); ?></label><br />
</div>

<div class="template-options template-text" style="display: none;">
    <input type="text" name="_text" id="_text" value="<?php echo htmlspecialchars( $data->text ); ?>" />
    <label for="_text"><?php _e( 'Text', 'eisma_subscriptions' ); ?></label>
</div>

<div class="template-options template-video" style="display: none;">
    <input type="text" name="_video_url" id="_video_url" value="<?php echo htmlspecialchars( $data->video_url ); ?>" />
    <label for="_video_url"><?php _e( 'YouTube video URL', 'eisma_subscriptions' ); ?></label>
</div>

<?php endif; ?>