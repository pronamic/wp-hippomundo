<tr class="hm-head">
	<td>
		<?php echo esc_html( $edition['competition_name'] . ' ' . $edition['name'] . ' ' . $edition['discipline'] ); ?>
	</td>
</tr>

<?php foreach ( $edition['classes'] as $class ) : ?>

	<?php include $this->locate_template( 'class' ); ?>

<?php endforeach; ?>
