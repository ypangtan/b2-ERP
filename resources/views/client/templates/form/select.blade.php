<div class="form-input-label mb-[0.5rem]">{{ __('member.country') }}</div>
<div class="FORM-SELECT-BOX">
    <select
        name="{{ @$name ? $name : @$id }}"
        style="{{ @$style ? $style : '' }}"
        id="{{ @$id ? $id : @$name }}">
            @foreach($data as $row)
            <option value="{{$row->id}}" {{ ($row->id==old(@$name) ? "selected":"") }}>{{$row->country_name}}</option>
            @endforeach
    </select>

    <i class="icon-icon15"></i>
</div>   
    