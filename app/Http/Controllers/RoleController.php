<?php

namespace App\Http\Controllers;

use App\Datatables\RolesDatatable;
use App\Http\Requests\Users\RoleFormRequest;
use Bouncer;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    public function index()
    {
        $abilities = Bouncer::ability()->whereNull('entity_type')->whereNull('entity_id')->orderBy('title')->get();

        $datatable = new RolesDatatable;

        return $datatable->render('roles.index', compact('datatable', 'abilities'));
    }

    public function create()
    {
        $model = new \App\Models\Role;
        $abilityCategories = Bouncer::ability()->whereNull('entity_type')
            ->whereNull('entity_id')
            ->when(! Bouncer::can('admin.edit'), function ($query) {
                $query->where('category', '<>', 'Admin');
            })->get()->groupBy('category');

        return view('roles.add', compact('model', 'abilityCategories'));
    }

    public function store(RoleFormRequest $request)
    {
        $validated = $request->validated();

        $role = Bouncer::role()->create([
            'name' => Str::slug($validated['title']),
            'title' => $validated['title'],
            'category' => $validated['category'],
            'description' => $validated['description'],
        ]);
        $managedModels = [];

        if (Arr::has($validated, 'model_abilities')) {
            foreach ($validated['model_abilities'] as $modelAbility) {
                [$ability, $model] = explode('|', $modelAbility, 2);
                if ($ability == 'manage') {
                    Bouncer::allow($role)->toManage(Config::get('roles-models')[$model]);
                } elseif (! array_key_exists($model, $managedModels)) {
                    Bouncer::allow($role)->to($ability, Config::get('roles-models')[$model]);
                }
            }
        }

        $role->save();
        if (Arr::has($validated, 'abilities')) {
            Bouncer::allow($role)->to($validated['abilities']);
        }

        flash('Successfully added role', 'success');

        return redirect()->route('roles.index');
    }

    public function show($name)
    {
        $model = Bouncer::role()
            ->with(['abilities' => function ($query) {
                $query->select('abilities.id', 'abilities.name', 'abilities.title', 'abilities.entity_type', 'abilities.category');
            }])
            ->where('name', $name)
            ->firstOrFail();

        $abilityCategories = $model->abilities->groupBy('category');

        return view('roles.show', compact('model', 'abilityCategories'));
    }

    public function edit($name)
    {
        if ($name == 'super-admin' || $name == 'admin') {
            flash('Admin roles cannot be edited.', 'danger');

            return redirect()->back();
        }

        $model = Bouncer::role()->where('name', $name)->firstOrFail();

        if (! Bouncer::can('admin.edit') && $model->can('admin')) {
            flash('You do not have sufficient privileges to edit admin roles.', 'danger');

            return redirect()->back();
        }

        $abilityCategories = Bouncer::ability()->whereNull('entity_type')
            ->whereNull('entity_id')
            ->when(! Bouncer::can('admin.edit'), function ($query) {
                $query->where('category', '<>', 'Admin');
            })->ordered()->get()->groupBy('category');

        return view('roles.edit', compact('model', 'abilityCategories'));
    }

    public function update(RoleFormRequest $request)
    {
        $role = Bouncer::role()->where('name', $request->name)->firstOrFail();

        $validated = $request->validated();

        $role->name = Str::slug($validated['title']);
        $role->title = $validated['title'];
        $role->description = $validated['description'];

        if (Arr::has($validated, 'abilities')) {
            Bouncer::sync($role)->abilities($validated['abilities']);
        }

        $managedModels = [];
        if (Arr::has($validated, 'model_abilities')) {
            foreach ($validated['model_abilities'] as $modelAbility) {
                [$ability, $model] = explode('|', $modelAbility, 2);
                if ($ability == 'manage') {
                    $managedModels[$model] = true;
                }
            }
            foreach ($validated['model_abilities'] as $modelAbility) {
                [$ability, $model] = explode('|', $modelAbility, 2);
                if ($ability == 'manage') {
                    Bouncer::allow($role)->toManage(Config::get('roles-models')[$model]);
                } elseif (! array_key_exists($model, $managedModels)) {
                    Bouncer::allow($role)->to($ability, Config::get('roles-models')[$model]);
                }
            }
        }

        $role->save();

        flash("Successfully updated {$role->name}", 'success');

        return redirect()->route('roles.index');
    }

    public function destroy($name)
    {
        $role = Bouncer::role()->where('name', $name)->firstOrFail()->delete();

        flash('Successfully deleted role', 'success');

        return redirect()->route('roles.index');
    }
}
