<div class="input-wrap {{ $errors->has('title') ? ' input-danger' : '' }}">
    <label>Role Name
        <div class="icon-input">
            <i class="material-icons pre-icon">perm_identity</i>
            <input type="text" name="title" value="{{ old('title', $model->title) }}">
        </div>
    </label>
    @error('title')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('description') ? ' input-danger' : '' }}">
    <label>Description
        <div class="icon-input">
            <textarea type="text" name="description" autocomplete="off">{{
                old('description', $model->description)
            }}</textarea>
        </div>
    </label>
    @error('description')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="input-wrap {{ $errors->has('category') ? ' input-danger' : '' }}">
    <label>Category
        <div class="icon-input">
            <i class="material-icons pre-icon">category</i>
            <input type="text" name="category" value="{{ old('category', $model->category) }}">
        </div>
    </label>
    @error('category')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="dropdown-wrap {{ $errors->has('abilities') ? ' dropdown-danger' : '' }}">
    <label>Abilities</label>
    <div class="dropdown-icon">
        <select name="abilities[]" class="searchable" multiple data-placeholder="Select Abilities">
            @foreach ($abilityCategories as $category => $abilities)
            <optgroup label="{{ $category }}">
                @foreach ($abilities as $ability)
                <option value="{{ $ability->name }}" data-description="{{ $ability->description }}" {{
                    old('abilities') ? (in_array($ability->name, old('abilities')) ? 'selected' : '' ) : ($model->can($ability->name) ? 'selected' : '')
                }}>
                    {{ $ability->title }}
                </option>
                @endforeach
            </optgroup>
            @endforeach
        </select>
    </div>
    @error('abilities')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

<div class="dropdown-wrap {{ $errors->has('model_abilities') ? ' dropdown-danger' : '' }}">
    <label>Model Abilities
        <div class="dropdown-icon">
            <select name="model_abilities[]" class="searchable" multiple data-placeholder="Select Abilities">
                @foreach (Config::get('roles-models') as $roleModel => $roleModelClass)
                <optgroup label="{{ $roleModel }}">
                    @foreach (Config::get('model-abilities') as $ability)
                    <option value="{{ $ability }}|{{ $roleModel }}" {{
                        old('model_abilities') ? (in_array("{$ability}|{$roleModel}", old('model_abilities')) ? 'selected' : '' ) :
                        ($model->can($ability, $roleModelClass) && ($ability == 'manage' || $model->cannot('manage', $roleModelClass)) ? 'selected' : '')
                    }}>
                        {{ ucfirst($ability) }} {{ Str::plural($roleModel) }}
                    </option>
                    @endforeach
                </optgroup>
                @endforeach
            </select>
        </div>
    </label>
    @error('model_abilities')
    <small class="info-danger">{{ $message }}</small>
    @enderror
</div>

@push('scripts')
{!! BladeHelper::initChosenSelect('searchable') !!}
@endpush
