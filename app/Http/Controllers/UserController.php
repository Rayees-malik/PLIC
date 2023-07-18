<?php

namespace App\Http\Controllers;

use App\Datatables\UsersDatatable;
use App\Http\Requests\Users\UserFormRequest;
use App\Models\Broker;
use App\Models\Product;
use App\Models\Vendor;
use App\User;
use Illuminate\Support\Arr;
use Silber\Bouncer\BouncerFacade as Bouncer;

class UserController extends Controller
{
    public function index()
    {
        $datatable = new UsersDatatable;

        return $datatable->render('users.index', compact('datatable'));
    }

    public function create()
    {
        $model = new User;
        $roleCategories = Bouncer::role()->editableByUser()->ordered()->get()->groupBy('category');
        $userType = 0;

        return view('users.add', compact('model', 'roleCategories', 'userType'));
    }

    public function store(UserFormRequest $request)
    {
        $validated = $request->validated();

        $user = User::firstOrCreate([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        if (auth()->user()->isVendor) {
            // Vendor User Creation
            $user->vendor_id = auth()->user()->vendor_id;
            $user->broker_id = auth()->user()->broker_id;

            $key = $user->broker_id ? 'broker' : 'vendor';

            if (Arr::get($validated, 'vendor_user_type', '0') == '0') {
                $user->assign("{$key}-view-only");
                $user->retract($key);
            } else {
                $user->assign($key);
                $user->retract("{$key}-view-only");

                if ($validated['vendor_user_type'] == '2') {
                    $user->assign('vendor-finance');
                } else {
                    $user->retract('vendor-finance');
                }
            }

            $user->save();
        } else {
            // Regular User Creation

            // Vendor/Brand
            if (Arr::has($validated, 'vendor_id')) {
                $user->vendor_id = $validated['vendor_id'];
            } elseif (Arr::has($validated, 'broker_id')) {
                $user->broker_id = $validated['broker_id'];
            }

            // Roles
            Bouncer::sync($user)->roles(Arr::wrap(Arr::get($validated, 'roles')));
            $user->save();

            // Clear out old vendor/brand if they've lost the role
            if (! $user->can('user.assign.vendor') && $user->vendor_id) {
                $user->vendor_id = null;
                $user->save();
            }
            if (! $user->can('user.assign.broker') && $user->broker_id) {
                $user->broker_id = null;
                $user->save();
            }
        }

        flash('Successfully added user: ' . $user->name, 'success');

        return redirect()->route('users.index');
    }

    public function show($id)
    {
        $model = User::withAccess()->with([
            'roles' => function ($query) {
                $query->select('roles.id', 'title');
            },
            'vendor' => function ($query) {
                $query->select('id', 'name');
            },
            'broker' => function ($query) {
                $query->select('id', 'name');
            },
        ])->findOrFail($id);

        return view('users.show', compact('model'));
    }

    public function edit($id)
    {
        $model = User::with('roles')->withAccess()->findOrFail($id);

        if ((! Bouncer::can('admin.edit') && $model->can('admin')) && auth()->user()->getKey() != $model->getKey()) {
            flash('You do not have sufficient privileges to edit admin users.', 'danger');

            return redirect()->back();
        }

        $roleCategories = Bouncer::role()->editableByUser(auth()->id() == $model->id)->ordered()->get()->groupBy('category');

        $vendors = [];
        $brokers = [];
        $userType = 0;
        if ($model->can('vendor') && $model->id !== auth()->id()) {
            if ($model->can('user.assign.vendor')) {
                $vendors = Vendor::withPending()->select('id', 'name')->ordered()->get();
            }

            if ($model->can('user.assign.broker')) {
                $brokers = Broker::select('id', 'name')->ordered()->get();
            }

            if ($model->can('vendor-finance')) {
                $userType = 2;
            } elseif ($model->can('edit', Product::class)) {
                $userType = 1;
            }
        }

        return view('users.edit', compact('model', 'roleCategories', 'vendors', 'brokers', 'userType'));
    }

    public function update(UserFormRequest $request)
    {
        $user = User::withAccess()->findorFail($request->id);
        $validated = $request->validated();

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($request->input('password') && ! empty($request->input('password_confirmation')))) {
            $user->password = bcrypt($validated['password']);
            $user->save();
        }

        if (auth()->user()->isVendor) {
            // Vendor User Creation
            $user->vendor_id = auth()->user()->vendor_id;
            $user->broker_id = auth()->user()->broker_id;

            $key = $user->broker_id ? 'broker' : 'vendor';

            if (Arr::get($validated, 'vendor_user_type', '0') == '0') {
                $user->assign("{$key}-view-only");
                $user->retract($key);
            } else {
                $user->assign($key);
                $user->retract("{$key}-view-only");

                if ($validated['vendor_user_type'] == '2') {
                    $user->assign('vendor-finance');
                } else {
                    $user->retract('vendor-finance');
                }
            }

            $user->save();
        } else {
            // Regular User Creation

            // Vendor/Brand
            if (Arr::has($validated, 'vendor_id')) {
                $user->vendor_id = $validated['vendor_id'];
            } elseif (Arr::has($validated, 'broker_id')) {
                $user->broker_id = $validated['broker_id'];
            }

            // Roles
            Bouncer::sync($user)->roles(Arr::wrap(Arr::get($validated, 'roles')));
            $user->save();

            // Clear out old vendor/brand if they've lost the role
            if (! $user->can('user.assign.vendor') && $user->vendor_id) {
                $user->vendor_id = null;
                $user->save();
            }
            if (! $user->can('user.assign.broker') && $user->broker_id) {
                $user->broker_id = null;
                $user->save();
            }
        }

        flash("Successfully updated {$user->name}", 'success');

        return redirect()->back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id)->delete();

        flash('Successfully deleted user', 'success');

        return redirect()->route('users.index');
    }

    public function impersonate(User $user)
    {
        auth()->user()->impersonate($user);
    }
}
