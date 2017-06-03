
<?php
  $yhendus=new mysqli("localhost", "test", "t3st3r123", "test");
  
  class Uudised{
    private $ab;
    function __construct($yhendus){
      $this->ab=$yhendus;    
    }
    function kysiTutvustused($algus=0, $kogus=3){
       $kask=$this->ab->prepare("SELECT id, pealkiri, sisu FROM uudised_mskorohh
         ORDER BY id DESC");
       $kask->bind_result($id, $pealkiri, $sisu);
       $kask->execute();
       $hoidla=array();
       while($kask->fetch()){
          $uudis=new stdClass();
          $uudis->id=$id;
          $uudis->pealkiri=htmlspecialchars($pealkiri);
          $uudis->sisu=htmlspecialchars($sisu);
          $m=explode(".",$sisu);
          $uudis->algus=htmlspecialchars($m[0]);
          array_push($hoidla, $uudis);
       }
       return $hoidla;
    }
  
  
    function kysiUudis($id){
      $kask=$this->ab->prepare("SELECT pealkiri, sisu FROM uudised_mskorohh WHERE id=?");
      $kask->bind_param("i", $id);
      $kask->bind_result($pealkiri, $sisu);
      $kask->execute();
      if(!$kask->fetch()){
       die("Vigane uudise ID");
      }
      $uudis=new stdClass();
      $uudis->id=$id;
      $uudis->pealkiri=htmlspecialchars($pealkiri);
      $uudis->sisu=htmlspecialchars($sisu);
      return $uudis;
    }
    
    function lisaUudis($pealkiri, $sisu){
      $kask=$this->ab->prepare("INSERT INTO uudised_mskorohh (pealkiri, sisu) VALUES (?, ?)");
      $kask->bind_param("ss", $pealkiri, $sisu);
      $kask->execute();
    }
  }


  class Kommentaarid{
    private $ab, $uudise_id;
    function __construct($yhendus, $uid){
       global $uudised_mskorohh;
       $this->ab=$yhendus;
       $this->uudise_id=$uid;
       $uudised_mskorohh->kysiUudis($uid);
    }
    
    function lisaKommentaar($kommenteerija, $kommentaarisisu){
       $kask=$this->ab->prepare("INSERT INTO kommentaarid 
         (kommenteerija, kommentaarisisu, uudise_id) VALUES (?, ?, ?)");
       $kask->bind_param("ssi", $kommenteerija, $kommentaarisisu, $this->uudise_id);
       $kask->execute();
    }
    
    function kysiKommentaarid(){
      $kask=$this->ab->prepare("SELECT id, kommenteerija, kommentaarisisu 
         FROM kommentaarid WHERE uudise_id=? AND korras=1 ORDER BY id DESC");
      $kask->bind_param("i", $this->uudise_id);
      $kask->bind_result($id, $kommenteerija, $kommentaarisisu);
      $kask->execute();
      $hoidla=array();
      while($kask->fetch()){
        $kommentaar=new stdClass();
        $kommentaar->id=$id;
        $kommentaar->kommenteerija=htmlspecialchars($kommenteerija);
        $kommentaar->kommentaarisisu=htmlspecialchars($kommentaarisisu);
        array_push($hoidla, $kommentaar);        
      }
      return $hoidla;
    }
  }

  function tootleSisend(){  
    global $kommentaariobj;
    global $yhendus;
    if(isSet($_REQUEST["uid"])){
      $kommentaariobj=new Kommentaarid($yhendus, $_REQUEST["uid"]);
    } else {
      return;
    }
    if(isSet($_REQUEST["kommentaarisisestus"])){
      $kommentaariobj->lisaKommentaar($_REQUEST["kommenteerija"], $_REQUEST["kommentaarisisu"]);
      header("Location: $_SERVER[PHP_SELF]?uid=$_REQUEST[uid]");
      exit();
    }
  }

  $uudised_mskorohh=new Uudised($yhendus);
?>