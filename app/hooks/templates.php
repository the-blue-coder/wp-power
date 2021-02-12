<?php

$templates = [
    'homepage' => 'Homepage'

];

add_filter('theme_page_templates', function ($values) use ($templates) {
    foreach ($templates as $slug => $label) {
        $values[$slug . '.php'] = $label;
    }
    
    return $values;
});

foreach ($templates as $slug => $label) {
    customTemplatePartLoading($slug . '.php');
}