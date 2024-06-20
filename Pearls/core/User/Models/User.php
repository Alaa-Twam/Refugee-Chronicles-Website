<?php

namespace Pearls\User\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Pearls\Base\Transformers\PresentableTrait;
use Pearls\Base\Traits\ModelActionsTrait;
use Pearls\Base\Traits\ModelHelpersTrait;
use Pearls\Base\Traits\HashTrait;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable, PresentableTrait, ModelHelpersTrait, ModelActionsTrait, HashTrait;
    
    public $config = 'user.models.user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'type',
        'status',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
        public function getIdentifier($key = null)
    {
        if (!is_null($key)) {
            return parent::getIdentifier($key);
        }

        return $this->present('name');
    }

    public function getShowURL($id = null, $params = [])
    {
        if (is_null($id)) {
            $id = $this->hashed_id;
        } else {
            $id = hashids_encode($id);
        }

        $config = config($this->config);

        $show_url = null;

        if ($this->type == 'employee') {
            $show_url = url(config('employee.models.employee.resource_url') . '/' . $id);
        } elseif ($config) {
            $show_url = urlWithParameters($config['resource_url'] . '/' . $id, $params);
        }

        return $show_url;
    }
}
