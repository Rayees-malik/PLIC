<div class="input-wrap {{ $errors->has('title') ? ' input-danger' : '' }}">
    <label>Ability Name
        <div class="icon-input">
            <i class="material-icons pre-icon">perm_identity</i>
            <input type="text" name="title" value="{{ old('title', $model->title) }}">
        </div>
    </label>
    @error('title')
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
