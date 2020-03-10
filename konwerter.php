<?php

/*

importer dla WP "by the way" v. 0.0.4
ostatnia modyfikacja: 2010-06-20
copyright 2010 Marcin Kowol
marcin@kowol.pl

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

Niniejszy program jest wolnym oprogramowaniem; mo¿esz go
rozprowadzaæ dalej i/lub modyfikowaæ na warunkach Powszechnej
Licencji Publicznej GNU, wydanej przez Fundacjê Wolnego
Oprogramowania - wed³ug wersji 2 tej Licencji lub (wed³ug twojego
wyboru) której¶ z pó¼niejszych wersji.

Niniejszy program rozpowszechniany jest z nadziej±, i¿ bêdzie on
u¿yteczny - jednak BEZ JAKIEJKOLWIEK GWARANCJI, nawet domy¶lnej
gwarancji PRZYDATNO¦CI HANDLOWEJ albo PRZYDATNO¦CI DO OKRE¦LONYCH
ZASTOSOWAÑ. W celu uzyskania bli¿szych informacji siêgnij do
Powszechnej Licencji Publicznej GNU.

Z pewno¶ci± wraz z niniejszym programem otrzyma³e¶ te¿ egzemplarz
Powszechnej Licencji Publicznej GNU (GNU General Public License);
je¶li nie - napisz do Free Software Foundation, Inc., 59 Temple
Place, Fifth Floor, Boston, MA  02110-1301  USA

*/

if (!(isset($_GET['plik'])))	{
    echo '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml2/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
	<title>by the way v. 0.0.1</title>
  <meta name="robots" content="none" >
	
	<style type="text/css">
		
	</style>
	
</head>

<body>
		
<div class="pudlo">
  <form enctype="application/x-www-form-urlencoded" action="konwerter.php" method="get">
    <div class="row">
      <span class="label">nazwa pliku do importu (musi znajdowaæ siê w g³ównym folderze WP na serwerze): </span><span
class="formw"><input type="text" name="plik" size="20" /></span> bez rozszerzenia (domy¶lnie dodawane .txt) - tylko pliki tekstowe np. z Worda lub Notatnika z kodowaniem UTF-8
    </div>
    <div class="row">
      <span class="label">ile dodaæ godzin opó¼nienia w publikacji kolejnych postów: </span><span
class="formw">od <input type="text" name="od" size="2" /> do <input type="text" name="do" size="2" /></span> - przedzia³ dla losowania
    </div>
    <div class="row">
      <hr /><span style="color:red;">tu koñcz± siê pola obowi±zkowe, poni¿ej s± opcje</span><hr />
    </div>
    <div class="row">
      <span class="label">tytu³ i tre¶æ posta: </span>
			<span
class="formw"><input type="radio" name="podzial" value="naprzemiennie" default> podawane w kolejnych akapitach</span>
			<span
class="formw"><input type="radio" name="podzial" value="wiersz" default> wyci±gane z jednego akapitu</span> - je¶li nic nie zaznaczysz, skrypt zrobi z ka¿dego akapitu zarówno tytu³, jak i tre¶æ (bêd± takie same)
    </div>
    <div class="row">
      <hr /><span style="color:red;">opcje poni¿ej dotycz± tylko przypadku, kiedy powy¿ej wybrano opcjê "tytu³ i tre¶æ posta: wyci±gane z jednego akapitu"</span><hr />
    </div>
    <div class="row">
      <span class="label">kolejno¶æ informacji w jednym akapicie</span>
			<span
class="formw"><input type="radio" name="kolejnosc" value="tp" default> tytu³ - post</span>
			<span
class="formw"><input type="radio" name="kolejnosc" value="pt" default> post - tytu³</span>
    </div>
    <div class="row">
      <span class="label">ci±g znaków rozdzielaj±cy akapit: </span><span
class="formw"><input type="text" name="dzielnik" size="20" /></span> (znaki te s± usuwane, a akapit dzielony na dwie czê¶ci sprzed i po usuniêtym ci±gu: tytu³ i tre¶æ)
    </div>
    <div class="row">
      <span class="label">data startowa: </span><span
class="formw">dzieñ (dwie cyfry): <input type="text" name="dzien" size="2" /> miesi±c (dwie cyfry): <input type="text" name="miesiac" size="2" /> rok (cztery cyfry): <input type="text" name="rok" size="2" /></span> - je¶li pozostawisz pola puste, wpisy zaczn± byæ publikowane od chwili zaimportowania
    </div>
    <div class="row">
      <hr /><span style="color:red;">SPRAWD¬ WPISANE WARTO¦CI! - skrypt jeszcze nie sprawdza b³êdów, importuje co dostanie</span><hr />
    </div>
		<div class="row">
			<input type="submit" value="importuj" /> 
		</div>
    <div class="row">
      <hr /><span style="color:silver;">skrypt jeszcze nie podaje kategorii, w tej chwili opcja ta nie jest jeszcze poprawnie zrobiona</span><hr />
    </div>
	</form>
</div>

</body>
</html>';

} else {

	include("wp-blog-header.php");
	include("wp-admin/includes/taxonomy.php");

	function fopen_utf8 ($filename, $mode) {
    $file = @fopen($filename, $mode);
    $bom = fread($file, 3);
    if ($bom != b"\xEF\xBB\xBF") { rewind($file, 0); }
		return $file;
	}
	
// definiowanie kategorii

	$kat = array(
	0 => "inne",
	1 => "A",
	2 => "B",
	3 => "C",
	4 => "D",
	5 => "E",
	6 => "F",
	7 => "G",
	8 => "H",
	9 => "I",
	10 => "J",
	11 => "K",
	12 => "L",
	13 => "M",
	14 => "N",
	15 => "O",
	16 => "P",
	17 => "Q",
	18 => "R",
	19 => "S",
	20 => "T",
	21 => "U",
	22 => "W",
	23 => "V",
	24 => "X",
	25 => "Y",
	26 => "Z",
	);

	for ($i = 0; ($i < count($kat)); $i++) {
		$id_kategorii = wp_create_category($kat[$i]);
		$kategorie[$kat[$i]] = $id_kategorii;
	}

	$plik = $_GET['plik'] . '.txt';
	$podzial = $_GET['podzial'];
	$kolejnosc = $_GET['kolejnosc'];
	$dzielnik = $_GET['dzielnik'];
	$od = (int)$_GET['od'];
	$do = (int)$_GET['do'];
	
	if ($_GET['dzien'] == '')	{
		$poczatek = new DateTime();
	} else {
		$dzien = $_GET['dzien'];
		$miesiac = $_GET['miesiac'];
		$rok = $_GET['rok'];
		$poczatek = new DateTime($rok . '-' . $miesiac . '-' . $dzien);
	}
	$kontrolka = 0;
	
	if ($podzial == '') {
		$uchwyt = @fopen_utf8($plik, "r");
		if ($uchwyt) {
			while (!feof($uchwyt)) {
				$buffer = fgets($uchwyt);
	// Create post object
				$my_post = array();
				$my_post['post_title'] = $buffer;
				$my_post['post_content'] = $buffer;
				$my_post['post_status'] = 'future';
				$my_post['post_author'] = 1;
				$my_post['post_category'] = array($kategorie[strtoupper(strtr(substr($buffer,0,1),"¡ÆÊ£ÑÓ¦¯¬±æê³ñó¶¿¼","ACELNOSZZACELNOSZZ"))]);
				if ($kontrolka == 0) {
					$my_post['post_date'] = date_format($poczatek, 'Y-m-d H:i:s');
					$kontrolka = 1;
					$poczatek -> modify('+' . rand($od, $do) . ' hours');
				} else {
					$my_post['post_date'] = date_format($poczatek, 'Y-m-d H:i:s');
					$poczatek -> modify('+' . rand($od, $do) . ' hours');
				}
	// Insert the post into the database
				wp_insert_post( $my_post );
			}
		}
		fclose ($uchwyt);
	} elseif ($podzial == 'naprzemiennie') {
		$uchwyt = @fopen_utf8 ($plik, "r");
		if ($uchwyt) {
			while (!feof($uchwyt)) {
				$buffer1 = fgets($uchwyt);
				$buffer2 = fgets($uchwyt);
	// Create post object
				$my_post = array();
				$my_post['post_title'] = $buffer1;
				$my_post['post_content'] = $buffer2;
				$my_post['post_status'] = 'future';
				$my_post['post_author'] = 1;
				$my_post['post_category'] = array($kategorie[strtoupper(strtr(substr($buffer1,0,1),"¡ÆÊ£ÑÓ¦¯¬±æê³ñó¶¿¼","ACELNOSZZACELNOSZZ"))]);
				if ($kontrolka == 0) {
					$my_post['post_date'] = date_format($poczatek, 'Y-m-d H:i:s');
					$kontrolka = 1;
					$poczatek -> modify('+' . rand($od, $do) . ' hours');
				} else {
					$my_post['post_date'] = date_format($poczatek, 'Y-m-d H:i:s');
					$poczatek -> modify('+' . rand($od, $do) . ' hours');
				}
	// Insert the post into the database
				wp_insert_post( $my_post );
			}
		}
		fclose ($uchwyt);
	} elseif ($podzial == 'wiersz') {
		$uchwyt = @fopen_utf8 ($plik, "r");
		if ($uchwyt) {
			while (!feof($uchwyt)) {
				$buffer = fgets($uchwyt);
				$buffer1 = substr($buffer, 0, (strpos($buffer, $dzielnik)));
				$buffer2 = substr($buffer, (strpos($buffer, $dzielnik) + strlen($dzielnik)));
	// Create post object
				$my_post = array();
				if ($kolejnosc == 'tp') {
					$my_post['post_title'] = $buffer1;
					$my_post['post_content'] = $buffer2;
					$my_post['post_category'] = array($kategorie[strtoupper(strtr(substr($buffer1,0,1),"¡ÆÊ£ÑÓ¦¯¬±æê³ñó¶¿¼","ACELNOSZZACELNOSZZ"))]);
					} elseif ($kolejnosc == 'pt') {
					$my_post['post_title'] = $buffer2;
					$my_post['post_content'] = $buffer1;
					$my_post['post_category'] = array($kategorie[strtoupper(strtr(substr($buffer2,0,1),"¡ÆÊ£ÑÓ¦¯¬±æê³ñó¶¿¼","ACELNOSZZACELNOSZZ"))]);
				}				
				$my_post['post_status'] = 'future';
				$my_post['post_author'] = 1;
				if ($kontrolka == 0) {
					$my_post['post_date'] = date_format($poczatek, 'Y-m-d H:i:s');
					$kontrolka = 1;
					$poczatek -> modify('+' . rand($od, $do) . ' hours');
				} else {
					$my_post['post_date'] = date_format($poczatek, 'Y-m-d H:i:s');
					$poczatek -> modify('+' . rand($od, $do) . ' hours');
				}
	// Insert the post into the database
				wp_insert_post( $my_post );
			}
		}
		fclose ($uchwyt);
	}
	echo 'zaimportowano';
}

?>