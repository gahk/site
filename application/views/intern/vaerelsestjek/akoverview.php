

<script src="<?=base_url('public/intern/js/buttons/dataTables.buttons-1.4.2.min.js')?>"></script>

<script src="<?=base_url('public/intern/js/buttons/buttons.flash.min.js')?>"></script>
<script src="<?=base_url('public/intern/js/ajax/jszip.min.js')?>"></script>
<script src="<?=base_url('public/intern/js/buttons.html5.min.js')?>"></script>
<script src="<?=base_url('public/intern/js/buttons/buttons.colVis.min.js')?>"></script>
 <link rel="stylesheet" href="<?=base_url('public/intern/css/buttons.dataTables.min.css')?>" />


<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li ><a href="<?= base_url('nyintern/vaerelsestjek') ?>">Personlig</a></li>
		  <li class="active"><a href="<?= base_url('nyintern/vaerelsestjek/akoverview') ?>">AK Oversigt</a></li>
		</ul>
</div>

<h1 class="page-header">AK
<small> Oversigt</small>
</h1>

<table id="conditionTable" class="table table-striped ">
<thead>
<th>Værelse</th>
<th>Dato</th>
<th>Alumne</th>
<? foreach ($criteria as $crit): ?> 
<th><?=$crit->name?></th>
<? endforeach; ?>	

</thead>
<tbody>
<? foreach ($roomConditions as $condition): ?> 
<tr>
<td>
<a href="<?=base_url("/nyintern/vaerelsestjek/besvar/".$condition->room_id)?>"><?=$condition->room_id?></a>
</td>
<td><?= $condition->date?></td>
<td><?=$condition->alumne_fullname?></td>
<? foreach (explode(";",$condition->criteria) as $crit): ?> 
<? if($crit!=""):?>
<td>
	<?=explode(":",$crit)[1]?>
</td>
<?endif;?>
<? endforeach;?>

</tr>
<? endforeach; ?>	
</tbody>

	</table>

		<script>

		$(document).ready(function(){
		    $('#conditionTable').DataTable({
				     "order": [[ 0, "desc" ]],
				     
				     dom: 'Bfrtip',
				     columnDefs: [
			            {
			                targets: 1,
			                className: 'noVis'
			            }
			        ],
			        buttons: [
			            'csv', 'excel',
			            {
						extend: 'colvis',
                		columns: ':not(.noVis)'

			            }
			        ]

				}
		    	);
		});
	</script>