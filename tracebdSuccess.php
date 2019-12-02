<div class="col-md-2">
  <?php include_component('phsUser', 'printMenu', array('accId' => $sf_user->getAttribute('accId', '', 'phsAuthSecurity'))) ?>
</div>
<div class="col-md-10">
  <h1><?php echo __('Listado de revisiones') ?></h1>
  <div id="divMessage"></div>
  <div class="yform">
    <div class="type-button">
      <?php $js = lightview(__("Agregar Huella"), '', url_for('manager/tracebdShowAdd'),
          array(), array('height' => 200, 'width' => 300)) ?>
      <button class="btn-primary" onclick="<?php echo $js ?>">
        <i class="fa fa-plus"></i>
        <?php echo __('Agregar Huella') ?>
      </button>
    </div>
    <table class="table-info" style="margin:0;">
      <thead>
        <tr>
          <td><?php echo __('Fecha') ?></td>
          <td><?php echo __('Hora') ?></td>
          <td><?php echo __('Tipo') ?></td>
          <td><?php echo __('Usuario') ?></td>
          <td style="width:1%"></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($list as $l) : ?>
          <tr>
            <td><?php echo date('Y-m-d', strtotime($l['date'])) ?></td>
            <td><?php echo date('H:i:s', strtotime($l['date'])) ?></td>
            <td><?php echo ($l['trace_type']=='BL') ? __('LINEA BASE') : ('REVISION') ?></td>
            <td><?php echo $l['entity_name'] ?></td>
            <td>
              <?php $jsdet = lightview(__("Detalle"), '', url_for('manager/tracebdDet'), array('traceId' => $l['id']), array('height' => 'max', 'width' => 'max')) ?>
              <?php echo button_to_function(__('Detalle'), $jsdet, array('class' => 'btn btn-xs btn-success')); ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>
<script type="text/javascript">
  function generateTraceBd(urlSave, traceType) {
  var params = 'traceType='+traceType;
  new Ajax.Request(urlSave, {
    method: 'post',
    parameters: params,
    evalScripts: true,
    asynchronous: false,
    onCreate: function() {
      showDivLoading();
    },
    onSuccess: function(transport, json) {
      var responseContent = transport.responseText;
      if (json.state == 'failure') {
        alert('Error');
      } else if (json.state == 'success') {
        location.reload();
        Lightview.hide();
      }
      hideDivLoading();
    }
  });
}
</script>