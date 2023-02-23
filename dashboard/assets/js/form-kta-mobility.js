"use strict";
// Get the modal
var modalProgress = document.getElementById("modalProgress");
var modalSuccess = document.getElementById("modalSuccess");
var radioEktp = "File";
var radioProfile = "File";

var cardModalHtml =
    '<div id="three-ds-container" style="display: none;">' +
    '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>' + 
    '   <iframe id="sample-inline-frame" name="sample-inline-frame" width="100%" height="400"> </iframe>' +
    '</div>' +
    '<form id="credit-card-form" name="creditCardForm" method="post">' +
    '<fieldset id="fieldset-card">' +
    '<div class="col">' +
    '<div class="">' +
    '<div id="grey-price" style="background-color: #f1f1f1; border: 1px solid #c3c3c3" class="p-2 form-group-2 mt-4 mb-4">' +
    '<div class="row">' +
    '<div class="col-6" style="color: #626262">' +
    title +
    '</div>' +
    '<div class="col-6 d-flex justify-content-end">' +
    '<b id="price-second">Rp. ' + price + '</b>' +
    '</div>' +
    '</div>' +
    '<div class="row">' +
    '<div class="col-6" style="color: #626262">' +
    'Administration Fee' +
    '</div>' +
    '<div class="col-6 d-flex justify-content-end">' +
    '<b>Rp. ' + price_fee + '</b>' +
    '</div>' +
    '</div>' +
    '<div id="total-price" class="row mt-2">' +
    '<div class="col-6" style="color: #626262">' +
    'Total Payment' +
    '</div>' +
    '<div class="col-6 d-flex justify-content-end">' +
    '<b id="total-slot" style="font-size: 20px">Rp. ' + total_price + '</b>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '  <div class="row">' +
    '    Credit Card Number' +
    '  </div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" pattern="[0-9]*" required class="form-control" id="credit-card-number" placeholder="e.g: 4000000000000002" name="creditCardNumber">' +
    '  </div>' +
    '  <div class="row mb-4">' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    Month' +
    '  </div>' +
    '      <div class="row">' +
    '        <select required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-month" placeholder="MM" style="border-color: #608CA5" name="creditCardExpMonth">' +
    '          <option>01</option>' +
    '          <option>02</option>' +
    '          <option>03</option>' +
    '          <option>04</option>' +
    '          <option>05</option>' +
    '          <option>06</option>' +
    '          <option>07</option>' +
    '          <option>08</option>' +
    '          <option>09</option>' +
    '          <option>10</option>' +
    '          <option>11</option>' +
    '          <option>12</option>' +
    '        </select>' +
    '      </div>' +
    '    </div>' +
    '    <div class="col-5 mx-1">' +
    '  <div class="row">' +
    '    Year' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="4" size="4" type="text" pattern="[0-9]*" required class="form-control form-control fs-16 fontRobReg" id="credit-card-exp-year" placeholder="YYYY" style="border-color: #608CA5" name="creditCardExpYear">' +    
    '      </div>' +
    '    </div>' +
    '    <div class="col-3">' +
    '  <div class="row">' +
    '    CVV' +
    '  </div>' +
    '      <div class="row">' +
    '        <input maxlength="3" size="3" type="text" pattern="[0-9]*" required class="form-control form-control fs-16 fontRobReg" id="credit-card-cvv" placeholder="123" style="border-color: #608CA5" name="creditCardCvv">' +
    '      </div>' +
    '    </div>' +
    '  </div>' +
    '<div class="row">' +
    '  <input class="pay-button text-white" onclick="return toSubmit();" type="submit" style="background-color: #f66701" id="pay-with-credit-card" value="Pay" name="payWithCreditCard">' +
    '</div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';

function numberWithDots(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

// FOR NATIVE ACCEPT JOIN IMI

var ref_id_global;

// ovo payment template
var ovoModalHtml =
    '<form id="ovo-form" name="ovoForm" method="post">' +
    '<fieldset id="fieldset-ovo">' +
    '<div class="col p-3">' +
    '  <div class="row">Phone Number</div>' +
    '  <div class="row mb-2">' +
    '    <input maxlength="16" size="16" type="text" required id="phone-number" placeholder="e.g: +6282111234567" name="phoneNumber">' +
    '  </div>' +
    '  <div class="row">' +
    '       <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitOVO();" type="submit" id="pay-with-ovo" value="Pay" name="payWithOVO">' +
    '  </div>' +
    '</div>' +
    '</fieldset>' +
    '</form>';

// dana payment template
var danaModalHtml =
    '<form id="dana-form" name="danaForm" method="post">' +
    '<fieldset id="fieldset-dana">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitDANA();" type="submit" id="pay-with-dana" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithDANA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// linkaja payment template
var linkajaModalHtml =
    '<form id="linkaja-form" name="linkajaForm" method="post">' +
    '<fieldset id="fieldset-linkaja">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitLINKAJA();" type="submit" id="pay-with-linkaja" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithLINKAJA">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// shopeepay template
var shopeepayModalHtml =
    '<form id="shopeepay-form" name="shopeepayForm" method="post">' +
    '<fieldset id="fieldset-shopeepay">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitSHOPEE();" type="submit" id="pay-with-shopeepay" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Pay" name="payWithSHOPEEPAY">' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';

// QRIS template
var qrisModalHtml =
    '<form id="qris-form" name="qrisForm" method="post">' +
    '<fieldset id="fieldset-qris">' +
    '   <div class="col p-3">' +
    '       <div class="row">' +
    '           <div id="qrcode"></div>' +
    '           <input style="background-color: #f06270" class="pay-button" onclick="return toSubmitQRIS();" type="submit" id="pay-with-qris" class="col-md-12 simple-modal-button-green py-1 px-3 m-0 my-4 fs-16" value="Generate QR Code" name="payWithQRIS">' +
    '           <br><button type="button" style="background-color: #f06270" class="pay-button mt-3 d-none" id="simulate-qris-payment">Pay QRIS</button>' +
    '       </div>' +
    '   </div>' +
    '</fieldset>' +
    '</form>';


var payment_method = "";

function selectMethod(e) {

    // document.getElementById('dropdownMenuSelectMethod').innerHTML = `${e} >`;
    // localStorage.setItem('payment-method', e.innerHTML);
    payment_method = e;
    localStorage.setItem('payment-method', payment_method);
    // console.log('select', payment_method);
}

async function palioPay() {
    this.myModal = new SimpleModal();

    try {
        const modalResponse = await myModal.question();
    } catch (err) {
        console.log(err);
    }
}

class SimpleModal {

    constructor(modalTitle) {
        this.modalTitle = "title";
        this.parent = document.body;

        this.modal = document.getElementById('modal-payment-body');
        this.modal.innerHTML = "";

        this._createModal();
    }

    question() {
        return new Promise((resolve, reject) => {
            this.closeButton.addEventListener("click", () => {
                resolve(null);
                this._destroyModal();
            })
        })
    }

    _createModal() {

        // Message window
        const window = document.createElement('div');
        window.classList.add('container');
        window.classList.add('mx-auto');
        this.modal.appendChild(window);

        // Main text
        const text = document.createElement('span');
        text.setAttribute("id", "payment-form");

        // let payment_method = document.getElementById('dropdownMenuSelectMethod').innerHTML;
        let payment_method = localStorage.getItem('payment-method');
        if (payment_method.includes("CARD")) {
            text.innerHTML = cardModalHtml;
        } else if (payment_method.includes("OVO")) {
            text.innerHTML = ovoModalHtml;
        } else if (payment_method.includes("DANA")) {
            text.innerHTML = danaModalHtml;
        } else if (payment_method.includes("LINKAJA")) {
            text.innerHTML = linkajaModalHtml;
        } else if (payment_method.includes("SHOPEEPAY")) {
            text.innerHTML = shopeepayModalHtml;
        } else if (payment_method.includes("QRIS")) {
            text.innerHTML = qrisModalHtml;
        } else {
            text.innerHTML = cardModalHtml;
        }

        window.appendChild(text);

        // SCRIPT ONLY FOR IMI (PRICE IN MODAL PAYMENT)

        if (typeof total_tax !== 'undefined') {
            if (parseInt(total_tax) > 0) {
                $("#total-price").hide();

                var html = '<div class="row">' +
                    '<div class="col-6" style="color: #626262">' +
                    'Tax (10%)' +
                    '</div>' +
                    '<div class="col-6 d-flex justify-content-end">' +
                    '<b>Rp. ' + total_tax + '</b>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row mt-2">' +
                    '<div class="col-6" style="color: #626262">' +
                    'Total Payment' +
                    '</div>' +
                    '<div id="total-price" class="col-6 d-flex justify-content-end">' +
                    '<b style="font-size: 20px">Rp. ' + total_price + '</b>' +
                    '</div>' +
                    '</div>';
                $('#grey-price').append(html);
            }
        }

        // SCRIPT FOR KIS (0) AND ERA (1) (PRICE IN MODAL PAYMENT)

        var text_kis = $('#price-second').text();

        if (text_kis == "Rp. 0") {
            $('#price-second').text("Rp. " + numberWithDots(total_price));
            $('#total-slot').text("Rp. " + numberWithDots(localStorage.getItem('grand-total')));
        }else if(text_kis == "Rp. 1"){
            $('#price-second').text("Rp. " + numberWithDots(total_price));
            $('#total-slot').text("Rp. " + numberWithDots(total_price));
        }

        // Let's rock

        $('#modal-payment').modal('show');
    }

    _destroyModal() {
        this.parent.removeChild(this.modal);
        delete this;
    }
}

function xenditResponseHandler(err, creditCardCharge) {
    if (err) {
        console.log(err);
        return displayError(err);
        // console.log(err);
    }

    // console.log(creditCardCharge);

    $('#ccLoader').addClass('d-none');

    if (creditCardCharge.status === 'APPROVED' || creditCardCharge.status === 'VERIFIED') {
        console.log("success");
        displaySuccess(creditCardCharge);
    } else if (creditCardCharge.status === 'IN_REVIEW') {
        window.open(creditCardCharge.payer_authentication_url, 'sample-inline-frame');
        $('.overlay').show();
        $('#three-ds-container').show();
    } else if (creditCardCharge.status === 'FRAUD') {
        displayError(creditCardCharge);
    } else if (creditCardCharge.status === 'FAILED') {
        displayError(creditCardCharge);
    }
}

function toSubmit() {
    event.preventDefault();

    $('#ccLoader').removeClass('d-none');

    var cc = $('#credit-card-number').val();
    var yy = $('#credit-card-exp-year').val();
    var cvv = $('#credit-card-cvv').val();

    if (cc && yy && cvv) {
        let fieldset = document.getElementById('fieldset-card');
        fieldset.setAttribute('disabled', 'disabled');

        // document.getElementById("credit-card-form").classList.add('d-none');

        //dev
        Xendit.setPublishableKey('xnd_public_development_qcfW9OvrvG3U0ph6Dc01xNMhKhhW2On4a0l7ZMUS696BBWR8vNbkSKyRZGlOLQ');
        //prod
        // Xendit.setPublishableKey('xnd_public_production_qoec6uRBSVSb4n0WwIijVZgDJevwSZ5xKuxaTRh4YBix0nMSsKgxi226yxtTd7');

        var tokenData = getTokenData();

        Xendit.card.createToken(tokenData, xenditResponseHandler);
    } else {
        $('#validation-text').text('Please fill all credit card information');
        $('#modal-validation').modal('show');
    }
}
// event.preventDefault();


function displayError(err) {
    // alert('Request Credit Card Charge Failed');
    // $('#validation-text').text(err);
    if (typeof err === 'object') {
        $('#validation-text').text(err.message);
    } else {
        $('#validation-text').text(err);
    }
    $('#modal-validation').modal('show');
    $('#three-ds-container').hide();
    $('.overlay').hide();
    let fieldset = document.getElementById('fieldset-card');
    fieldset.removeAttribute('disabled');
    // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");
};

function displaySuccess(creditCardCharge) {
    var $form = $('#credit-card-form');
    $('#three-ds-container').hide();
    $('.overlay').hide();

    var js = {
        token_id: creditCardCharge.id,
        amount: localStorage.getItem('grand-total'),
        cvv: $form.find('#credit-card-cvv').val()
    };
    // var items = JSON.stringify(cart);
    // var base64items = btoa(items);
    // var fpin = getFpin();

    // let purchased_cart = [];
    // cart.forEach(merchant => {
    //     merchant.items.forEach(product => {
    //         if (product.selected == 'checked') {
    //             let p = {};
    //             p.p_code = product.itemCode;
    //             p.price = product.itemPrice;
    //             p.amount = product.itemQuantity;
    //             p.isPost = product.isPost;
    //             purchased_cart.push(p);
    //         }
    //     })
    // })

    // if (userAgent) {
    //     var fpin = getFpin();
    // } else {
    //     var fpin = "test";
    // }

    // postForm("../logics/insert_membership_payment_mobility", {
    //     fpin: F_PIN,
    //     method: "card",
    //     status: 1,
    //     price: parseInt(parseInt(localStorage.getItem("grand-total"))),
    //     reg_type: REG_TYPE,
    //     date: new Date().getTime()
    // });

    $.post("../logics/paliobutton/php/paliopay",
        js,
        function (data, status) {
            try {
                if (data.status == "CAPTURED") {
                    // clearCart();
                    
                    insertKTA();

                    // postForm("../logics/insert_membership_payment_mobility", {
                    //     fpin: btoa(F_PIN),
                    //     method: "card",
                    //     status: 1,
                    //     price: parseInt(localStorage.getItem("grand-total")),
                    //     reg_type: REG_TYPE,
                    //     date: new Date().getTime()
                    // });
                } else {
                    alert("Credit card transaction failed");
                    let fieldset = document.getElementById('fieldset-card');
                    fieldset.removeAttribute('disabled');
                }
            } catch (err) {
                console.log(err);
                alert("Error occured");
                let fieldset = document.getElementById('fieldset-card');
                fieldset.removeAttribute('disabled');
            }
        }, 'json'
    );
}

// payment with ovo
function toSubmitOVO() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));

    var js = {
        phone_number: $('#phone-number').val(),
        amount: amt,
    };

    // var callbackURL = this.callbackURL;
    // var amount = this.price;

    $.post("../logics/paliobutton/php/paliopay_ovo",
        js,
        function (data, status) {
            try {
                if (data == "SUCCEEDED") {
                    postForm("../logics/insert_membership_payment_mobility", {
                        fpin: F_PIN,
                        method: "OVO",
                        status: 1,
                        price: parseInt(localStorage.getItem("grand-total")),
                        reg_type: REG_TYPE,
                        date: new Date().getTime()
                    });
                } else {
                    // alert("Credit card transaction failed");
                    $('#validation-text').text('An error occured');
                    $('#modal-validation').modal('show');
                    $('#three-ds-container').hide();
                    $('.overlay').hide();
                    // showSuccessModal(dictionary.checkout.notice.failed[defaultLang], "OVO");
                }
            } catch (err) {
                console.log(err);
                // alert("Error occured");
                // $('#modal-payment').modal('toggle');
                // $('#modal-payment-status-body').text("Payment failed");
                // $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
                $('#validation-text').text('An error occured');
                $('#modal-validation').modal('show');
                $('#three-ds-container').hide();
                $('.overlay').hide();
            }
        }
    );

    // alert("Please finish your payment.");
}

// payment with dana
function toSubmitDANA() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));
    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    var js = {
        // callback: this.callbackURL,
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_dana",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url);
                window.location.href = response.actions.desktop_web_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment shopeepay
function toSubmitSHOPEE() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));
    let f_pin = new URLSearchParams(window.location.search).get('f_pin');
    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_shopee",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url, "_blank");
                // window.open(response.actions.mobile_deeplink_checkout_url);
                window.location.href = response.actions.mobile_deeplink_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

function checkQRISStatus(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/qris_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            // console.log('checkqris', responseObj);

            if (responseObj.status == "COMPLETED") {
                // alert(`Payment received!`); // response is the server response

                // HIT API
                // ganti vbot_ sesuai pilihan
                // let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                // digipos_cart.method = responseObj.payment_detail.source;
                // digipos_cart.last_update = new Date().getTime();

                // vbotAPI(digipos_cart);
                postForm("../logics/insert_membership_payment_mobility", {
                    fpin: btoa(F_PIN),
                    method: "TEST_QRIS",
                    status: 1,
                    price: parseInt(localStorage.getItem("grand-total")),
                    reg_type: REG_TYPE,
                    date: new Date().getTime()
                });

            } else {
                checkQRISStatus(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

function simulateQRISPayment(ext_id) {

    let amt = parseInt(localStorage.getItem("grand-total"));

    var js = {
        amount: amt,
        external_id: ext_id
    };

    $.post("../logics/paliobutton/php/qris_check",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                let responseObj = JSON.parse(data);
                // console.log('simulateqris', responseObj);

                if (responseObj.status == "COMPLETED") {
                    // alert(`Payment received!`); // response is the server response
                    var method = responseObj.payment_details.source ? responseObj.payment_details.source : "TEST_QRIS";

                    // HIT API
                    // ganti vbot_ sesuai pilihan
                    // let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                    // digipos_cart.method = method;
                    // digipos_cart.last_update = new Date().getTime();

                    // vbotAPI(digipos_cart);

                    postForm("../logics/insert_membership_payment_mobility", {
                        fpin: btoa(F_PIN),
                        method: method,
                        status: 1,
                        price: parseInt(localStorage.getItem("grand-total")),
                        reg_type: REG_TYPE,
                        date: new Date().getTime()
                    });
                } else {
                    checkQRISStatus(id);
                }
            } catch (err) {
                console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

function runSimulateQRIS(ext_id) {
    $('#simulate-qris-payment').off('click');

    $('#simulate-qris-payment').removeClass('d-none');

    $('#simulate-qris-payment').click(function (e) {
        e.preventDefault();

        simulateQRISPayment(ext_id);
    })
}

function toSubmitQRIS() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        // callback: window.location.origin + "/nexilis/pages/payment.php?f_pin=" + getFpin(),
        callback: window.location.href,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_qris",
        // $.post("/test/paliopay_dana",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                // console.log(response);

                new QRCode(document.getElementById('qrcode'), response.qr_string);

                runSimulateQRIS(response.external_id);

            } catch (err) {
                console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "DANA");
            }
        }
    );
}

// payment with linkaja
function toSubmitLINKAJA() {
    event.preventDefault();

    let amt = parseInt(localStorage.getItem("grand-total"));

    let f_pin = new URLSearchParams(window.location.search).get('f_pin');

    var js = {
        // callback: this.callbackURL,
        // callback: "http://202.158.33.26/paliobutton/php/close",
        callback: window.location.origin + "/nexilis/pages/digipos.php?f_pin=" + f_pin,
        amount: amt,
    };

    $.post("../logics/paliobutton/php/paliopay_linkaja",
        js,
        function (data, status) {
            try {
                var response = JSON.parse(data);
                localStorage.setItem('ewallet_id', response.id);
                // console.log(response);
                checkEwallet(response.id);

                // window.open(response.actions.desktop_web_checkout_url);
                window.location.href = response.actions.desktop_web_checkout_url;
                // console.log(response.actions.desktop_web_checkout_url);
            } catch (err) {
                // console.log(err);
                // alert("Error occured");
                $('#modal-payment').modal('toggle');
                $('#modal-payment-status-body').text("Payment failed");
                $('#modal-payment-status').modal('toggle');
                // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "LINKAJA");
            }
        }
    );
}

// check ewallet payment status
function checkEwallet(id) {
    // 1. Create a new XMLHttpRequest object
    let xhr = new XMLHttpRequest();

    // 2. Configure it: GET-request for the URL /article/.../load
    xhr.open('GET', '../logics/ewallet_check?id=' + id);
    // xhr.open('GET', '/test/ewallet_check?id=' + id);

    xhr.responseType = 'json';

    // 3. Send the request over the network
    xhr.send();

    // 4. This will be called after the response is received
    xhr.onload = async function () {
        if (xhr.status != 200) { // analyze HTTP status of the response
            // alert(`Error ${xhr.status}: ${xhr.statusText}`); // e.g. 404: Not Found
            console.log(`Error ${xhr.status}: ${xhr.statusText}`);
            $('#modal-payment').modal('toggle');
            $('#modal-payment-status-body').text("Payment failed");
            $('#modal-payment-status').modal('toggle');
            // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "");

        } else { // show the result
            let responseObj = xhr.response;
            // console.log(responseObj);

            if (responseObj.status == "SUCCEEDED" || responseObj.status == "COMPLETED") {
                // alert(`Payment received!`); // response is the server response
                localStorage.removeItem('ewallet_id');
                if (responseObj.channel_code == "ID_DANA") {
                    var method = "DANA";
                } else if (responseObj.channel_code == "ID_LINKAJA") {
                    var method = "LINKAJA";
                } else if (responseObj.channel_code == "ID_SHOPEEPAY") {
                    var method = "SHOPEEPAY";
                }

                // HIT API
                // ganti vbot_ sesuai pilihan
                let digipos_cart = JSON.parse(localStorage.getItem("digipos_cart"));
                digipos_cart.method = method;
                digipos_cart.last_update = new Date().getTime();

                vbotAPI(digipos_cart);

            } else {
                checkEwallet(id);
            }
            // alert(`Done, got ${xhr.response.length} bytes`); // response is the server response
        }
    };

    xhr.onerror = function () {
        alert("Request failed");
        // showSuccessModal(dictionary.checkout.notice.error[defaultLang], "OVO");
    };
}

function postForm(path, params, method) {
    method = method || 'post';

    var form = document.createElement('form');
    form.id = 'my-form';
    form.setAttribute('method', method);
    form.setAttribute('action', path);

    for (var key in params) {
        if (params.hasOwnProperty(key)) {
            var hiddenField = document.createElement('input');
            hiddenField.setAttribute('type', 'hidden');
            hiddenField.setAttribute('name', key);
            hiddenField.setAttribute('value', params[key]);

            form.appendChild(hiddenField);
        }
    }

    document.body.appendChild(form);

    let myform = $('#my-form')[0];
    let formData = new FormData(myform);

$.ajax({
        type: "POST",
        url: "/gaspol_web/logics/insert_membership_payment_mobility",
        data: formData,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            submitForm(REG_TYPE);
        },
        error: function (response) {
            alert(response.responseText);
        }
    })

    // let xmlHttp = new XMLHttpRequest();
    // xmlHttp.onreadystatechange = function () {
    //   if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {
    //     // // console.log(xmlHttp.responseText);
    //     // updateScore($productCode);
    //     submitForm(REG_TYPE);
    //   }
    // }
    // xmlHttp.open("post", "/gaspol_web/logics/insert_membership_payment_mobility");
    // xmlHttp.send(formData);
}

function getTokenData() {
    var $form = $('#credit-card-form');
    return {
        // amount: $form.find('#credit-card-amount').val(),
        amount: localStorage.getItem('grand-total'),
        card_number: $form.find('#credit-card-number').val(),
        card_exp_month: $form.find('#credit-card-exp-month').val(),
        card_exp_year: $form.find('#credit-card-exp-year').val(),
        card_cvn: $form.find('#credit-card-cvv').val(),
        is_multiple_use: false,
        should_authenticate: true
    };
}

$.validator.addMethod("photoSize", function (value, element, param) {
    return this.optional(element) || (element.files[0].size <= param);
}, 'File must be JPG, GIF or PNG, less than 3MB');
$.validator.addMethod("checkPhoto", function (value, element, param) {
    return this.optional(element) || /png|jpe?g|gif/g.test(element.files[0].name.toLowerCase().split('.').pop());
}, 'File must be JPG, GIF or PNG, less than 3MB');
$('#admin-kta-form').validate({
    rules: {
        ektp: {
            number: true
        },
        fotoEktp: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoSim: {
            photoSize: 4000000,
            checkPhoto: true
        },
        fotoProfil: {
            photoSize: 4000000,
            checkPhoto: true
        }
    },
    submitHandler: function(form,event) {
        // modalProgress.style.display = "block";
        event.preventDefault();
        // var myform = $("#kta-form")[0];
        // var fd = new FormData(myform);
        
        // fd.append("f_pin",F_PIN);

        // VALIDATION

        var name = $('#name').val();
        var email = $('#email').val();
        var birthplace = $('#birthplace').val();
        var date_birth = $('#date_birth').val();
        // var gender_radio = document.querySelector('input[name="gender_radio"]:checked').value;
        var gender_radio = $('#gender').val();
        // var kta_type = document.querySelector('input[name="kta_type"]:checked').value;
        var kta_type = $('#kta_type').val();
        var bloodtype = $('#bloodtype').val();
        var nationality = $('#nationality').val();
        var hobby = $('#hobby').val();
        var nohp = $('#nohp').val();

        var address = $('#address').val();
        var rt = $('#rt').val();
        var rw = $('#rw').val();
        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        var payment = $('#dropdownMenuSelectMethod').val(); 

        var fotoProfile = $('#fotoProfile').val();
        var fotoEktp = $('#fotoEktp').val();
        var ektp = $('#ektp').val();

        console.log("name = "+name);
        console.log("email = "+email);
        console.log("birthplace = "+birthplace);
        console.log("date_birth = "+date_birth);
        console.log("gender_radio = "+gender_radio);
        console.log("no_hp = "+nohp);
        console.log("bloodtype = "+bloodtype);
        console.log("nationality = "+nationality);
        console.log("hobby = "+hobby);
        console.log("address = "+address);
        console.log("rt = "+rt);
        console.log("rw = "+rw);
        console.log("postcode = "+postcode);
        console.log("province = "+province);
        console.log("city = "+city);
        console.log("district = "+district);
        console.log("subdistrict = "+subdistrict);
        console.log("fotoProfile = "+fotoProfile);
        console.log("fotoEktp = "+fotoEktp);
        console.log("ektp = "+ektp);
        console.log("ktaType = "+kta_type);

        if (fotoProfile && fotoEktp && kta_type && ektp && email && date_birth && bloodtype && name && birthplace && gender_radio && nationality && hobby && address && rt && rw && city && subdistrict && province && district && postcode && payment && is_takken == 1 && is_takken_ktp == 1){

            // if ($('#flexCheckChecked').is(':checked')) {

                // validateEmail();
                palioPay();
                // $("#submit").attr("style", "font-size: 16px; height: 50px; background-color: #FF6B00; color: white !important; border-radius: 30px; border: 1px solid transparent; font-weight: 700");
                
                
            

            // }else{

            //     $("#submit").attr("style", "font-size: 16px; height: 50px; background-color: #FF6B00; color: white !important; border-radius: 30px; border: 1px solid transparent; font-weight: 700")
            //     $('#validation-text').text("Please check Terms & Condition and Privacy Policy from Gaspol!");
            //     $('#modal-validation').modal('show');

            // }

        }else{

            // $("#submit").attr("style", "font-size: 16px; height: 50px; background-color: #E7E7E7; color: white !important; border-radius: 30px; border: 1px solid transparent; font-weight: 700")
            console.log("Gagal Submit");
            $('#validation-text').text("Please fill all required form");
            $('#modal-validation').modal('show');

        }


        // $("#submit").prop("disabled", true);
        
        // $.ajax({
        //     type: "POST",
        //     url: "/gaspol_web/logics/register_new_kta",
        //     data: fd,
        //     enctype: 'multipart/form-data',
        //     cache : false,
        //     processData: false,
        //     contentType: false,
        //     success: function (response) {
        //         modalProgress.style.display = "none";
        //         modalSuccess.style.display = "block";
        //         if (window.Android) {
        //             window.Android.finishGaspolForm()
        //         }
        //         $("#submit").prop("disabled", false);
        //     },
        //     error: function (response) {
        //         modalProgress.style.display = "none";
        //         alert(response.responseText);
        //         $("#submit").prop("disabled", false);
        //     }
        // });
        // $.post('/gaspol_web/logics/register_new_esim',fd,function(){
        //     alert( "success" );
        //   },"multipart/form-data").done(function() {
        //       alert( "second success" );
        //     }).fail(function() {
        //       alert( "error" );
        //     }).always(function() {
        //       alert( "finished" );
        //     });
    }
});

function generateRandomNumber() {
    var minm = 100000;
    var maxm = 999999;
    return Math.floor(Math
    .random() * (maxm - minm + 1)) + minm;
}

function validateEmail(){

    $('#modal-otp').modal('show');

    var formData = new FormData();

    var email = $('#email').val();
    var otp = generateRandomNumber();
    localStorage.setItem('otp',otp);

    $('#email-place-otp').text(email);

    formData.append('email', email);
    formData.append('otp', otp);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = JSON.parse(xmlHttp.responseText);
 
            // $('#modal-otp').modal('show');
            
        }
    }
    xmlHttp.open("post", "../logics/send_email_gmail");
    xmlHttp.send(formData);

}

function checkOTP(){

    var input = $('#input-otp').val();
    var otp = localStorage.getItem('otp');

    if (input == otp){

        $('#modal-otp').modal('hide');
        palioPay();

    }else{
        $('#otp-not-correct').removeClass('d-none');
    }
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modalSuccess) {
    modalSuccess.style.display = "none";
    window.open(
        '/gaspol_web/pages/card-kta-mobility.php?f_pin=' + F_PIN,
        '_blank' // <- This is what makes it open in a new window.
      );
    // document.location = 'card-kta.php?f_pin=' + F_PIN;
  }
}

function ktpOcr(data){
    // nik name address
    var d = JSON.parse(data)

    if (($('#ektp').val() == null || $('#ektp').val() == "") && (d['nik'] != null && d['nik'] != "")){

        $('#ektp').val(d['nik'])
        $('#ektp-error').text("");
        $('.starnoktp').hide();

        var formData = new FormData();
        formData.append('ektp', d['nik']);


        if(d['nik'].length < 16){
            $('#ktp-not-exist').text("Image detection might be imperfect. Please correct accordingly.");
            $('#ktp-exist').text("");
        } else {
            let xmlHttp = new XMLHttpRequest();
            xmlHttp.onreadystatechange = function(){
                if (xmlHttp.readyState == 4 && xmlHttp.status == 200){
                    
                    // console.log(xmlHttp.responseText);

                    var result = xmlHttp.responseText;

                    if (result == 1){
                        console.log("KTP Ada");
                        $('#ktp-not-exist').text("");
                        $('#ktp-exist').text("That KTP Number is taken, try another.");

                        is_takken_ktp = 0;

                    }else if(result == 0){
                        console.log("KTP Tidak Ada");
                        $('#ktp-not-exist').text("That KTP Number is available.");
                        $('#ktp-exist').text("");

                        is_takken_ktp = 1;
                    }

                }
            }
            xmlHttp.open("post", "../logics/check_ktp");
            xmlHttp.send(formData);
        }
    }

    if (($('#name').val() == null || $('#name').val() == "") && (d['name'] != null && d['name'] != "")){
        $('#name').val(d['name'])

        // $('#ektp-error').text("");
        $('.fullname').hide();
        
    }

    if (($('#address').val() == null || $('#address').val() == "") && (d['address'] != null && d['address'] != "")){
        $('#address').val(d['address'])

        // $('#ektp-error').text("");
        $('.staraddress').hide();
        
    }
}

$("input[name=ektp_radio]:radio").on("click", function(){
    if($(this).val() == "File"){
        $('#fotoEktp').prop('required',true);
        $("#ektpLabelBtn").text("Choose File")
        $("#fotoEktp").prop('accept',"image/*,ocr_file/*")
        radioEktp = $(this).val();

        $('#imageKTP').attr('src','../assets/img/tab5/create-post-black.png');
        $('#ektpFileName').text("No file chosen");
    }
    else {
        $('#fotoEktp').prop('required',false);
        $("#ektpLabelBtn").text("Take Photo")
        $("#fotoEktp").prop('accept',"image/*,ocr_photo/*")
        radioEktp = $(this).val();

        $('#imageKTP').attr('src','../assets/img/tab5/create-post-black.png');
        $('#ektpFileName').text("No file chosen");
    }
});

$('#fotoEktp').change(function (e) { 
    e.preventDefault();
    $('#ektpFileName').text(this.files[0].name)
});

// NEW PROFILE PIC

$("input[name=profile_radio]:radio").on("click", function(){
    if($(this).val() == "File"){
        $('#fotoProfile').prop('required',true);
        $("#profileLabelBtn").text("Choose File")
        $("#fotoProfile").prop('accept',"image/*,profile_file/*")
        radioProfile = $(this).val();

        $('#imageProfile').attr('src','../assets/img/tab5/create-post-black.png');
        $('#profileFileName').text("No file chosen");
    }
    else {
        $('#fotoProfile').prop('required',false);
        $("#profileLabelBtn").text("Take Photo")
        $("#fotoProfile").prop('accept',"image/*,profile_photo/*")
        radioProfile = $(this).val();

        $('#imageProfile').attr('src','../assets/img/tab5/create-post-black.png');
        $('#profileFileName').text("No file chosen");
    }
});

$('#fotoProfile').change(function (e) { 
    e.preventDefault();
    $('#profileFileName').text(this.files[0].name)
});

function sendInstruction() {

    var formData = new FormData();

    var email = $('#email').val();
    var f_pin = F_PIN;

    formData.append('email', email);
    formData.append('f_pin', f_pin);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = JSON.parse(xmlHttp.responseText);

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "../../../logics/send_email_gmail_2");
    xmlHttp.send(formData);

}

function insertKTA(){

    var myform = $("#admin-kta-form")[0];
    var fd = new FormData(myform);
    fd.append("f_pin", F_PIN);
    fd.append("is_android", 0);
    fd.append("postcode", $('#postcode').text());
    $.ajax({
        type: "POST",
        url: "/gaspol_web/pages/gaspol-landing/dashboard/logics/register_new_kta_mobility",
        data: fd,
        enctype: 'multipart/form-data',
        cache: false,
        processData: false,
        contentType: false,
        success: function (response) {
            // modalProgress.style.display = "none";
            $('#modalProgress').modal('hide');
            $('#modal-payment').modal('hide');
            // modalSuccess.style.display = "block";

            console.log(response);
            $('#modalMembership').modal('show');

            $("#submit").prop("disabled", false);

            // sendInstruction();

            insertRegisterPayment();
        },
        error: function (response) {
            // modalProgress.style.display = "none";
            $('#modalProgress').modal('hide');
            $('#modal-payment').modal('hide');
            // alert(response.responseText);

            $('#error-modal-text').text(response.responseText);
            $('#modal-error').modal('show');
            $("#submit").prop("disabled", false);
        }
    });

}

function insertRegisterPayment(){

    var formData = new FormData();

    var f_pin = '';
    var method = 'admin';
    var status = 1;
    var price = PRICE;
    var reg_type = REG_TYPE;

    var datex = new Date();
    var date = datex.getTime();

    formData.append('f_pin', f_pin);
    formData.append('method', method);
    formData.append('status', status);
    formData.append('price', price);
    formData.append('reg_type', reg_type);
    formData.append('date', date);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function () {
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200) {

            const response = xmlHttp.responseText;

            // $('#modal-otp').modal('show');

        }
    }
    xmlHttp.open("post", "logics/insert_membership_payment_mobility");
    xmlHttp.send(formData);


}