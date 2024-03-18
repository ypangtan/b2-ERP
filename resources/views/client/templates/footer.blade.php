<script src="{{ asset( 'member/Scripts/js/flatpickr-4.6.13.js' ) . Helper::assetVersion() }}"></script>
<script src="{{ asset( 'member/Scripts/js/select2.min.js' ) . Helper::assetVersion() }}"></script>
<script src="{{ asset( 'member/Scripts/js/bootstrap.bundle.min.js' ) . Helper::assetVersion() }}"></script>

<form id="logout_form" action="{{ route('member.logout') }}" method="POST">
    @csrf
</form>
<script>



    Number.prototype.toFixedDown = function(digits) {
        var re = new RegExp("(\\d+\\.\\d{" + digits + "})(\\d)"),
            m = this.toString().match(re);
        return m ? parseFloat(m[1]).toFixed(digits) : this.valueOf().toFixed( 2 ).replace(/\d(?=(\d{3})+\.)/g, '$&,');
    };

    document.addEventListener( 'DOMContentLoaded', function() {

        $( document ).on( 'click', '.logout_', function() {
            $( '#logout_form' ).submit();
        } );
        
        $( document ).on( 'hidden.bs.modal', '#notification-modal', function() {
            
            $.ajax( {
                headers: { 
                'X-CSRF-TOKEN': "{{ csrf_token() }}" 
                },
                url: '{{ route( 'member.member.announcementRead' ) }}',
                type: 'POST',
                method: 'POST',
                enctype: 'multipart/form-data',
                contentType: false,
                processData: false,
                data: {
                    _token: '{{ csrf_token() }}',
                },
                success: function() {
                    console.log( 'ok' );
                }
            } );
        } );
    } );

    function viewPassword(id,e)
    {
        if($('#'+id).attr('type') == 'password')
        {
            $('#'+id).attr('type','text');
            $(e).attr('class',"icon-icon7 opened-eye");
        }else{
            $('#'+id).attr('type','password');
            $(e).attr('class',"icon-icon8 closed-eye");
        }
    }
    
    
    function launch_toast(msg) {
        $('#toast').find('#desc').html(msg);
        var x = document.getElementById("toast")
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
    }

    
    function open_select(modal)
    {
        //alert(modal);
        //$(modal).trigger('open');
        const element = document.getElementById(modal);
        const event = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
        });
        element.dispatchEvent(event);
        console.log(event);
    }
    function copy_clipboard(value, object, msg='Successfully')
    {
        var temp = $( '<input>' );

        $( 'body' ).append( temp );
        temp.val( value ).select();
        temp[0].setSelectionRange( 0, 99999 );
        document.execCommand( 'copy' );
        temp.remove();
        
        $('#'+object).css( 'opacity' , '0.5');
        if(object == 'no')
        {
            $('#'+object).html(`{{ __('member.copied') }}`);
        }
        setTimeout(function(e){
            
            $('#'+object).css( 'opacity' , '1');
            if(object == 'no')
            {
                $('#'+object).html(`{{ __('member.copy') }}`);

            }
            
        }, 3000);

    }

    /** Save Session for Theme Changing */
    function saveTheme()
    {
        var form = $('.theme-form')[0];
        var formData = new FormData(form);
        $.ajax( {
            headers: { 
            'X-CSRF-TOKEN': "{{ csrf_token() }}" 
            },
            url: '{{ route( 'web.changeThemeColor' ) }}',
            type: 'POST',
            method: 'POST',
            enctype: 'multipart/form-data',
            contentType: false,
            processData: false,
            data: formData,
            success: function( response ) {
                

                //console.log(response);

            },
        } );
    }
    /** End of Save Session for Theme Changing */

    /** Switch Site Navigation */
    $('#backdrop').click(function(){
        console.log('ss')
        switchSiteNav();
    });
    function switchSiteNav()
    {
        $('.SITE_NAV').toggleClass('MIN');
        $('.SITE_NAV').toggleClass('MAX');

    }

    /** END OF Switch Site Navigation */

    function launch_toast(msg) {
        $('#toast').find('#desc').html(msg);
        var x = document.getElementById("toast")
        x.className = "show";
        setTimeout(function(){ x.className = x.className.replace("show", ""); }, 5000);
    }

    

    function redirectPage(routes,checking,public_routes='') //if checking is true, then need check user login onot
    {
        if(checking == true)
        {
            var user = '{{ @Auth::user()->name }}';
            if(user !== '')
            {
                window.location.href = routes;
            }else{
                $('#login-modal').modal('show');
            }
            
        }else{
            if(public_routes !== '')
            {
                window.location.href = public_routes;
            }else{
                window.location.href = routes;
            }
            
        }
    }

    /** Mask Amount input */
    $(".MaskAmount").maskMoney({
        prefix:'', 
        thousands:',', 
        decimal:'.'
    })
    const format = { 
        minimumFractionDigits: 4, 
        style: 'currency', 
        currency: 'BRL' 
    }
    /** END OF Mask Amount input */

    /** Open/Close Modal */
    function open_modal(modal)
    {
        $('#'+modal).modal('show');
    }
    function close_modal(modal)
    {
        $('#'+modal).modal('hide');
    }
    /** End of Open/Close Modal */

    /** Informate/Unformate Thousand Separator Number */
    function informatted_number(n) {
        var parts = n.toString().split(".");
        const numberPart = parts[0];
        const decimalPart = parts[1];
        const thousands = /\B(?=(\d{3})+(?!\d))/g;
        return numberPart.replace(thousands, ",") + (decimalPart ? "." + decimalPart : "");
    }
    function unformatted_number(n) {
        return n.toString().replace(/[,]/g, "");
    }
    /** END OF Informate Thousand Separator Number */
</script>



</body>

</html>