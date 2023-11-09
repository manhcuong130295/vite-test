<style>
    #message-container {
        width: 100%;
        text-align: center;
        display: none;
    }
    #message {
        padding: 10px;
        border-radius: 5px;
        color: #fff;
    }
    .success {
        background-color: #4CAF50;
    }
    .error {
        background-color: #f44336;
        color: white;
    }
    .bg-aliceblue {
        background: aliceblue;
    }
</style>
<script src="https://js.stripe.com/v3/"></script>
<form id="payment-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Update Payment Method</div>
                    @if (!empty($paymentCustomer))
                        <div id="card-info" class="d-flex justify-content-between align-items-center p-3 bg-aliceblue">
                            <span>Current card:</span>
                            <span id="card-number">Card Number: **** **** **** {{ $paymentCustomer->card->last4 }}</span>
                            <span id="expiration-date">Expiration Date: {{ $paymentCustomer->card->exp_month }} /{{ $paymentCustomer->card->exp_year }}</span>
                            <button id="delete-card" class="btn btn-danger">Delete Card</button>
                            <input type="hidden" id="card_id" value="{{ $paymentCustomer->id }}"/>
                        </div>
                    @endif
                    <div class="card-body" id="card-element"></div>
                    <div class="d-flex justify-content-end">
                        <button id="btn_pymennt_save" type="submit" class="btn btn-primary mx-1 mb-3">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="card-errors" class="p-3" role="alert"></div>
    <div id="message-container">
        <div id="message"></div>
    </div>
  </form>
  <script>
    var stripe = Stripe(`{{ $stripe_pk }}`);
    var elements = stripe.elements();
    var cardElement = elements.create('card');
    const user_id = `{{ auth()->user()->uuid }}`;

    cardElement.mount('#card-element');

    var form = document.getElementById('payment-form');
    var errorElement = document.getElementById('card-errors');

    form.addEventListener('submit', function(event) {
      event.preventDefault();
      $('#btn_pymennt_save').addClass('disabled');
      errorElement.classList.remove('error');
      errorElement.innerHTML = "";


      stripe.createToken(cardElement).then(function(result) {
        if (result.error) {
          errorElement.textContent = result.error.message;
          errorElement.classList.add('error');
          $('#btn_pymennt_save').removeClass('disabled');
          $('#message-container').addClass('d-none');
        } else {
          var token = result.token.id;
          $.ajax({
            type: 'POST',
            url: '{{ route("api.card.update") }}',
            data: { stripeToken: token, user_id: user_id },
            success: function(response) {
                $('#btn_pymennt_save').removeClass('disabled');
              if (response.success) {
                showMessage('success', 'The payment card has been updated successfully.');
              } else {
                showMessage('error', 'An error occurred while updating the payment card.');
              }
            }
          });
        }
      });
    });

    function showMessage(type, message) {
        var messageContainer = $('#message-container');
        var messageElement = $('#message');
        var className = (type === 'success') ? 'success' : 'error';
        messageElement.text(message);
        messageContainer.removeClass('d-none').addClass(className);
        messageContainer.css('display', 'block');
        window.location.reload();
    }

    var deleteCardButton = document.getElementById('delete-card');
    deleteCardButton.addEventListener('click', function(event) {
      event.preventDefault();
      var cardId = $('#card_id').val();

      Swal.fire({
        icon: 'warning',
        html: `<div class="w-full">
                    <p class="fs-5 fw-semibold">Are you sure delete this card ?</p>
                    <p class="fs-3 mt-2 text-warning">You won't be able to revert this!</p>
               </div>`,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Delete',
        showCancelButton: true,
        showConfirmButton: true, 
        reverseButtons: true,
        customClass: {
          confirmButton: 'btn btn-danger swal-button-container',
          cancelButton: 'btn btn-dark swal-button-container me-2',
          icon: 'fs-1'
        },
        allowOutsideClick: () => !Swal.isLoading(),
        allowOutsideClick: false,
        allowEscapeKey: false,
        backdrop: true,
        buttonsStyling: false,

      }).then(function(result) {
        if (result.value) {
          $.ajax({
            type: 'POST',
            url: '{{ route("api.card.delete") }}',
            data: { card_id: cardId, user_id: user_id },
            success: function(response) {
              $('#btn_pymennt_save').removeClass('disabled');
              if (response.success) {
                $('#card-info').remove();
                showMessage('success', 'The payment card has been updated successfully.');
              } else {
                showMessage('error', 'An error occurred while delete the payment card.');
              }
            }
          });
        }
      });
    });
  </script>


