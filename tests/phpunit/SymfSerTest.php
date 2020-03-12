<?php

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Model\Category;
use App\Model\Person;

class SymfonyTest extends \PHPUnit_Framework_TestCase {
    /**
     * Symfony class Serializer
     */
    protected $serialize;

    /**
     * Test class Serializer
     */
    protected $person;

    /**
     * Настройки для преобразования в XML
     */
    protected $context;

    /**
     * нормализация класса Person
     */
    protected $normalizePerson = [
        "name" => "Name",
        "age" => 20,
        "sportsperson" => false,
        "categories" => [
            ["name" => "Category0"],
            ["name" => "Category1"],
            ["name" => "Category2"],
            ["name" => "Category3"]
        ],
        "createdAt" => null,
    ];

    /**
     * Вызываеться каждый раз приновом тесте 
     * Тестируется все на 1 обьекте 
     */
    public function setUp() {
        $encoders = [new XmlEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serialize = new Serializer($normalizers, $encoders);
        $categories = [];
        $name = 'Category';
        for ($i = 0; $i < 4; $i++) {
            $categories[] = new Category($name . $i);
        }
        $this -> person = new Person();
        $this->person->setName('Name');
        $this->person->setAge(20);
        $this->person->setSportsperson(false);
        $this->person->setCategories($categories);
        $this -> context = [
            'xml_root_node_name' => 'DocumentListResponse',
            'xml_format_output' => true,
        ];
    }

    /**
     * ПРоверка на создание Xml из класса
     */
    public function testXML() {        
        $res = $this->serialize->serialize($this->person, 'xml', $this->context);
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR ."file.xml", $res);
    }

    /**
     * Создание массива
     */
    public function testNormalize() {
        $res = $this->serialize->normalize($this->person);
        $this->assertEquals($this->normalizePerson, $res);
    }

    /**
     * 
     */
    public function testNormalizeToXML() {
        $res = $this->serialize->normalize($this->person);
        $this->assertEquals($this->normalizePerson, $res);

        $res = $this->serialize->serialize($res, 'xml', $this->context);
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR ."file.xml", $res);
    }

    public function testDenormalize() {
        // echo __DIR__ . DIRECTORY_SEPARATOR ."file.xml";
        $res = $this->serialize->denormalize($this->normalizePerson, Person::class, "xml");
        $categoryAdapter = new App\Adapter\CategoryAdapter();
        $denormalizeCategories = $categoryAdapter->getDenormalize($res->getCategories());
        $res->setCategories($denormalizeCategories);
        $this->assertEquals($this->person, $res);
    }
    
    
}
