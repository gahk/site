
<div class="pull-right">
		<ul class="nav nav-pills pull-right">
		  <li class="active"><a href="<?= base_url('nyintern/vaerelsestjek') ?>">Personlig</a></li>
		  <li><a href="<?= base_url('nyintern/vaerelsestjek/akoverview') ?>">AK Oversigt</a></li>
		</ul>
</div>


<h1 class="page-header">Værelsestjek <small> Oversigt</small></h1>

<div class="row text-center">
	<div class="col-sm-6">
<img src="<?=base_url('public/image/intern/stuen.png') ?>" class="floorimage">
<div id="stuenbtns" class="btn-group"></div>
</div>
	<div class="col-sm-6" >
<img src="<?=base_url('public/image/intern/1. sal.png') ?>" class="floorimage">
<div id="sal1btns" class="btn-group"></div>
</div>
</div>

<div class="row text-center">
	<div class="col-sm-6">
<img src="<?=base_url('public/image/intern/2. sal.png') ?>" class="floorimage">
<div id="sal2btns" class="btn-group"></div>
</div>
	<div class="col-sm-6" >
<img src="<?=base_url('public/image/intern/3. sal.png') ?>" class="floorimage">
<div id="sal3btns" class="btn-group"></div>
</div>
</div>

<div class="row text-center">
	<div class="col-sm-6">
<img src="<?=base_url('public/image/intern/4. sal.png') ?>" class="floorimage">
<div id="sal4btns" class="btn-group"></div>
</div>

</div>
<script>



function createFloorButtons(id, floorNumber, numberOfRooms){
	var firstRoom = floorNumber*100+1;
	for(i=firstRoom;i<firstRoom+numberOfRooms;i++){
		var but = document.createElement('a');


		but.className = 'btn btn-default';
		but.innerText = i.toString();

		but.id = i.toString();
		but.setAttribute("href","<?= base_url('nyintern/vaerelsestjek/besvar/"+but.id+"') ?>");
		$(id).append(but);
	}
}



$(function(){
	//stuen
	for(i=1;i<=10;i++){
		var but = document.createElement('a');


		but.className = 'btn btn-default';

		if(i!=10){
			but.innerText = "00"+i.toString();

		}
		else but.innerText = "010";

		but.id = but.innerText;
		but.setAttribute("href","<?= base_url('nyintern/vaerelsestjek/besvar/"+but.id+"') ?>");
		$("#stuenbtns").append(but);
	}
	//other floors
	createFloorButtons("#sal1btns",1,14);
	createFloorButtons("#sal2btns",2,14);
	createFloorButtons("#sal3btns",3,14);
	createFloorButtons("#sal4btns",4,9);


});



</script>
