<?php

namespace ForceCMS\Controller\Plugin;

use ForceCMS\Controller\Plugin\Model\AclResource,
    ForceCMS\Controller\Plugin\Model\Role;

class Acl extends \Zend_Controller_Plugin_Abstract {
    
    public function preDispatch(\Zend_Controller_Request_Abstract $request) {
        
        $request = $this->getRequest();

        $aclResource = new AclResource();

        // Check if the request is valid and controller an action exists.
        // If not redirects to an error page.
        if(!$aclResource->resourceValid($request)) {
            $request->setControllerName('error');
            $request->setActionName('error');
            return;
        }

        // Get resources from request
        // Check if the requested resource exists in database. 
        // If not it will create it.
        $controller = $request->getControllerName();
        $action = $request->getActionName();
        if(!$aclResource->resourceExists($controller, $action)) {
            $aclResource->createResource($controller, $action);
        }

        // Get role if user is logged-in else set role to 'Guest'
        $role = Role::getRoleFromSessionOrSetDefault();

        $role_id = $role['id'];
        $role = $role['role'];

        // setup acl
        $acl = new \Zend_Acl();
        $acl->addRole(new \Zend_Acl_Role($role));
        $acl->addResource(new \Zend_Acl_Resource('cms'));

        if(!preg_match('/^admin_/', $controller) && $role == Role::GUEST) {
            $acl->allow($role, $controller, $action);

        } else {

            if ($role_id == Role::SUPERADMIN) {
                $acl->allow($role, null);
                $resources = $aclResource->getAllResources();
                foreach ($resources as $resource) {
                    $acl->add(new \Zend_Acl_Resource($resource->controller));
                }
            } else {
                // create all the existing resources
                // and add it to acl resource list
                $resources = $aclResource->getAllResources();
                foreach ($resources as $resource) {
                    $acl->add(new \Zend_Acl_Resource($resource->getController()));
                }
                //Create user AllowedResources and add user permissions to ACL
                $userAllowedResources = $aclResource->getCurrentRoleAllowedResources($role_id);
                foreach ($userAllowedResources as $controllerName => $allowedActions) {
                    $arrayAllowedActions = [];
                    foreach ($allowedActions as $allowedAction) {
                        $arrayAllowedActions[] = $allowedAction;
                    }
                    $acl->allow($role, $controllerName, $arrayAllowedActions);
                }
            }

            // save acl to zend registry for using in application
            \Zend_Registry::set('acl', $acl);

            if (!$acl->isAllowed($role, $controller, $action)) {
                $request->setControllerName('error');
                $request->setActionName('noauth');
                return;
            }
        }
    }
}
