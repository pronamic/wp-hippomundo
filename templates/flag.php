<?php

if ( empty( $result['iso_code'] ) ) {
	return;
}

printf(
	'<img src="https://www.hippomundo.com/images/blank.gif" class="flag flag-%s" title="%s" />',
	esc_attr( strtolower( $result['iso_code'] ) ),
	esc_attr( $result['country'] )
);
