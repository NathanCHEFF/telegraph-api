<?php
/* https://telegra.ph/
 *
 *
 *
 */

class filer{
  static function $singleton;
  public $file;

  static function getMe($f){
    if(filler::$singleton===null)filler::$singleton = new filler($f);
    return filler::$singleton;
  }

  function __construct( $file ) {
    $this->file = $file;
  }

  function _getFileData(){
    return file_get_contents( $this->file );
  }

  function _setFileData( $array ) {
    return file_put_contents ( $this->file, $array );
  }

  function _addFileData( $array ){
    $dump = json_decode($this->_getFileData());
    if( !array_intersect_key( $dump, $array ) ) {
      return $this->_setFileData( json_encode( array_merge( $dump, $array ) ) );
    }
    return 0;
  }

  function _clearData( ) {
    return file_put_contents( $this->file, '' );
  }
}

class telegraph {

  const API_ENDPOINT = 'https://api.telegra.ph/';

  protected $token;
  protected $file = 'telegraph.accounts';

  function __construct(){

  }

  function sender($params, $method = null){
    if ( isset( $params['token'] ) and $params['token'] == null ) $params['token'] = $this->token;
    $param = http_build_query($params);
    $url = API_ENDPOINT . ( (!is_null($method) ) ? $method : next( debug_backtrace() )['function'] ). '/' . $param;
    return send($url);
  }

  // short_name (String, 1-32 characters) Required. Account name
  // author_name (String, 0-128 characters) Default author name
  // author_url (String, 0-512 characters) Default profile link,

  // return  returns an Account object with the regular fields
  function createAccount($short, $author, $url = null){

    $param = array ( 'short_name' => $short,
                     'author_name' => $author,
                      'url' => $url
                    );
    return sender($param);
  }

  function editAccountInfo(){
    $param = array ( 'short_name' => $short,
                     'author_name' => $author,
                     'author_url' => $url,
                     'token' => null
                    );
    return sender($param);
  }

  /*
    fields (Array of String, default = [“short_name”,“author_name”,“author_url”])
      List of account fields to return. Available fields: short_name, author_name, author_url, auth_url, page_count.
  */
  function getAccountInfo(){
    $param = array ( 'token' => null, 'fields' => 'short_name, author_name, author_url, auth_url, page_count' )
    return sender($param);
  }

  function revokeAccessToken(){
    $param = array( 'token' => null );
    return sender($param);
  }

  function createPage($title, $content, $return_content = false){
    $param = array( 'token' => null,
                    'short_name' => $this->getShortName(),
                    'author_name' => $this->getAuthorName(),
                    'title' => $title,
                    'content'=>'$content',
                    'return_content'=> $return_content);
    return sender($param);
  }

  function editPage($path, $title, $content){
    $param = array( 'token' => null,
                    'short_name' => $this->getShortName(),
                    'author_name' => $this->getAuthorName(),
                    'path' => $path,
                    'title' => $title,
                    'content'=>$content);
    return sender($param);
  }


  /*
    getPage
      Use this method to get a Telegraph page. Returns a Page object on success.

      path (String)
      Required. Path to the Telegraph page (in the format Title-12-31, i.e. everything that comes after http://telegra.ph/).
      return_content (Boolean, default = false)
      If true, content field will be returned in Page object.
  */
  function getPage($path, $return_content = false){
    $param = compact($path,$return_content,$token = null);
    return sender($param);
  }

  fucntion getPageList($offset, $limit){
    $param = compact($token = null, $offset, $limit);
    return sender($param);
  }

  function getViews($path, $year, $month, $day, $hour){
    $param = compact($path, $year, $month, $day, $hour, $token = null);
    return sender($param);
  }
}
?>
