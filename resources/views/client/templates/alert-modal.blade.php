<!-- Modal -->
<div class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" id="alert-modal" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered bg-white rounded-lg py-4 px-4 md:min-w-[400px]" role="document">
      <i class="float-right block icon-icon9 close_btn cursor-pointer"></i>
      <!-- Modal Content -->
        <div class="text-center">
          <img src="{{ asset( 'member/Element/sucessful-icon.png?v=1.1' ) }}" id="modal_icon" alt="" class="block w-[5.313rem] h-auto mt-6 mb-6 mx-auto">

          <div class="">
            <div class="mb-2 text-[16px] font-bold text-[#FF446B]" id="modal_subject"></div>
            <div class="text-[11px] text-[#8A94B8] px-4" id="modal_desc"></div>
          </div>
        </div>
        <a class="primary_btn w-full mt-6 mb-4 block max-w-[200px] mx-auto" id="modal_btn" href="#"></a>
  </div>
</div>

<script>
  $(document).ready(function () {
    $('.close_btn, #modal_btn').on('click', function (){
      $('#alert-modal').addClass('hidden');
    });
  });
</script>