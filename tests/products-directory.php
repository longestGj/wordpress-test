<?php
require __DIR__.'/local-only.php';
$hub=(int)get_option('tio2_product_hub');$original=get_post_meta($hub,'_tio2_discovery',true);
$failure=null;
try {
    $missing=$original;unset($missing['not_sure']);update_post_meta($hub,'_tio2_discovery',$missing);
    $guidance=tio2_discovery_data()['not_sure']??[];
    if (!$guidance || !str_contains(do_shortcode('[tio2_grade_results]'),'Start with the full grade directory')) throw new RuntimeException('Missing Not Sure guidance must have an approved fallback.');
    $edited=$original;$edited['not_sure']=['Editor guidance retained.'];update_post_meta($hub,'_tio2_discovery',$edited);
    if (tio2_discovery_data()['not_sure']!==$edited['not_sure']) throw new RuntimeException('Edited guidance was overwritten.');
    $empty=$original;$empty['not_sure']=[];
    if (!is_wp_error(tio2_validate_discovery($empty))) throw new RuntimeException('Empty guidance should be rejected by editor validation.');
    update_post_meta($hub,'_tio2_discovery',$original);
    $html=do_shortcode('[tio2_grade_directory]');
    if (substr_count($html,'data-grade=')!==14 || substr_count($html,'Listed applications')!==14 || substr_count($html,'Key characteristics')!==14) throw new RuntimeException('Each of 14 grades needs comparison fields.');
    foreach ($original['rows'] as $row) {
        $post=get_page_by_path(sanitize_title($row['grade']),OBJECT,'product');
        $fields=tio2_grade_comparison($post->ID);
        $terms=wp_get_object_terms($post->ID,'product_application',['fields'=>'names']);
        if ($fields['applications']!==$terms) throw new RuntimeException('Directory applications differ from product taxonomy: '.$row['grade']);
        if (!str_contains($html,esc_html($row['summary']))) throw new RuntimeException('Existing grade claim changed: '.$row['grade']);
        if (!str_contains($html,esc_url(get_permalink($post)))) throw new RuntimeException('Grade URL missing: '.$row['grade']);
    }
    $cr=get_page_by_path('cr-901',OBJECT,'product');$fields=tio2_grade_comparison($cr->ID);
    if ($fields['process_label']!=='Process descriptor' || $fields['process']!=='Vapor-phase oxidation') throw new RuntimeException('Specialty descriptor was turned into a route taxonomy.');
} catch (Throwable $e) {$failure=$e->getMessage();}
finally {update_post_meta($hub,'_tio2_discovery',$original);}
if ($failure) WP_CLI::error($failure);
WP_CLI::success('14 comparison entries, product relationships, unchanged claims, and missing/edited guidance verified; fixture restored.');
