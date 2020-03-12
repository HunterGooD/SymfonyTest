<?php

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

require_once '__autoload.php';
/**
 * Тестовый файл в дальнейшем будет удален
 */
$encoders = [new XmlEncoder()];
$normalizers = [new ObjectNormalizer()];

$serializer = new Serializer($normalizers, $encoders);

// $categories = [];
// $name = 'Category';
// for ($i = 0; $i < 4; $i++) {
//     $categories[] = new \App\Model\Category($name . $i);
// }
// $person = new \App\Model\Person();
// $person->setName('Name');
// $person->setAge(20);
// $person->setSportsperson(false);
// $person->setCategories($categories);
// $context = [
//     'xml_root_node_name' => 'DocumentListResponse',
//     'xml_format_output' => true,
// ];
// echo $serializer->serialize($categories, 'xml',$context);
// print_r($serializer->normalize($person));
// $xmlContent = $serializer->serialize($person, 'xml', [
//     'xml_root_node_name' => 'DocumentListResponse',
//     'xml_format_output' => true,
//     'xml-stylesheet' => [
//         '@type' => 'text/xsl',
//         '@href' => 'stylesheets/TestApp/DocumentList.xsl'
//     ]
// ]);
// $fp = fopen("file.xml", "w");
// fwrite($fp, $xmlContent);
// fclose($fp);
// $personNormalizer = $serializer->normalize($person);
// print_r($personNormalizer);
// echo $xmlContent . "\r\n";
echo \App\Model\Person::class;
$person = $serializer->deserialize(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR ."file.xml"), \App\Model\Person::class, 'xml');
var_dump($person);
var_dump($person->getCategories());

