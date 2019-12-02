<?php $stlTableFails = '' ?>
<?php $stlLabelFails = 'display: none' ?>
<?php if (!count($list['fails'])) : ?>
  <?php $stlTableFails = 'display: none' ?>
  <?php $stlLabelFails = '' ?>
<?php endif; ?>
<?php $infoerrors = [0 => '', 2 => 'No existe en la linea base', 1 => 'No existe en la revision actual', 3 => 'Presenta diferencia respecto a la linea base'] ?>
<div class="yform">

  <h2><?php echo __('Revision') ?></h2>
  <table class="table-info" style="" id="">
    <tr>
      <th style="width: 10%;"></th>
      <th><?php echo __('Fecha') ?></th>
      <th><?php echo __('Usuario') ?></th>
    </tr>
    <tr>
      <th style="width: 10%;"><?php echo __('Revision') ?></th>
      <td><?php echo date('Y-m-d H:i:s', strtotime($trace['date'])) ?></td>
      <td><?php echo $trace['entity_name'] ?></td>
    </tr>
    <tr>
      <th style="width: 10%;"><?php echo __('Linea base') ?></th>
      <td><?php echo date('Y-m-d H:i:s', strtotime($traceBl['date'])) ?></td>
      <td><?php echo $traceBl['entity_name'] ?></td>
    </tr>
  </table>

  <fieldset style="<?php echo $stlTableFails ?>">
    <legend style="font-weight: bold;"><?php echo __('Inconsistencias de estructura') ?></legend>
    <table class="table-info" id="tableFails">
      <thead>
        <tr class="error">
          <td><?php echo __('Tabla') ?></td>
          <td><?php echo __('Huella Revision') ?></td>
          <td><?php echo __('Huella Linea Base') ?></td>
          <td><?php echo __('Error') ?></td>
        </tr>
      </thead>
      <tbody id="bodyDataFails">
        <?php foreach ($list['fails'] as $l) : ?>
          <tr>
            <td>
            <?php //echo '<pre>'; print_r($l); echo '</pre>'; ?>
            <?php echo ($l['rv_name']) ? $l['rv_name'] : $l['bl_name']?></td>
            <td><?php echo $l['rv_sha1'] ?></td>
            <td><?php echo $l['bl_sha1'] ?></td>
            <td>
              <?php echo $infoerrors[$l['type_fail']] ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </fieldset>
  <label id="lbFails" style="<?php echo $stlLabelFails ?>">
    <?php echo __("No se encontraron inconsistencias de estructura") ?>
  </label>


  <?php $stlTableFailsConf = '' ?>
  <?php $stlLabelFailsConf = 'display: none' ?>
  <?php if (!count($lconf['fails'])) : ?>
    <?php $stlTableFailsConf = 'display: none' ?>
    <?php $stlLabelFailsConf = '' ?>
  <?php endif; ?>
  <fieldset style="<?php echo $stlTableFailsConf ?>">
    <legend style="font-weight: bold;"><?php echo __('Inconsistencias de configuracion') ?></legend>
    <table class="table-info" id="tableFailsConf">
      <thead>
        <tr class="error">
          <td><?php echo __('Parametro') ?></td>
          <td><?php echo __('Huella Revision') ?></td>
          <td><?php echo __('Huella Linea Base') ?></td>
          <td><?php echo __('Error') ?></td>
        </tr>
      </thead>
      <tbody id="bodyDataFailsConf">
        <?php foreach ($lconf['fails'] as $l) : ?>
          <tr>
            <td>
            <?php //echo '<pre>'; print_r($l); echo '</pre>'; ?>
            <?php echo ($l['rv_name']) ? $l['rv_name'] : $l['bl_name']?></td>
            <td><?php echo $l['rv_sha1'] ?></td>
            <td><?php echo $l['bl_sha1'] ?></td>
            <td>
              <?php echo $infoerrors[$l['type_fail']] ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </fieldset>
  <label id="lbFailsConf" style="<?php echo $stlLabelFailsConf ?>">
    <?php echo __("No se encontraron inconsistencias de configuracion") ?>
  </label>


  <div class="type-button">
    <?php $jsShowDet = "$('tableDetail').show();$('btnShowDet').hide();$('btnHideDet').show();" ?>
    <?php $jsHideDet = "$('tableDetail').hide();$('btnShowDet').show();$('btnHideDet').hide();" ?>
    <?php echo button_to_function(__('Mostrar Detalle'), $jsShowDet, array('class' => 'btn btn-primary', 'id' => 'btnShowDet')); ?>
    <?php echo button_to_function(__('Ocultar Detalle'), $jsHideDet, array('class' => 'btn btn-primary', 'id' => 'btnHideDet', 'style' => "display: none;")); ?>
  </div>
  <fieldset style="display: none;" id="tableDetail">
    <legend style="font-weight: bold;"><?php echo __('Detalle de la revision') ?></legend>
    <table class="table-info">
      <thead>
        <tr>
          <td><?php echo __('Tabla') ?></td>
          <td><?php echo __('Huella Revision') ?></td>
          <td><?php echo __('Huella Linea Base') ?></td>
        </tr>
      </thead>
      <tbody id="bodyDataDetail">
        <?php foreach ($list['success'] as $l) : ?>
          <tr>
            <td><?php echo $l['rv_name'] ?></td>
            <td><?php echo $l['rv_sha1'] ?></td>
            <td><?php echo $l['bl_sha1'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <table class="table-info">
      <thead>
        <tr>
          <td><?php echo __('Parametro') ?></td>
          <td><?php echo __('Huella Revision') ?></td>
          <td><?php echo __('Huella Linea Base') ?></td>
        </tr>
      </thead>
      <tbody id="bodyDataDetailConf">
        <?php foreach ($lconf['success'] as $l) : ?>
          <tr>
            <td><?php echo $l['rv_name'] ?></td>
            <td><?php echo $l['rv_sha1'] ?></td>
            <td><?php echo $l['bl_sha1'] ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </fieldset>
</div>