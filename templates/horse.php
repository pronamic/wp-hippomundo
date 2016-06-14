<?php

// Horse
printf(
	'<a href="%s" target="_blank">%s</a>',
	esc_attr( $this->get_pedigree_url( $result['horse_id'] ) ),
	esc_html( $result['horse_name'] )
);

// Non breaking space
echo ' ';

// Father
printf(
	'<span class="sire">(<a href="%s" target="_blank" class="hm-sire">%s</a>)</span>',
	esc_attr( $this->get_pedigree_url( $result['father_id'] ) ),
	esc_html( $result['father_name'] )
);
