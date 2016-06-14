<?php

$output = '';

$keys = array(
	'a_fout'   => '',
	'a_tijd'   => '',
	'b_fout'   => '',
	'b_tijd'   => '',
	'one_fout' => '',
	'one_tijd' => '',
);

$values = array_intersect_key( $result, $keys );
$values = array_diff( $values, array( '' ) );

$string = implode( '/', $values );

echo esc_html( $string );

$value = filter_var( $result['prize'], FILTER_VALIDATE_FLOAT );

if ( ! empty( $value ) ) {
	echo '<br />';
	echo esc_html( '€ ' . number_format_i18n( $result['prize'], 0 ) );
}
