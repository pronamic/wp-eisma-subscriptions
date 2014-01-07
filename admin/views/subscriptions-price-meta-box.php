<?php if ( $data instanceof stdClass ): ?>

<input type="text" name="_price" value="<?php echo htmlspecialchars( $data->price ); ?>" />

<?php endif; ?>