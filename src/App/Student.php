<?php

/*
 * This file is part of the Slim API skeleton package
 *
 * Copyright (c) 2016-2017 Mika Tuupola
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Project home:
 *   https://github.com/tuupola/slim-api-skeleton
 *
 */

 namespace App;

 use Spot\EntityInterface as Entity;
 use Spot\MapperInterface as Mapper;
 use Spot\EventEmitter;

 use Tuupola\Base62;

 use Ramsey\Uuid\Uuid;
 use Psr\Log\LogLevel;
 
 class Student extends \Spot\Entity
 {
    protected static $table = "students";

    public static function fields()
    {
        return [


        "id" => ["type" => "integer" , "unsigned" => true, "primary" => true, "autoincrement" => true],
        "college_id" => ["type" => "integer"],
        "name" => ["type" => "string"],
        "username" => ["type" => "string"],
        "roll_number" => ["type" => "integer"],
        "email" => ["type" => "string"],
        "phone" => ["type" => "integer"],
        "photo" => ["type" => "string"],
        "hostelid" => ["type" => "integer"],
        "room_number" => ["type" => "string"],
        "home_city" => ["type" => "string"],
        "grad_id" => ["type" => "integer"],
        "branch_id" => ["type" => "integer"],
        "year" => ["type" => "string"],
        "class_id" => ["type" => "integer"],
        "passout_year" => ["type" => "integer"],
        "age" => ["type" => "integer"],
        "gender" => ["type" => "string"]
        ];
    }

    public static function students(EventEmitter $emitter)
    {
        $emitter->on("beforeInsert", function (EntityInterface $entity, MapperInterface $mapper) {
            $entity->id = Base62::encode(random_bytes(9));
            });
    }

    public function clear()
    {
        $this->data([
            "id" => 0,
            "college_id" => null,
            "name" => null,
            "username" => null,
            "roll_number" => null,
            "email" => null,
            "phone" => null,
            "photo" => null,
            "hostelid" => null,
            "room_number" => null,
            "home_city" => null,
            "grad_id" => null,
            "branch_id" => null,
            "year" => null,
            "class_id" => null,
            "passout_year" => null,
            "age" => null,
            "gender" => null
            ]);
    }

    public static function relations(Mapper $mapper, Entity $entity)
    {
        return [
        'Hostel' => $mapper->belongsTo($entity, 'App\Hostel', 'college_id'),

        'Following' => $mapper->hasManyThrough($entity, 'App\Student', 'App\Follow', 'follower_id', 'following_id'),
        'Followers' => $mapper->hasManyThrough($entity, 'App\Student', 'App\Follow', 'following_id', 'follower_id'),
        
        'ClassGroup' => $mapper->belongsTo($entity, 'App\ClassGroup', 'college_id'),
        'CreativeContents' => $mapper->hasMany($entity, 'App\CreativeContent', 'post_id'),
        'CreativeContentLikes' => $mapper->hasManyThrough($entity, 'App\CreativeContent', 'App\CreativeContentLike', 'post_id'),
        'CreativeContentBookmarks' => $mapper->hasManyThrough($entity, 'App\CreativeContent', 'App\CreativeContentBookmark', 'post_id'),
        
        'Events' => $mapper->hasMany($entity, 'App\Event', 'post_id'),
        'EventLikes' => $mapper->hasManyThrough($entity, 'App\Event', 'App\EventLike', 'post_id'),
        'EventBookmarks' => $mapper->hasManyThrough($entity, 'App\Event', 'App\EventBookmark', 'post_id'),
        'EventParticipated' => $mapper->hasManyThrough($entity, 'App\Event', 'App\Event', 'post_id'),
        
        'Skills' => $mapper->hasMany($entity, 'App\Skills', 'post_id'),
        
        'Interets' => $mapper->hasMany($entity, 'App\Event', 'post_id'),
                    'hostel' => $mapper->hasOne($entity, 'Entity\User\hostel', 'user_id')
        ];
    }
}