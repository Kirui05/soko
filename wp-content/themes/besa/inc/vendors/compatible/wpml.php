<?php

if (! function_exists('besa_add_language_to_storage_key')) {
    function besa_add_language_to_storage_key( $storage_key )
    {
      global $sitepress;

      return $storage_key . '-' . $sitepress->get_current_language();
    }
}
add_filter( 'besa_storage_key', 'besa_add_language_to_storage_key', 10, 1 );