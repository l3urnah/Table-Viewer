<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');


class MehPage {
    private $property1;
    private $property2;
    private $property3;
    private $property4;
    private $property5;

    public function setProperty1($value) {
        $this->property1 = $value;
    }

    public function setProperty2($value) {
        $this->property2 = $value;
    }

    public function setProperty3($value) {
        $this->property3 = $value;
    }

    public function setProperty4($value) {
        $this->property4 = $value;
    }

    public function setProperty5($value) {
        $this->property5 = $value;
    }

    public function getProperty1() {
        return $this->property1;
    }

// weil wir auch ein standard objekt übergeben 
// können wir nicht unsere methode rekursiv damit aufrufen
// sondern brauchen eine wrapper function 


// heisst das wenn ich eine objekt klasse übernehme die 
// member mitbringe, kann ich die mit this ansprechen im ersten ?

// todo: wo hört diese klasse auf und eine andere wird übergeben 
// wo sollen die transofrmationen stattfinden 
// files loaden 
// check content and create title -> davon abhängig die formatter 


// ordner mit datum und was auch immer an beschreibung im namen 
// cron führt script aus, das neuen ordner findet und schaut ob terminal file 
// darin unverarbeitet ist --> erzeugt die einzelnen files in dem ordner 
// recap, results und was sonst noch von interesse ist (meta info wie host, gather facts, usw )
// unreachables - info wie anzahl und welchen objekte kommen vor 
// switch configs kommen regelmässig automatisch --> mit cron gitten 

// ordner inhalt nach größe und timestamp sortiert verarbeiten
// größtes jüngstes file zu erst -> erzeugen der einzelnen teile als files die angezeigt werden 
    
    // todo: example method call that literally iterates the object on itself.. 
    // rather call iterateObject and handover external object or reference a single local property 
    // this objects properties should only be the external data that will be 
    // transformed for our needs to display 
    public function iterateProperties() {
        $this->iterateObject($this);
    }

    // maybe make this "overloadable" with variable number of args
    // for ($i = 0; $i < func_num_args(); $i++) {
        // fixed header creates wrapping table 
    public function makeTableHead($firstHead, $secondtHead, $thirdHead) {
        echo "
        <table style='background: beige;'><tr><th>$firstHead</th><th>$secondtHead</th><th>$thirdHead</th></tr>
        ";
    }

    private function makeNestedTableHead() {
        echo "
        <table>";
        
    }
  
    // Create Page 

   //todo: funktion erweitern um titel aus file content zu extrahieren 
    private function makePageTitel() {$title = "Table Viewer"; return $title;}

    private function makeFaviconLines() {$lines = '<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">'; return $lines;}

    public function pageTop() {
        $title = $this->makePageTitel();
        echo "<!DOCTYPE html>
        <html>
        <head>
        <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css'>
        
        <title>  $title </title>
        ";
        echo $this->makeFaviconLines();
        echo "
        </head>
        <body>
        <!--<h1> $title </h1>-->
        ";
    }

    
    public function pageEnd() {echo "
        </body>
        </html>";}



    // Table Rows

    // use function overloading to make this function take as many td as you want 
    // and let it create the lines accordingly 
    
    public function makeRowWrapper() {
        $this->beginRow();

        for ($i = 0; $i < func_num_args(); $i++) {
           // printf("Argument %d: %s\n", $i, func_get_arg($i));$this->makeRow($value);
           $this->makeRowData(func_get_arg($i));
        }

        $this->endRow();
    }

    public function makeRowData($value) {$row = "
        <td>$value</td>
        "; 
        echo $row;}

    public function beginRow() {$row = "
        <tr>
        "; 
        echo $row;}


    public function endRow() {$row = "
        </tr>
        "; 
        echo $row;}

    public function beginNestedTable($key, $value) {$row = "
        <tr>
        <td> 
        $key 
        </td>
        <td>$value
        <table border='1'>
        "; 
        echo $row;}




    public function endNestedTable(){$row = "
        </table>
        </td>
        </tr>
        ";
        echo $row;
    }

    public function makeTableFoot(){echo "</table>";}


    // Iterator - create Table

    // die funktion wird bisher von der wrapper function aufgerufen und bekommt die klasse selbst übergeben 
    // iteriert somit über die eigenproperties 
    // public damit von aussen mit beliebigen objektdaten aufrufbar 

    public function iterateObject($obj) {

        foreach ($obj as $key => $value) {
            if (is_object($value) || is_array($value)) {

                $this->beginNestedTable("wn:".$key, "Node");

                //foreach ($value as $key => $nestedvalue) {
                //    $this->makeRowWrapper($key, $nestedvalue);
                //}
                $this->iterateLvl1($value);

                $this->endNestedTable();
                
            } else {
                
                $this->makeRowWrapper("w:".$key, $value);

            }
        }
    }

// keine funktion soll sich jemals selbst aufrufen - dis is de way
        //$iwashere = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'];
        //if ( __FUNCTION__ === $iwashere) {}
// kein dirty flag business - nur foreach für arrays und key value ausgabe 
// mehr als drei ebenen gibts halt nicht und wenn mehr dann halt erweitern 
// ist sicher sauberer als dieser "egal wie tief das objekt ist, wir geben es aus" ansatz 
// das kann nix -> zurück zu den lvl1 bis 3 iteratoren 
// jeder caller ist für das tags schließen verantwortlich, nicht der callee
// und leider kann man auch nicht mehr einfach durch alles durchiterieren auch wenn es 
// in ein array mündet, weil dafür ist keine node erzeugung gefragt und somit ist es nicht 
// das gleiche und kann nicht von der gleichen function gerendert werden


// wenn array übergeben wird, dann ohne array index ausgeben 

    private function iterateLvl1($obj) {
        if (is_array($obj)) {
            $this->arrayiterator2($obj); 
            } else {
                foreach ($obj as $key => $value) {
 //                   if (is_array($value)) {
 //                       $this->arrayiterator2($value); 
 //                       } // wenn arrays im ersten node ausgegeben werden sollen dann uncomment
                        // grundsätzlich erzeugen arrays in values einen neuen node und ausgabe über arraiterator
                    if (is_object($value) || is_array($value)) {
                        $this->beginNestedTable("xn:".$key, "Node");

                        $this->iterateLvl2($value);

                        $this->endNestedTable();
                    } else {
                        $this->makeRowWrapper("x:".$key, $value);
                        
                    }
                }
            }
        }

    private function iterateLvl2($obj) {
        if (is_array($obj)) {
            $this->arrayiterator2($obj); 
        } else {
            foreach ($obj as $key => $value) {
                    if (is_object($value) || is_array($value)) {
                        $this->beginNestedTable("yn:".$key, "Node");

                        $this->iterateLvl3($value);

                        $this->endNestedTable();
                    } else {
                        $this->makeRowWrapper("y:".$key, $value);
                        
                    }
                }
            }
        }
    
    private function iterateLvl3($obj) {
        if (is_array($obj)) {
            $this->arrayiterator2($obj); 
        } else {
        foreach ($obj as $key => $value) {
            if (is_object($value) || is_array($value)) {
                //$this->iterateObjectLvl4($value);
                echo "<br>Object has more than three levels...";
            } else {$this->makeRowWrapper("z:".$key, $value);}
            }
        }
    }

    // tatüütaaa - der arrayiterator ruft sich selbst auf wenn er ein array findet was gegen die regel verstößt
    // und erzeugt somit keinen neuen node sondern zeigt eine imploded version des arrays an
    // das ist nicht schön aber es funktioniert und ist nicht besser als die alternative aber einfacher zu implementieren
    // #codepolizei
    // korrekte Lösung wäre vom Caller her zu prüfen ob er nested values übergeben will und dann stattdessen entsprechend eine neue liste
    // zu erzeugen auf die man dann in dem node verlinken kann - weil sehen will das eh niemand auf einer page 

    private function arrayiterator2($value) {
        foreach($value as $key => $arraymember) {
            if (is_array($arraymember)) {
                $this->arrayiterator2($arraymember); 
            } else {
                $this->makeRowWrapper("a:".$arraymember); 
            }
        }
    }

    private function arrayIterator($value) {
         // Array, iterate over its members
         $this->beginRow("Array Name: " , $key );
         foreach ($value as $itemKey => $itemValue) {
             echo "Key: " . $itemKey . ", Value: " . $itemValue . "<br>";
         }
         $this->endRow();
    }


    


// end class
}

// die klasse DataBlock soll alle dataArray dinge tun und für alle datablocks genutzt werden aus 
// denen die pages gebaut werden 
// wo bleiben die iterators -> eigene Klasse? bleiben in mehpage, warum nicht? 

// class lineEtractor should take an element to search for and return a line number

// array reverse und split ftw -- DataBlock ist obsolete


class DataBlock {


    // this class is for data handling and data extraction and too big


    //// DATA 
    public $occurenceIndex0;
    public $occurenceIndex1;
           
    // Content Check und Extraction   

    public function resetOccurenceIndex() { unset($this->occurenceIndex0); unset($this->occurenceIndex1); }


    public function findElement($element, $index) {
       if (!is_array($this->data) || !is_object($this->data) && count($this->data) < 1) { echo "<br>findElement needs countable array or object"; return;}
       if (is_array($this->data)) {
            if ($this->data[0] != 0 && $this->data[0] != "0" && $this->data[0] != null ) { echo "<br>findElement needs an array or object with empty first member"; return;}
        } // spuren von komischen dingen...
        // eigentlich soll hier kein objekt landen, oder? 
        // hier prüfungen auf klasseneigene dinge zu machen stinkt sicher..
        
        // check if we got a valid search value
        if ($element == "") {echo "<br>findElement needs to be not empty "; return;}
        $searchString = $element;
        $found = false; 

        // resetOccurenceIndex() - hier index resetten ist schlecht für mehrfach suche 
        
        while (!$found && $index < count($this->data)) {
            $index++;
            if (strpos($this->data[$index - 1], $searchString) !== false) {
                $found = true;
                //echo "<br>Found the string '$searchString' in line " . ($index - 1);

            }

        }

        // Call Helper Functions
        //echo $this->data[$index - 1];
        if (strpos($this->data[$index - 1], '[')) {$elementName = $this->extractFromBrackets($this->data[$index - 1]);}
        //if (strpos($this->data[$index - 1], '@')) {$elementName = $this->extractServerName($this->data[$index - 1]);}
        //if (strpos($this->data[$index - 1], '-playbook')) {$elementName = $this->extractPlaybookCall($this->data[$index - 1]);}
        
        // da wir hier nocht einen test auf die zeile ausführen, aber dadurch für Dinge die in der gleichen Zeile 
        // vorkommen hier der erste check immer match auslöst -> vergleich direkt mit $element  - corner oder öfter?
        // es ist eigenltich hier nicht nötig noch mal zu prüfen -> found is found ^^
        // von den einzelnen extractor functions kann noch etwas herausgehoben werden wenn args gezählt und elemente übergeben werden

        if ($element == '@') {$elementName = $this->extractServerName($this->data[$index - 1]);}
        if ($element == '-playbook') {$elementName = $this->extractPlaybookCall($this->data[$index - 1]);}

        // to be able to call the function again and again with index of first occurance to find second occurance 
        
        if (isset($this->occurenceIndex0)) {$this->occurenceIndex1 = $index - 1 ;
            } else {$this->occurenceIndex0 = $index - 1 ;}
        
        if (!$found) {
            echo "The string '$searchString' was not found in any line.";
        } else { return $elementName; }
    }

    private function extractFromBrackets($line) {
        $firstIndex = strpos($line, '[');
        $lastIndex = strpos($line, ']');
        //echo "found play";
        $elementName = substr($line, $firstIndex + 1 , $lastIndex - $firstIndex - 1 );
        //echo "playname: <br>";
        //echo $playName;
        // wurde $elementName jetzt auch in der parent function gesetzt und ich brauch das return gar nicht? 
        return $elementName;
    }

    private function extractServerName($line) {
        $firstIndex = strpos($line, '@');
        $lastIndex = strpos($line, ':');
        $elementName = substr($line, $firstIndex + 1 , $lastIndex - $firstIndex - 1 );
        return $elementName;
    }

    private function extractPlaybookCall($line) {
        $firstIndex = strpos($line, '-playbook');
        //$lastIndex = strpos($line, ':');
        $elementName = substr($line, $firstIndex - 7 );
        return $elementName;
    }

// end class
}


class BeList {
    private $list;
    // public function __construct() {
    //     $this->list = new stdClass();
    //}
    // stay with arrays since we can use array functions 

    // constructor initializes this-list as an array using the extendList function handing over any number of arguments

    public function __construct() {
        
        if (func_num_args() > 0) {
            if (func_num_args() == 1 && is_array(func_get_arg(0))) {
                $this->list = func_get_arg(0);
            } else {
                if (func_num_args() == 1) {
                    $this->list = array();
                    $this->list[] = func_get_arg(0);
                } else {
                    // echo "<br>constructing list with args";
                    // print_r(func_get_args());
                    $this->extendList(func_get_arg(0), func_get_arg(1));
                }
            }   
        } else {
            $this->list = array();
        }
    }

    public function extendList() {
        // echo "<br>extending list with args";
        // print_r(func_get_args());

        if (func_num_args() == 1) {
            if (is_array(func_get_arg(0))) {
                //$this->list = array_merge($this->list, func_get_arg(0));
                // array merge does overwrite keys
                // so we just add one array to another 
                if (!isset($this->list)) {
                    $this->list = array();
                }
                $this->list += func_get_arg(0);

            } else { $this->list[] = func_get_arg(0); }
        }
       
        if (func_num_args() == 2) {
            $key = func_get_arg(0);
            $value = func_get_arg(1);
            // create associactive array member from $key and $value
            $this->list[$key] = $value;
        }
    }

    public function getList() {


        if (func_num_args() == 1) {
            // echo "<br>getting list";
            // print_r(func_get_args());
            
            if (is_array(func_get_arg(0))) {
                $index = func_get_arg(0)[0];
            } else {

                $index = func_get_arg(0);
                              
            }
            return $this->list[$index];  
        }
        return $this->list;
    }

    public function getListfromIndex($index) {
        // return a list from a given index to the end of the list
        $result = new BeList();
        for ($i = $index; $i < count($this->list); $i++) {
            
            $result->extendList($this->list[$i]);
        }
        return $result;

    }


    public function getKeyWordByIndex($index) {
        return $this->keyWordIndex[$index];
    }

    public function findValues($searchValue) {
        $result = array();

        foreach ($this->list as $key => $value) {
            if ($value === $searchValue) {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    public function findElement() {
        
        if (func_num_args() == 1) {
            $index = 0;
        }
        if (func_num_args() == 2) {
            $element = func_get_arg(0);
            $index = func_get_arg(1);
        }
        // we also want to use assoc keys as index - check if empty for fail safe 
        if ($index == "") {$index = 0;}

        if ($element == "") {echo "<br>findElement needs to be not empty "; return;}
        $searchString = $element;
        $found = false; 
        // check if $index is set and set to zero if not - it does not matter if index is numeric or string
        if (isset($index) && is_numeric($index)) {
            $index = $index;
        } else {
            $index = 0;
        }
        

        // resetOccurenceIndex() - hier index resetten ist schlecht für mehrfach suche 
        
        while (!$found && $index < count($this->data)) {
            $index++;
            if (strpos($this->data[$index - 1], $searchString) !== false) {
                $found = true;
                //echo "<br>Found the string '$searchString' in line " . ($index - 1);
                return $index - 1;
            }
        }
    }  
    // todo: maybe make these functions take a startindex so we can find all oks from start to end block 
    // hmm, da überschneiden wir uns mit datablock class -> wir machen blocks aus diesen indizes 

    
    // function die nach first occurence sucht und nur einen index als wert returniert
    public function findFirstValue($searchValue) {
        $pattern = '/' . $searchValue . '/';
        if ($searchValue == "ok:") { echo "found ok:";$pattern = '@ok:@';}
        
            foreach ($this->list as $key => $value) {
                //if (strpos($value, $searchValue)) {
                    if (preg_match($pattern, $value)) {
                    $result = $key;
                    return $result;
                }
            }
       }

    // public function findLastValue searches last occurence and returns only one index as value
    // it does not matter if the value is an array or object
    public function findLastValue($searchValue) {
        
        $result = null;
        $i = 0;
        $maxOccurences = 1000;
        
        $pattern = '/' . $searchValue . '/';
        

        if ($searchValue == "ok:") { echo "found last ok:"; $pattern = '@^ok:@'; $maxOccurences = 30    ;}
        
            foreach ($this->list as $key => $value) {
                //if (strpos($value, $searchValue)) {
                    if (preg_match($pattern, $value)) {
                    $result = $key;
                    $i++;
                    if ($i > $maxOccurences) {
                        return $result;
                    }
                }
            }
        return $result;      
        
    }

    // public function findallvalues searches all occurences and returns an array of indices
    public function findAllValues ($searchValue) {
        $result = array();
        $i = 0;
        $maxOccurences = 1000;
        
        $pattern = '/' . $searchValue . '/';
        
        if ($searchValue == "ok:") { echo "found ok:";$pattern = '@ok:@';}
        
            foreach ($this->list as $key => $value) {
                //if (strpos($value, $searchValue)) {
                    if (preg_match($pattern, $value)) {
                    $result[] = $key;
                    $i++;
                    if ($i > $maxOccurences) {
                        return $result;
                    }
                }
            }
        return $result;      
        
    }


// end class
}

// TEST BeList class

// $keyWordList = new BeList();
// $keyWordList->extendList('key_1', 'Lvalue1');
// $keyWordList->extendList('key_2', 'Lvalue2');
// $keyWordList->extendList('key_3', 'Lvalue1');
// $keyWordList->extendList('key_4', 'Lvalue2');
// $keyWordList->extendList('key_5', 'Lvalue2');
// $keyWordList->extendList('key_6', 'Lvalue1');
// $keyWordList->extendList('Lvalue1');
// $keyWordList->extendList('Lvalue1');
// $keyWordList->extendList('Lvalue1');

// $arrList = new BeList();
// $arrList->extendList('Lvalue1');

// $searchResult = $keyWordList->findValues('value1');

// $aList = new BeList();
// $aList->extendList('ListValue1');
// $aList->extendList('ListValue2');
// $aList->extendList('ListValue3');
// $aList->extendList(10, 'ListValue3');
// $aList->extendList(9, 'ListValue3');
// $aList->extendList(12, 'ListValue3');
// $aList->extendList('key0', 'ListValue3');

// $bList = new BeList();
// $bList->extendList('ListValue1');
// $bList->extendList('ListValue2');
// $bList->extendList('ListValue3');
// $bList->extendList('ListValue3');
// $bList->extendList('ListValue3');
// $bList->extendList('ListValue3');
// $bList->extendList('ListValue3');

// echo "<br>All the Lists: ";
// $allLists = new BeList();
// //$allLists->extendList('ListValue3');
// $allLists->extendList($keyWordList->getList());
// $allLists->extendList($aList->getList());
// $allLists->extendList($bList->getList());
// echo "<br>length: " . count($allLists->getList()) . "<br>";
// print_r($allLists);
// echo "<br><br>";

// $out = $bList->findFirstValue('ListValue3');
// echo "<br>out first: ".$out;
// echo "<br>length: " . count($bList->getList()) . "<br>";
// $out = $keyWordList->findFirstValue('Lvalue2');
// echo "<br>out first1: ".$out;
// echo "<br>length: " . count($keyWordList->getList()) . "<br>";
// echo "<br>find last blist: <br>";
// echo $bList->findLastValue('ListValue3');
// echo "<br>findlast keywordlist: <br>";
// echo $keyWordList->findLastValue('Lvalue2');
// echo "<br>";
// echo "<br>bList: <br>";
// print_r($bList);
// echo "<br>bList: <br>";
// print_r($bList->getList());
// echo "<br>aList: <br>";
// print_r($aList);
// echo "<br>aList: <br>";
// print_r($aList->getList());
// echo "<br>length: " . count($aList->getList()) . "<br>";
// echo "<br>";
// echo "<br>keywordlist: <br>";
// print_r($keyWordList);
// echo "<br>keywordlist: <br>";
// print_r($keyWordList->getList());
// echo "<br><br>";



// Test Data

$tempProp2 = (object) [
    'nestedProperty1' => 'Nested Value 1',
    'nestedProperty2' => (object) [
        'nest2Property1' => 'Nest2 Value 1',
        'nest2Property2' => [0, 1, 2, 3, "bla", "plopp","blubb","foo"],
        'nest2Property3' => (object) [
            'nest3Property1' => 'Nest3 Value 1',
            'nest3Property2' => 'Nest3 Value 2',
            'nest3Property3' => (object) [
                'nest4Property1' => 'Nest4 Value 1',
                'nest4Property2' => 'Nest4 Value 2'
            ],
        ],
    ],
];


// Create Page class
$mehPage = new MehPage();


// fill testdata - properties are set again further down 
$mehPage->setProperty2($tempProp2);
$mehPage->setProperty3([1, 2, 3, "bla", "plopp","blubb","foo"]);
// der test ist wichtig um zu sehen ob das array vom arry iterator (ohne key)
// oder vom object iterator (mit key) ausgegeben wird 

////////////////////////////////////////////////////////

function loadData($file){
    $dataArray = array();
    foreach(file($file) as $line) {
        array_push($dataArray, $line) ;
        }
    return $dataArray;
    }

 
// load data from file 

//$file = "file16.json";
//$file = "file-long-terminal-00.log";
$file = "wf-kw-21-OM-complete.txt";
//$loadeddata = file_get_contents($file);


// get filename from post request
$file = $_POST["file"];
// --> page loads empty which is good since we set the test table far down below

$dataArray = loadData($file);

//echo "<br>dataarray: <br>";
//print_r($dataArray);

// create data object

$dataArrayObject = new BeList($dataArray);

// put all data into last property to show it for bebug   

$mehPage->setProperty5($dataArrayObject->getList());



// Create Elements

// find Playbooks and playnames 
$playNames = array();
// create array $playnname with first element is the result of $dataArrayObject->findFirstValue($element);


// find ansible-playbook
$element = "ansible-playbook";
$ansiblePlaybookIndex = $dataArrayObject->findAllValues($element);

$ansiblePlaybookLines = array();
$playnname = array();
foreach ($ansiblePlaybookIndex as $key => $value) {
    array_push($ansiblePlaybookLines, $dataArrayObject->getList($value));
    
    $playnname[] = $dataArrayObject->getList($value);
    // create dataarrayobject slice from ansiblePlaybookIndex
    // todo: create a slice that is only a number of lines after the index so we dont copy the whole array over and over again
    //$playArray = $dataArrayObject->getListfromIndex($value);  // funktioniert nicht
    // find next play
    //$playName = NextPlay($dataArrayObject, $value);
    // in der for each jeweils den index so lange immer weiter erhöhen bis PLAY gefunden wird.

    //$i = 0;
    //getNextPlay($dataArrayObject, $value, $i);
    
    for ($i=0; $i < 10; $i++) { 
        
        if (strpos($dataArrayObject->getList($value+$i), "PLAY") !== false) {
            //echo "<br>found play at index: ".$value+$i;
            echo "<br>playname: ".$dataArrayObject->getList($value+$i);
            $playName[] = $dataArrayObject->getList($value+$i);
            break;
        }
    }





}

// in der for each jeweils den index so lange immer weiter erhöhen bis PLAY gefunden wird.
// function find next play
// function getNextPlay ($dataArrayObject, $index){
//     $element = "PLAY";
    

//     $nextPlayNameIndex = $playArray->findFirstValue($element);

//     echo "<br>playname: ".$playName;
//     print_r($playArray);

//     return $playName;
// }


// $playName  =+ $ansiblePlaybookLines;
//print_r($playName);




// function nextPlay ($dataArrayObject, $index){
//     $element = "PLAY";
//     $playArray = $dataArrayObject->getListfromIndex($index); // does not work yet
//     $playName = $playArray->findFirstValue($element);

//     echo "<br>playname: ".$playName;
//     print_r($playArray);

//     return $playName;
// }


// function find play -- es gibt auch PLAY RECAP  
// function findNextPlay($playArray){
//     $element = "PLAY";
//     $playName = $playArray->findFirstValue($element);

//     echo "<br>playname: ".$playName;
//     return $playName;
// }

echo "<br>ansiblePlaybookLines: ";
print_r($ansiblePlaybookLines);


echo "<br>ansiblePlaybookIndex: ";
print_r($ansiblePlaybookIndex);



$mehPage->setProperty1($playName);




// find terminal Hostname if pasted..
// until now we find only lines with elements -> make that a class and extend from it for further extraction of strings 
// like playname, hostname, tasknames, etc.



$element = "@";
$ansibleServerName = $dataArrayObject->findFirstValue($element, 0);

// find terminal ansible-playbook if pasted..

$element = "-playbook";
$ansiblePlaybook = $dataArrayObject->findFirstValue($element, 0);



///////////////////////////////////////////////

// find and count any occurences of "fatal" in $dataArrayObject and keep indices within a list $fatalLinesIndex
$element = "fatal";
//$fatalLines = new BeList();
$fatalLinesIndexArray = $dataArrayObject->findAllValues($element);
    // create BeList with $fatalLinesIndex as values
    
    // foreach ($fatalLinesIndexArray as $key => $value) {
    //     $fatalLines->extendList($value);
    // }
$fatalLines = array();
    foreach ($fatalLinesIndexArray as $key => $value) {
        // die gehen alle ohne as key nur mit as value
            //use array_push to add values to the array
            array_push($fatalLines, $dataArrayObject->getList($value));
            
    }

    // echo "<br>fatalLinesIndices: <br>";
    // print_r ($fatalLinesIndexArray);


//  extract server names from fatal lines and keep the lines in an array with server names as keys while keeping server names unique

$fatalLinesServerNames = array();

foreach ($fatalLines as $key => $value) {
    // array explode value at [ and use everything until ] as server name
    $serverName = explode("[", $value);
    $serverName = explode("]", $serverName[1]);
    // arraypush server name to array keeping the original index as key 
    //array_push($fatalLinesServerNames, $serverName[0]);

    // create associative array with servername as key and $value as value 
    $fatalLinesServerNames[$serverName[0]] = $value;

}

// echo "<br>fatalLinesServerNames: <br>";
// print_r ($fatalLinesServerNames);


$mehPage->setProperty2($fatalLinesServerNames);


/////////////////////////////////////////////////////////

// find all lines with TASK in them

function getAllTaskLines ($dataArrayObject) {
    // find all lines with TASK in them
    $element = "TASK";
    $taskName = $dataArrayObject->findAllValues($element);
    // echo "<br><br><br>taskname: <br>";
    // print_r ($taskName);

    // echo out all lines of $dataArrayObject with the indexes from the array $taskName using the getlist function of the $dataArrayObject

    // foreach ($taskName as $key => $value) {
    //     // echo "<br>taskindex: <br>";
    //     // echo $value;
    //     // echo "<br>taskname: <br>";
    //     print_r ($dataArrayObject->getList($value));
    // }
    
    // create list with the indices of object BeList with the name of the task as the value
    // if we create the object with a value we get an array within an array naturally thanks copilot,.,,,
    $taskList = new BeList();
    foreach ($taskName as $key => $value) {
        $taskList->extendList($value, $value);
    }
    return $taskList;

}

$taskIndices = getAllTaskLines($dataArrayObject);

//  create BeList object with $dataArrayObject lines with the indices from the $tasksBlock list 
function createTaskLines ($dataArrayObject, $tasksBlock) {
    // echo "<br>taskBlock: <br>";
    // print_r ($tasksBlock);
    //$taskLines = new BeList("taskLines");
    $taskLines = new BeList();
    foreach ($tasksBlock->getList() as $key => $value) {
        $taskLines->extendList($key, $dataArrayObject->getList($key));
    }
    // echo "<br>taskLines: <br>";
    // print_r ($taskLines);
    return $taskLines;
}

$tasksLines = createTaskLines($dataArrayObject, $taskIndices);
$mehPage->setProperty3($tasksLines->getList());

// property4 will be used for the array that will hold the values after the occurence of gaterhing facts until there 
// are no further consecutive lines with the string 'ok:' in them in form of a DataBlock Object
// this will be used to create a BeList Object with the indices of the occurences of the string 'ok:' in the $dataArrayObject

function findPhrase($inputString, $phrase) {
    // Prepare the regex pattern to match the specified phrase
    $pattern = '/(?<!\S)' . preg_quote($phrase) . '(?!\S)/';

    // Perform the regex match
    preg_match_all($pattern, $inputString, $matches);

    // Return the matched results
    return $matches[0];
}


/////////////////////////////////////////////////////////



function gatheringFacts ($dataArrayObject) {
    // finds a line with gathering facts and the search from there for all consecutive lines with 'ok:' in them

    //echo "<br>gatheringFacts: <br>";
    // find first occurence of 'Gathering' in the $dataArrayObject and put it into the $list
    $element = "Gathering";
    $gathering_index = $dataArrayObject->findFirstValue($element);
    //echo "<br>gathering: index: " . $gathering_index;
    //$list->extendList("gathering_index", $gathering_index);
    //$gathering_value = "gathering_index";
    $list = new BeList("gathering_index", $gathering_index);
    //echo "<br>" . $list->getList();

    // find first occurence of 'ok:' in the $dataArrayObject
    $element = "ok:";
    $ok_first_index = $dataArrayObject->findFirstValue($element);
    $list->extendList('ok_first_index', $ok_first_index);

    //echo "<br>first list echo: " . $list->getList();


    // find last concurrent occurrence of 'ok:' by iterating one value at a time and checking if the previous value was 'ok:'
    $element = "ok:";
    $ok_current_index = $ok_first_index;
    //echo "<br> value of ok_current_index: " . $dataArrayObject->getList($ok_current_index);
    //while (strpos($dataArrayObject->getList($ok_current_index), 'ok')){
    while (strpos($dataArrayObject->getList($ok_current_index), 'ok') !== false){
        
        $ok_current_index++;
    }

    $ok_last_index = $ok_current_index - 1;
    //echo "<br>last index: " . $ok_last_index;
    $list->extendList('ok_last_index', $ok_last_index);
    //echo "<br>should be only one line<br>" . $dataArrayObject->getList($ok_last_index);
    //echo "<br>";
    // create BeList object with the lines between $ok_first_index and $ok_last_index using a loop 

    $okBlock = new BeList();
    for ($i = $ok_first_index; $i <= $ok_last_index; $i++) {
        $okBlock->extendList($dataArrayObject->getList($i));
    }



    //echo "<br>okBlock: ";
    //print_r($okBlock);

    // $pattern = '@ok:@';}
        
            // foreach ($this->list as $key => $value) {
            //     //if (strpos($value, $searchValue)) {
            //         if (preg_match($pattern, $value)) {


    // findlast occurence of 'ok:' in the $dataArrayObject after the first occurence of 'Gathering'
    // $element = "ok:";
    // $ok_last_index = $dataArrayObject->findLastValue($element);
    // $list->extendList('ok_last_index', $ok_last_index);
    //echo "<br>" . $list->getList();

    // create a DataBlock Object with the indiced of the occurences of the string 'ok:' in the $dataArrayObject
    // echo "<br><br>";
    // print_r($list->getList());
    // echo "<br>";
    // print_r($list);


    // //$okBlock = new DataBlock($dataArrayObject[$ok_first_index], $dataArrayObject[$ok_last_index]);

    // echo "<br><br><br>";

    //print_r($dataArrayObject);


    // create a BeList Object with the indiced of the occurences of the string 'ok:' in the $dataArrayObject
    //$okList = new BeList();
    //$okList->extendList($dataArrayObject->occurenceIndex0, $ok);
    // debug print r oklist
    //print_r($okList->getList());


    return $okBlock;

    }


//gatheringFacts($dataArrayObject);

// print_r($searchResult);
// echo "<br>";
// print_r($keyWordList);

// kann ich von innerhalb der funktion nicht setzen und ist auch unnötig weil das resultat ist ja der block 
//$mehPage->setProperty3($list->getList());

$okBlock = gatheringFacts($dataArrayObject);
$mehPage->setProperty4($okBlock->getList());






////
////
//// Create Html Page
////

$mehPage->pageTop();

///// todo: 

// maybe we show only ever one level with click option to show more if there is another level
// which will open a new page that shows a table with just this node and a back button 
// --> copy button will only ever copy one table and no nested tables 
// show hide function is nice but to make the copy button only copy the displayed stuff is easy? 

// fille empty proerties -> todo: check if node is just empty node and fill all empty properties

if ($mehPage->getProperty1() == "") {
    $mehPage->setProperty1($tempProp2);    
}


?>

<div class="container">

<div class="row sticky-top" style="padding: 5px; background: bisque; border-bottom-color: burlywood; border-bottom-width: 12px; border-bottom-style: solid;"> 
    
  <div class="col"> <h2>Table Viewer</h2><p><?php echo $file; ?></p></div>
  <div class="col">

    <button onclick="topFunction()" type="button"  id="TopBtn" title="Go to top">Top</button>

    <button onclick="toggleLines()" type="button"  id="ToggleBtn" title="Toggle show one or more Lines">Toggle</button>

    <button onclick="homeButton()" type="button" id="homeBtn" title="Start Page">Home</button>

    <input id="copy_btn" type="button" value="Copy" title="Copy Data Table Data">

<?php    
// create form field in html code and take file name from there

echo "<div style='margin-top:5px;'>
<form action='index.php' method='post'>" 
. "<input type='text' name='file' value='wf-kw-21-OM-complete.txt'>"
. "<input type='submit' value='Load' style='margin-left: 4px'>"
. "</form></div>";

?>
    </div>
</div>


    <script type="text/javascript">
        var copyBtn = document.querySelector('#copy_btn');
        copyBtn.addEventListener('click', function () {
        var urlField = document.querySelector('table');

        // create a Range object
        var range = document.createRange();
        // set the Node to select the "range"
        range.selectNode(urlField);
        // add the Range to the set of window selections
        window.getSelection().addRange(range);

        // execute 'copy', can't 'cut' in this case
        document.execCommand('copy');
        }, false);

        function topFunction() {
            document.body.scrollTop = 0; // For Safari
            document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        }

        function homeButton() {
            len = "json_viewer.php".length;
            currentUrl = location.href;
            replacedUrl = currentUrl.split('?')[0];
            location.href = replacedUrl.slice(0, - len);
        }
        
        function toggleLines() {
            currentUrl = location.href;

            if (currentUrl.match('&toggleLines=NULL')) {
                replacedUrl = currentUrl.replace('&toggleLines=NULL', '&toggleLines=1' );
                
            }else{
                replacedUrl = currentUrl.replace('&toggleLines=1', '&toggleLines=NULL' );

            }
            location.href = replacedUrl;
        }

    </script>
<div class='row' style='background: burlywood;'>
<!-- echo " -->
<div class="text-center"> ------- class iterates over standard class objects as well as  --------
<br>------- values, arrays and objects as members --------
<br>------- and have the function as a method of the class --------
<br>------- bot optimized bot code mit getter und setter und public wrapper
<br>------- für private method die man recursive nutzen kann  -------- 
<br>------- table generator für nested tables und beliebige anzahl an feldern -------- 
<br>------- variable tiefe von nested objects  -------- 
<br>------- jetzt kann man damit anfangen die file inhalte in objekte zu laden   -------- 
<br>------- und von dort aus weiter zu verarbeiten  -------- 
<br>------- use a class for datablocks and for lists  -------- 
<br>------- that can be array or dict -------- 
<br>------- wasted time with trying to get rid of index modifires for the while function   -------- 
<br>------- while moves through a large file only until first occ ... im old... -------- 
<br>------- man könnte... -------- 
<br>------- banner und hostname vom CC auswerten und diverse infos anzeigen - wie oft kommt fatal vor wieviele PlaybookCalls usw 
<br>------- schauen wo fatals auftreten und die anfangs tests vergessen bzw fatals die im jeweiligen playrun auftreten anzeigen
<br>------- play-book aufrufe und play names bzw tasks danach wirklich als gruppe zuordnen 
<br>------- und dann die gruppen als blöcke anzeigen oder einzelne pages
<br>------- tables mit ids die dann den copy button auf diesen table uns seine children beschränken
<br>------- und dann die copy function so anpassen dass sie nur den table mit der id kopiert ok das ist ja eh klar 
<br>------- man sollte: 
<br>------- entscheiden wieviele properties eine mehpage braucht 
<br>------- was man auf einer page darstellen will und was auf andere pages soll -> e.g. task blöcke auf einzelne pages oder nicht
<br>------- sich überlegen wie man andere pages machen würde - file erzeugen und anzeigen? 
<br>------- eine übersicht über die verfügbaren files anzeigen und aufruf gestalten 
<br>------- file upload? 
<br>------- find more than one gathering facts with their ok blocks - same logic as for playbooks and tasks 
<br>------- MVP home button zeigt alle files an und man kopiert den filename und ladt mit load button -> 
<br>------- Endprodukt: ich klicke mir meine Blöcke mit dem Content den ich haben will zusammen und erzeuge davon eine Page oder einen Export 

</div>
<!-- "; --> 
</div>


<?php

////
//// End Html Page 
////

$firstHead = "First Value";
$secondtHead = "Second Value";
$thirdHead = "Third Value";
echo "
<div class='row'>
";
$mehPage->makeTableHead($firstHead, $secondtHead, $thirdHead);

// Call the iterateProperties method blindly - here we will call the iterator and hand over an object 
$mehPage->iterateProperties();

// last row to check integrity of table
$mehPage->makeRowWrapper($firstHead, $secondtHead, $thirdHead);

$mehPage->makeTableFoot();

echo "
    </div>
    ";

// close container div
echo "
    </div>
    ";

$mehPage->pageEnd();



