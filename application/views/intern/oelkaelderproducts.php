<? $started = false; ?>[
<? foreach($products as $product): ?>
  <? if ($started): ?>,<? endif; ?>{
    "productId": "<?=$product->productId?>",
    "name": "<?=$product->name?>",
    "current_price": "<?=($product->current_price/100)?>",
    <? $price_steps = explode(";", $product->price_steps); ?>
    "price_steps1": "<?=($price_steps[0]/100)?>",
    "price_steps2": "<?=($price_steps[1]/100)?>",
    "price_steps3": "<?=($price_steps[2]/100)?>",
    "price_steps4": "<?=($price_steps[3]/100)?>",
    "weight_price": "<?=($product->weight_price/100)?>",
    "imageurl": "<?=$product->imageurl?>",
    "active": "<?=$product->active?>",
    "highlighted": "<?=$product->highlighted?>"
  } <? $started = true; ?>
<? endforeach; ?>
]
