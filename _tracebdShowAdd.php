
<?php $types = ['BL' => 'LINEA BASE', 'RV' => 'REVISION']?>
<div class="yform">
  <div class="type-select">
    <label><?php echo __("Seleccione el tipo de huella") ?></label>
    <?php echo select_tag('trace_type', options_for_select($types, 'RV'), array('class' => 'fixed', 'style' => 'min-width:100px;')); ?>
  </div>

  <div class="type-button">
    <?php $urlSave = url_for('manager/tracebdCreate') ?>
    <?php $jsSave  = "generateTraceBd('".$urlSave."', $('trace_type').value)" ?>
    <?php echo button_to_function(__('Generar Huella'), $jsSave, array('class' => 'btn btn-primary')); ?>
    <?php echo button_to_function(__('Cancelar'), "closeWindows();", array('class' => 'btn')); ?>
  </div>
</div>