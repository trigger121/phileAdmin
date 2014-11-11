<?php include 'partials/header.php'; ?>
<section id="content" class="content">
	<div class="breadcrumb">
		<ul>			
			<li><a href="<?php echo $base_url ?>/admin/photos">Photos</a></li><span class="oi" data-glyph="chevron-right"></span>
			
			<?php if(isset($_GET['gallery-name'])){?>
				<li><?php 
					$galname = $_GET['gallery-name'];
					
					echo $photos[$galname]->slugged;
				?></li>
			
			<?php }else{?>
				<li>Listing Photos</li>
			
			<?php } ?>
		</ul>
	</div>
	<div class="clearfix"></div>
	<div class="editor-controls">
		<a href="#" id="upload-files" class="hint--top" data-hint="Upload File"><span class="oi" data-glyph="data-transfer-upload"></span></a>
		<a href="#" id="download-selected" class="hint--top" data-hint="Download Selected"><span class="oi" data-glyph="data-transfer-download"></span></a>
		<a href="#" id="view-selected" class="hint--top" data-hint="View Selected"><span class="oi" data-glyph="image"></span></a>
		<a href="#" id="file-info" class="hint--top" data-hint="File Info"><span class="oi" data-glyph="info"></span></a>
		<a href="#" class="hint--top" data-hint="Clear Selected" id="clear-selected"><span class="oi" data-glyph="ban"></span></a>
		<a href="#" class="hint--top" data-hint="Delete File" id="delete-photos"><span class="oi" data-glyph="x"></span></a>
		<a href="#" class="hint--top last" data-hint="Create Folder" id="create-folder"><span class="oi" data-glyph="new-folder"></span></a>
		<label class="range-input"><input type="range" id="column-count" value="6" step="2" min="2" max="6"><span id="column-count-val">6</span></label>
	</div>
	<form action="upload" method="post" enctype="multipart/form-data" class="dropzone center form" id="media-upload">
		<?php	if(isset($_GET['gallery-name'])){	?>
			<input type="hidden" name="url" value="<?php echo $_GET['gallery-name']?>"/>
		<?php	}?>
		<div class="drop-icon"><span class="oi" data-glyph="data-transfer-upload"></span></div>
	</form>
	<div class="photo-list columns-6">
		<?php 
			if($photos !== false){
				if(!isset($_GET['gallery-name'])){
					foreach($photos as $photo){ ?>
							
							<div class="photo-item" id="<?php echo $photo->slug ?>">							
								<?php if(property_exists($photo,'children')){ ?>
									<a href="<?php echo $base_url ?>/admin/photos?gallery-name=<?php echo $photo->name ?>"><img src="<?php echo $photo->url ?>" width="<?php echo $photo->info[0] ?>" height="<?php echo $photo->info[1] ?>"></a>
									<p><input type="checkbox" name="" value="<?php echo $photo->slug ?>" data-url="<?php echo $photo->path ?>"> <?php echo $photo->slugged ?></p>
								<?php }else{?>
									<img src="<?php echo $photo->url ?>" width="<?php echo $photo->info[0] ?>" height="<?php echo $photo->info[1] ?>">
									<p><input type="checkbox" name="" value="<?php echo $photo->slug ?>" data-url="<?php echo $photo->path ?>"> <?php echo $photo->name ?></p>
								<?php } ?>							
								
							</div>
					<?php }

				}else{?>
						
						<?php 
						$galname = $_GET['gallery-name'];
						
							if(array_key_exists($galname,$photos)){
								if(property_exists($photos[$galname],'children')){
									 foreach($photos[$galname]->children as $c => $child){?>
										
										<div class="photo-item" id="<?php echo $child->slug ?>">
											<img src="<?php echo $child->url ?>" width="<?php echo $child->info[0] ?>" height="<?php echo $child->info[1] ?>">
											<p><input type="checkbox" name="" value="<?php echo $child->slug ?>" data-url="<?php echo $child->path ?>"> <?php echo $child->name ?></p>
										</div>							
									<?php 
									 }
								}
							}else{
								?>
								<h1>You have made a wrong turn please turn around</h1>
								<h2><a href="<?php echo $base_url.'/admin/photos' ?>">Photos</a></h2>
								<?php
							}
						
				}
			}?>
	</div>
</section>
<?php include 'partials/footer.php'; ?>
