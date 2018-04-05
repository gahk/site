<?php


/*$data = '[{
  "varer": [
    {
      "varetype": "enhed",
      "varenavn": "Almindelig øl",
      "varebillede": "images/produkter/produkt1.jpg",
      "enhedspris": "6.75"
    },
    {
      "varetype": "enhed",
      "varenavn": "Specialøl",
      "varebillede": "images/produkter/produkt2.jpg",
      "enhedspris": "8.25"
    },
    {
      "varetype": "ikroner",
      "varenavn": "Slik",
      "varebillede": "images/produkter/produkt1.jpg",
      "enhedspris": ""
    }
  ]
}]';*/


$data = '[
    {
      "varetype": "enhed",
      "varenavn": "Almindelig øl",
      "varebillede": "images/produkter/produkt1.jpg",
      "enhedspris": "6.75"
    },
    {
      "varetype": "enhed",
      "varenavn": "Specialøl",
      "varebillede": "images/produkter/produkt2.jpg",
      "enhedspris": "8.25"
    },
    {
      "varetype": "ikroner",
      "varenavn": "Slik",
      "varebillede": "images/produkter/produkt1.jpg",
      "enhedspris": ""
    }
]';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo $data;




?>