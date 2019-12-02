<?php

/**
 * manager actions.
 *
 * @package    pacific-gbl
 * @subpackage manager
 * @author     Charly Aguirre Manzano
 */
class managerActions extends sfActions
{
  public function preExecute()
  {
    $request = $this->getRequest();
    if ($this->getUser()->isAuthenticated()) {
      $userId = $this->getUser()->getAttribute('userId', '', 'phsAuthSecurity');
      $acc    = $this->getUser()->getAttribute('accId', '', 'phsAuthSecurity');

      $params      = ['oid' => PROJECT_OID, 'acc' => $acc, 'usr' => $userId];
      $urlRest     = preg_replace('/edu|demo|oncologia/', "", sfConfig::get('sf_host')) . sfConfig::get('sf_phs_suite_route') . '/credentials';
      $rest        = new RestRequest($urlRest, 'POST', $params);
      $credentials = $rest->execute();

      if ($credentials['state'] == 'SUCCESS') {
        $this->getUser()->clearCredentials();
        $this->getUser()->saveCredentials($credentials['credentials']);
      } else {
        $this->forward('phsAuth', 'secure');
      }
    } else {
      $this->forward('phsAuth', 'signIn');
    }
  }

  public function executeTracebd(sfWebRequest $request)
  {
    $this->account = (int) $this->getUser()->getAttribute('accId', '', 'phsAuthSecurity');
    $this->list = HandlerTraceStructBD::listTraces();
  }

  public function executeTracebdDet(sfWebRequest $request)
  {
    $account = (int) $this->getUser()->getAttribute('accId', '', 'phsAuthSecurity');
    $traceId = $request->getParameter('traceId');

    $trace   = HandlerTraceStructBD::getTraceById($traceId);
    $list    = [];
    if ($trace['trace_type']=='BL') {
      $list  = HandlerTraceStructBD::getTraceBlDetailById($traceId);
      $lconf = HandlerTraceStructBD::getTraceBlConfigById($traceId);
      return $this->renderPartial('manager/tracebdDet', ['trace' => $trace, 'list' => $list, 'lconf' => $lconf]);
    }
    if ($trace['trace_type']=='RV') {
      $traceBl = HandlerTraceStructBD::getTraceLnForTraceVf($trace['date']);
      $list    = HandlerTraceStructBD::getTraceRvDetailById($traceBl['id'], $traceId);
      $lconf   = HandlerTraceStructBD::getTraceRvConfigById($traceBl['id'], $traceId);
      return $this->renderPartial('manager/tracebdDetRv', ['trace' => $trace, 'list' => $list, 'traceBl' => $traceBl, 'lconf' => $lconf]);
    }
    return sfView::NONE;
  }

  public function executeTracebdShowAdd(sfWebRequest $request)
  {
    return $this->renderPartial('manager/tracebdShowAdd');
  }

  public function executeTracebdCreate(sfWebRequest $request)
  {
    $account = $this->getUser()->getAttribute('accId', '', 'phsAuthSecurity');
    $userId  = $this->getUser()->getAttribute('userId', '', 'phsAuthSecurity');
    $entity  = PhsGblAccountUser::getEntByAccUser($userId, $account);
    
    $type    = $request->getParameter('traceType');
    $trace   = HandlerTraceStructBD::generateTrace($type, $entity);

    $this->getResponse()->setHttpHeader("X-JSON", '{"state": "success"}');
    return $this->renderText('');
  }
}
