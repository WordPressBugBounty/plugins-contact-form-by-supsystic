<?php
class admin_navControllerCfs extends controllerCfs
{
  public function getPermissions()
  {
    return [
      CFS_USERLEVELS => [
        CFS_ADMIN => [],
      ],
    ];
  }
}
