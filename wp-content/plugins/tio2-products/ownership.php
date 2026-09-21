<?php
defined('ABSPATH') || exit;
// Stable project identity, independent from source paths, hashes and versions.
function tio2_owns_page($id,$key,$identity){
    return get_post_type($id)==='page'
        && get_post_meta($id,'_tio2_owner',true)==='tio2-wordpress'
        && get_post_meta($id,$key,true)===$identity;
}
