<?php

namespace App\Constant;

class Permission {
    public const string OBJECT_USER = "object == user";
    public const string OBJECT_OWNER = "object.owner == user";
    public const string PREVIOUS_OBJECT_OWNER = "previous_object.owner == user";
    public const string FULL_OWNER = "(object.owner == user and previous_object.owner == user)";

    public const string ROLE_USER = "is_granted('ROLE_USER')";
    public const string ROLE_ADMIN = "is_granted('ROLE_ADMIN')";
}
