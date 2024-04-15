<?php
include('storage.php');

class CardStorage extends Storage {
  public function __construct() {
    parent::__construct(new JsonIO('cards.json'));
  }

  function makeopts(){ 
    $types = ['normal','fire','water','electric','grass','ice','fighting','poison','ground','psychic','bug','rock','ghost','dark','steel','fairy'];
     
    $default= (isset($_POST['type'])) ? $_POST['type'] : 'none';
    foreach($types as $value) { 
        if($value === $default){ 
            $sel = ' selected' ;
        }  
        else{ 
            $sel = '' ;
        } 
        echo "<option value='".$value."' ".$sel.">". ucfirst($value) ."</option>\n"; 
    }
  }
  
  public function findByOwner($uid) {
    return $this->findMany(function ($card) use ($uid) {
        return $card['owner'] === $uid;
    });
  }
  
}
?>