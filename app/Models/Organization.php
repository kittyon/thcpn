<?php

namespace App\Models;


class Organization extends Base
{
    //

    protected $table = 'organizations';

    protected $fillable = ['name', 'description', 'organization_id'];

    public function devices(){
      return $this->belongsToMany('App\Models\Device');
    }

    public function parentOrganization(){
      return $this->belongsTo($this,'organization_id', 'id');
    }

    public function childrenOrganization(){
      return $this->hasMany($this, 'id', 'organization_id');
    }

    public function roles(){
        return $this->belongsToMany('App\Models\ORole');
    }

    public function allows($permission, $requireAll = false)
    {
        //Log::info($permission);
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->allows($permName, false);

                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->roles as $role) {
                // Validate against the Permission table
                //if ($role->app_id != $app_id) continue;
                foreach ($role->perms as $perm) {
                    if ($perm->name == $permission) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

}
