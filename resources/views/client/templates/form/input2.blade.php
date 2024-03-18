
<div class="relative form-input-box2">
    @if (@$prefix)
    <div class="form-input-prefix2 mr-[0.625rem]" id="phone_prefix">{{ $prefix }}</div>
    @endif

    <!-- div class="form-input-label mb-[0.5rem]">{{ @$label ? $label : '' }}</!-->
    @if (@$icon)
    <i class="{{ $icon }} mr-[1rem]"></i>
    @endif

    <input 
        type="{{ @$type ? $type : 'text' }}"
        name="{{ @$name ? $name : @$id }}"
        id="{{ @$id ? $id : @$name }}"
        placeholder="{{ @$placeholder ? $placeholder : 'Type Here...' }}"
        value="{{ @$value ? $value : old($name) }}"
        style="text-align:left;{{ @$style ? $style : '' }}"
        {{@!$disabled?'':'readonly'}}
      
    />

    @if (@$viewPassword == true)
    <div class="viewPassword">
        <i class="icon-icon8 closed-eye" onclick="viewPassword('{{ $id }}',this)"></i>
    </div>
    @endif

    @if (@$sendTac == true)
    <div class="sendTac" id="sendtac" role="button">{{ __('member.send') }}</div>
    @endif
</div>