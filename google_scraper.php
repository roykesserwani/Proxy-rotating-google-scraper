<?php

include ('htmlparser/simple_html_dom.php');
ini_set("memory_limit","128M"); // For scraping 100 results pages 32MB memory expected, for scraping the default 10 results pages 4MB are expected. 64MB is selected just in case.
set_time_limit(0); // script maximum run execution time 10 minutes
ini_set("log_errors", 1);
ini_set("error_log", __DIR__.'/errors.txt');

// list of ip addresses
$ipaddresses = array(
    0 => '104.247.218.223:3353', // 28+ using act proxy
    1 => '104.247.216.229:3499',
    2 => '172.82.175.206:2334',
    3 => '191.101.29.242:3372',
    4 => '181.215.1.202:3332',
    5 => '172.82.171.30:1157',
    6 => '170.75.158.52:1179',
    7 => '107.161.119.106:1233',
    8 => '45.43.217.88:2216',
    9 => '172.82.156.239:1366'
);
// user agents
$user_agents = [
    'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Safari/602.1.50',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Safari/537.36 Edge/13.10586',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:48.0) Gecko/20100101 Firefox/48.0',
    'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.71 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:49.0) Gecko/20100101 Firefox/49.0','Mozilla/5.0 (Windows NT 6.1; WOW64; rv:41.0) Gecko/20100101 Firefox/41.0',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.101 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.71 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11) AppleWebKit/601.1.56 (KHTML, like Gecko) Version/9.0 Safari/601.1.56',
    'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.80 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_1) AppleWebKit/601.2.7 (KHTML, like Gecko) Version/9.0.1 Safari/601.2.7',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; AS; rv:11.0) like Gecko',
    'Mozilla/5.0 (compatible, MSIE 11, Windows NT 6.3; Trident/7.0; rv:11.0) like Gecko',
    'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.3; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.0; Trident/5.0; Trident/5.0)',
    'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:47.0) Gecko/20100101 Firefox/47.0',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/602.1.50 (KHTML, like Gecko) Version/10.0 Safari/602.1.50',
    'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.143 Safari/537.36',
    'Mozilla/5.0 (Windows NT 6.1; Trident/7.0; rv:11.0) like Gecko'
];
// keep track of ip logs
$proxyLogs = array();
// keep track of current page per query
$page = 0;
// query results
$scrapeResult = "";
$scrapedHtml = "";
// keeping track of ip address being used
$currentip = 0;
$subquery = "";
$company_index_file = "company_index.txt";
$urls = array();

$index = intval(fgets(fopen($company_index_file, 'r')));
$max_index = 1000000;


function buildOwlerUrl($current_index) {
  $urlstring = "";
  for ($i = $current_index; $i < $current_index + 10; $i++) {
    echo $i."<br/>";
    if ($i == $current_index + 9)
        $urlstring .= "site%3Ahttps%3A%2F%2Fwww.owler.com%2FiaApp%2F".$i;
    else
        $urlstring .= "site%3Ahttps%3A%2F%2Fwww.owler.com%2FiaApp%2F".$i."+OR+";
  }
  return $urlstring;
}

// check to see if proxy ip ready to be used (we executive the exact ip address every 6 minutes)
function isProxyReady($proxyLogs, $currentIP) {
    if ($proxyLogs[$currentIP] === '' || is_null($proxyLogs[$currentIP])) {
        $proxyLogs[$currentIP] = new DateTime();
    } else {
        $pastTime = $proxyLogs[$currentIP];
        $timeNow = new DateTime();
        $since_start = $pastTime->diff($timeNow);
        $minutes = $since_start->days * 24 * 60;
        $minutes += $since_start->h * 60;
        $minutes += $since_start->i;
        if ($minutes < 6) {
            error_log("UNDER 6 MINUTES");
            $left = 6 - $minutes;
            $leftInSeconds = $left * 60;
            sleep($leftInSeconds);
        }
    }
    return true;
}

function checkForMoreResults($currentpage) {
    // Analyze if more results are available (next page)
    $next = 0;
    if ($currentpage < 6) {
        return 1;
    }
    return $next;
}


// writing in csv format enclosed with ""
function writeToFile($extractedData, $keyword)
{
    foreach($extractedData as $contact) {
        $name = (isset($contact['name'])) ? '"'.$contact['name'].'"' : '""';
        $username = (isset($contact['username'])) ? '"'.$contact['username'].'"' : '""';
        $company = (isset($contact['company'])) ? '"'.$contact['company'].'"' : '""';
        $title = (isset($contact['title'])) ? '"'.$contact['title'].'"' : '""';
        $url = (isset($contact['url'])) ? '"'.$contact['url'].'"' : '""';
        $loc1 = (isset($contact['location']['loc1'])) ? '"'.$contact['location']['loc1'].'"' : '""';
        $loc2 = (isset($contact['location']['loc2'])) ? '"'.$contact['location']['loc2'].'"' : '""';
        $loc3 = (isset($contact['location']['loc3'])) ? '"'.$contact['location']['loc3'].'"' : '""';
        $line = trim($username).','.trim($name).','.rtrim(strip_tags($title)).','.rtrim(strip_tags($company)).','.strip_tags($url).','.strip_tags($loc1).','.strip_tags($loc2).','.strip_tags($loc3).','.rtrim(strip_tags($keyword)).',""';
        file_put_contents("contacts.csv",$line.PHP_EOL, FILE_APPEND | LOCK_EX);
    }

}


function extractUrlsFromGoogle($scrapedHtml)
{
    $html = new simple_html_dom();
    $html->load($scrapedHtml);
    $results = $html->find('div#ires div.g');
    $final_results = array();

    foreach ($results as $result) {
        $tempResult = array();


        $h3 = $result->find('h3.r', 0);
        if (!$h3) {
            continue;
        }

        $name = $h3->find('a', 0);
        $raw_name = html_entity_decode($name->plaintext);

        $a = $result->find('cite', 0)->plaintext;
        array_push($urls, $a);
    }
}

function extractLinkedin($scrapedHtml, $page)
{
    $html = new simple_html_dom();
    $html->load($scrapedHtml);
    $results = $html->find('div#ires div.g');
    $final_results = array();

    foreach ($results as $result) {
        $tempResult = array();


        $h3 = $result->find('h3.r', 0);
        if (!$h3) {
            continue;
        }

        $name = $h3->find('a', 0);
        $raw_name = html_entity_decode($name->plaintext);

        $a = $result->find('cite', 0)->plaintext;
        $tempResult['url'] = $a;
        $tempResult['username'] = substr($a, strpos($a, "/in/") + 4);
        $tempResult['name'] = filterLinkedInName($raw_name);


        // extract location, company, & title from sub title
        foreach ($result->find('div.slp') as $loc) {
            // filter location from description
            $desc_split = explode(" - ", str_replace("&nbsp;-&nbsp;", ' - ', $loc->plaintext));

            if (isset($desc_split[0])) {
                $tempResult['location'] = filterLinkedInLocation($desc_split[0]);
            }

            if (isset($desc_split[1])) {
                if (strpos($desc_split[1], " at ") !== false) {
                    $tempResult['title'] = substr($desc_split[1], 0, strrpos($desc_split[1], " at "));
                } else {
                    $tempResult['title'] = $desc_split[1];
                }
            }
            if (isset($desc_split[2])) {
                $tempResult['company'] = $desc_split[count($desc_split) - 1];
            }
        }


        // if subtitle not found OR one of the (location|company|title) missing, execute this code
        foreach ($result->find('span.st') as $desc) {
            $desc_raw = strip_tags(html_entity_decode($desc));
            $company = '';

            // extracting location from detail
            if (!isset($tempResult['location'])) {
                if (strpos($desc_raw, "Location: ") !== false) {
                    $pos = strpos($desc_raw, "Location: ");
                    $pos2 = strpos($desc_raw, ";", $pos);

                    $chosenEndOfLine = -1;
                    $dotsposition = strpos($desc_raw, "...", $pos);


                    // if end of sentence for location ends with semicolon, else ...
                    if ($dotsposition && $pos2 && $dotsposition < $pos2) {
                        $chosenEndOfLine = $dotsposition;
                    } else if ($dotsposition && $pos2 < $dotsposition) {
                        $chosenEndOfLine = $pos2;
                    } else if ($pos2) {
                        $chosenEndOfLine = $pos2;
                    } else if ($dotsposition) {
                        $chosenEndOfLine = $dotsposition;
                    }


                    if ($chosenEndOfLine != -1) {
                        $location = substr($desc_raw, $pos + strlen("Location: "), $chosenEndOfLine - $pos - strlen("Location: "));
                        $tempResult['location'] = filterLinkedInLocation($location);
                    }
                }
            }


            // extracting title & company information if subtitle wasn't found
            if (!isset($tempResult['company'])) {

                if (strpos($desc_raw, " at ") !== false) {
                    $pos = strpos($desc_raw, " at ");
                    $pos2 = strpos($desc_raw, ".", $pos);


                    if (($pos2 - $pos) < 40) {
                        $tempResult['company'] = $company = substr($desc_raw, $pos + strlen(" at "), $pos2 - $pos - strlen(" at "));
                    }

                    if (!isset($tempResult['title'])) {
                        $tempString = substr($desc_raw, 0, $pos2);
                        $pos3 = strrpos($tempString, ".");
                        if ($pos3 !== false) {
                            $tempResult['title'] = substr($desc_raw, $pos3 + 1, $pos2 - $pos3 - 5 - strlen($company));
                        }
                    }
                }
            }
        }
        array_push($final_results, $tempResult);
    }
    return $final_results;
}

// filtering location from string
function filterLinkedInLocation($rawString) {
	$tempResult = array();
	$location = explode(",", $rawString);
	$i = 1;
	foreach($location as $tempLoc) {
		$tempResult['loc'.$i] = $tempLoc;
		$i++;
	}
	return $tempResult;
}



// filter linkedin name
function filterLinkedInName($rawString) {
	$name_plain = $rawString;
	if (strpos($name_plain, "-") !== false) {
		$name_plain = substr($name_plain, 0, strpos($name_plain, "-"));
	}

    if (strpos($name_plain, " | LinkedIn") !== false) {
		$name_plain = substr($name_plain, 0, strpos($name_plain, "|") - 1);
	}
	return $name_plain;
}



// access google based on parameters and return raw html or "0" in case of an error
function scrape_google($search_string, $page, $proxy)
{
    global $currentip;
    global $proxyLogs;
    global $ipaddresses;
    $ch = new_curl_session($proxy);
    global $scrape_result;
    $scrape_result = "";
    $htmdata = "";


    if ($page == 0) {
        $url = "http://google.com/search?q=$search_string&start=0&num=100&hl=en";
    } else {

        $num = $page * 100;
        $url = "http://google.com/search?q=$search_string&start=0&num=100&start=$num&hl=en";
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    if ($currentip === (count($ipaddresses) - 1)) {
        $htmdata = curl_exec($ch);
        error_log("Waiting");
        sleep(500);
    } else {
        $htmdata = curl_exec($ch);
    }

    if ($htmdata === false) {
        $error = curl_error($ch);
        return null;
    }

    curl_close($ch);


    if (strstr($htmdata, "computer virus or spyware application")) {
        error_log("IP BLOCKED: ".$currentip);
        $scrape_result = "SCRAPE_DETECTED";
        return null;
    }
    if (strstr($htmdata, "entire network is affected")) {
        error_log("IP BLOCKED: ".$currentip);
        $scrape_result = "SCRAPE_DETECTED";
        return null;
    }
    if (strstr($htmdata, "http://www.download.com/Antivirus")) {
        error_log("IP BLOCKED: ".$currentip);
        $scrape_result = "SCRAPE_DETECTED";
        return null;
    }
    if (strstr($htmdata, "Cache Access Denied.")) {
        error_log("CACHE ACCESS DENIED: ".$currentip);
        return null;
    }

    if (strstr($htmdata, "/images/yellow_warning.gif")) {
        error_log("IP BLOCKED: ".$currentip);
        $scrape_result = "SCRAPE_DETECTED";
        return null;
    }

    if (strstr($htmdata, "Our systems have detected unusual traffic from your computer ")) {
        error_log("IP BLOCKED: ".$currentip);
        $scrape_result = "SCRAPE_DETECTED";
        return null;
    }

    error_log("Scrape Success: ".$currentip);
    echo $htmdata;
    return $htmdata;
}



function mult_curl_session() {
    $mh = curl_multi_init();
    return $mh;
}

function new_curl_session($proxy)
{
    global $currentip;
    global $user_agents;

    $proxy = explode(":", $proxy);
    if (isset($ch) && ($ch != NULL))
    {
        curl_close($ch);
    }

    // if cookie directory doesn't exist, create one.
    if (!file_exists(__DIR__.'/'.$currentip)) {
        mkdir(__DIR__ . '/' . $currentip, 0777, true);
    }


    $options = [
        CURLOPT_RETURNTRANSFER => true, 	// return web page
        CURLOPT_HEADER         => true, 	//return headers in addition to content
        CURLOPT_FOLLOWLOCATION => true, 	// follow redirects
        CURLOPT_ENCODING       => "", 		// handle all encodings
        CURLOPT_AUTOREFERER    => true, 	// set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120, 		// timeout on connect
        CURLOPT_REFERER => "https://www.google.com/",
        CURLOPT_TIMEOUT        => 120, 		// timeout on response
        CURLOPT_MAXREDIRS      => 10, 		// stop after 10 redirects
        CURLINFO_HEADER_OUT    => true,
        CURLOPT_SSL_VERIFYPEER => false, 	// Disabled SSL Cert checks
        CURLOPT_COOKIESESSION => true,
        CURLOPT_COOKIEJAR => __DIR__.'/'.$currentip.'/cookie.txt',
        CURLOPT_COOKIEFILE => __DIR__.'/'.$currentip.'/cookie.txt',
        CURLOPT_USERAGENT => $user_agents[ $currentip ],
        CURLOPT_PROXY => $proxy[0],
        CURLOPT_PROXYPORT => $proxy[1]
    ];


    array_push($options, [
        CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
        CURLOPT_USERPWD => "1154roykesserwani:kesserwany1"
    ]);

    $ch = curl_init();
    curl_setopt_array( $ch, $options );
    return $ch;
}


