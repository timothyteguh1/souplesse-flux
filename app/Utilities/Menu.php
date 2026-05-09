<?php

namespace App\Utilities;

class Menu
{
    private $listMenu;

    public function __construct($listMenu)
    {
        $this->listMenu = $listMenu;
    }

    public function getListMenu()
    {
        return $this->listMenu;
    }

    public function getAllPermissions()
    {
        $permissions = [];
        foreach ($this->listMenu as $menu) {
            $permissions = array_merge($permissions, $menu['permissions'] ?? []);

            foreach ($menu['children'] ?? [] as $child) {
                $permissions = array_merge($permissions, $child['permissions'] ?? []);

                foreach ($child['children'] ?? [] as $grandChild) {
                    $permissions = array_merge($permissions, $grandChild['permissions'] ?? []);
                }
            }
        }

        return $permissions;
    }

    public function getChildrenPermissions($item)
    {
        $permissions = [];
        foreach ($item['children'] as $child) {
            $permissions = array_merge($permissions, $child['permissions'] ?? []);

            foreach ($child['children'] ?? [] as $grandChild) {
                $permissions = array_merge($permissions, $grandChild['permissions'] ?? []);
            }
        }

        return $permissions;
    }

    public function getChildrenActiveRoutes($item)
    {
        $routes = [];
        foreach ($item['children'] as $child) {
            $routes = array_merge($routes, [$child['active']]);

            foreach ($child['children'] ?? [] as $grandChild) {
                $routes = array_merge($routes, is_array($grandChild['active']) ? $grandChild['active'] : [$grandChild['active']]);
            }
        }

        return $routes;
    }

    public function getChildrenBadgeCount($item)
    {
        $count = 0;
        foreach ($item['children'] as $child) {
            $count += $child['badge'] ?? 0;

            foreach ($child['children'] ?? [] as $grandChild) {
                $count += $grandChild['badge'] ?? 0;
            }
        }

        return $count;
    }
}
