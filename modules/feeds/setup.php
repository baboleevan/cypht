<?php

if (!defined('DEBUG_MODE')) { die(); }

handler_source('feeds');
output_source('feeds');

/* servers page data */
add_handler('servers', 'load_feeds_from_config',  true, 'feeds', 'load_user_data', 'after');
add_handler('servers', 'process_add_feed', true, 'feeds', 'load_feeds_from_config', 'after');
add_handler('servers', 'add_feeds_to_page_data', true, 'feeds', 'process_add_feed', 'after');
add_handler('servers', 'save_feeds',  true, 'feeds', 'add_feeds_to_page_data', 'after');
add_output('servers', 'add_feed_dialog', true, 'feeds', 'content_section_start', 'after');
add_output('servers', 'display_configured_feeds', true, 'feeds', 'add_feed_dialog', 'after');
add_output('servers', 'feed_ids', true, 'feeds', 'page_js', 'before');

add_handler('ajax_hm_folders', 'load_feeds_from_config',  true, 'feeds', 'load_user_data', 'after');
add_handler('ajax_hm_folders', 'load_feed_folders',  true, 'feeds', 'load_feeds_from_config', 'after');
add_handler('ajax_hm_folders', 'add_feeds_to_page_data', true, 'feeds', 'load_feeds_from_config', 'after');
add_output('ajax_hm_folders', 'filter_feed_folders',  true, 'feeds', 'folder_list_content', 'before');

/* message list page */
add_handler('message_list', 'load_feeds_from_config', true, 'feeds', 'load_user_data', 'after');
add_handler('message_list', 'add_feeds_to_page_data', true, 'feeds', 'load_feeds_from_config', 'after');
add_output('message_list', 'feed_message_list', true, 'feeds', 'content_section_start', 'after');
add_output('message_list', 'feed_ids', true, 'feeds', 'page_js', 'before');

/* combined inbox */
add_handler('ajax_feed_combined_inbox', 'login', false, 'core');
add_handler('ajax_feed_combined_inbox', 'load_user_data', true, 'core');
add_handler('ajax_feed_combined_inbox', 'load_feeds_from_config',  true);
add_handler('ajax_feed_combined_inbox', 'feed_combined_inbox',  true);
add_handler('ajax_feed_combined_inbox', 'date', true, 'core');
add_output('ajax_feed_combined_inbox', 'filter_feed_combined_inbox', true);

return array(

    'allowed_pages' => array(
        'ajax_feed_combined_inbox'
    ),

    'allowed_post' => array(
        'feed_server_ids' => FILTER_SANITIZE_STRING,
        'submit_feed' => FILTER_SANITIZE_STRING,
        'new_feed_name' => FILTER_SANITIZE_STRING,
        'new_feed_address' => FILTER_SANITIZE_STRING
    )
);

?>
