<form method="POST" action="{{ route('brands.categories.update', $model->id) }}">
    @csrf
    <div class="col-12">
        <div class="dataTables_wrapper">
            <input type="hidden" name="deleted_items" class="js-delete-input">
            <table class="js-category-table table datatable">
                <thead>
                    <tr class="js-header-row">
                        <th style="width: 60px;">
                            <div class="checkbox-wrap">
                                <label class="checkbox">
                                    <input type="checkbox" class="js-delete-header">
                                    <span class="checkbox-checkmark"></span>
                                </label>
                            </div>
                        </th>
                        <th style="width: 150px;">Order</th>
                        <th>Name</th>
                        <th>Name FR</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($model->catalogueCategories as $category)
                    <tr>
                        <td>
                            <input type="hidden" name="category_id[]" class="js-id" value="{{ $category->id }}">
                            <div class="checkbox-wrap mt-2">
                                <label class="checkbox">
                                    <input type="checkbox" class="js-delete-row">
                                    <span class="checkbox-checkmark"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap table-input {{ $errors->has("sort.{$loop->index}") ? ' input-danger' : '' }}">
                                <input type="text" class="js-sort" name="sort[]" value="{{ $category->sort }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap table-input {{ $errors->has("name.{$loop->index}") ? ' input-danger' : '' }}">
                                <input type="text" name="name[]" value="{{ $category->name }}">
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap table-input {{ $errors->has("name_fr.{$loop->index}") ? ' input-danger' : '' }}">
                                <input type="text" name="name_fr[]" value="{{ $category->name_fr }}">
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    <tr class="js-category-template-row" style="display: none;">
                        <td>
                            <input type="hidden" name="category_id[]" class="js-id" disabled>
                            <div class="checkbox-wrap mt-2">
                                <label class="checkbox">
                                    <input type="checkbox" class="js-template-delete-row" disabled>
                                    <span class="checkbox-checkmark"></span>
                                </label>
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap table-input">
                                <input type="text" class="js-sort" name="sort[]" value="10000" disabled>
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap table-input">
                                <input type="text" name="name[]" disabled>
                            </div>
                        </td>
                        <td>
                            <div class="input-wrap table-input">
                                <input type="text" name="name_fr[]" disabled>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="button" class="link-btn js-category-add" title="Add Row" style="float: right;">
                <i class="material-icons">playlist_add</i>
                Add Row
            </button>
            <button type="button" class="link-btn delete js-category-delete" data-toggle="modal" title="Delete Categories" data-action="deleteCategoryRows" data-label="selected categories" data-target="#js-deleteModal" style="float: left; display: none;">
                <i class="material-icons">delete_forever</i>
                Delete
            </button>

            <div class="bottom-controls">
                <a class="secondary-btn" href="{{ BladeHelper::backOr(route('brands.show', $model->id)) }}" title="Cancel">
                    <i class="material-icons">cancel</i>
                    Cancel
                </a>
                <button class="secondary-btn" name="action" value="save" type="submit" title="Save">
                    Save
                </button>
            </div>
        </div>
    </div>
    @include('modals.js-delete')
</form>

@push('scripts')
<script type="text/javascript" src="{{ mix('js/modules/catalogue-categories.js') }}"></script>
@endpush
