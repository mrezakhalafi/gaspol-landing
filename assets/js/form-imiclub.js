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
                    
                    insertTKT();

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

var ref_id_global;

$('#imiclub-form').validate({
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

        var fotoprofile = $('#fotoProfile').val();
        var clubname = $('#club_name').val();
        var clubtype = document.querySelector('input[name="clubtype_radio"]:checked').value;
        var kategori = $('#category').val();
        var clublocation = $('#club_location').val();
        var description = $('#desc').val();
        var link = $('#link').val();

        var address = $('#address').val();
        var rt = $('#rt').val();
        var rw = $('#rw').val();
        var postcode = $('#postcode').val();
        var province = $('#province').val();
        var city = $('#city').val();
        var district = $('#district').val();
        var subdistrict = $('#subdistrict').val();

        var bank = $('#bank-category').val();
        var banknumber = $('#acc-number').val();
        var bankname = $('#acc-name').val();

        var president = $('#president').val();
        var secretary = $('#secretary').val();

        var clubadmin = $('#club-admin').val();
        var clubkta = $('#admin_kta').val();

        var finance = $('#finances').val();
        var vicepresident = $('#vice-president').val();
        var hrd = $('#human-resource').val();

        var adart = $('#docAdart').val();
        var certificate = $('#docCertificate').val();
        var additional = $('#docAdditional').val();

        var method = $('#dropdownMenuSelectMethod').val();

        console.log("fotoProfile = "+fotoprofile);
        console.log("club_name = "+clubname);
        console.log("clubtype_radio = "+clubtype);
        console.log("category = "+kategori);
        console.log("club_location = "+clublocation);
        console.log("desc = "+description);
        console.log("link = "+link);

        // console.log("nationality = "+nationality);
        // console.log("hobby = "+hobby);

        console.log("address = "+address);
        console.log("rt = "+rt);
        console.log("rw = "+rw);
        console.log("postcode = "+postcode);
        console.log("province = "+province);
        console.log("city = "+city);
        console.log("district = "+district);
        console.log("subdistrict = "+subdistrict);

        console.log("bank-category = "+bank);
        console.log("acc-number = "+banknumber);
        console.log("acc-name = "+bankname);

        console.log("president = "+president);
        console.log("secretary = "+secretary);
        console.log("club-admin = "+clubadmin);
        console.log("club-kta = "+clubkta);
        console.log("finance = "+finance);
        console.log("vice-president = "+vicepresident);
        console.log("human-resource = "+hrd);

        console.log("docAdart = "+adart);
        console.log("docCertificate = "+certificate);
        console.log("docAdditional = "+additional);

        console.log("method = "+method);

        // console.log("fotoProfile = "+fotoProfile);
        // console.log("fotoEktp = "+fotoEktp);
        // console.log("ektp = "+ektp);

        if (fotoprofile && clubname && clubtype && kategori && clublocation && description && link && address && rt && rw && postcode && province && city && district && subdistrict && bank && banknumber && bankname && president && secretary && clubadmin && finance && adart && certificate && additional && is_takken == 1 && clubkta && method){

            palioPay();

        }else{

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

function makeGroup(){

    var formData = new FormData();

    var f_pin = admin_f_pin;
    var club_name = $('#club_name').val();
    var base64_image = $('#club_image').attr('src').split(',')[1];
    let desc = $('#desc').val();

    formData.append('f_pin', f_pin);
    formData.append('group_name', club_name);
    formData.append('base64_image', base64_image);
    formData.append('description', desc)

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = xmlHttp.responseText;
            console.log("1",response);

            // var name_group = club_name;

            var id_group = response.split("\n")[1];
            // console.log("2",id_group);

            updateGroup(club_name,id_group);
            
        }
    }
    xmlHttp.open("post", "../logics/make_group_private");
    xmlHttp.send(formData);
}

function updateGroup(club_name,id_group){

    var formData = new FormData();

    var name_group = club_name;
    var id_group = id_group;

    formData.append('name_group', name_group);
    formData.append('id_group', id_group);

    let xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function(){
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200){

            const response = xmlHttp.responseText;
            console.log(response);

        }
    }
    xmlHttp.open("post", "../logics/update_group");
    xmlHttp.send(formData);
}

// When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//   if (event.target == modalSuccess) {
//     modalSuccess.style.display = "none";
//     window.open(
//         '/gaspol_web/pages/card-kta-mobility.php?f_pin=' + F_PIN,
//         '_blank' // <- This is what makes it open in a new window.
//       );
//     // document.location = 'card-kta.php?f_pin=' + F_PIN;
//   }
// }

function taaDocs(data){
    // nik name address
    var d = JSON.parse(data)

    // if (($('#ektp').val() == null || $('#ektp').val() == "") && (d['nik'] != null && d['nik'] != "")){
    //     $('#ektp').val(d['nik'])
    // }

    if (($('#name').val() == null || $('#name').val() == "") && (d['name'] != null && d['name'] != "")){
        $('#name').val(d['name'])
    }

    if (($('#address').val() == null || $('#address').val() == "") && (d['address'] != null && d['address'] != "")){
        $('#address').val(d['address'])
    }
}

// $("input[name=ektp_radio]:radio").on("click", function(){
//     if($(this).val() == "File"){
//         $('#fotoEktp').prop('required',true);
//         $("#ektpLabelBtn").text("Choose File")
//         $("#fotoEktp").prop('accept',"image/*,ocr_file/*")
//         radioEktp = $(this).val();
//     }
//     else {
//         $('#fotoEktp').prop('required',false);
//         $("#ektpLabelBtn").text("Take Photo")
//         $("#fotoEktp").prop('accept',"image/*,ocr_photo/*")
//         radioEktp = $(this).val();
//     }
// });

// $('#fotoEktp').change(function (e) { 
//     e.preventDefault();
//     $('#ektpFileName').text(this.files[0].name)
// });

// CLUB PHOTO

$("input[name=profile_radio]:radio").on("click", function(){
    if($(this).val() == "File"){
        $('#fotoProfile').prop('required',true);
        $("#profileLabelBtn").text("Choose File")
        $("#fotoProfile").prop('accept',"image/*,profile_file/*")
        radioProfile = $(this).val();

        $('#club_image').attr('src','../assets/img/tab5/create-post-black.png');
        $('#profileFileName').text("No file chosen");
    }
    else {
        $('#fotoProfile').prop('required',false);
        $("#profileLabelBtn").text("Take Photo")
        $("#fotoProfile").prop('accept',"image/*,profile_photo/*")
        radioProfile = $(this).val();

        $('#club_image').attr('src','../assets/img/tab5/create-post-black.png');
        $('#profileFileName').text("No file chosen");
    }
});

$('#fotoProfile').change(function (e) { 
    e.preventDefault();
    $('#profileFileName').text(this.files[0].name)
});

$('#docAdart').change(function (e) { 
    e.preventDefault();
    $('#adartFileName').text(this.files[0].name)
});

// CERTIFICATE DOCUMENT
$('#docCertificate').change(function (e) { 
    e.preventDefault();
    $('#certificateFileName').text(this.files[0].name)
});

// ADDITIONAL CERTIFICATE DOCUMENT
$('#docAdditional').change(function (e) { 
    e.preventDefault();
    $('#additionalFileName').text(this.files[0].name)
});


function insertRegisterPayment(){

    var formData = new FormData();

    var f_pin = admin_f_pin;
    var method = 'admin';
    var status = 1;
    var price = PRICE;
    var reg_type = REG_TYPE;

    var datex = new Date();
    var date = datex.getTime();

    formData.append('fpin', f_pin);
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

function insertTKT(){

    var myform = $("#imiclub-form")[0];
    var fd = new FormData(myform);

    var is_android = 0;

    if (window.Android) {
        is_android = 1;
    }

    fd.append("f_pin", admin_f_pin);
    fd.append("postcode", $('#postcode').text());
    fd.append("is_android", is_android);

    var date = new Date();
    ref_id_global = F_PIN + date.getTime();
    fd.append("ref_id", ref_id_global);

    var category = $('#category').val();
    fd.append("category", category);

    $.ajax({
        type: "POST",
        url: "/gaspol_web/logics/register-imi-club",
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
            
            $('#modalSuccess').modal('show');

            insertRegisterPayment();
            makeGroup();

            $("#submit").prop("disabled", false);

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