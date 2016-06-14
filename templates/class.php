<tr class="hm-subhead">
	<td>
		<span class="date"><?php echo esc_html( $class['date'] ); ?></span> - 
		<?php echo esc_html( $class['name'] ); ?> : <?php echo esc_html( $class['horses'][0]['sort'] ); ?>
		<?php

		if ( isset( $class['height'] ) && ! empty( $class['height'] ) ) {
			echo esc_html( '(' . $class['height'] . ')' );
		}

		?>
	</td>
</tr>

<?php if ( ! empty( $class['horses'] ) ) : ?>

	<tr>
		<td>
			<table>
				<colgroup class="hm-columns">
					<col />
					<col />
					<col />
					<col />
					<col />
				</colgroup>

				<?php foreach ( $class['horses'] as $result ) : ?>

					<?php include $this->locate_template( 'result' ); ?>

				<?php endforeach; ?>
			</table>
		</td>
	</tr>

<?php endif; ?>
