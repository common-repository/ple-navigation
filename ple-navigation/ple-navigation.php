<?php
//-----------------------------------------------------------------------------
/*
Plugin Name: PostLists-Extension Navigation
Version: 3.0.2
Plugin URI: http://www.rene-ade.de/inhalte/wordpress-plugin-postlists-extension-navigation.html
Description: PostLists Extension to add prev- and next- navigation links to a list (with ajax support)
Author: <a href="http://www.rene-ade.de" target="_blank">Ren&eacute; Ade</a> and <a href="http://www.mediaculture.nl" target="_blank">Marcel Goor</a>
*/
//-----------------------------------------------------------------------------
?>
<?php

//-----------------------------------------------------------------------------

// get var name
function ple_navigation_varname( $args ) {
  return 'ple-navigation-'.$args['id'];
}

//-----------------------------------------------------------------------------

// overwrite offset
function ple_navigation_args( $args ) {

  // check if offset ist set
  $varname = ple_navigation_varname( $args );
  if( !array_key_exists($varname,$_GET) )
    return $args;
    
  // set new offset
  $args['offset'] = (int)$_GET[$varname];
  return $args;
}

//-----------------------------------------------------------------------------

// add placeholders
function ple_navigation_placeholders( $placeholders, $post, $args ) {

  // only list placeholders
  if( $post )
    return $placeholders;
    
  // add placeholders
  $placeholders[] = 'ple_navigation_next_link';
  $placeholders[] = 'ple_navigation_prev_link';
  $placeholders[] = 'ple_navigation_next_url'; 
  $placeholders[] = 'ple_navigation_prev_url';  
  $placeholders[] = 'ple_navigation_next_offset';     
  $placeholders[] = 'ple_navigation_prev_offset';    
  $placeholders[] = 'ple_navigation_variable';

  return $placeholders;
}

// create link
function ple_navigation_placeholdervalue_url( $args, $newoffset ) {
 
  // get var name
  $varname = ple_navigation_varname( $args );
  // get file url
  $url = substr($_SERVER['REQUEST_URI'],0,strpos($_SERVER['REQUEST_URI'],'?'));
  
  // get all get vars
  $getvars = $_GET;
  
  // add or overwrite offset var
  $getvars[ $varname ] = $newoffset;
  
  // create var string
  $varstring = '';
  foreach( $getvars as $getvar_name=>$getvar_value ) {
    if( strlen($varstring)==0 )
      $varstring .= '?';
    else
      $varstring .= '&';    
    $varstring .= $getvar_name.'='.$getvar_value;
  }
  
  // create url
  $url = $url.$varstring;
  
  // ajax support
  if( $args['ple_navigation_ajax'] ) {
    $url = 'JavaScript:onClick=ple_navigation_ChangeList(\''.$args['id'].'\',\''.$url.'\');';
  }
  
  // return url
  return $url;
}

// placeholder values
function ple_navigation_placeholdervalue( $value, $name, $args, $posts, $post ) {

  // only list placeholders
  if( $post )
    return $value;
  
  // navigation placeholders
  switch( $name ) {
    case 'ple_navigation_variable':
      return ple_navigation_varname( $args );
    case 'ple_navigation_next_offset':
      return ( $args['offset']+$args['numberposts'] );
    case 'ple_navigation_prev_offset':
      return ( $args['offset']-$args['numberposts'] );
    case 'ple_navigation_next_url':
      return ple_navigation_placeholdervalue_url( $args, $args['offset']+$args['numberposts'] );
    case 'ple_navigation_prev_url':
      return ple_navigation_placeholdervalue_url( $args, $args['offset']-$args['numberposts'] );
    case 'ple_navigation_next_link':
      if( count($posts)>=$args['numberposts'] )
        return pl_getstring( $args['ple_navigation_next_link'], $args, $posts, $post );   
      else 
        return '';
    case 'ple_navigation_prev_link':
      if( $args['offset']>0 )
        return pl_getstring( $args['ple_navigation_prev_link'], $args, $posts, $post );         
      else 
        return '';
  }
  
  // not a navigation placeholder
  return $value;    
}

// get placeholder description
function ple_navigation_placeholderdescription( $description, $placeholdername, $inpost ) {

  // only list placeholders
  if( $inpost )
    return $description;
  
  // navigation placeholders
  switch( $placeholdername ) {
    case 'ple_navigation_variable':
      return 'the the name of the get-variable to overwrite the offset of this list';
    case 'ple_navigation_next_offset':
      return 'the offset to get the posts after the last post of this list';
    case 'ple_navigation_prev_offset':
      return 'the offset to get the posts before the first post of this list';
    case 'ple_navigation_next_url':
      return 'the url to this list showing the posts after the last post of this list';
    case 'ple_navigation_prev_url':
      return 'the url to this list showing the posts before the first post of this list';
    case 'ple_navigation_next_link':
      return 'only if the number of posts to display was reached, the next-link you defined in the "navigation next-link" field (you need to configure that field and can use %ple_navigation_next_url% there)';
    case 'ple_navigation_prev_link':
      return 'only if needed, the prev-link you defined in the "navigation prev-link" field (you need to configure that field and can use %ple_navigation_prev_url% there)';
  }
  
  // not a navigation placeholder
  return $description;    
}

//-----------------------------------------------------------------------------

// add fields
function ple_navigation_fields( $fields ) {

  // get types
  $types = pl_admin_data_getfields_gettypes();

  // add link string fields
  $fields['ple_navigation_next_link'] = array(
    'description'=>'Navigation next-link',
    'type'=>$types['string'],
    'expert'=>false,
    'placeholders'=>'list'
  );
  $fields['ple_navigation_prev_link'] = array(
    'description'=>'Navigation prev-link',
    'type'=>$types['string'],
    'expert'=>false,
    'placeholders'=>'list'
  );
  
  // ajax support
  $fields['ple_navigation_ajax'] = array(
    'description'=>'Navigation AJAX Support',
    'type'=>$types['boolean'],
    'expert'=>false
  );

  // return fields
  return $fields;
}

//-----------------------------------------------------------------------------

// wrap output for ajax support
function ple_navigation_list( $output, $args, $ple_getlist ) {

  // check if ajax support is active
  if( !$args['ple_navigation_ajax'] )
    return $output;
    
  // check output
  if( !is_string($output) )
    return $output;
    
  // check if output allready added
  if( strpos($output,'<!-- ple_navigation_'.$args['id'].' -->')!==false )
    return $output;  
    
  // add start and end output
  $output = '<!-- ple_navigation_'.$args['id'].' -->'
           .'<div id="ple-navigation-'.$args['id'].'">'
           .$output
           .'</div>'
           .'<!-- /ple_navigation_'.$args['id'].' -->';

  // return output
  return $output;
}

//-----------------------------------------------------------------------------

function ple_navigation_getpluginfolderurl() {
  return WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),'',plugin_basename(__FILE__)); 
}

// load ajax js
function ple_navigation_init() {

  // load ajax script
  wp_enqueue_script( 'ple_navigation_ajax',ple_navigation_getpluginfolderurl().'js/'.'ajax.js' );
}

//-----------------------------------------------------------------------------

// filters
add_filter( 'ple_args',                   'ple_navigation_args',                   5, 1 );
add_filter( 'ple_placeholders',           'ple_navigation_placeholders',           5, 3 );
add_filter( 'ple_placeholdervalue',       'ple_navigation_placeholdervalue',       5, 5 );
add_filter( 'ple_placeholderdescription', 'ple_navigation_placeholderdescription', 5, 3 );
add_filter( 'ple_fields',                 'ple_navigation_fields',                 5, 1 );
add_filter( 'ple_list',                   'ple_navigation_list',                   9, 3 );
// action
add_action( 'ple_init',                   'ple_navigation_init',                   5, 0 );

//-----------------------------------------------------------------------------

?>