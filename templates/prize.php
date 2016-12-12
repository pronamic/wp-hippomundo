<?php

$output = '';

$string = $this->plugin->formatScore($result);

echo esc_html( $string );

$value = filter_var( $result['prize'], FILTER_VALIDATE_FLOAT );

if ( ! empty( $value ) ) {
	echo '<br />';
	echo esc_html( '€ ' . number_format_i18n( $result['prize'], 0 ) );
}
