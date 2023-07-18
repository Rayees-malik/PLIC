<div class="col-12">
    <div class="dataTables_wrapper">
        <table class="table datatable">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Name FR</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($model->catalogueCategories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->name_fr }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
