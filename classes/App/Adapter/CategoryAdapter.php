<?php

namespace App\Adapter;

use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Model\Category;

class CategoryAdapter {
    
    /**
     * массив
     * @param array $array
     */
    public function getDenormalize($array) {
        $encoders = [new XmlEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers);
        $result = array();
        foreach ($array as $element) {
            $result[] = $serializer->denormalize($element, Category::class, "xml");
        }
        return $result;
    }
}
