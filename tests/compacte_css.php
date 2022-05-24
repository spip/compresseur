<?php
/**
 * Test unitaire de minifier_css
 * du fichier inc/compresseur_minifier
 *
 */

$test = 'minifier_css';
$remonte = "";
while (!is_file($remonte."test.inc") and !is_dir($remonte.'ecrire/'))
	$remonte = $remonte."../";
foreach ([$remonte."test.inc", $remonte."tests/test.inc", $remonte."tests/tests/legacy/test.inc"] as $f) {
	if (is_file($f)){
		require $f;
		break;
	}
}
if (!defined('_SPIP_TEST_INC')) {
	die('Impossible de trouver test.inc depuis ' .getcwd());
}
$ok = true;

include_spip('inc/compresseur_minifier');
include_spip('inc/filtres');

lire_fichier(__DIR__ . '/css/source.css', $css_code);

// test du compacteur simple
lire_fichier(__DIR__ . '/css/expected.css', $expected);

$compacte = minifier_css($css_code);
if (rtrim($compacte) != rtrim($expected)) {
	erreur('minifier_css()', $compacte, $expected);
	$ok = false;
}

// le compacteur simple doit donner le meme resultat
// avec un media all
$compacte = minifier_css($css_code, 'all');
if (rtrim($compacte) != rtrim($expected)) {
	erreur("minifier_css('all')", $compacte, $expected);
	$ok = false;
}

lire_fichier(__DIR__ . '/css/expected_more.css', $expected);
$compacte = minifier_css($css_code, array());
if (rtrim($compacte) != rtrim($expected)) {
	erreur('minifier_css(array())', $compacte, $expected);
	$ok = false;
}

lire_fichier(__DIR__ . '/css/expected_more_screen.css', $expected);
$compacte = minifier_css($css_code, 'screen');
if (rtrim($compacte) != rtrim($expected)) {
	erreur("minifier_css('screen')", $compacte, $expected);
	$ok = false;
}

$compacte = minifier_css($css_code, array('media' => 'screen'));
if (rtrim($compacte) != rtrim($expected)) {
	erreur("minifier_css(array('media'=>'screen'))", $compacte, $expected);
	$ok = false;
}

lire_fichier(__DIR__ . '/css/expected_highest_screen.css', $expected);
$compacte = minifier_css($css_code, array('media' => 'screen', 'template' => 'highest'));
if (rtrim($compacte) != rtrim($expected)) {
	erreur("minifier_css(array('media'=>'screen','template'=>'highest'))", $compacte, $expected);
	$ok = false;
}

lire_fichier(__DIR__ . '/css/source_simple.css', $css_code);

lire_fichier(__DIR__ . '/css/expected_simple.css', $expected);
$compacte = minifier_css($css_code, 'screen');
if (rtrim($compacte) != rtrim($expected)) {
	erreur("minifier_css('screen')", $compacte, $expected);
	$ok = false;
}


if ($ok) {
	echo 'OK';
}

function erreur($titre, $result, $expected) {
	echo "Erreur $titre<br />";
	echo "<tt>Resultat:</tt><pre>$result</pre>";
	echo "<tt>Attendu :</tt><pre>$expected</pre>";
}
