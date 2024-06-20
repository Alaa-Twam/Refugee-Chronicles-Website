<?php

namespace Pearls\Base\Models;

use Pearls\Base\Traits\HashTrait;
use Pearls\Base\Traits\ModelActionsTrait;
use Pearls\Base\Traits\ModelHelpersTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use HashTrait, ModelHelpersTrait, ModelActionsTrait;

    protected static $logOnlyDirty = true;

    /**
     * BaseModel constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->initialize();

        return parent::__construct($attributes);
    }


    /**
     * custom Update function for the model
     * to save the originals
     *
     * @param  array $attributes
     * @param  array $options
     * @return bool
     */
    public function updateWithKeepingOriginals(array $attributes = [], array $options = [])
    {
        if (!$this->exists) {
            return false;
        }
        $original = $this->getOriginal();
        $status = $this->fill($attributes)->save($options);
        $this->original = $original;

        return $status;
    }

    /*
     * Function to check if a $field has been updated during update
     *
     * @param $field
     * @return bool
     */
    public function isFieldUpdated($field)
    {
        return $this->$field != $this->getOriginal($field);
    }

    /**
     * init model
     */
    public function initialize()
    {
        $config = config($this->config);
        if ($config) {
            if (isset($config['presenter'])) {
                $this->setPresenter(new $config['presenter']);
                unset($config['presenter']);
            }

            foreach ($config as $key => $val) {
                if (property_exists(get_called_class(), $key)) {
                    $this->$key = $val;
                }
            }
        }
    }
}
