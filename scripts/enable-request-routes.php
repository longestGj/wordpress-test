<?php
/** Enable the three receiver destinations only on a local, verified installation. */
require '/workspace/tests/local-only.php';
$privacy=get_page_by_path('privacy-policy',OBJECT,'page');
if (!$privacy || !tio2_owns_page($privacy->ID,'_tio2_page_id','LEGAL-PRIV-EN') || !str_contains($privacy->post_content,'The quotation, document and sample request forms collect')) WP_CLI::error('Update the owned Privacy Policy before enabling forms.');
$thank=get_page_by_path('thank-you',OBJECT,'page');
if (!$thank || !tio2_owns_page($thank->ID,'_tio2_page_id','CONV-THANK') || get_post_status($thank)!=='publish') WP_CLI::error('Owned Thank You Page is not ready.');
$ids=[];
foreach (tio2_request_page_ids() as $kind=>$identity) {
    $post=get_page_by_path(trim(tio2_request_paths()[$kind],'/'),OBJECT,'page');
    if (!$post || !tio2_owns_page($post->ID,'_tio2_page_id',$identity) || get_post_status($post)!=='publish' || post_password_required($post)) WP_CLI::error('Request Page is not ready: '.$identity);
    if (!str_contains($post->post_content,'[tio2_request_form kind="'.$kind.'"]')) WP_CLI::error('Missing form shortcode: '.$identity);
    $ids[$kind]=$post->ID;
}
$options=get_option('tio2_targets',[]);
foreach ($ids as $kind=>$id) { $options[$kind]=$id; $options[$kind.'_ready']=true; }
update_option('tio2_targets',$options);
foreach ($ids as $kind=>$id) if (tio2_target_url($kind)!==get_permalink($id)) WP_CLI::error('Route verification failed: '.$kind);
WP_CLI::success('Three request routes enabled on this local installation.');
