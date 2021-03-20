<!DOCTYPE html>
<html lang="it">

<head>
<style>
    table {
        width:100%;
    }
    table, th, td {
        border: 1px solid black;
        border-collapse: collapse;
    }
    th, td {
        padding: 15px;
        text-align: center;
    }
    #domainTable tr:nth-child(even) {
        background-color: #fff;
    }
    #domainTable tr:nth-child(odd) {
        background-color: #eee;
        font-size: 170%;
    }
    #domainTable th {
        background-color: black;
        color: white;
    }
</style>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>
    <h2>Whois</h2>
    <form action="" method="post">
        Inserire il Domino qui: <input type="text" name="text_domain" placeholder="Es. digiform.it" value="<?php echo isset($_POST['text_domain']) ? $_POST['text_domain']: '' ?>" />
        <input type="submit" name="submit" value="Cerca" /><br><br>
        <label id="reslbl" style="align-content: center"></label>
    </form>
    <table id="domainTable">
    <tbody></tbody>
    </table>
    <?php
    // https://github.com/sparc/phpWhois.org
    include_once('src/whois.main.php');
	include_once('src/whois.utils.php');

	$whois = new Whois();
    // fast whois
    // whois->deep_whois = false;
    
        if ( isset($_POST['submit']) && !empty($_POST['text_domain']) )
        {
            $text_domain = $_POST['text_domain'];
            // check domain
            if (strpos($text_domain,".") < 1) {
                $text_domain = strtolower($text_domain);
                echo "<script>
                document.getElementById('reslbl').style.color = 'red';
                document.getElementById('reslbl').innerHTML = 'Nome del dominio non valido.';
                </script>";
                return;
            }
            $slit_domain = explode(".", $text_domain);
            $text_domain = $slit_domain[0];
            echo "<script>
            var table = document.getElementById('domainTable').getElementsByTagName('tbody')[0];
            </script>";
            // dell all row
            echo "<script>
            var totalRowCount = table.rows.length;
            for (i = 0; i < totalRowCount; i++) {
                document.getElementById('domainTable').deleteRow(0);
            }
            </script>";

            $arr = array(
            		"it" => "whois.nic.it",
                   "com" => "whois.internic.net",
                   "eu"  => "whois.registry.eu",
                   "net"  => "whois.internic.net",
                   "org"  => "whois.publicinterestregistry.net",
                   "info" => "whois.afilias.net",
                   "biz"  => "whois.neulevel.biz"
                   );
            // I use the function whois from functions_whois.php
            echo "<script>
            const arr = ['.it', '.com', '.eu', '.net', '.org', '.info', '.biz'];
            var row = table.insertRow(-1);
            var i = 0;
            </script>";
			// add the header row.
            foreach ($arr as $key => $value) {
                echo "<script>
                var cell = row.insertCell(-1);
                cell.innerHTML = arr[i];
                i++;
                </script>";
            }

			// add data row
            echo "<script>
                var row = table.insertRow(-1);
            </script>";
            foreach ($arr as $key => $value) {
                echo "<script>
                var cell = row.insertCell(-1);
                </script>";
                // Domain Available?
                // $whois->UseServer($key, $value);
                $result = $whois->Lookup($text_domain . "." . $key);
                if (!empty($result['regrinfo']) && !empty($result['regrinfo']['registered']))
				{
					$regrinfo = $result['regrinfo']['registered'];
				}
                if ($regrinfo == 'no' && !checkdnsrr($text_domain . "." . $key, 'ANY')) {
                    echo "<script>
                    cell.innerHTML = 'LIBERO';
                    cell.style.color = 'green';
                    </script>";
                }
                else {
                    echo "<script>
                    cell.innerHTML = 'OCCUPATO';
                    cell.style.color = 'red';
                    </script>";
                }
            }
        }
    ?>
</body>
</html>
