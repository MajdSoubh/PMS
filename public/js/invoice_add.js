
class InvoiceItem {
    constructor(medicine, exp, qty, discount) {
        this.medicine = medicine;
        this.exp = exp;
        this.qty = qty;
        this.discount = discount;

    }
}

function addInvoice() {
    let container = document.getElementById("bill-rows");
    let bill = container.firstElementChild;
    let newbill = bill.cloneNode(1);
    let hr = document.createElement("hr");
    hr.classList.add("horizontal-rule");
    newbill.classList.add("canremove");
    inputs = newbill.querySelectorAll("input");
    let list = newbill.querySelector(".list");
    if (list != null) {
        list.remove();
    }
    inputs.forEach(e => {
        e.value = "";
    });
    container.appendChild(newbill);
    container.appendChild(hr);
}
function removeInvoice(element) {

    let invoice = element.parentElement.parentElement;
    let hr = invoice.nextSibling;
    if (invoice.classList.contains("canremove")) {

        invoice.remove();
        hr.remove();
    }

}


function removeChilderns(parent) {
    while (parent.firstChild) {
        parent.removeChild(parent.lastChild);
    }
}

function checkQuantity(billElement) {
    let qtyElement = billElement.querySelector("#qty");
    let avQtyElement = billElement.querySelector("#avQty");

    if ((!isNaN(avQtyElement.value)) && (!isNaN(qtyElement.value)) && (+avQtyElement.value < +qtyElement.value)) {
        qtyElement.style.color = "red";
        qtyElement.value = avQtyElement.value;
    }
    else {
        qtyElement.style.color = "var(--black2)";

    }
}
function calcPrice(billElement) {
    let qtyElement = billElement.querySelector("#qty");
    let totalElement = billElement.querySelector("#total");
    let discountElement = billElement.querySelector("#discount");
    let unitPriceElement = billElement.querySelector("#unitPrice");
    if (!isNaN(qtyElement.value) && !isNaN(discountElement.value)) {
        totalElement.value = (qtyElement.value * unitPriceElement.value);
    }

}

function fillFields(billElement) {
    let expElement = billElement.querySelector("#exp_date");
    let avQtyElement = billElement.querySelector("#avQty");
    let QtyElement = billElement.querySelector("#qty");
    let medicineELement = billElement.querySelector("#medicine");
    let price = billElement.querySelector("#unitPrice");


    //Reset all fields
    avQtyElement.value = 0;
    price.value = "";
    removeChilderns(expElement);


    isMedicineExist(medicineELement.value).then(response => {
        if (response['exist'] == 1) {
            fillExpDates(billElement).then(response => {

                fillAvQty(billElement);


            });
            fillUnitPrice(billElement);


        }
    });


}

function isMedicineExist(name) {

    return promiseJax("/medicine/exist", { "medicine": name }, "POST");
}

function fillExpDates(billElement) {

    let expElement = billElement.querySelector("#exp_date");
    let medicineELement = billElement.querySelector("#medicine");

    return promiseJax("/stock/show", { medicine: medicineELement.value }, "POST").then(response => {

        removeChilderns(expElement);
        response.instance.forEach(date => {
            let option = document.createElement("option");
            option.setAttribute("value", date.exp);
            option.innerText = date.exp;
            expElement.appendChild(option);
        })
    }).catch((error) => {

        console.log(error);
    });
}

function fillAvQty(billElement) {
    let avQtyElement = billElement.querySelector("#avQty");
    let expElement = billElement.querySelector("#exp_date");
    let medicineELement = billElement.querySelector("#medicine");
    return promiseJax("/stock/show", { medicine: medicineELement.value, "exp": expElement.value }, "POST", true).then(response => {
        if (response.instance !== null) {


            avQtyElement.value = response.instance[0].qty;

        }
        else {

            avQtyElement.value = 0;
        }
    }).catch((error) => {

        console.log(error);
    });
}

function fillUnitPrice(billElement) {
    let medicineELement = billElement.querySelector("#medicine");
    let unitPriceElement = billElement.querySelector("#unitPrice");
    let typeElement = billElement.querySelector("#type");
    return promiseJax("/medicine/show", { medicine: medicineELement.value }, "POST", true).then(response => {

        if (typeElement.value == "strip") {

            unitPriceElement.value = response.instance.strip_price;
        }
        else if (typeElement.value == "pack") {

            unitPriceElement.value = response.instance.price;
        }

    }).catch((response) => {

        console.log(response);
    });

}

function fillInvoiceDetails() {
    let billItemsElement = document.getElementById("bill-rows");
    let finalBill = document.getElementById("final-bill");
    let finalPriceElement = finalBill.querySelector("#finalprice");
    let finalDiscountElement = finalBill.querySelector("#finaldiscount");
    let finalNetElement = finalBill.querySelector("#finalnet");
    let paidElement = finalBill.querySelector("#paid");
    let paidRestElement = finalBill.querySelector("#paid-rest");
    let totalPrice = 0;
    let totalDiscount = 0;
    let totalNet = 0;
    let paidRest = 0;
    let totalAll = billItemsElement.querySelectorAll("#total");
    totalAll.forEach(totalElement => {
        totalPrice += +totalElement.value;
    })
    let discountAll = billItemsElement.querySelectorAll("#discount");
    discountAll.forEach(DiscountElement => {
        totalDiscount += +DiscountElement.value;
    })
    finalPriceElement.value = totalPrice;
    finalDiscountElement.value = totalDiscount;
    finalNetElement.value = totalPrice - totalDiscount;
    if (+paidElement.value > (+totalPrice - +totalDiscount)) {
        paidElement.style.color = "red";
        paidElement.value = totalPrice;
    }
    else {
        paidElement.style.color = "var(--rose)";
    }
    paidRest = (totalPrice - totalDiscount) - paidElement.value;
    paidRestElement.value = paidRest;

}




function saveInvoice() {
    //check all inputs if they valid
    validate();
    //get all data of invoice
    invoice = getInvoiceDetails();
    console.log(invoice);
    //send http post request contain invoice data to server
    promiseJax("/invoice/add", invoice, "POST", false).then(response => {
        //if invoice saved successfully show message success
        openPopup('/ajax/popup/feedback', { msg: "success" });

    }).catch((response) => {
        //if there is any error print it
        console.log(response);
        let errorList = document.getElementById("invalid-feedback-list");
        while (errorList.firstChild) {
            errorList.removeChild(errorList.lastChild);
        }
        for (let item of Object.values(response.errors)) {
            let li = document.createElement("li");
            li.innerText = item;
            errorList.appendChild(li);
        }
    });



}
function getInvoiceDetails() {
    let invoiceItemsArray = [];
    let invoiceItems = document.getElementsByClassName("invoice-items");
    let invoiceHeader = document.querySelector("#bill-header");
    let customer = invoiceHeader.querySelector("#customer-name");
    let invoiceDate = invoiceHeader.querySelector("#invoice-date");
    let total = document.querySelector("#finalprice");

    let paymentType = invoiceHeader.querySelector("#payment-type");
    let paidAmount = document.getElementById("paid");
    let paidRestElement = document.querySelector("#paid-rest");
    let finalNetElement = document.querySelector("#finalnet");
    let finalDiscountElement = document.querySelector("#finaldiscount");
    Array.from(invoiceItems).forEach(item => {
        let medicine = item.querySelector("#medicine");
        let qty = item.querySelector("#qty");
        let expDate = item.querySelector("#exp_date");
        let discount = item.querySelector("#discount");
        let invoiceItemObject = new InvoiceItem(medicine.value, expDate.value, qty.value, discount.value);
        invoiceItemsArray.push(invoiceItemObject);
    });
    return { status: ((paidRestElement.value == 0) ? "Paid" : "Partially Paid"), total: total.value, rest: paidRestElement.value, total_discount: finalDiscountElement.value, total_net: finalNetElement.value, date: invoiceDate.value, provider: paymentType.value, customer: customer.value, paid: paidAmount.value, items: invoiceItemsArray };
}

function validate() {
    let invoiceItems = document.getElementsByClassName("invoice-items");
    let invoiceHeader = document.querySelector("#bill-header");
    let customer = invoiceHeader.querySelector("#customer-name");
    let invoiceDate = invoiceHeader.querySelector("#invoice-date");
    let paymentType = invoiceHeader.querySelector("#payment-type");
    let paidAmount = document.getElementById("paid");
    let valid = 1;
    for (let i = 0; i < invoiceItems.length; i++) {

        let medicine = invoiceItems[i].querySelector("#medicine");
        let qty = invoiceItems[i].querySelector("#qty");
        let expDate = invoiceItems[i].querySelector("#exp_date");
        let discount = invoiceItems[i].querySelector("#discount");
        if (medicine.value.trim() == "") {
            medicine.focus();
            valid = 0;
        }
        else if (expDate.value.trim() == "") {
            expDate.focus();
            valid = 0;
        }
        else if (qty.value.trim() == "") {
            qty.focus();
            valid = 0;
        }
        else if (discount.value.trim() == "") {
            discount.focus();
            valid = 0;
        }
        if (!valid) {
            return;
        }

    }
    if (invoiceDate.value.trim() == "") {
        invoiceDate.focus();
        valid = 0;
    }
    else if (paymentType.value.trim() == "") {
        paymentType.focus();
        valid = 0;
    }
    else if (paidAmount.value.trim() == "") {
        paidAmount.focus();
        valid = 0;
    }

}

let discardBtn = document.getElementById("discard-btn").onclick = function () {
    inputs = document.getElementsByTagName("input");
    Array.from(inputs).forEach((ele) => {
        ele.value = "";
    });
};
