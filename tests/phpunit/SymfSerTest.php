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
    protected $normalizePerson;

    /**
     * Хранит расположение фалйа xml
     * @var string
     */
    protected $file;

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
        $this->person = new Person();
        $this->person->setName('Name');
        $this->person->setAge(20);
        $this->person->setSportsperson(false);
        $this->person->setCategories($categories);
        $this->context = [
            'xml_root_node_name' => 'DocumentListResponse',
            'xml_format_output' => true,
        ];
        $this->normalizePerson = $this->serialize->normalize($this->person);
        $this->file = __DIR__ . DIRECTORY_SEPARATOR . "file.xml";
    }

    /**
     * из класса в Xml 
     */
    public function testXML() {
        $res = $this->serialize->serialize($this->person, 'xml', $this->context);
        $this->assertXmlStringEqualsXmlFile($this->file, $res);
    }

    /**
     * из класса в массив
     */
    public function testNormalize() {
        $res = $this->serialize->normalize($this->person);
        $this->assertEquals($this->normalizePerson, $res);
    }

    /**
     * Из класса в xml через массив
     */
    public function testNormalizeToXML() {
        $res = $this->serialize->normalize($this->person);
        $this->assertEquals($this->normalizePerson, $res);
        $res = $this->serialize->encode($res, 'xml', $this->context);
        $this->assertXmlStringEqualsXmlFile(__DIR__ . DIRECTORY_SEPARATOR . "file.xml", $res);
    }

    /**
     * Из xml в класс
     */
    public function testDeserialize() {
        $res = $this->serialize->deserialize(file_get_contents($this->file), Person::class, "xml");
        $res->dezerializeCategory();
        $res->deserializeBoolean();
        $this->assertEquals($res->getCategories(), $this->person->getCategories());
        $this->assertEquals($this->person, $res);
    }

    /**
     * из массива в класс
     */
    public function testDenormalize() {
        // echo __DIR__ . DIRECTORY_SEPARATOR ."file.xml";
        $res = $this->serialize->denormalize($this->normalizePerson, Person::class, "xml");
        $res->dezerializeCategory();
        $this->assertEquals($this->person, $res);
    }

    /**
     * Из xml в класс через массив
     */
    public function testDenormalizeToClass() {
        $res = $this->serialize->decode(file_get_contents($this->file), "xml");
        $res["sportsperson"] = false;
        $res['createdAt'] = null;
        $this->assertEquals($this->normalizePerson, $res);
        $res = $this->serialize->denormalize($res, Person::class, "xml");
        $res->dezerializeCategory();
        $res->deserializeBoolean();
        $this->assertEquals($res->getCategories(), $this->person->getCategories());
        $this->assertEquals($this->person, $res);
    }

}
