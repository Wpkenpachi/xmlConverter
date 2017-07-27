<?php
$data = json_decode(file_get_contents("php://input"), true);
$default_dir = __DIR__ . '/files/';
$file = isset($_FILES['xmlfile']) ? $_FILES['xmlfile'] : null;
$xml_file = null;
if($file && (strpos($file['type'], 'xml') !== false )){
    $file_dir = $default_dir . $file['name'] .date('Y-m-d');
    if(move_uploaded_file($file['tmp_name'], $file_dir)){
        $contents = file_get_contents($file_dir);
        $xml_file = simplexml_load_string($contents);
        //exit($xml_file);
    }
}else{
    {   
        $file_dir = 'files/file_uploaded';
        unlink($file_dir);
        $fb = fopen($file_dir, "a");
        fwrite($fb, $data['data']);
        fclose($fb);
        $data_file = file_get_contents($file_dir);
        
    }
    {
        $data = simplexml_load_string($data_file, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($data);
        $array = json_decode($json, true);
        $parsed = isset($array['Worksheet']['Table']['Row']) ? $array['Worksheet']['Table']['Row'] : $array['Worksheet'][0]['Table']['Row'];
        //var_dump($array['Worksheet'][0]['Table']);die;
        /*echo "
        <!-- Jquery -->
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js'></script>

        <!-- Latest compiled and minified CSS -->
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css'>

        <!-- Optional theme -->
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css'>

        <!-- Latest compiled and minified JavaScript -->
        <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js'></script>

        <script src='https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js'></script>
        ";*/
        $toShow = '';
        $HEADER = '';
        $toShow .= "<table class='table table-striped'";
        $num = count($parsed[0]['Cell']);
        $toShow .= "<tr>";
        for($i = 0; $i < $num; $i++):
            $HEADER[] = $parsed[0]['Cell'][$i]['Data'];
            $toShow .= "<th> {$parsed[0]['Cell'][$i]['Data']} </th>";
        endfor;
        $toShow .= "</tr>";
        $DATAS = '';
        for($i = 1; $i < count($parsed); $i++):
        $toShow .= "<tr>";
            for($j = 0; $j < count($parsed[$i]['Cell']); $j++){
                $DATAS[] = $parsed[$i]['Cell'][$j]['Data'];
                $toShow .= "<td> {$parsed[$i]['Cell'][$j]['Data']} </td>";
            }
        $toShow .= "</tr>";
        endfor;
        $toShow .= "</table>";
    }
    {
        //exit(json_encode(array_merge([], ['headers' => $HEADER], ['data' => $DATAS]), JSON_UNESCAPED_UNICODE));
        exit($toShow);
    }
}
?>