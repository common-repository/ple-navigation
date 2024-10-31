//-----------------------------------------------------------------------------
/*
  this file will get loaded by ple-navigation.php
    it contains the ajax support
  developed by Marcel Goor (http://www.mediaculture.nl) and René Ade
*/   
//-----------------------------------------------------------------------------

// globals
var ple_navigation_xmlHttp = null;
var ple_navigation_currentId = 0;

// update a list by loading the url and searching for the list
function ple_navigation_ChangeList( id, url ) { 
  
  // get xmlhttp object
  ple_navigation_xmlHttp = ple_navigation_GetXmlHttpObject();

  // check if xmlhttp is supported
  if( ple_navigation_xmlHttp==null ) {
    location.href = url; // refresh page to url
    return
  }

  // reset if needed
  if( ple_navigation_currentId!=0 ) {
    ple_navigation_xmlHttp.abort();
    ple_navigation_currentId = 0;
  }

  // lock
  ple_navigation_currentId = id;

  // set receiver function
  ple_navigation_xmlHttp.onreadystatechange = ple_navigation_StateChanged;
  
  // send request
  ple_navigation_xmlHttp.open( "GET", url, true );
  ple_navigation_xmlHttp.send( null );
}

// get only the list of a pages content
function ple_navigation_GetContent( content, id )
{
  // find start of the list
  var startpos;
  startpos = content.indexOf( "<!-- ple_navigation_"+id+" -->" ); // search for this comment
  startpos = content.indexOf( "<div", startpos ); // search for the open-div start after the comment
  startpos = content.indexOf( ">", startpos ); // search for the open-div end after open-div start

  // find end
  var endpos;
  endpos = content.indexOf( "<!-- /ple_navigation_"+id+" -->", startpos ); // find the close comment after the position of the div-open

  // cut out the list with the div-close
  content = content.slice( startpos + 1, endpos );

  // find the closing div for also cutting it out
  endpos = content.lastIndexOf( "</div>" ); // find the last </div> (the div-close of the list)

  // cut out the div-close
  content = content.slice( 0, endpos );
  
  // return the list
  return content;
}

// parse content if received
function ple_navigation_StateChanged() {

  // if completely received
  if( ple_navigation_xmlHttp.readyState==4 || ple_navigation_xmlHttp.readyState=="complete") {
  
    // change the content of the list-div to the new list content found in the response text
    document.getElementById("ple-navigation-"+ple_navigation_currentId).innerHTML
      = ple_navigation_GetContent( ple_navigation_xmlHttp.responseText, ple_navigation_currentId );
  }
}

// get xmlhttp object
function ple_navigation_GetXmlHttpObject() {

  // check if allready available
  if( ple_navigation_xmlHttp!=null )
    return ple_navigation_xmlHttp;

  // try to get the object
  try {
    // Firefox, Opera 8.0+, Safari
    ple_navigation_xmlHttp = new XMLHttpRequest();
  }
  catch( e ) {
    //Internet Explorer
    try {
      ple_navigation_xmlHttp = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      ple_navigation_xmlHttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
  }
  
  // return the object
  return ple_navigation_xmlHttp;
}