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
    "vareid": "1",
    "varetype": "enhed",
    "varenavn": "Grøn Tuborg",
    "varebillede": "images/produkter/tuborg.jpg",
    "enhedspris": "6.75"
  },
  {
    "vareid": "2",
    "varetype": "enhed",
    "varenavn": "Classic & Guld øl",
    "varebillede": "images/produkter/classic_guld.jpg",
    "enhedspris": "8.25"
  },
  {
    "vareid": "3",
    "varetype": "ikroner",
    "varenavn": "Slik",
    "varebillede": "images/produkter/slik.jpg",
    "enhedspris1": "0.5",
    "enhedspris2": "1",
    "enhedspris3": "5",
    "enhedspris4": "10"
  },
  {
    "vareid": "4",
    "varetype": "enhed",
    "varenavn": "Popcorn",
    "varebillede": "images/produkter/popcorn.jpg",
    "enhedspris": "5"
  },
  {
    "vareid": "5",
    "varetype": "enhed",
    "varenavn": "Sodavand",
    "varebillede": "images/produkter/sodavand.jpg",
    "enhedspris": "5"
  },
  {
    "vareid": "6",
    "varetype": "enhed",
    "varenavn": "Vin",
    "varebillede": "images/produkter/vin.jpg",
    "enhedspris": "70"
  },
  {
    "vareid": "7",
    "varetype": "enhed",
    "varenavn": "Red Bull",
    "varebillede": "images/produkter/redbull.jpg",
    "enhedspris": "12"
  },
  {
    "vareid": "8",
    "varetype": "enhed",
    "varenavn": "Sommersby",
    "varebillede": "images/produkter/sommersby.jpg",
    "enhedspris": "20"
  },
  {
    "vareid": "9",
    "varetype": "enhed",
    "varenavn": "Tyggegummi",
    "varebillede": "images/produkter/tyggegummi.jpg",
    "enhedspris": "6"
  },
  {
    "vareid": "10",
    "varetype": "enhed",
    "varenavn": "Chips",
    "varebillede": "images/produkter/chips.jpg",
    "enhedspris": "16"
  },
  {
    "vareid": "11",
    "varetype": "enhed",
    "varenavn": "Cocio",
    "varebillede": "images/produkter/billedemangler.jpg",
    "enhedspris": "16"
  },
  {
    "vareid": "12",
    "varetype": "enhed",
    "varenavn": "Dyrere specialøl",
    "varebillede": "images/produkter/billedemangler.jpg",
    "enhedspris": "25"
  },
  {
    "vareid": "13",
    "varetype": "ikroner",
    "varenavn": "Anden vare",
    "varebillede": "images/produkter/billedemangler.jpg",
    "enhedspris1": "0.5",
    "enhedspris2": "1",
    "enhedspris3": "5",
    "enhedspris4": "10"
  }
]';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo $data;




?>