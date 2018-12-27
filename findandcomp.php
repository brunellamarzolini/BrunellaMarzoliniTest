<html>

<head>
   <title>Find & Compare</title>
</head>

<body>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
    <br>
        website 1: <input type="url" name="website1"><br>  <!--qui non mi era chiaro se dovessi per forza usare un type="text"-->
    <br>
        website 2: <input type="url" name="website2"><br>
    <br>
    <br>
        <button type="submit" name="submit">Find&Compare</button>
    <br>
    </form>
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

    }


  }


    function findAndCompare(){


        $url1 = $_POST['website1'];
        $array1 = crawler($url1);
        $arrlength1 = count($array1);

        echo $arrlength1;


        $url2 = $_POST['website2'];
        $array2 = crawler($url2);
        $arrlength2 = count($array2);

        echo $arrlength2;

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