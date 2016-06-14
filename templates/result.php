<tr>
	<td class="rank">
		<?php echo esc_html( $this->format_rank( $result['place'] ) ); ?>
	</td>
	<td>
		<?php include $this->locate_template( 'horse' ); ?>
		<?php include $this->locate_template( 'breeder' ); ?>
	</td>
	<td>
		<?php include $this->locate_template( 'rider' ); ?>
	</td>
	<td class="hm-small">
		<?php include $this->locate_template( 'flag' ); ?>
	</td>
	<td class="hm-prize">
		<?php include $this->locate_template( 'prize' ); ?>
	</td>
</tr>
