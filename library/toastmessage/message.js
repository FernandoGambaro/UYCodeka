function showToast(text, tipo){

    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": true,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "200",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      }

      if(tipo=='warning'){
        toastr.warning(text);
      }
      if(tipo=='success'){
        toastr.success(text);
      }
      if(tipo=='error'){
        toastr.error(text);
      }
      if(tipo=='info'){
        toastr.info(text);
      }
}