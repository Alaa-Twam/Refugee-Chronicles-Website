<?php

namespace Pearls\User\Models;

use Pearls\Base\Traits\ModelActionsTrait;
use Pearls\Base\Traits\ModelHelpersTrait;
use Pearls\Base\Transformers\PresentableTrait;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;
use Pearls\Base\Traits\HashTrait;
// use Yajra\Auditable\AuditableTrait;

class Role extends SpatieRole
{
    use PresentableTrait, ModelHelpersTrait, ModelActionsTrait, HashTrait;
    /**
     *  Model configuration.
     * @var string
     */
    public $config = 'user.models.role';

    public function __construct(array $attributes = [])
    {
        $config = config($this->config);

        if (isset($config['presenter'])) {
            $this->setPresenter(new $config['presenter']);
            unset($config['presenter']);
        }

        foreach ($config as $key => $val) {
            if (property_exists(get_called_class(), $key)) {
                $this->$key = $val;
            }
        }

        return parent::__construct($attributes);
    }

}
