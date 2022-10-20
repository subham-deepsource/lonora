<?php
    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    //setup file log
    $logFile=fopen("logFlussiSifte.txt", "a");

    function updateLog($msg){
        global $logFile;
        fwrite($logFile, "[".date("d-m-Y H:i:s")."] ".$msg."\n");
    }

    //acquisizione codice destinazione
    $addressId=checkUserAddressId($data);
    //creazione file deserializzato ad uso interno
    completeDeserializationJson($data);

    //controllo per bonifico bancario
    if(
        (strcmp(strval($data['payment_method']),"bacs")==0&&!strcmp(strval($data['status']),"completed")==0) ||
        (strcmp(strval($data['payment_method']),"paypal")==0&&!strcmp(strval($data['status']),"processing")==0)||
        (strcmp(strval($data['payment_method']),"stripe")==0&&!strcmp(strval($data['status']),"processing")==0)
    ){
        die();
    }

    //creazione file iiop per sifteberti
    $iiop_sifte=fopen("./sifteberti_files/RU_".adaptStrLength(strval($data['id']),8,"0",STR_PAD_LEFT).".IIOP", "a+") or die("error creating sifteberti's output iiop file");

    //setup variabili
    //space filler
    $spaceFiller="";
    for($i=0;$i<414;$i++){
        $spaceFiller .= " ";
    }
    //partita iva
    $pIva=adaptStrLength($data['meta_data']['2']['value'],11," ",STR_PAD_RIGHT);
    if(empty($pIva)||!isset($pIva)||is_null($pIva)){$pIva="           ";}
    //codice fiscale
    $codFisc=adaptStrLength($data['meta_data']['3']['value'],16," ",STR_PAD_RIGHT);
    if(empty($codFisc)||!isset($codFisc)||is_null($codFisc)){$codFisc="                ";}
    //azienda
    $azienda=strval($data['billing']['company']);
    if(empty($azienda)||!isset($azienda)||is_null($azienda)){$azienda="";}else{$azienda=" - ".$azienda;}

    $ragioneSocEstesa=strval($data['billing']['first_name'])." ".strval($data['billing']['last_name']).$azienda;

    fwrite($iiop_sifte,
        "00MSBVCRU".
        str_pad(strval($data['id']),8,"0",STR_PAD_LEFT).
        "            P&S   ".
        str_replace("-","",array_shift(explode("T",strval($data['date_created'])))).
        "        0500".
        "001   ".
        "                              ".
        str_pad(strval($data['customer_id']),20," ",STR_PAD_LEFT).
        adaptStrLength($ragioneSocEstesa,40," ",STR_PAD_RIGHT).
        "               ".
        adaptStrLength($data['billing']['city'],50," ", STR_PAD_RIGHT).
        adaptStrLength($data['billing']['address_1']." ".$data['billing']['address_2'],50," ",STR_PAD_RIGHT).
        adaptStrLength($data['billing']['postcode'],10," ",STR_PAD_RIGHT).
        adaptStrLength($data['billing']['state'],10," ",STR_PAD_RIGHT).
        adaptStrLength($data['billing']['country'],40," ",STR_PAD_RIGHT).
        adaptStrLength($data['billing']['phone'],15," ",STR_PAD_RIGHT).
        "               ".
        adaptStrLength($data['billing']['email'],40," ", STR_PAD_RIGHT).
        $pIva.
        $codFisc.
        adaptStrLength(strval($addressId), 20, " ", STR_PAD_RIGHT).
        adaptStrLength($ragioneSocEstesa,40," ",STR_PAD_RIGHT).
        "               ".
        adaptStrLength($data['shipping']['city'],50," ",STR_PAD_RIGHT).
        adaptStrLength($data['shipping']['address_1']." ".$data['shipping']['address_2'],50," ",STR_PAD_RIGHT).
        adaptStrLength($data['shipping']['postcode'],10," ",STR_PAD_RIGHT).
        adaptStrLength($data['shipping']['state'],10," ",STR_PAD_RIGHT).
        adaptStrLength($data['shipping']['country'],40," ",STR_PAD_RIGHT).
        adaptStrLength($data['billing']['phone'],15," ",STR_PAD_RIGHT).
        "               ".
        adaptStrLength($data['billing']['email'],40," ",STR_PAD_RIGHT).
        $pIva.
        $codFisc.
        adaptStrLength("GLS",20," ",STR_PAD_RIGHT).
        adaptStrLength("GLS",40," ",STR_PAD_RIGHT).
        "               ".
        adaptStrLength("",50," ",STR_PAD_RIGHT).
        adaptStrLength("",50," ",STR_PAD_RIGHT).
        adaptStrLength("",10," ",STR_PAD_RIGHT).
        adaptStrLength("",10," ",STR_PAD_RIGHT).
        adaptStrLength("",40," ",STR_PAD_RIGHT).
        adaptStrLength("",15," ",STR_PAD_RIGHT).
        "               ".
        adaptStrLength("",40," ",STR_PAD_RIGHT).
        $pIva.
        $codFisc.
        $spaceFiller.
        adaptStrLength("",8,"0",STR_PAD_LEFT).
        adaptStrLength("",3,"0",STR_PAD_RIGHT).
        "EUR\n"
    );
    if(!empty($data["customer_note"])) {
        $progressivo_identificativo = 1;
        foreach(explode("\n", str_replace("\r", "\n", $data["customer_note"])) as $note_row) {
            $note_row = trim($note_row);
            if(strlen($note_row) === 0) {
                continue;
            }
            fwrite($iiop_sifte,
                "01". // 1 001-002 9(2) Tipo record
                "M". // 2 003-003 X(1) Azione
                "SBVC". // 3 004-007 X(4) Cod. Sito
                "RU". // 4 008-009 X(2) Proprietà = RU
                str_pad(strval($data["id"]), 8, "0", STR_PAD_LEFT)."            ". // 5 010-029 X(20) Codice ordine
                "0001". // 6 030-033 9(4) Identificativo Nota
                str_pad(strval($progressivo_identificativo), 4, "0", STR_PAD_LEFT). // 7 034-037 9(4) Progr. Su identificativo
                "T". // 8 038-038 X(1) Tipo Nota
                adaptStrLength($note_row, 255, " ", STR_PAD_RIGHT). // 9 039-293 X(255) Testo nota
                "N". // 10 294-294 X(1) Stampa su lista prelievo
                "N". // 11 295-295 X(1) Stampa su packing list A4
                "S". // 12 296-296 X(1) Stampa su DDT
                "\n"
            );
            $progressivo_identificativo++;
        }
    }
    fclose($iiop_sifte);

    $spaceFiller="";
    for($i=0;$i<120;$i++){
        $spaceFiller .= " ";
    }

    $iiop_sifte=fopen("./sifteberti_files/RU_".str_pad(strval($data['id']),8,"0",STR_PAD_LEFT).".IIOP", "a+") or die("error creating sifteberti's output iiop file");
    //note di riga
    foreach($data['line_items'] as $record => $val){
        fwrite($iiop_sifte,
            "02MSBVCRU".
            str_pad(strval($data['id']),8,"0",STR_PAD_LEFT).
            "            ".
            str_replace("-","",array_shift(explode("T",strval($data['date_created'])))).
            "        "."                              ".
            adaptStrLength(strval(intval($record)+1), 4, "0", STR_PAD_LEFT).
            str_pad(strval($val['sku']),25," ",STR_PAD_RIGHT).
            "** ".
            " BUO".
            "     ".
            "     ".
            str_pad(strval($val['quantity']),7,"0",STR_PAD_LEFT).
            "000".$spaceFiller."\n"
        );
    }

    fclose($iiop_sifte);

    /**
     * adatta la lunghezza della stringa passata in @param string alla lunghezza specificata in @param needed_length,
     * eventualmente aggiungendo a destra i caratteri necessari per raggiungere la lunghezza indicata. Tali caratteri sono passati in @param filler_char
     * se la stringa è più lunga del numero specificato in @param needed_length verrà restituita la prima parte della stringa, di lunghezza @param needed_char
     * @param string -> stringa da adattare
     * @param needed_length -> lunghezza della stringa finale
     * @param filler_container -> carattere usato per adattare la stringa
     */
    function adaptStrLength($string, $needed_length, $filler_char, $dir=STR_PAD_LEFT){
        if(isset($string)&&isset($needed_length)&&isset($filler_char)){
            if(empty($string)||is_null($string)){
                for($i=0;$i<$needed_length;$i++){
                    $string .= $filler_char;
                }
                return $string;
            }
            $original_locale = setlocale(LC_CTYPE, 0);
            setlocale(LC_CTYPE, "it_IT");
            $string = iconv("UTF-8", "ASCII//TRANSLIT", $string);
            setlocale(LC_CTYPE, $original_locale);
            if(strlen($string)==($needed_length)){return $string;}
            if(strlen($string)>$needed_length){
                return substr($string,0,$needed_length);
            }
            if(strlen($string)<$needed_length){
                return str_pad($string,$needed_length,$filler_char,$dir);
            }
            return $string;
        }else{
            for($j=0;$j<$needed_length;$j++){
                $string .= $filler_char;
            }
            return $string;
        }
    }

    /**
     * esegue la deserializzazione completa del json passato in @param data
     * @param data -> decoded json
     * @param path -> cartella di destinazione file output, includere "/" finale. Utilizzare un absolute path (es "./cartella/") (default "./json-orders/")
     * @param name -> prefisso nome file, verrà seguito dall'id dell'ordine (es. name="esempio_", id="12340" -> "esempio_12340") (default "ordine")
     * @param ext -> estensione file, includere il "." (default ".txt")
     */
    function completeDeserializationJson($data, $path="./json_orders/", $name="ordine-", $ext=".txt"){

        $output_file = fopen($path.$name.$data["id"].$ext, "w") or die("error creating the output file");

        if(!isset($data)||is_null($data)||empty($data)){
            fwrite($output_file,"invalid data");
            fclose($output_file);
            die("invalid data");
            return;
        }

        foreach($data as $key => $value) {
            fwrite($output_file,$key." -> ".$value."\n");

            if(strcmp(strval($value),"Array")==0){
                foreach($value as $arrk => $arrval) {
                    fwrite($output_file,"\t (+)".$arrk." -> ".$arrval."\n");

                    if(strcmp(strval($arrval),"Array")==0){
                        foreach($arrval as $arrk2 => $arrval2) {
                            fwrite($output_file,"\t\t (++)".$arrk2." -> ".$arrval2."\n");

                            if(strcmp(strval($arrval2),"Array")==0){
                                foreach($arrval2 as $arrk3 => $arrval3) {
                                    fwrite($output_file,"\t\t\t (+++)".$arrk3." -> ".$arrval3."\n");

                                    if(strcmp(strval($arrval3),"Array")==0){
                                        foreach($arrval3 as $arrk4 => $arrval4) {
                                            fwrite($output_file,"\t\t\t\t (++++)".$arrk4." -> ".$arrval4."\n");
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        fclose($output_file);
    }

    /**
     * controlla se esiste già il dato cliente/indirizzo, se esiste ritorna l'id in del dato, altrimenti aggiunge il dato e lo restituisce
     * @param data -> decoded json
     * @param path -> cartella di destinazione file output, includere "/" finale. Utilizzare un absolute path (es "./cartella/") (default "./")
     * @param file_name -> nome del file in cui verranno registrati i dati utente/indirizzo (default "address_table")
     * @param ext -> estensione file, includere il "." (default ".txt")
     * @return id_indirizzoCliente
     */
    function checkUserAddressId($data, $path="./", $file_name="address_table", $ext=".txt"){
        //var contenente file per log
        global $logFile;

        //dati da json decodificato
        $user_id=$data['customer_id'];
        $name=$data['shipping']['first_name'];
        $surname=$data['shipping']['last_name'];
        $company=$data['shipping']['company'];
        $address_1=$data['shipping']['address_1'];
        $address_2=$data['shipping']['address_2'];
        $cap=$data['shipping']['postcode'];
        $city=$data['shipping']['city'];
        $state=$data['shipping']['state'];

        /* //OLD -> uso txt per cod.dest

        if(!file_exists($path.$file_name.$ext)){//se file non esiste lo crea
            fopen($path.$file_name.$ext, "a");
            fclose($path.$file_name.$ext);
        }

        //stringa indirizzo
        //formato: idIndirizzoGenerato§idUtente#nome#cognome#società#indirizzo1#indirizzo2#cap#città#provincia(nuova riga)
        $shipping=$user_id."#".$name."#".$surname."#".$company."#".$address_1."#".$address_2."#".$cap."#".$city."#".$state."\r\n";

        $contents = file($path.$file_name.$ext);//array -> ogni riga del file è un record
        $foundIndex=-1; //indice dell'indirizzo, se già presente in file (-1 -> default, non esiste nel file)
        $currIndex=0;   //indice del record indirizzo corrente in foreach sottostante
        $lastIndex=0;   //ultimo indice scansionato in foreach sottostante

        //loop tra tutte le righe del file
        foreach($contents as $line) {
            $currIndex++;

            $splitLine=explode("§",$line);
            $lastIndex=$splitLine[0];

            if(strcmp(strval($splitLine[1]), strval($shipping))==0){
                $foundIndex=$currIndex;
            }

        }

        //azioni di registrazione e restituzione valore
        if(intval($foundIndex)!=-1){
            return $foundIndex;
        }else{
            $fileTable = fopen($path.$file_name.$ext, "a");
            fwrite($fileTable, (intval($lastIndex)+1)."§".$shipping);
            fclose($fileTable);
            return intval($lastIndex)+1;
        }*/

        //NEW -> usa tabella db lonora
        //connessione db
        //$conn= new mysqli("127.0.0.1","lonora","u_Mh85879v9A-ibK", "lonora");
        $conn = new mysqli("igehigur.mysql.db.internal","igehigur_banklo","zGbZ5FC4Yw!5B-*KN*co", "igehigur_lonbank");

        // verifica connessione
        if ($conn -> connect_errno) {
            updateLog("Connessione al DB fallita: " . $conn -> connect_error . "(code: ". $conn -> connect_error.")");
            fclose($logFile);
            die();
        }else{
            //query per cercare se cod destinazione esiste
            $stmt = $conn->stmt_init();
            $sql="SELECT id FROM sifte_address_table WHERE id_cliente=? AND nome_cliente=? AND cognome_cliente=? AND azienda_cliente=? AND indirizzo_1_cliente=? AND indirizzo_2_cliente=? AND cap_cliente=? AND citta_cliente=? AND stato_cliente=?";
            if(!$stmt->prepare($sql)){//preparazione query
                updateLog ("Errore Query ricerca cod. destinazione(id): ". $conn->error);
            }else{
                //assemblamento + esecuzione query
                $stmt->bind_param("isssssiss",$user_id, $name, $surname, $company, $address_1, $address_2, $cap, $city, $state);
                $stmt->execute();
                //raccolta risultati query
                $result = $stmt->get_result();
                $row = $result->fetch_array(MYSQLI_NUM);
                if(sizeof($row)>0){
                    //se esiste ritorna id (cod. destinazione)
                    //updateLog(strval($row[0]));
                    $conn->close();
                    fclose($logFile);
                    return strval($row[0]);
                }else{
                    //altrimenti lo inserice (nessun risultato trovato in tabella)
                    $stmt = $conn->stmt_init();
                    $sql="INSERT INTO sifte_address_table (id_cliente, nome_cliente, cognome_cliente, azienda_cliente, indirizzo_1_cliente, indirizzo_2_cliente, cap_cliente, citta_cliente, stato_cliente)
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    if(!$stmt->prepare($sql)){
                        updateLog ("Errore Query inserimento record in sifte_address_table: ". $conn->error);
                    }else{
                        $stmt->bind_param("isssssiss",$user_id, $name, $surname, $company, $address_1, $address_2, $cap, $city, $state);
                        $stmt->execute();
                    }
                    //ritorna id record inserito
                    $sql = "SELECT MAX(id) FROM sifte_address_table";
                    $result= $conn->query($sql);
                    if(!$result){
                        updateLog ("Errore Query select ultimo id tabella sifte_address_table (per cod. destinazione) ". $conn->error);
                    }else{
                        if($result->num_rows>0){
                            updateLog(strval(mysqli_fetch_row($result)[0]));
                            $conn->close();
                            fclose($logFile);
                            return strval(mysqli_fetch_row($result)[0]);
                        }else{
                            updateLog ("Errore nel ritorno dell'ultimo id tabella sifte_address_table (per cod. destinazione) ". $conn->error);
                        }
                    }
                }
            }
        }
        $conn->close();
        fclose($logFile);
    }


?>