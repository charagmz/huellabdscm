<?php

/**
 * manager actions.
 *
 * @package    pacific-gbl
 * @subpackage manager
 * @author     Charly Aguirre Manzano
 */
class HandlerTraceStructBD
{
  
  public static function listTraces()
  {
    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT t.*, e.entity_name
                  FROM trace_struct t
                  LEFT JOIN gbl_entity e ON t.gbl_entity_id=e.id
                  ORDER BY t.date DESC";

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetchAll(PDO::FETCH_ASSOC);
    //echo '<pre>';print_r($data);echo '</pre>';
    return $data;
  }

  public static function getTraceById($traceId)
  {
    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT t.*, e.entity_name
                  FROM trace_struct t
                  LEFT JOIN gbl_entity e ON t.gbl_entity_id=e.id
                  WHERE t.id=".$traceId;

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetch(PDO::FETCH_ASSOC);
    //echo '<pre>';print_r($data);echo '</pre>';
    return $data;
  }

  public static function getTraceBlDetailById($traceId)
  {
    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT dbl.id, dbl.table_name, dbl.sha1_info, dbl.struct_info
                  FROM trace_struct_det dbl
                  WHERE dbl.trace_struct_id=".$traceId."
                  ORDER BY dbl.table_name";

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetchAll(PDO::FETCH_ASSOC);
    //echo '<pre>';print_r($data);echo '</pre>';
    return $data;
  }

  public static function getTraceBlConfigById($traceId)
  {
    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT dbl.id, dbl.var_name, dbl.sha1_info, dbl.struct_info
                  FROM trace_struct_conf dbl
                  WHERE dbl.trace_struct_id=".$traceId."
                  ORDER BY dbl.var_name";

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetchAll(PDO::FETCH_ASSOC);
    //echo '<pre>';print_r($data);echo '</pre>';
    return $data;
  }


  public static function getTraceRvDetailById($traceRvId, $traceBlId)
  {
    if (!$traceBlId) {
      $traceBlId = 0;
    }

    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT dbl.id bl_id, dbl.table_name bl_name, dbl.sha1_info bl_sha1, drv.id rv_id, drv.table_name rv_name, drv.sha1_info rv_sha1, dbl.struct_info bl_info, drv.struct_info rv_info,
                    CASE WHEN dbl.id IS NULL THEN 1
                         WHEN drv.id IS NULL THEN 2
                         WHEN dbl.sha1_info<>drv.sha1_info THEN 3
                         ELSE 0 END AS TYPE_FAIL
                  FROM (SELECT a.* FROM trace_struct_det a WHERE a.trace_struct_id=".$traceBlId.") dbl
                  FULL OUTER JOIN (SELECT b.* FROM trace_struct_det b WHERE b.trace_struct_id=".$traceRvId.") drv ON dbl.table_name=drv.table_name
                  --WHERE (dbl.id IS NULL OR drv.id IS NULL OR dbl.sha1_info<>drv.sha1_info)
                  ORDER BY dbl.table_name";

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetchAll(PDO::FETCH_ASSOC);

    //echo '<pre>';print_r($data);echo '</pre>';
    $result = ['success' => [], 'fails' => []];
    foreach ($data as $dt) {
      if ($dt['type_fail']) {
        $result['fails'][] = $dt;
      } else {
        $result['success'][] = $dt;
      }
    }
    return $result;
  }

  public static function getTraceRvConfigById($traceRvId, $traceBlId)
  {
    if (!$traceBlId) {
      $traceBlId = 0;
    }

    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT dbl.id bl_id, dbl.var_name bl_name, dbl.sha1_info bl_sha1, drv.id rv_id, drv.var_name rv_name, drv.sha1_info rv_sha1, dbl.struct_info bl_info, drv.struct_info rv_info,
                    CASE WHEN dbl.id IS NULL THEN 1
                         WHEN drv.id IS NULL THEN 2
                         WHEN dbl.sha1_info<>drv.sha1_info THEN 3
                         ELSE 0 END AS TYPE_FAIL
                  FROM (SELECT a.* FROM trace_struct_conf a WHERE a.trace_struct_id=".$traceBlId.") dbl
                  FULL OUTER JOIN (SELECT b.* FROM trace_struct_conf b WHERE b.trace_struct_id=".$traceRvId.") drv ON dbl.var_name=drv.var_name
                  --WHERE (dbl.id IS NULL OR drv.id IS NULL OR dbl.sha1_info<>drv.sha1_info)
                  ORDER BY dbl.var_name";

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetchAll(PDO::FETCH_ASSOC);

    //echo '<pre>';print_r($data);echo '</pre>';
    $result = ['success' => [], 'fails' => []];
    foreach ($data as $dt) {
      if ($dt['type_fail']) {
        $result['fails'][] = $dt;
      } else {
        $result['success'][] = $dt;
      }
    }
    return $result;
  }

  public static function getTraceLnForTraceVf($dateVf)
  {
    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');
    $sqlquery   = "SELECT bl.*, e.entity_name
                  FROM trace_struct bl
                  LEFT JOIN gbl_entity e ON bl.gbl_entity_id=e.id
                  WHERE bl.trace_type='BL' AND bl.date<='".$dateVf."'
                  ORDER BY bl.date DESC
                  LIMIT 1";

    $data = $connection->prepare($sqlquery);
    $data->execute();

    $data = $data->fetch(PDO::FETCH_ASSOC);
    //echo '<pre>';print_r($data);echo '</pre>';
    return $data;
  }



  public static function generateTrace($type, $entity)
  {
    $connection = sfContext::getInstance()->getDatabaseConnection('doctrine');

    $sqlquery   = "SELECT nextval('trace_struct_id_seq'::regclass);";
    $data = $connection->prepare($sqlquery);
    $data->execute();
    $data = $data->fetch(PDO::FETCH_ASSOC);
    $traceId = $data['nextval'];

    $sqlquery   = "INSERT INTO trace_struct VALUES (".$traceId.", now(), '".$type."', ".$entity.");";
    $data = $connection->prepare($sqlquery);
    $data->execute();

    $sqlquery = "INSERT INTO trace_struct_det 
                  SELECT nextval('trace_struct_det_id_seq'::regclass), ".$traceId.", dt.table_name, encode(digest(dt.dt_info,'sha1'), 'hex'), dt.dt_info
                FROM (
                SELECT inf.table_name, listbreak(
                    COALESCE(inf.column_name, '')||' '||
                    COALESCE(inf.column_default, '')||' '||
                    COALESCE(inf.is_nullable, '')||' '||
                    COALESCE(inf.data_type, '')||' '||
                    COALESCE(inf.character_maximum_length, -1)||' '||
                    COALESCE(inf.character_octet_length, -1)||' '||
                    COALESCE(inf.numeric_precision, -1)||' '||
                    COALESCE(inf.numeric_precision_radix, -1)||' '||
                    COALESCE(inf.numeric_scale, -1)||' '||
                    COALESCE(inf.datetime_precision, -1)) AS dt_info
                  FROM (
                      SELECT s.table_name, s.column_name, s.column_default, s.is_nullable, s.data_type, s.character_maximum_length, s.character_octet_length, s.numeric_precision, s.numeric_precision_radix, s.numeric_scale, s.datetime_precision
                      FROM information_schema.columns s
                      ORDER BY s.table_name, s.column_name
                  ) inf
                  GROUP BY inf.table_name
                  ORDER BY inf.table_name
                ) dt;";
    $data = $connection->prepare($sqlquery);
    $data->execute();

    $sqlquery = "INSERT INTO trace_struct_conf 
                  SELECT nextval('trace_struct_conf_id_seq'::regclass), ".$traceId.", dt.var_name, encode(digest(dt.dt_info,'sha1'), 'hex'), dt.dt_info
                FROM (
                  SELECT s.var_name, encode(digest(s.value,'sha1'), 'hex') sha1_info, s.value dt_info
                  FROM gbl_account_config s
                  ORDER BY s.var_name
                ) dt;";
    $data = $connection->prepare($sqlquery);
    $data->execute();

    return true;
  }

    
}

