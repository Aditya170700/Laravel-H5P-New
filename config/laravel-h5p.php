<?php

/*
 *
 * @Project        laravel-h5p
 * @Copyright      Djoudi
 * @Created        2018-02-20
 * @Filename       laravel-h5p.php
 * @Description
 *
 */

return [
    'H5P_DEV'         => false,
    'language'        => 'en',
    'domain'          => 'http://localhost',
    'h5p_public_path' => '/vendor',
    'slug'            => 'laravel-h5p',
    'views'           => 'h5p', // h5p view path
    'layout'          => 'h5p.layouts.h5p', // layoute path
    'use_router'      => 'ALL', // ALL,EXPORT,EDITOR

    'H5P_DISABLE_AGGREGATION' => false,

    // Content screen setting
    'h5p_show_display_option'    => true,
    'h5p_frame'                  => true,
    'h5p_export'                 => true,
    'h5p_embed'                  => true,
    'h5p_copyright'              => false,
    'h5p_icon'                   => true,
    'h5p_track_user'             => false,
    'h5p_ext_communication'      => true,
    'h5p_save_content_state'     => false,
    'h5p_save_content_frequency' => 30,
    'h5p_site_key'               => [
        'h5p_h5p_site_uuid' => false,
    ],
    'h5p_content_type_cache_updated_at' => 0,
    'h5p_check_h5p_requirements'        => false,
    'h5p_hub_is_enabled'                => true,
    'h5p_version'                       => '1.23.0',

    // Max POST size (bytes or e.g. 128M) for H5P editor file uploads (video, etc.)
    'h5p_editor_max_post_size'          => env('H5P_EDITOR_MAX_POST_SIZE', '128M'),

    // Chunked editor uploads (iframe): many small POSTs then server-side merge into one file for H5P FILES handler.
    'h5p_editor_chunk_upload_enabled'      => env('H5P_EDITOR_CHUNK_UPLOAD_ENABLED', false),
    'h5p_editor_chunk_threshold_bytes'     => (int) env('H5P_EDITOR_CHUNK_THRESHOLD_BYTES', 5242880),
    'h5p_editor_chunk_max_chunk_bytes'     => (int) env('H5P_EDITOR_CHUNK_MAX_CHUNK_BYTES', 5242880),
    'h5p_editor_chunk_max_total_bytes'     => (int) env('H5P_EDITOR_CHUNK_MAX_TOTAL_BYTES', 536870912),
];
