const prevBtns = document.querySelectorAll(".btn-prev");
const nextBtns = document.querySelectorAll(".btn-next");
const progress = document.getElementById("progress");
const formSteps = document.querySelectorAll(".form-step");
const progressSteps = document.querySelectorAll(".progress-step");

let formStepsNum = 0,
pass = false,
anotherpass = false;

nextBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    if(formStepsNum == 0){
        if($("#vls option:selected" ).val() != ""){
            $("#vls").parent().removeClass("err");
            if($("#trans option:selected" ).val() != ""){
                $("#trans").parent().removeClass("err");
                pass = true
            }else{
                $("#trans").parent().addClass("err");
            }
        }else{
            $("#vls").parent().addClass("err");
        }    
    }else if(formStepsNum == 1){
            if($("#title").val().length >= 10){
                $("#title").parent().parent().removeClass("err");
                if($("#desc").val().length >= 10){
                    $("#desc").parent().removeClass("err");
                    pass = true;
                }else{
                    $("#desc").parent().addClass("err");
                    
                }
            }else{
                $("#title").parent().parent().addClass("err");
            }
    }else if(formStepsNum == 2){
      if(anotherpass){
        pass = true;
      }else{
        pass = false;
      }
    }else{
      pass = true;
    }

    if(pass == true){
      formStepsNum += 1;
      updateFormSteps();
      updateProgressbar();
      pass = !pass;
    }
  });
});

$("#file").on("change", function() {
  if ($(this)[0].files.length > 8) {
    anotherpass = false;
    const swalWithBootstrapButtons = Swal.mixin({
      customClass: {
        confirmButton: 'btn btn-success',
        cancelButton: 'btn btn-danger'
      },
      buttonsStyling: false
    })
    
    swalWithBootstrapButtons.fire({
      title: 'maximum 8 imgs!',
      text: "Paid to get infinite images",
      icon: 'info',
      showCancelButton: true,
      confirmButtonText: 'Paid',
      cancelButtonText: 'No, cancel!',
      reverseButtons: true
    }).then((result) => {
      if (result.isConfirmed) {

        Swal.fire({
          title: 'Via Paypal',
          html: `<div id="smart-button-container">
          <div style="text-align: center;">
            <div id="paypal-button-container"></div>
          </div>
        </div>`,
        })
        function initPayPalButton() {
          paypal.Buttons({
            style: {
              shape: 'rect',
              color: 'gold',
              layout: 'vertical',
              label: 'paypal',
              
            },
        
            createOrder: function(data, actions) {
              return actions.order.create({
                purchase_units: [{"amount":{"currency_code":"USD","value":1}}]
              });
            },
        
            onApprove: function(data, actions) {
              return actions.order.capture().then(function(orderData) {
                
                // Full available details
                console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));
        
                // Show a success message within this page, e.g.
                const element = document.getElementById('paypal-button-container');
                element.innerHTML = '';
                element.innerHTML = '<h3>Thank you for your payment!</h3>';
        
                // Or go to another URL:  actions.redirect('thank_you.html');
                
              });
            },
        
            onError: function(err) {
              console.log(err);
            }
          }).render('#paypal-button-container');
        }
        initPayPalButton();

      } else if (
        /* Read more about handling dismissals below */
        result.dismiss === Swal.DismissReason.cancel
      ) {
        swalWithBootstrapButtons.fire(
          'Cancelled',
          'You have limited images (max: 8) :)',
          'error'
        )
      }
    })
    $("#imageDropzone").addClass("err");
  } else {
    anotherpass = true;
  }
});

prevBtns.forEach((btn) => {
  btn.addEventListener("click", () => {
    formStepsNum--;
    updateFormSteps();
    updateProgressbar();

    console.log(formStepsNum)
  });
});

function updateFormSteps() {
  formSteps.forEach((formStep) => {
    formStep.classList.contains("form-step-active") &&
      formStep.classList.remove("form-step-active");
  });

  formSteps[formStepsNum].classList.add("form-step-active");
}

function updateProgressbar() {
  progressSteps.forEach((progressStep, idx) => {
    if (idx < formStepsNum + 1) {
      progressStep.classList.add("progress-step-active");
    } else {
      progressStep.classList.remove("progress-step-active");
    }
  });

  const progressActive = document.querySelectorAll(".progress-step-active");

  progress.style.width =
    ((progressActive.length - 1) / (progressSteps.length - 1)) * 100 + "%";
}


let idannonce;






var inputs = document.querySelectorAll( '.inputfile' );
Array.prototype.forEach.call( inputs, function( input )
{
	var label	 = input.nextElementSibling.nextElementSibling,
		labelVal = label.innerHTML;

	input.addEventListener( 'change', function( e )
	{
		var fileName = '';
		if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length);
		else{
			fileName = e.target.value.split( '\\' ).pop();
    }
		if( fileName )
			label.innerHTML = fileName;
		else
			label.innerHTML = labelVal;
	});
});