<?php

if ( empty( $result['breeder'] ) ) {
	return;
}

echo '<br />';

echo '<span class="fokker-link">';

esc_html_e( 'Breeder:', 'hippomundo' );

echo ' ';

if ( '0' !== $result['breeder_published'] && ! empty( $result['breeder_website'] ) ) {
	printf(
		'<a target="_blank" href="%s">%s</a>',
		esc_attr( $result['breeder_website'] ),
		esc_html( $result['breeder'] )
	);
} else {
	echo esc_html( $result['breeder'] );

	echo ' '; // Non breaking space

	printf(
		'<a target="_blank" href="%s"><img src="%s" alt="Advertise your link here"></a>',
		esc_attr( 'http://www.hippomundo.com/nl/fokker-link' ),
		esc_attr( 'http://www.hippomundo.com/addons/shared_addons/themes/hippomundo/img/info-icon-grey.png' )
	);
}

echo '<span>';
