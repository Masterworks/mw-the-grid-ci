Please note, this module requires the "run in background" option to be set or else it will not run the feed.

Here is the original importable feed this module was used for, in case the one in the database is compromised at some point in the future:

$feeds_importer = new stdClass();
$feeds_importer->disabled = FALSE; /* Edit this to true to make a default feeds_importer disabled initially */
$feeds_importer->api_version = 1;
$feeds_importer->id = 'json_update_node_by_project_id';
$feeds_importer->config = array(
  'name' => 'JSON Update Node by Project ID',
  'description' => 'JSON Update Node by Project ID',
  'fetcher' => array(
    'plugin_key' => 'FeedsHTTPFetcherAppendHeaders',
    'config' => array(
      'append_headers' => 'APIAccessToken|bcx8R4uwQK+mdk6+Cn607wK9dMabVWVF0IsMoK0aIqPHwxeujj2sI9d+xEJl/i3eA4jxGUXG5XnsmHceDbAYvw==
UserToken|ivsv6Eib5sK8lok5rW4rZLskpE5QW6n9dOc6e11x4RGxqrlWTWTjAscMUKjkKKJALFAqHWMxCGX5qHsLtHzJIw==',
      'request_timeout' => '',
    ),
  ),
  'parser' => array(
    'plugin_key' => 'FeedsExJsonPath',
    'config' => array(
      'sources' => array(
        'projectnumber' => array(
          'name' => 'projectNumber',
          'value' => 'projectNumber',
          'debug' => 1,
          'weight' => '2',
        ),
        'officename' => array(
          'name' => 'officeName',
          'value' => 'officeName',
          'debug' => 1,
          'weight' => '3',
        ),
        'projectfullname' => array(
          'name' => 'projectFullName',
          'value' => 'projectFullName',
          'debug' => 1,
          'weight' => '4',
        ),
        'feeds_update_flag' => array(
          'name' => 'Feeds Update Flag',
          'value' => 'feeds_update_flag',
          'debug' => 1,
          'weight' => '5',
        ),
      ),
      'context' => array(
        'value' => '$.data.project[*]',
      ),
      'display_errors' => 0,
      'source_encoding' => array(
        0 => 'auto',
      ),
      'debug_mode' => 0,
    ),
  ),
  'processor' => array(
    'plugin_key' => 'FeedsNodeProcessor',
    'config' => array(
      'expire' => '-1',
      'author' => '1',
      'authorize' => 1,
      'mappings' => array(
        0 => array(
          'source' => 'projectnumber',
          'target' => 'guid',
          'unique' => 1,
          'language' => 'und',
        ),
        1 => array(
          'source' => 'projectnumber',
          'target' => 'field_projectnumber',
          'unique' => FALSE,
          'language' => 'und',
        ),
        2 => array(
          'source' => 'officename',
          'target' => 'field_officename',
          'unique' => FALSE,
          'language' => 'und',
        ),
        3 => array(
          'source' => 'projectfullname',
          'target' => 'title',
          'unique' => FALSE,
          'language' => 'und',
        ),
        4 => array(
          'source' => 'feeds_update_flag',
          'target' => 'field_feeds_update_flag',
          'unique' => FALSE,
        ),
      ),
      'insert_new' => '0',
      'update_existing' => '2',
      'update_non_existent' => 'skip',
      'input_format' => 'plain_text',
      'skip_hash_check' => 1,
      'bundle' => 'campaign',
      'language' => 'und',
    ),
  ),
  'content_type' => '',
  'update' => 0,
  'import_period' => '-1',
  'expire_period' => 3600,
  'import_on_create' => 1,
  'process_in_background' => 1,
);
