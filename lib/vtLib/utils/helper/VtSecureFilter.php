<?php
/**
 * Created by JetBrains PhpStorm.
 * User: tuanbm
 * Date: 12/18/12
 * Time: 9:32 AM
 * To change this template use File | Settings | File Templates.
 */
class VtSecureFilter extends sfFilter
{
  public function execute($filterChain) {

    $context = $this->getContext();
    $request = $context->getRequest();

    $moduleName = $request->getParameter('module');
    $actionName = $request->getParameter('action');

    if ($context->getController()->actionExists($moduleName, $actionName)) {

      $action = $context->getController()->getAction(
        $moduleName,
        $actionName
      );

      if ($action->getSecurityValue('is_ssl', false) && !$request->isSecure()) {

        //$arrayData = parse_url($request->getUri());
         $myUrl=$request->getUri();
//        $myUrl = str_replace(':8080', '', $request->getUri());
        $secure_url = str_replace('http', 'https', $myUrl);

        return $context->getController()->redirect($secure_url);

      } else if (!$action->getSecurityValue('is_ssl', false) && $request->isSecure()) {

        $arrayData = parse_url($request->getUri());
//        var_dump($arrayData);
        $not_secure_url = str_replace('https', 'http', $request->getUri());

        return $context->getController()->redirect($not_secure_url);
      }
    }

    $filterChain->execute();
  }
}
