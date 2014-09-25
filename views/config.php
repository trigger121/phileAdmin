<?php include 'partials/header.php'; ?>
    <section id="content" class="content">
      <div class="breadcrumb">
        <ul>
         <li><a href="<?php echo $base_url ?>/admin/config"><?php echo $title ?></a></li><span class="oi" data-glyph="chevron-right"></span>
          <li>Change Config Values</li>
        </ul>
      </div>
      <div class="clearfix"></div>
	  <form name="form_config" id="form_config">
		  <table class="item-list" id="configX">
			<colgroup>
			<col span="1" style="width: 5%;">
			<col span="1" style="width: 40%;">
			<col span="1" style="width: 55%;">
		  </colgroup>
		  <thead>
			<tr>
			  <th></th>
			  <th>Key</th>
			  <th>Value</th>
			</tr>
		  </thead>
		  <tbody>
		  <?php foreach ($config as $key => $value): ?>
			<tr>
			  <td align="center" class="actions">
				<a class="btn red small hint--right delete-setting" data-hint="Delete Key"><span class="oi" data-glyph="delete"></span></a>
			  </td>
			  <td><?php echo $key ?></td>
			  <td><input type="text" name="config[<?php echo $key ?>]" value="<?php echo $value ?>" placeholder="<?php echo $value ?>" class="input-100"></td>
			</tr>
		  <?php endforeach; ?>
		  </tbody>
		</table>
	</form>
    <div class="editor-buttons">
		<button type="button" class="btn blue add-setting" data-url="configX">Add Setting</button>
		<button type="button" class="btn green save-config">Save Settings</button>
    </div>
  </section>
<?php include 'partials/footer.php'; ?>
