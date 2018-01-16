<?php


function mw_feeds_node_update($node){

}

function mw_feeds_node_insert($node){

}

function mw_feeds_form_alter(&$form, &$form_state, $form_id) {
  switch($form_id){
    case 'campaign_node_form':
      //ALWAYS set to true. This how we know it's an update from the form not the feed.
      $form['field_feeds_update_flag']['und'][0]['value']['#default_value'] = 1;
      $form['field_feeds_update_flag']['und'][0]['value']['#attributes']['READONLY'] = 'READONLY';


      $form['#validate'][] = 'mw_feeds_project_id_validate';

      break;
    default:
      break;
  }
}

function mw_feeds_project_id_validate(&$form, &$form_state){
  $project_id = false;
  $project_id = @$form_state['values']['field_projectnumber']['und'][0]['value'];;
  if($project_id){
    //example duplicate MW-540-28738-001
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'campaign')
      ->propertyCondition('status', NODE_PUBLISHED)
      ->fieldCondition('field_projectnumber', 'value', $project_id, '=')
      ->range(0, 10)
      ->addMetaData('account', user_load(1));

    $nid = @$form_state['build_info']['args'][0]->nid;
    if($nid && $nid > 0){
      $query->entityCondition('entity_id', $nid, '!=');
    }
    $result = $query->execute();
    if(count($result['node']) > 0){
      return form_set_error('field_projectnumber', t('This Project Number is already in use. Project Number must be unique across all Campaigns'));
    }
  }
}

function mw_feeds_node_postupdate($node) {
  watchdog('grid', 'post update ran');
  if(@$node->field_feeds_update_flag['und'][0]['value'] != '1'){
    //exit to prevent infinite loop
    watchdog('grid', 'post update exited to prevent infinite loop');
    return;
  }

  if($node->type == 'campaign' && $node->status == 1){
    watchdog('grid', 'post update ran');
    watchdog('grid', print_r($node, true));
    $projectNumber = @$node->field_projectnumber['und'][0]['value'];
    if($projectNumber){
      watchdog('grid', 'project number found');
      //1. This node was likely not inserted by feeds and therefore is not in the feeds_item table.
      //We need to save it there in order for this import to do squat diddle.
      //HACKAGE! this ensures that feeds will udpate this node. Without this it would just ignore the node.
      db_query("insert into feeds_item (id,entity_type, entity_id, guid, hash, feed_nid, url)
      select 'json_update_node_by_project_id', 'node', n.nid,
      :projectnumber, '', 0, ''
      from node as n
      left outer join feeds_item as b
      on (n.nid = b.entity_id and b.id = 'json_update_node_by_project_id')
      where b.entity_id is null and n.nid=:nid;", array(':projectnumber' => $projectNumber, ':nid' => $node->nid));

      //2. manually run import, with custom URL parameter and auth headers.
      $importer_id = 'json_update_node_by_project_id';
      $source = feeds_source($importer_id);
      $fetcher_config = $source->getConfigFor($source->importer->fetcher);
      $fetcher_config['source'] = 'https://app18.workamajig.com/api/beta1/projects?id=' . $projectNumber;// . '-001';
      $source->setConfigFor($source->importer->fetcher, $fetcher_config);
      $source->save();
      watchdog('feeds_source', print_r($source, true));
      $source->startImport();
    }else{
      watchdog('grid', 'project number not found.');
    }
  }
}
