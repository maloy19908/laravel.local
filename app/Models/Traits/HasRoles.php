<?php

namespace App\Models\Traits;

use App\Models\Role;

trait HasRoles {

  /**
   * @return mixed
   */

  public function roles() {
    return $this->belongsToMany(Role::class, 'users_roles');
  }

  /**
   * @param mixed ...$roles
   * @return bool
   */

  public function hasRole(...$roles) {
    foreach ($roles as $role) {
      if ($this->roles->contains('slug', $role)) {
        return true;
      }
    }
    //return $this->roles()->where('name', $role)->exists();
    return false;
  }
}
