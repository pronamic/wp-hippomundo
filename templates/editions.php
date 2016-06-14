<?php

$editions = $this->get_editions();

if ( empty( $editions ) ) : ?>

	<div class="alert alert-info"><?php esc_html_e( 'No results.', 'hippomundo' ); ?></div>

<?php else : ?>

	<table>

		<?php foreach ( $editions as $edition ) : ?>

			<?php include $this->locate_template( 'edition' ); ?>

		<?php endforeach; ?>

	</table>

	<div style="text-align: center;">
		<p class="hm-powered">Powered by <a href="" target="_blank">Hippomundo</a> ©</p>
	</div>

<?php endif; ?>
