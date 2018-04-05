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


$data = '{
  "alumner": [
    {
      "navn": "Orla Frøsmapper",
      "alumneid": "10",
      "oelsaldo": "15",
      "beboertype": "alumne"
    },
    {
      "navn": "Wesley Snipes",
      "alumneid": "11",
      "oelsaldo": "202",
      "beboertype": "alumne"
    },
    {
      "navn": "Kanye West",
      "alumneid": "12",
      "oelsaldo": "112",
      "beboertype": "alumne"
    },
    {
      "navn": "Michelle Obama",
      "alumneid": "65",
      "oelsaldo": "-60",
      "beboertype": "alumne"
    },
    {
      "navn": "Otto Brandenburg",
      "alumneid": "99",
      "oelsaldo": "-42",
      "beboertype": "fremlejer"
    }
  ]
}';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
echo $data;




?>