<?php
    chdir(__DIR__);
    //setup file log
    $logFile=fopen("logFlussiSifte.txt", "a");

    function updateLog($msg){
        global $logFile;
        fwrite($logFile, "[".date("d-m-Y H:i:s")."] ".$msg."\n");
    }

    updateLog("------ Cron ricezione EOP iniziato ------");


    //setup connessione
    updateLog("Connessione all'ambiente SifteBerti (31.194.228.105)");
    $conn = ftp_connect("31.194.228.105");
    if(!$conn){
        updateLog("Impossibile connettersi all'ambiente SifteBerti (31.194.228.105), termino operazione");
        die("Impossibile connettersi all'ambiente SifteBerti");
    }

    //login all'ambiente ftp
    updateLog("Login all'ambiente SifteBerti");
    if (ftp_login($conn, "rugsco", "?r2Us00.GC1mp6x!")){
        ftp_pasv($conn, true);
        //navigazione in dir /out
        updateLog("Navigazione in /out");
        if(ftp_chdir($conn,"/out")){
            $eop_files = ftp_nlist($conn, "/out");
            updateLog("File trovati in /out: ".sizeof($eop_files));
        }else{
            ftp_close($conn);
            updateLog("Directory /out non trovata, termino operazione");
            die("Directory /out non trovata");
        }
    }else{
        ftp_close($conn);
        updateLog("Login fallito. Username o password per l'accesso all'ambiente SifteBerti errati, termino operazione");
        die("Username o password per l'accesso all'ambiente SifteBerti errati");
    }

    //trasferisce tutti gli eop in /out in /temp_eop in ambiente locale
    foreach($eop_files as $file){
        $currExt = explode(".", $file);
        $currExt = array_pop($currExt);
        if( strcmp(strval($currExt), "EOP")==0||strcmp(strval($currExt), "eop")==0 ){
            ftp_get($conn, "./temp_eop/".basename($file), $file, FTP_BINARY);
            updateLog("download di ".basename($file)." in /temp_eop in ambiente locale");
        }
    }

    //collezionamento nomi file iiop
    $iiop_files=glob("./sifteberti_files/*.{IIOP,iiop}", GLOB_BRACE);
    for($i=0; $i<sizeof($iiop_files); $i++){
        $iiop_files[$i] = array_pop(explode("_",pathinfo($iiop_files[$i], PATHINFO_FILENAME)));

    }

    //controlla se eop contiene n.ordine = nome iiop
    $eop_files=glob("./temp_eop/*.{EOP,eop}", GLOB_BRACE);
    foreach($eop_files as $currEop){
        $eop_content=file_get_contents($currEop);
        foreach($iiop_files as $iiop_name){
            if(strpos($eop_content, $iiop_name)!==FALSE){
                updateLog("Ordine RU_".$iiop_name." risulta elaborato, procedo a spostare il relativo IIOP in /sifte_spediti e cancellare il relativo EOP dall'ambiente Sifteberti");
                //muove file da cartella sifteberti_files a spediti_sifte
                copy("./sifteberti_files/RU_".$iiop_name.".IIOP", "./sifte_spediti/RU_".$iiop_name.".IIOP");
                unlink("./sifteberti_files/RU_".$iiop_name.".IIOP");
                //cancella eop da ftp sifteberti
                ftp_delete($conn,"/out/".basename($currEop));
            }
        }
    }
    updateLog("------ Cron ricezione EOP concluso ------\n\r");
    fclose($logFile);
    ftp_close($conn);
?>
