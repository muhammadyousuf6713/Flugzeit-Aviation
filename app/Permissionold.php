<?php

namespace App;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'label', 'guard_name', 'parent_id'];

    /**
     * A permission can belong to many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_has_permissions');
    }

    /**
     * A permission can belong to many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'model', 'model_has_permissions');
    }

    /**
     * Get the child permissions for a parent permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(Permission::class, 'parent_id');
    }

    /**
     * Get the parent permission for a child permission.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Permission::class, 'parent_id');
    }

    /**
     * A static helper method for getting a permission list for a specific menu.
     */
    public static function permissionList($menu)
    {
        $permissionsList = Permission::where('name', 'LIKE', '%' . str_slug($menu))->get();
        $permissions['add'] = $permissionsList->where('name', '=', 'add-' . str_slug($menu))->pluck('id')->first();
        $permissions['edit'] = $permissionsList->where('name', '=', 'edit-' . str_slug($menu))->pluck('id')->first();
        $permissions['view'] = $permissionsList->where('name', '=', 'view-' . str_slug($menu))->pluck('id')->first();
        $permissions['delete'] = $permissionsList->where('name', '=', 'delete-' . str_slug($menu))->pluck('id')->first();

        return $permissions;
    }
}
