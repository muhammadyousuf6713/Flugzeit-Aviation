<?php

namespace Juzaweb\CMS\Traits\Permission;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Juzaweb\CMS\Contracts\Permission;
use Juzaweb\CMS\Contracts\Role;
use Juzaweb\CMS\Exceptions\GuardDoesNotMatch;
use Juzaweb\CMS\Exceptions\PermissionDoesNotExist;
use Juzaweb\CMS\Exceptions\WildcardPermissionInvalidArgument;
use Juzaweb\CMS\Support\Permission\Guard;
use Juzaweb\CMS\Support\Permission\PermissionRegistrar;
use Juzaweb\CMS\Support\Permission\WildcardPermission;

trait HasPermissions
{
    /** @var string */
    private $permissionClass;

    public static function bootHasPermissions(): void
    {
        static::deleting(
            function ($model) {
                if (method_exists($model, 'isForceDeleting') && ! $model->isForceDeleting()) {
                    return;
                }

                $model->permissions()->detach();
            }
        );
    }

    public function getPermissionClass()
    {
        if (! isset($this->permissionClass)) {
            $this->permissionClass = app(PermissionRegistrar::class)->getPermissionClass();
        }

        return $this->permissionClass;
    }

    /**
     * A model may have multiple direct permissions.
     */
    public function permissions(): BelongsToMany
    {
        $relation = $this->morphToMany(
            config('permission.models.permission'),
            'model',
            config('permission.table_names.model_has_permissions'),
            config('permission.column_names.model_morph_key'),
            PermissionRegistrar::$pivotPermission
        );

        if (! PermissionRegistrar::$teams) {
            return $relation;
        }

        return $relation->wherePivot(PermissionRegistrar::$teamsKey, getPermissionsTeamId());
    }

    /**
     * Scope the model query to certain permissions only.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePermission(Builder $query, $permissions): Builder
    {
        $permissions = $this->convertToPermissionModels($permissions);

        $rolesWithPermissions = array_unique(
            array_reduce(
                $permissions,
                function ($result, $permission) {
                    return array_merge($result, $permission->roles->all());
                },
                []
            )
        );

        return $query->where(
            function (Builder $query) use ($permissions, $rolesWithPermissions) {
                $query->whereHas(
                    'permissions',
                    function (Builder $subQuery) use ($permissions) {
                        $permissionClass = $this->getPermissionClass();
                        $key = (new $permissionClass())->getKeyName();
                        $subQuery->whereIn(
                            config('permission.table_names.permissions').".$key",
                            \array_column($permissions, $key)
                        );
                    }
                );

                if (count($rolesWithPermissions) > 0) {
                    $query->orWhereHas(
                        'roles',
                        function (Builder $subQuery) use ($rolesWithPermissions) {
                            $roleClass = $this->getRoleClass();
                            $key = (new $roleClass())->getKeyName();
                            $subQuery->whereIn(
                                config('permission.table_names.roles').".$key",
                                \array_column($rolesWithPermissions, $key)
                            );
                        }
                    );
                }
            }
        );
    }

    /**
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return array
     * @throws \Juzaweb\CMS\Exceptions\PermissionDoesNotExist
     */
    protected function convertToPermissionModels($permissions): array
    {
        if ($permissions instanceof Collection) {
            $permissions = $permissions->all();
        }

        return array_map(
            function ($permission) {
                if ($permission instanceof Permission) {
                    return $permission;
                }
                $method = is_string($permission) ? 'findByName' : 'findById';

                return $this->getPermissionClass()->{$method}($permission, $this->getDefaultGuardName());
            },
            Arr::wrap($permissions)
        );
    }

    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|\Juzaweb\CMS\Contracts\Permission $permission
     * @param string|null $guardName
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo($permission, $guardName = null): bool
    {
        if (config('permission.enable_wildcard_permission', false)) {
            return $this->hasWildcardPermission($permission, $guardName);
        }

        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permissionModel = $permissionClass->findByName(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (is_int($permission)) {
            $permissionModel = $permissionClass->findById(
                $permission,
                $guardName ?? $this->getDefaultGuardName()
            );
        }

        if (! $permissionModel instanceof Permission) {
            return false;
        }

        return $this->hasDirectPermission($permissionModel) || $this->hasPermissionViaRole($permissionModel);
    }

    /**
     * Validates a wildcard permission against all permissions of a user.
     *
     * @param string|int|\Juzaweb\CMS\Contracts\Permission $permission
     * @param string|null $guardName
     *
     * @return bool
     */
    protected function hasWildcardPermission($permission, $guardName = null): bool
    {
        $guardName = $guardName ?? $this->getDefaultGuardName();

        if (is_int($permission)) {
            $permission = $this->getPermissionClass()->findById($permission, $guardName);
        }

        if ($permission instanceof Permission) {
            $permission = $permission->name;
        }

        if (! is_string($permission)) {
            throw WildcardPermissionInvalidArgument::create();
        }

        foreach ($this->getAllPermissions() as $userPermission) {
            if ($guardName !== $userPermission->guard_name) {
                continue;
            }

            $userPermission = new WildcardPermission($userPermission->name);

            if ($userPermission->implies($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * An alias to hasPermissionTo(), but avoids throwing an exception.
     *
     * @param string|int|\Juzaweb\CMS\Contracts\Permission $permission
     * @param string|null $guardName
     *
     * @return bool
     */
    public function checkPermissionTo($permission, $guardName = null): bool
    {
        try {
            return $this->hasPermissionTo($permission, $guardName);
        } catch (PermissionDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Determine if the model has any of the given permissions.
     *
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection ...$permissions
     *
     * @return bool
     */
    public function hasAnyPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->checkPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has all of the given permissions.
     *
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAllPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the model has, via roles, the given permission.
     *
     * @param \Juzaweb\CMS\Contracts\Permission $permission
     *
     * @return bool
     */
    protected function hasPermissionViaRole(Permission $permission): bool
    {
        return $this->hasRole($permission->roles);
    }

    /**
     * Determine if the model has the given permission.
     *
     * @param string|int|\Juzaweb\CMS\Contracts\Permission $permission
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasDirectPermission($permission): bool
    {
        $permissionClass = $this->getPermissionClass();

        if (is_string($permission)) {
            $permission = $permissionClass->findByName($permission, $this->getDefaultGuardName());
        }

        if (is_int($permission)) {
            $permission = $permissionClass->findById($permission, $this->getDefaultGuardName());
        }

        if (! $permission instanceof Permission) {
            throw new PermissionDoesNotExist();
        }

        return $this->permissions->contains($permission->getKeyName(), $permission->getKey());
    }

    /**
     * Return all the permissions the model has via roles.
     */
    public function getPermissionsViaRoles(): Collection
    {
        return $this->loadMissing('roles', 'roles.permissions')
            ->roles->flatMap(
                function ($role) {
                    return $role->permissions;
                }
            )->sort()->values();
    }

    /**
     * Return all the permissions the model has, both directly and via roles.
     */
    public function getAllPermissions(): Collection
    {
        /** @var Collection $permissions */
        $permissions = $this->permissions;

        if ($this->roles) {
            $permissions = $permissions->merge($this->getPermissionsViaRoles());
        }

        return $permissions->sort()->values();
    }

    /**
     * Grant the given permission(s) to a role.
     *
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return $this
     */
    public function givePermissionTo(...$permissions)
    {
        $permissions = collect($permissions)
            ->flatten()
            ->reduce(
                function ($array, $permission) {
                    if (empty($permission)) {
                        return $array;
                    }

                    $permission = $this->getStoredPermission($permission);
                    if (! $permission instanceof Permission) {
                        return $array;
                    }

                    $this->ensureModelSharesGuard($permission);

                    $array[$permission->getKey()] = PermissionRegistrar::$teams && ! is_a($this, Role::class) ?
                        [PermissionRegistrar::$teamsKey => getPermissionsTeamId()] : [];

                    return $array;
                },
                []
            );

        $model = $this->getModel();

        if ($model->exists) {
            $this->permissions()->sync($permissions, false);
            $model->load('permissions');
        } else {
            $class = \get_class($model);

            $class::saved(
                function ($object) use ($permissions, $model) {
                    if ($model->getKey() != $object->getKey()) {
                        return;
                    }
                    $model->permissions()->sync($permissions, false);
                    $model->load('permissions');
                }
            );
        }

        if (is_a($this, get_class(app(PermissionRegistrar::class)->getRoleClass()))) {
            $this->forgetCachedPermissions();
        }

        return $this;
    }

    /**
     * Remove all current permissions and set the given ones.
     *
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return $this
     */
    public function syncPermissions(...$permissions)
    {
        $this->permissions()->detach();

        return $this->givePermissionTo($permissions);
    }

    /**
     * Revoke the given permission(s).
     *
     * @param \Juzaweb\CMS\Contracts\Permission|\Juzaweb\CMS\Contracts\Permission[]|string|string[] $permission
     *
     * @return $this
     */
    public function revokePermissionTo($permission)
    {
        $this->permissions()->detach($this->getStoredPermission($permission));

        if (is_a($this, get_class(app(PermissionRegistrar::class)->getRoleClass()))) {
            $this->forgetCachedPermissions();
        }

        $this->load('permissions');

        return $this;
    }

    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }

    /**
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return \Juzaweb\CMS\Contracts\Permission|\Juzaweb\CMS\Contracts\Permission[]|\Illuminate\Support\Collection
     */
    protected function getStoredPermission($permissions)
    {
        $permissionClass = $this->getPermissionClass();

        if (is_numeric($permissions)) {
            return $permissionClass->findById($permissions, $this->getDefaultGuardName());
        }

        if (is_string($permissions)) {
            return $permissionClass->findByName($permissions, $this->getDefaultGuardName());
        }

        if (is_array($permissions)) {
            $permissions = array_map(
                function ($permission) use ($permissionClass) {
                    return is_a($permission, get_class($permissionClass)) ? $permission->name : $permission;
                },
                $permissions
            );

            return $permissionClass
                ->whereIn('name', $permissions)
                ->whereIn(' ', $this->getGuardNames())
                ->get();
        }

        return $permissions;
    }

    /**
     * @param \Juzaweb\CMS\Contracts\Permission|\Juzaweb\CMS\Contracts\Role $roleOrPermission
     *
     * @throws \Juzaweb\CMS\Exceptions\GuardDoesNotMatch
     */
    protected function ensureModelSharesGuard($roleOrPermission)
    {
        if (! $this->getGuardNames()->contains($roleOrPermission->guard_name)) {
            throw GuardDoesNotMatch::create($roleOrPermission->guard_name, $this->getGuardNames());
        }
    }

    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    /**
     * Forget the cached permissions.
     */
    public function forgetCachedPermissions()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Check if the model has All of the requested Direct permissions.
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection ...$permissions
     * @return bool
     */
    public function hasAllDirectPermissions(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if (! $this->hasDirectPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if the model has Any of the requested Direct permissions.
     * @param string|int|array|\Juzaweb\CMS\Contracts\Permission|\Illuminate\Support\Collection ...$permissions
     * @return bool
     */
    public function hasAnyDirectPermission(...$permissions): bool
    {
        $permissions = collect($permissions)->flatten();

        foreach ($permissions as $permission) {
            if ($this->hasDirectPermission($permission)) {
                return true;
            }
        }

        return false;
    }
}
