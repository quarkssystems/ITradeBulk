<li>
    <div class="checkbox">
        <label><input type="checkbox" value="{{ $category->uuid }}" name="categories[]"
                {{ in_array($category->uuid, $selectedCategories) ? 'checked' : '' }}> {{ $category->name }}</label>
    </div>
    @if ($category->hasChild())
        <ul>
            @foreach ($category->childCategory as $childCategory)
                {!! $childCategory->getCategoryHierarchy($childCategory, $selectedCategories) !!}
            @endforeach
        </ul>
    @endif

</li>
