<h3> init </h3>
<?php
    //TEST AGGORNAMENTO GIACENZE -> eliminare se non si utilizza
    $mysqli = new mysqli("80.88.87.121","pi40853j","Cf7Sd9QA5TwUhf33", "pi40853j_pi40853d");
	// verifica connessione
    if ($mysqli -> connect_errno) {
        die();
    }
	$result= $mysqli->query("SELECT sku_id,stock_qty,stock_status FROM next_stock_update_table");
	// verifica connessione
    if ($result===false) die("errore lettura tabella next_stock_update_table");
	$i=0;//contatre proditti aggiornati
	//loop tra i risultati
	if ($result->num_rows > 0) {
        ?>
        <script>var prods=[];</script>
        <?php
		while($row = $result->fetch_assoc()) {
            ?>
            <script>
                prods.push("<?php echo $row['sku_id']."|".$row['stock_qty']."|".$row['stock_status'];?>");
            </script>
            <?php
		}
	}
    $mysqli->close();
?>
<h3>end 1</h3>
<div id="try">

</div>
<script>
    for(var i=0; i<prods.length;i++){
        var currProd=prods[i];
        currProd=currProd.split("|");

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("try").innerHTML += this.responseText;
            }
        };
        xhttp.open("GET", "https://www.lonora.it/stockupdater?id="+currProd[0]+"&qty="+currProd[1]+"&status="+currProd[2]+"&actkey=provaupdate", false);
        xhttp.send();
    }
</script>
