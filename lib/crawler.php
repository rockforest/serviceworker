<?php 

//ライブラリロード
require_once './vendor/autoload.php';

//use
use Goutte\Client;

//インスタンス生成
$client = new Client();

//取得とDOM構築
$crawler = $client->request('GET','http://sweet.2ch.sc/headline/');

$dom = array();
$dom = $crawler->filter('li')->each(function($node) {

	//タイトル
	$title = $node->filter('a')->text();
	//日付
	$date = $node->filter('var')->text();
	//リンク先
	$link_url = $node->filter('a')->attr('href');

	return array('title'=>$title, 'date'=>$date, 'link_url'=>$link_url);

});

echo json_xencode($dom);

function json_xencode($value, $options = 0, $unescapee_unicode = true)
{
  $v = json_encode($value, $options);
  if ($unescapee_unicode) {
    $v = unicode_encode($v);
    // スラッシュのエスケープをアンエスケープする
    $v = preg_replace('/\\\\\//', '/', $v);
  }
  return $v;
}

function unicode_encode($str)
{
  return preg_replace_callback("/\\\\u([0-9a-zA-Z]{4})/", "encode_callback", $str);
}

function encode_callback($matches) {
  return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UTF-16");
}


?>