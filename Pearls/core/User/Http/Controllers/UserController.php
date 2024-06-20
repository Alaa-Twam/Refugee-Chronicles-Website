<?php

namespace Pearls\User\Http\Controllers;

use Pearls\Base\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Pearls\User\DataTables\UsersDataTable;
use Pearls\User\Http\Requests\UserRequest;
use Pearls\User\Models\User;

class UserController extends BaseController
{
    protected $type = 'admin';
    protected $breadcrumb = 'users';

    public function __construct()
    {
        $this->resource_url = config('user.models.user.resource_url');
        $this->title = 'Users';
        $this->title_singular = 'User';

        parent::__construct();
    }

    public function index(UsersDataTable $dataTable)
    {
        $this->setViewSharedData(['breadcrumb' => $this->breadcrumb]);
        
        return $dataTable->render('User::users.index');
    }

    public function create(UserRequest $request)
    {
        $user = new User();

        $this->setViewSharedData([
            'title_singular' => 'Create ' . $this->title_singular,
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('User::users.create_edit')->with(compact('user'));
    }

    public function store(UserRequest $request)
    {
        try {
            $user_data = $request->except(['roles', 'password_confirmation']);

            $user_data['type'] = $this->type;

            $user = User::create($user_data);

            $user->roles()->sync($request->roles);

            flash(trans('Pearls::messages.success.created', ['item' => ucfirst($this->type)]))->success();
        } catch (\Exception $exception) {
            // log_exception($exception, User::class, 'store');
            $exception->getMessage();
        }

        return redirectTo($this->resource_url);
    }

    public function edit(UserRequest $request, User $user)
    {
        $this->setViewSharedData([
            'title_singular' => "Update [{$user->username}]",
            'breadcrumb' => \Str::singular($this->breadcrumb) . '_create_edit'
        ]);

        return view('User::users.create_edit')->with(compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        try {
            $user_data = $request->except(['roles', 'password_confirmation']);

            if (is_null($user_data['password'])) {
                unset($user_data['password']);
            }
            $user->update($user_data);

            $user->roles()->sync($request->roles);

            flash(trans('Pearls::messages.success.updated', ['item' => ucfirst($this->type)]))->success();
        } catch (\Exception $exception) {
            log_exception($exception, User::class, 'update');
        }

        return redirectTo($this->resource_url);
    }

    public function destroy(UserRequest $request, User $user)
    {
        try {
            if (user()->id == $user->id) {
                throw new \Exception('You can\'t delete yourself!!');
            }
            $user->syncRoles([]);
            $user->delete();

            $message = ['level' => 'success', 'message' => trans('Pearls::messages.success.deleted', ['item' => ucfirst($this->type)])];
        } catch (\Exception $exception) {
            log_exception($exception, User::class, 'destroy');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}