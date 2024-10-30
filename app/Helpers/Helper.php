<?php

namespace App\Helpers;

use Carbon\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Helper
{

    function dateFormat($date)
    {
        try {
            if ($date != null) {
                return Carbon::parse($date)->format('d-m-y');
            } else {
                return '';
            }
        } catch (\Throwable) {
            return '';
        }
    }


    function chunk($data, $length = 2, $separator = " ")
    {
        if (empty($data)) return "";

        return chunk_split($data, $length,  $separator);
    }

    function getCurrentRole()
    {
        $user = auth()->user();

        if ($user->roles->isEmpty()) {
            return  "Sin Rol Asignado";
        } else {
            $roleName = $user->roles->first()->name;
            return  strtoupper($roleName);
        }
    }

    function roleHasAllPermissions($roleName)
    {
        // Obtiene el rol
        $role = Role::findByName($roleName);

        // Obtiene todos los permisos
        $allPermissions = Permission::all()->pluck('name');

        // Obtiene los permisos del rol
        $rolePermissions = $role->permissions->pluck('name');

        // Comprueba si el rol tiene todos los permisos
        return $allPermissions->diff($rolePermissions)->isEmpty();
    }


    function overdue($startDate, $endDate)
    {
        try {

            $sD = Carbon::parse($startDate);
            $eD = Carbon::parse($endDate);

            $days = $sD->diffInDays($eD);

            return  $days;
        } catch (\Throwable $th) {
            return '-0';
        }
    }

    function resetCache()
    {
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
