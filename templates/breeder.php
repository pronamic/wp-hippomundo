<?php

if ( ! isset($result) ) {
    return null;
}

echo '<br />';

echo '<span class="fokker-link">';

if ( $result['breeder'] && isset( $result['breeder_user_details'] ) && $result['breeder_user_details'] ) {

    esc_html_e( 'Breeder:', 'hippomundo' );

    echo ' ';

    $details = $result['breeder_user_details'];

    if ( isset($details['website_url']) && $details['website_url'] ) {
        $url = $details['website_url'];
    } elseif( isset($details['facebook_url']) && $details['facebook_url'] ) {
        $url = $details['facebook_url'];
    } elseif( isset($details['twitter_url']) && $details['twitter_url'] ) {
        $url = $details['twitter_url'];
    } elseif ( isset($details['youtube_url']) && $details['youtube_url'] ) {
        $url = $details['youtube_url'];
    } else {
        $url = null;
    }

    if ( $url ) {
        printf(
            '<a target="_blank" href="%s">%s</a>',
            esc_attr( $url ),
            esc_html( $result['breeder'] )
        );
    } else {
        esc_html( $result['breeder'] );
    }
} else {
	printf(
		'<a target="_blank" href="%s"><img src="%s" alt="Advertise your link here">',
		esc_attr( 'https://www.hippomundo.com/hippomundo-tools#breeder_link' ),
		esc_attr( 'https://www.hippomundo.com/images/info-icon-grey.png' )
	);

    echo ' ';

    esc_html_e( 'Breeder info', 'hippomundo' );

	echo '</a>';
}

echo '<span>';
