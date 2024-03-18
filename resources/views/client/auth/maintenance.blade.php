<?php echo view( 'client/templates/header', [ 'header' => @$header ] );?>
<video autoPlay="true" muted="true" loop="true" playsInline="true" class='fixed z-[-1] left-0 right-0 top-0 w-[100vw] min-h-[300px] h-[100vh] object-cover'>
    <source src="{{ asset('member/Element/jdg-bg-video.mp4') }}" type='video/mp4' />
</video>
<div class="absolute left-0 right-0 mx-auto flex flex-col items-center justify-center py-12 h-full">
    <div class="mb-10">
        <img src="{{ asset( 'member/ekuitas2.png?v=' ) }}{{ date('Y-m-d-H:i:s') }}" alt="Logo Image" width="250" height="100" class="block mx-auto"/>
        <p class="text-white  text-[19px] mt-4">{{ __( 'member.system_maintenance' ) }}</p>
    </div>
    <div class="px-4 md:px-12">
        <p class="text-[16px] text-white text-center mb-4">{{ __( 'member.system_maintenance_1' ) }}</p>
        <p class="text-[16px] text-white text-center mb-8">{{ __( 'member.system_maintenance_2' ) }}</p>
    </div>
</div>
<div id="footer" class="text-center mt-12 absolute bottom-6 left-0 right-0 mx-auto text-white">
    <p>&copy; <span id="copyrightYear"></span> Ekuitas. All Rights Reserved.</p>
</div>

<script>
    $(document).ready(function () {
        var currentYear = new Date().getFullYear();
        $( '#copyrightYear' ).text(currentYear);
    });
</script>