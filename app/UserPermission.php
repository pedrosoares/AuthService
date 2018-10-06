<?php
/**
 * Created by PhpStorm.
 * User: pedrosoares
 * Date: 10/6/18
 * Time: 2:05 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model {

    protected $table = "user_has_permissions";
    protected $guarded = [];


}