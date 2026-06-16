<?php

namespace App\Helpers;

class MenuHelper
{
    public static function getMainNavItems(): array
    {
        return [
            [
                'icon' => 'dashboard',
                'name' => 'Dashboard',
                'path' => '/',
            ],
        ];
    }

    public static function getManagementNavItems(): array
    {
        $user  = auth()->user();
        $items = [];

        if ($user && $user->hasAnyRole(['Super Admin', 'Primary Teacher', 'High School Teacher'])) {
            $items[] = ['icon' => 'students', 'name' => 'Students', 'path' => '/students'];
        }

        if ($user && $user->hasRole('Super Admin')) {
            $items[] = ['icon' => 'teachers',     'name' => 'Teachers',     'path' => '/teachers'];
            $items[] = ['icon' => 'class-groups',  'name' => 'Class Groups', 'path' => '/class-groups'];
            $items[] = ['icon' => 'class',         'name' => 'Classes',      'path' => '/classes'];
        } elseif ($user && $user->hasAnyRole(['Primary Teacher', 'High School Teacher'])) {
            $items[] = ['icon' => 'teachers', 'name' => 'Teachers', 'path' => '/teachers'];
            $items[] = ['icon' => 'class',    'name' => 'Classes',  'path' => '/classes'];
        }

        return $items;
    }

    public static function getSettingsNavItems(): array
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('Super Admin')) {
            return [];
        }

        return [
            ['icon' => 'users', 'name' => 'Users',  'path' => '/users'],
            ['icon' => 'roles', 'name' => 'Roles',  'path' => '/roles'],
        ];
    }

    public static function getMenuGroups(): array
    {
        $groups = [
            [
                'title' => 'Dashboard',
                'items' => self::getMainNavItems(),
            ],
        ];

        $mgmt = self::getManagementNavItems();
        if (!empty($mgmt)) {
            $groups[] = ['title' => 'Management', 'items' => $mgmt];
        }

        $settings = self::getSettingsNavItems();
        if (!empty($settings)) {
            $groups[] = ['title' => 'Settings', 'items' => $settings];
        }

        return $groups;
    }

    public static function isActive(string $path): bool
    {
        return request()->is(ltrim($path, '/'));
    }

    public static function getIconSvg(string $iconName): string
    {
        $icons = [
            'dashboard'    => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M5.5 3.25C4.25736 3.25 3.25 4.25736 3.25 5.5V8.99998C3.25 10.2426 4.25736 11.25 5.5 11.25H9C10.2426 11.25 11.25 10.2426 11.25 8.99998V5.5C11.25 4.25736 10.2426 3.25 9 3.25H5.5ZM4.75 5.5C4.75 5.08579 5.08579 4.75 5.5 4.75H9C9.41421 4.75 9.75 5.08579 9.75 5.5V8.99998C9.75 9.41419 9.41421 9.74998 9 9.74998H5.5C5.08579 9.74998 4.75 9.41419 4.75 8.99998V5.5ZM5.5 12.75C4.25736 12.75 3.25 13.7574 3.25 15V18.5C3.25 19.7426 4.25736 20.75 5.5 20.75H9C10.2426 20.75 11.25 19.7427 11.25 18.5V15C11.25 13.7574 10.2426 12.75 9 12.75H5.5ZM4.75 15C4.75 14.5858 5.08579 14.25 5.5 14.25H9C9.41421 14.25 9.75 14.5858 9.75 15V18.5C9.75 18.9142 9.41421 19.25 9 19.25H5.5C5.08579 19.25 4.75 18.9142 4.75 18.5V15ZM12.75 5.5C12.75 4.25736 13.7574 3.25 15 3.25H18.5C19.7426 3.25 20.75 4.25736 20.75 5.5V8.99998C20.75 10.2426 19.7426 11.25 18.5 11.25H15C13.7574 11.25 12.75 10.2426 12.75 8.99998V5.5ZM15 4.75C14.5858 4.75 14.25 5.08579 14.25 5.5V8.99998C14.25 9.41419 14.5858 9.74998 15 9.74998H18.5C18.9142 9.74998 19.25 9.41419 19.25 8.99998V5.5C19.25 5.08579 18.9142 4.75 18.5 4.75H15ZM15 12.75C13.7574 12.75 12.75 13.7574 12.75 15V18.5C12.75 19.7426 13.7574 20.75 15 20.75H18.5C19.7426 20.75 20.75 19.7427 20.75 18.5V15C20.75 13.7574 19.7426 12.75 18.5 12.75H15ZM14.25 15C14.25 14.5858 14.5858 14.25 15 14.25H18.5C18.9142 14.25 19.25 14.5858 19.25 15V18.5C19.25 18.9142 18.9142 19.25 18.5 19.25H15C14.5858 19.25 14.25 18.9142 14.25 18.5V15Z" fill="currentColor"></path></svg>',
            'users'        => '<i class="fa-solid fa-users"></i>',
            'roles'        => '<i class="fa-solid fa-shield-halved"></i>',
            'students'     => '<i class="fa-solid fa-user-graduate"></i>',
            'teachers'     => '<i class="fa-solid fa-chalkboard-user"></i>',
            'class-groups' => '<i class="fa-solid fa-layer-group"></i>',
            'class'        => '<i class="fas fa-medal"></i>',
        ];

        return $icons[$iconName] ?? '';
    }
}
