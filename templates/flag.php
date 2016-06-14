<?php

if ( empty( $result['iso_code'] ) ) {
	return;
}

printf(
	'<img src="http://www.hippomundo.com/img/blank.gif" class="flag flag-%s" title="%s" />',
	esc_attr( strtolower( $result['iso_code'] ) ),
	esc_attr( $result['country'] )
);
