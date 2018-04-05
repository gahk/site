<h1 class="page-header">
Værelse <?=$roomId?><small> Besvar</small>
</h1>
<div id="errorMessage"></div>
<div id="warningMessage"></div>
<div class="col-sm-8">&nbsp;</div>
<div class="col-sm-4">
							<select name="roomConditionsSelect" class="form-control" id="roomConditionsSelect">
								<? foreach($conditions as $condition): ?>
									<option value="<?=$condition->date?>">
										<?=$condition->alumne_fullname?> &nbsp;&nbsp; - &nbsp;&nbsp; <?=$condition->date?>
									</option>
								<? endforeach; ?>
							</select>
</div>

<div class="col-sm-8">
<?=form_open("nyintern/vaerelsestjek/indsend/$roomId", array('class' => 'form-vertical', 'method'=>"post", 'enctype'=>"multipart/form-data"))?>

<? foreach ($criteria as $crit): ?> 
	<div class="form-group">

		<label for="<?=$crit->id?>"><?=$crit->name?></label>	
		<div class="row">
			<div class="col-sm-2">
		<select class="form-control" id="selected<?=$crit->id?>" name="selected<?=$crit->id?>" disabled>
				<?if($crit->options==3):?>
						<option value="0">0</option>
			<option value="1">1</option>
						<option value="2">2</option>
		<?elseif($crit->options > 2): ?>
			
			<? for ($i=1; $i <= $crit->options; $i++): ?>
				<option value="<?=$i?>"><?=$i?></option>
			<? endfor; ?>

		<?else: ?>
			<option value="0">0</option>
			<option value="1">1</option>
		<? endif; ?>
		</select>
	</div>
<div class="col-sm-5">
		<textarea cols="40" rows="3" id="comment<?=$crit->id?>" name="comment<?=$crit->id?>" disabled></textarea>
	</div>
<div class="col-sm-2">
        <button class="btn btn-default showImages" name="<?=$crit->id?>" data-toggle="modal" data-target="#imageModal<?=$crit->id?>" >
          <span class="glyphicon glyphicon-picture" ></span> Billeder
        </button>
</div>

		<div class="col-sm-3">
		<button class="btn btn-default update" name="<?=$crit->id?>"><span class="glyphicon glyphicon-pencil"></span>Opdater</button>
	</div>
		 </div>
		 <small class="form-text text-muted"><?=$crit->description?></small>

	</div>

<!-- Modal -->
<div id="imageModal<?=$crit->id?>" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?=$crit->name?> : Uploadede billeder</h4>
      </div>
      <div class="modal-body">
      	<div id="imageContainer<?=$crit->id?>">
      	</div>
      	<div id="gallery<?=$crit->id?>"></div>
      </div>
      <div class="modal-footer">

      	<input style="display:none;" id="savedImages<?=$crit->id?>" name ="savedImages<?=$crit->id?>">
       	<input type="file" multiple name="userfile<?=$crit->id?>[]" class="imageUpload" data-id="<?=$crit->id?>">
       	
    </div>

  </div>
</div>
</div>

<? endforeach;?>



<script>
$(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
        	$(placeToInsertImagePreview).empty();
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {

                	var img = $("<img></img>");
                	img.attr('class','img');
                	img.attr('src',event.target.result);

                	$(placeToInsertImagePreview).append(img);

                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };
    $(document).on('change','.imageUpload',function(){

    	imagesPreview(this, '#gallery'+$(this).data('id'));
    });

});






$(function(){
	$(".showImages").click(function(event){
		event.preventDefault();

	});


});

</script>

<!--  <div class="form-group">
    <label for="walls">Tilstand af vægge</label>
    <select class="form-control" id="walls" name="walls">
      <option value="1" >1</option>
      <option value="2" >2</option>
      <option value="3" >3</option>
      <option value="4" >4</option>
      <option value="5" >5</option>
    </select>
    <small id="emailHelp" class="form-text text-muted">Her står noget om skalaen</small>
  </div>-->
<button type="submit" id="btnSave" class="btn btn-primary">Indsend</button>

<?php echo form_close(); ?>






<script>
	$(function(){
		$("#roomConditionsSelect").change(function() {
			var chosendate = $("#roomConditionsSelect").val();
			setFormData(chosendate);
			

		});

		$(".update").click(function(event){
			event.preventDefault();
			var id = $(this).attr("name");
			$("#comment"+id).attr("disabled",false);
			$("#selected"+id).attr("disabled",false);



		});

		$("#btnSave").click(function(){
			$(":input").prop('disabled', false);
			$(":textarea").prop('disabled', false);
		});

	});

	$(function(){

			setFormData($("#roomConditionsSelect").val());


		

		if("<?php print $errormessage?>" != ""){
			$("#errorMessage").text("<?php print $errormessage?>");
			$("#errorMessage").attr("class", "alert alert-danger");
		}
		else{
			$("#errorMessage").text("");
			$("#errorMessage").attr("class", "");
		}









		$(document).on('click','.img',function(){
			$(this).toggleClass('enlarge');

		});

		$(document).on('click','.removeImage',function(event){
			event.preventDefault();


			var condition = $(this).data("condition");
			var savedImagesContainer = $("#savedImages"+condition);
			console.log(savedImagesContainer.val());
			savedImagesContainer.val(savedImagesContainer.val().replace($(this).data("src")+";",""));
			console.log($(this).data("src"));
			$(this).parent().empty();

		});

		
	});

	function setFormData(chosendate){
		var conditions = <?php echo json_encode($conditions); ?>;
			$.each(conditions, function(i,val){

				if(val.date==chosendate){
					//this is the selected condition
					var splitconditions = val.criteria.split(';');
					$.each(splitconditions,function(j,condition){
						var parts = condition.split(':');

						$("#selected"+parts[0]).val(parts[1]);


					});


					var splitcomments = val.comments.split(';');
					$.each(splitcomments,function(j,comment){
						var parts = comment.split(':');

						$("#comment"+parts[0]).val(parts[1]);


					});

					var imageList = val.images.split('|');

					$.each(imageList,function(j,cv){

						var ps = cv.split(':');
						var condition = ps[0];
						if(ps[1]!=undefined){

						$.each(ps[1].split(';'),function(i,v){
							if(v!=""){


							var div = $("<div></div>");

							var img = $("<img></img>");
							img.attr('src','https://www.gahk.dk/'+v);
							img.attr('class','img');

							var b = $("<button></button>");
							b.attr("class","removeImage btn btn-default");
							b.html("x");
							b.attr("data-src",v);
							b.attr("data-condition",condition);

							$("#savedImages"+condition).val($("#savedImages"+condition).val()+v+";");




							div.append(b);
							div.append(img);
							$("#imageContainer"+condition).append(div);
						}

						});
					}

					});




				}

			});

	}

</script>





