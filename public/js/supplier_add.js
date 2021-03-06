function save() {
    let name = document.getElementById("supplier-name").value;
    let email = document.getElementById("supplier-email").value;
    let contact = document.getElementById("supplier-contact").value;
    let address = document.getElementById("supplier-address").value;
    let data = {
        name: name,
        email: email,
        contact: contact,
        address: address
    };
    promiseJax("/supplier/add", data, "POST", 1, 1).then(response => {
        openPopup('/ajax/popup/feedback', {
            msg: "Supplier " + name + " has been added"
        });

    }).catch((response) => {
        validate(response.errors);
    });
}

function validate(errors) {
    let nameElement = document.getElementById("supplier-name");
    let emailElement = document.getElementById("supplier-email");
    let contactElement = document.getElementById("supplier-contact");
    let addressElement = document.getElementById("supplier-address");
    let feedbacksElements = document.getElementsByClassName("invalid-feedback");
    Array.from(feedbacksElements).forEach(element => {
        element.innerText = "";
    });


    if (errors.hasOwnProperty('name')) {
        nameElement.focus();
        let error = nameElement.nextElementSibling;
        error.innerText = errors.name;

    }

    if (errors.hasOwnProperty('email')) {
        nameElement.focus();
        let error = emailElement.nextElementSibling;
        error.innerText = errors.email;

    }
    if (errors.hasOwnProperty('contact')) {
        contactElement.focus();
        let error = contactElement.nextElementSibling;
        error.innerText = errors.contact;

    }
    if (errors.hasOwnProperty('address')) {
        addressElement.focus();
        let error = addressElement.nextElementSibling;
        error.innerText = errors.address;

    }
}
