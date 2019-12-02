<?php $stlTable = '' ?>
<?php $stlLabel = 'display: none' ?>
<?php if (count($list)<1) : ?>
  <?php $stlTable = 'display: none' ?>
  <?php $stlLabel = '' ?>
<?php endif; ?>
<div class="yform">

  <h2><?php echo __('Linea Base') ?></h2>
  <table class="table-info" style="" id="">
    <tr>
      <th style="width: 10%;"><?php echo __('Linea Base') ?></th>
      <td><?php echo date('Y-m-d H:i:s', strtotime($trace['date'])) ?></td>
    </tr>
    <tr>
      <th style="width: 10%;"><?php echo __('Usuario') ?></th>
      <td><?php echo $trace['entity_name'] ?></td>
    </tr>
  </table>

  <table class="table-info" style="<?php echo $stlTable ?>" id="">
    <thead>
      <tr>
        <td><?php echo __('Tabla')?></td>
        <td><?php echo __('Huella')?></td>
      </tr>
    </thead>
    <tbody id="">
      <?php foreach ($list as $l) : ?>
        <tr>
          <td><?php echo $l['table_name'] ?></td>
          <td><?php echo $l['sha1_info'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <label id="" style="<?php echo $stlLabel ?>">
    <?php echo __("No hay registros de estructura") ?>
  </label>

  <?php $stlTableConf = '' ?>
  <?php $stlLabelConf = 'display: none' ?>
  <?php if (count($list)<1) : ?>
    <?php $stlTableConf = 'display: none' ?>
    <?php $stlLabelConf = '' ?>
  <?php endif; ?>

  <table class="table-info" style="<?php echo $stlTableConf ?>" id="">
    <thead>
      <tr>
        <td><?php echo __('Parametro')?></td>
        <td><?php echo __('Huella')?></td>
      </tr>
    </thead>
    <tbody id="">
      <?php foreach ($lconf as $c) : ?>
        <tr>
          <td><?php echo $c['var_name'] ?></td>
          <td><?php echo $c['sha1_info'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
  <label id="" style="<?php echo $stlLabelConf ?>">
    <?php echo __("No hay registros de configuracion") ?>
  </label>
</div>