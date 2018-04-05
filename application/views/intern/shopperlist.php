<? $started = false; ?>[
<? foreach($shoppers as $shopper): ?>
  <? if ($started): ?>,<? endif; ?>{
    "navn": "<?=$shopper->name?>",
    "shopperid": "<?=$shopper->id?>",
    "oelsaldo": "0",
    "beboertype": "alumne"
  } <? $started = true; ?>
<? endforeach; ?>
  ,{
    "navn": "Louise Brünniche Lund",
    "shopperid": "99001",
    "oelsaldo": "0",
    "beboertype": "fremlejer"
  }
  ,{
    "navn": "Adam Andreev",
    "shopperid": "99002",
    "oelsaldo": "0",
    "beboertype": "fremlejer"
  }
]
