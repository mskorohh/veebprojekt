
<?php
  require("abifunktsioonid.php");
  tootleSisend();
  $loetelu=$uudised_mskorohh->kysiTutvustused();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Loodusuudised</title>
<link href="kujundus.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="header">
	<h1>Loodusuudised</h1>
	<h2>Kodutöö raames</h2>
</div>
<div id="content">
	<div id="colOne">
		<div id="menu">
			<ul>
			 
                          <?php
                            echo "<li><a href='$_SERVER[PHP_SELF]'>avaleht</a></li>";
                            foreach($loetelu as $rida){
                              echo "<li><a href= 
'$_SERVER[PHP_SELF]?uid=$rida->id'>$rida->pealkiri</a></li>";
                            }
                          ?>
			</ul>
		</div>
	</div>
	<div id="colTwo">
         <?php
          if(!isSet($_REQUEST["uid"])){
           foreach($loetelu as $rida){
            echo "<div class='post'>
                 <h2>$rida->pealkiri</h2>
                 <p>
                   $rida->algus;
                   <br />
                   <a href='$_SERVER[PHP_SELF]?uid=$rida->id'>Loe edasi ...</a>
                 </p>
                </div>
             ";
           }
          } else {
            $rida=$uudised_mskorohh->kysiUudis($_REQUEST["uid"]);
            echo  "
              <div class='post'>
                 <h2>$rida->pealkiri</h2>
                 <p>
                   $rida->sisu
                 </p>
              </div>
              <div class='post'>
                <form action='$_REQUEST[PHP_SELF]' method='post'>
                 <input type='hidden' name='uid' value='$rida->id' />
                 <h2>Lisa kommentaar</h2>
                 Kommenteerija nimi:<br />
                 <input type='text' name='kommenteerija' class='sisestus'/><br />
                 Kommentaari sisu: <br />
                 <textarea name='kommentaarisisu' class='sisestus'></textarea>
                 <br />
                 <input type='submit' name='kommentaarisisestus' value='Sisesta' />
                 <input type='submit' name='kommentaarikatkestus' value='katkesta' />                 
                </form>
              </div>
            ";
            $kommentaarimassiiv=$kommentaariobj->kysiKommentaarid();
            foreach($kommentaarimassiiv as $kommentaar){
              echo "
                <div class='post'>
                   <h2>$kommentaar->kommenteerija</h2>
                   <p>
                     $kommentaar->kommentaarisisu
                   </p>
                </div>
              ";
            }
          }
         ?>
		</div>
</div>
<div id="footer">
	<p>Copyright &copy; 2006 Below the Horizon. Designed by <a href="http://freecsstemplates.org"><strong>Free CSS Templates</strong></a></p>
</div>
<p>
 <a href="http://validator.w3.org/check?uri=referer">
  <img src="http://www.w3.org/Icons/valid-xhtml10" alt="Valid XHTML 1.0 Strict" height="31" width="88" />
 </a>
</p>
</body>
</html>