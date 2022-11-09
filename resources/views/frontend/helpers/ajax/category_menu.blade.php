<div class="nav_top_heading">
    @if($data['is_parent'])
        <i class="menu_item_icon fas fa-arrow-left callMenuAjax back_to_parent" cat_id="{{$data['current_cat_detail']->parent_category_id}}"></i>
        <a href="#">{{$data['current_cat_detail']->name}}</a>
    @else
        Main Categories
    @endif
</div>
<ul>
    @foreach($data['cats'] as $cat)
    <li>
        <a href="/products?category={{$cat->slug}}"  class="menu_item_name">
            <span>{{$cat->name}} </span>
        </a>
        @if($cat->hasChild()) <i class="menu_item_icon fas fa-arrow-right @if($cat->hasChild()) callMenuAjax @endif"  cat_id="{{$cat->uuid}}"></i> @endif
        
    </li>
    @endforeach
</ul>