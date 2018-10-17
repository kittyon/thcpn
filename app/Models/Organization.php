<?php

namespace App\Models;


class Organization extends Base
{
    //
    const TABLE = 'organizations';
    protected $table = self::TABLE;

    protected $fillable = ['name', 'description', 'organization_id'];

    public function getFullNameAttribute($v){
      return $this->attributes['id']."-".$this->attributes['name'];
    }
    
    public function devices(){
      return $this->belongsToMany('App\Models\Device');
      //return $this->hasMany('App\Models\Device');
    }

    public function parent(){
      return $this->belongsTo($this,'organization_id', 'id');
    }

    public function children(){
      return $this->hasMany($this, 'organization_id','id');
    }

    public function users(){
      return $this->belongsToMany('App\Models\User');
    }

    public function roles(){
        return $this->belongsToMany('App\Models\ORole');
    }

    public function invitations()
    {
        return $this->morphMany('App\Models\Invitation', 'invitationable');
    }

    public function hasSub($id){
      $res = false;
      foreach ($this->children()->get() as $sub) {
        // code...
        if($sub->id == $id){
          return true;
        }
        $res |= $sub->hasSub($id);
        if($res){
          return $res;
        }
      }
      return $res;
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
