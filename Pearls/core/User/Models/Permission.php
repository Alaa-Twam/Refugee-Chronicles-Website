<?php

namespace Pearls\User\Models;

// use Pearls\Base\Traits\HashTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
}