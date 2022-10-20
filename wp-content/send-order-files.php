<?php
//SPOSTAMENTO FILE IIOP DA AMBIENTE LOCALE A AMBINTE SIFTEBERTI
    chdir(__DIR__);
    //setup file log
    $logFile=fopen("logFlussiSifte.txt", "a");

    //setup file tracciamento ordini inviati
    //OLD//$sentOrdersFile=fopen("sentOrders.txt", "a+");

    function updateLog($msg){
        global $logFile;
        fwrite($logFile, "[".date("d-m-Y H:i:s")."] ".$msg."\n");
    }

    updateLog("------ Cron invio IIOP iniziato ------");

    //collezionamento di tutti i file iiop in sifte_files
    $iiop_files=glob("./sifteberti_files/*.{IIOP,iiop}", GLOB_BRACE);

    //setup connessione ftp all'ambiente sifteberti
    //se server ha ssl usa sftp
    /*if (isset($_SERVER['HTTPS']) && !strcmp(strval($_SERVER['HTTPS']),'off')==0) {
        //apertura connessione ftp sicura
        //NOTA: la connessione ritorna TRUE anche se il server non è configurato per SSL-FTP o se non ha un certificato valido
        $conn = ftp_ssl_connect("2.228.135.197") or die("Impossibile connettersi all'ambiente SifteBerti");
    }else{*/
        //se non ha ssl connessione con ftp normale
        updateLog("Connessione all'ambiente SifteBerti (31.194.228.105)");
        $conn = ftp_connect("31.194.228.105");
        if(!$conn){
            updateLog("Impossibile connettersi all'ambiente SifteBerti (31.194.228.105), termino operazione");
            die("Impossibile connettersi all'ambiente SifteBerti");
        }
    //}

    //login all'ambiente ftp - in questa fase viene verificato il certificato ssl
    updateLog("Login all'ambiente SifteBerti");
    if (ftp_login($conn, "rugsco", "?r2Us00.GC1mp6x!")){
        ftp_pasv($conn, true);
        //navigazione in dir /in
        updateLog("Navigazione in /in");
        if(ftp_chdir($conn,"/in")){
            //collezionamento lista ordini già inviati
            //OLD//$sentOrders = file_get_contents("./sentOrders.txt");
            //OLD//$sentOrders = explode(",", $sentOrders);
            updateLog("Trovati ".sizeof($iiop_files)." files IIOP da caricare");

            //OLD
                /*if(!in_array(basename($file),$sentOrders)){
                    if(ftp_put($conn, pathinfo($file, PATHINFO_FILENAME).".UP", $file)){
                        ftp_rename($conn, pathinfo($file, PATHINFO_FILENAME).".UP", pathinfo($file, PATHINFO_FILENAME).".IIOP");
                        updateLog("File ".basename($file)." caricato con successo");
                        //scrive nome file in lista ordini inviati
                        fwrite($sentOrdersFile, ",".basename($file));
                    }else{
                        updateLog("Errore nel caricamento di ".$file);
                    }
                }else{
                    updateLog(basename($file)." non è stato caricato in quanto risulta presente nella lista di ordini già inviati");
                }*/

            //upload file in ambiente sifteberti
            //connessione db
	        //$mysqli= new mysqli("80.88.87.121","pi40853j","Cf7Sd9QA5TwUhf33", "pi40853j_pi40853d");
             //$mysqli = new mysqli("127.0.0.1","lonora","u_Mh85879v9A-ibK", "lonora");
             $mysqli = new mysqli("igehigur.mysql.db.internal","igehigur_banklo","zGbZ5FC4Yw!5B-*KN*co", "igehigur_lonbank");

             // verifica connessione
             if ($mysqli -> connect_errno) {
                updateLog("Connessione al DB fallita: " . $mysqli -> connect_error . "(code: ". $mysqli -> connect_error.")");
                fclose($logFile);
                die();
            }else{
                foreach ($iiop_files as $file){
                    //query verifica se ordine è già stato spostato in ambiente sifte
                    $stmt = $mysqli->stmt_init();
                    $sql="SELECT order_number FROM sifte_sent_orders WHERE order_number=?";
                    if(!$stmt->prepare($sql)){//preparazione query
                        updateLog ("Errore Query verifica ordine inviato: ". $mysqli->error);
                    }else{
                        //assemblamento + esecuzione query
                        $basename = basename($file);
                        $stmt->bind_param("s", $basename);
                        $stmt->execute();
                        //raccolta risultati query
                        $result = $stmt->get_result();
                        $row = $result->fetch_array(MYSQLI_NUM);
                        if(!empty($row)){//se gia spedito
                            updateLog(basename($file)." non è stato caricato in quanto risulta presente nella lista di ordini già inviati");
                        }else{
                            //altrimenti lo carica
                            if(ftp_put($conn, pathinfo($file, PATHINFO_FILENAME).".UP", $file)){
                                ftp_rename($conn, pathinfo($file, PATHINFO_FILENAME).".UP", pathinfo($file, PATHINFO_FILENAME).".IIOP");
                                updateLog("File ".basename($file)." caricato con successo");
                                $stmt = $mysqli->stmt_init();
                                $sql="INSERT INTO sifte_sent_orders (order_number)
                                    VALUES (?)";
                                if(!$stmt->prepare($sql)){
                                    updateLog ("Errore Query inserimento record in sifte_sent_orders: ". $mysqli->error);
                                }else{
                                    //scrive nome file in tabella ordini inviati
                                    $basename = basename($file);
                                    $stmt->bind_param("s", $basename);
                                    $stmt->execute();
                                }
                            }else{
                                updateLog("Errore nel caricamento di ".$file);
                            }
                        }
                    }
                }
            }
            $mysqli->close();
        }else{
            ftp_close($conn);
            updateLog("Directory /in non trovata, termino operazione");
            die("Directory /in non trovata");
        }
    }else{
        ftp_close($conn);
        updateLog("Login fallito. Username o password per l'accesso all'ambiente SifteBerti errati, termino operazione");
        die("Username o password per l'accesso all'ambiente SifteBerti errati");
    }
    updateLog("------ Cron invio IIOP concluso ------\n\r");
    //OLD//fclose($sentOrdersFile);
    fclose($logFile);
    ftp_close($conn);
?>
