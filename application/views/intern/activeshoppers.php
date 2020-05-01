<? $started = false; ?>[
<? foreach($shoppers as $shopper): ?>
  <? if ($started): ?>,<? endif; ?>{
    "name": "<?=$shopper->name?>",
    "shopper_id": "<?=$shopper->shopperId?>",
    "alumnumId": "<?=$shopper->alumnumId?>",
    "saldo": "<?=$shopper->saldo?>"
  } <? $started = true; ?>
<? endforeach; ?>
]
