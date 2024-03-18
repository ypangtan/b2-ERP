
<div class="relative form-input-box">
    @if (@$prefix)
    <div class="form-input-prefix mb-[0.5rem]" id="phone_prefix">{{ $prefix }}</div>
    @endif

    <div class="form-input-label mb-[0.5rem]">{{ @$label ? $label : '' }}</div>

    <input 
        type="{{ @$type ? $type : 'text' }}"
        name="{{ @$name ? $name : @$id }}"
        id="{{ @$id ? $id : @$name }}"
        placeholder="{{ @$placeholder ? $placeholder : 'Type Here...' }}"
        value="{{ @$value ? $value : old($name) }}"
        style="{{ @$style ? $style : '' }}"
        {{@!$disabled?'':'readonly'}}
      
    />

    @if (@$viewPassword == true)
    <div class="viewPassword">
        <i class="icon-icon8 closed-eye" onclick="viewPassword('{{ $id }}',this)"></i>
    </div>
    @endif

    @if (@$sendTac == true)
    <div class="sendTac" id="sendtac">{{ __('member.send') }}</div>
    @endif
</div>