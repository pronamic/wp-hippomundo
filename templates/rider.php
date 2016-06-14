<?php

printf(
	'<a href="%s" target="_blank">%s</a>',
	esc_attr( $this->get_rider_url( $result['rider_id'] ) ),
	esc_html( $result['rider'] )
);
