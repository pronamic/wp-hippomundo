<!-- HTML code for the LRT -->

<div class="hm-lrt">
	<div class="hm-powered">Powered by <a href="https://www.hippomundo.com" target="_blank">Hippomundo</a> ©</div>

	<?php if ( $this->has_title() ) : ?>
	
		<h1 class="hm-title"><?php echo esc_html( $this->get_title() ); ?></h1>

	<?php endif; ?>

	<?php if ( $this->has_subtitle() ) : ?>
	
		<div class="hm-subtitle"><?php echo esc_html( $this->get_subtitle() ); ?></div>

	<?php endif; ?>

	<?php include $this->locate_template( 'editions' ); ?>
</div>
