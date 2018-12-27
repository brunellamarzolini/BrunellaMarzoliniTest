<html>
   <head>
      <title>Find & Compare</title>
   </head>
   <body>
      <br>
      <h2>Find & Compare - Brunella Marzolini</h2>
      <h4>Fill the two input fields with the homepage url address (e.g. http://www.homepage.com) of two websites</h4>
      <br>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
         <br>
         website 1: <input id="uno" type="text" name="website1" value="" oninput="countOnType()"><br>  <!--qui non mi era chiaro se dovessi per forza usare un type="text"-->
         <br>
         website 2: <input id="due" type="text" name="website2" value ="" oninput="countOnType()"><br>
         <br>
         <p>Counter: <a id="counter">0</a></p>
         <p>Sum Alphabet Position: <a id="alphabet">0</a></p>
         <br>
         <button type="submit" name="submit" onclick="alphabetPosition()">Find&Compare</button>
         <br>
      </form>
      <script type="text/javascript" src="js/counter.js"></script>
   </body>
</html>


<?php

  if(isset($_POST['submit']))
  {
    if($_POST['website1']=="" || $_POST['website2']==""){

        echo "Fill both Fields!";

    }else{

        //form has been submitted
        findAndCompare();



       //this section is a necessary check if I do not want to impose type = "url"
       
       /*
        $web1 = $_POST['website1'];
        $web2 = $_POST['website2'];
        if (strpos($web1, 'http') === false) {		
    		$website1 = "http://".$web1;
		}else{
			$website1 = $web1;
		}
		if (strpos($web2, 'http') === false) {		
    		$website2 = "http://".$web2;
		}else{
			$website2 = $web2;
		}
		findAndCompare($website1,$website2);
		*/

    }
  }


    function findAndCompare(){


        $url1 = $_POST['website1'];
        $array1 = crawler($url1);
        $arrlength1 = count($array1);

        $url2 = $_POST['website2'];
        $array2 = crawler($url2);
        $arrlength2 = count($array2);


        // output headers so that the file is downloaded rather than displayed
        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename= "FindAndCompare.csv"');
 
        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
     
        //this is to avoid to print html text at the beginning of the .csv file
        ob_end_clean();


        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'w');
 

        $similarity = array();

        for($i = 0; $i < $arrlength1; $i++) {
            for($n = 0; $n < $arrlength2; $n++) {

                similar_text($array1[$i], $array2[$n], $perc);
                //echo "$array1[$i], $array2[$n], $perc %"."<br>";

                array_push($similarity, array($array1[$i], $array2[$n], $perc.'%'));

            }
        }

 
        // output each row of the data
        foreach ($similarity as $row)
            {
                fputcsv($file, $row);
            }

    	fclose($file);
    	exit();

  }



    function crawler($url){
    $array_url = array();

        // strip trailing slash from URL
        if(substr($url, -1) == '/') {
            $url= substr($url, 0, -1);
        }

        // which URLs have we already crawled?
        static $seen = array();
        if (isset($seen[$url])) {
            return;
        }

        $seen[$url] = true;

        $dom = new DOMDocument('1.0');
        @$dom->loadHTMLFile($url);

        $anchors = $dom->getElementsByTagName('a');
        foreach ($anchors as $element) {
            
            $href = $element->getAttribute('href');

            if (0 !== strpos($href, 'http')) {
                // build the URLs to the same standard - with http:// etc
                $path = '/' . ltrim($href, '/');
                if (extension_loaded('http')) {
                    $href = http_build_url($url, array('path' => $path));
                } else {
                    $parts = parse_url($url);
                    $href = $parts['scheme'] . '://';
                    if (isset($parts['user']) && isset($parts['pass'])) {
                        $href .= $parts['user'] . ':' . $parts['pass'] . '@';
                    }
                    $href .= $parts['host'];
                    if (isset($parts['port'])) {
                        $href .= ':' . $parts['port'];
                    }
                    $href .= $path;
                }
            }

        array_push($array_url, $href);

            
        }

        return $array_url;

    }

?>