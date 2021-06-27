// Functions from postCalls.js          // Functions from helperFunctions.js
//      refreshInvoiceTable                     editElement
//      refreshNotes                            getElemValue 



// Various elements to show/hide later
const notepad = document.getElementById('notepad');
const showNoteBtn = document.getElementById('show-note-btn');
const noteBtnContainer = document.getElementById('note-btn-div');
const customerBtn = document.getElementById('add-customer-btn');

// An array holding commonly used id tags
const idArray = [
    'first-name',
    'last-name',
    'address',
    'zip-code',
    'city',
    'state',
    'phone',
    'email',
    'preferred-contact'
];


// Handles showing and hiding various form elements when a user clicks 'create new customer' button
const createNewCustomer = (e) => {
    
    // Get elements to hide/show
    const filterForm = document.getElementById('filter-form');
    const customerDisplay = document.getElementById('customer-display');
    const createForm = document.getElementById('create-container');
    const customerBtn = document.getElementById('create-btn');

    // Hide/Show elements
    filterForm.style.display = 'none';
    customerDisplay.style.display = 'none';
    customerBtn.style.display = 'none';   // hides button that was clicked 

    createForm.style.display = 'flex';
};

// Toggles the add note form 
const toggleNote = () => {

    // Determine current state of note form and reverse it
    if (notepad.style.display === 'none') {
        notepad.style.display = 'inherit';
        noteBtnContainer.style.display = 'flex';

        showNoteBtn.style.display = 'none';
    } else {
        notepad.style.display = 'none';
        notepad.value = '';
        noteBtnContainer.style.display = 'none';
        showNoteBtn.style.display = 'inherit';
    } 
};

// Shows invoice form
const toggleInvoice = () => {
    const invoiceForm = document.getElementById('add-invoice-input-container');
    const addInvoiceBtn = document.getElementById('show-invoice-btn');  // Button that was just clicked and needs to be hidden

    if (invoiceForm.style.display === 'none') {
        invoiceForm.style.display = 'flex';
        addInvoiceBtn.style.display = 'none';
    } else {
        invoiceForm.style.display = 'none';
        addInvoiceBtn.style.display = 'inherit';
    }
};

// Shows the edit form 
const showEditForm = () => {
    document.getElementById('edit-container').style.display = 'flex';
};

// Changes 'Add Customer' btn to 'Update Customer' and changes its onClick functionality
const toggleCustomerBtn = () => {
    const customerLabel = document.getElementById('create-label');

    // check to see what function the button currently has and toggle it
    if (customerBtn.name === 'create') {
        customerBtn.name = 'update';
        customerBtn.innerHTML = 'Update Customer';
        customerBtn.onclick = updateCustomer;
        customerBtn.setAttribute('type', 'button');
        customerLabel.innerHTML = 'Edit Customer';

    } else {
        customerBtn.name = 'create';
        customerBtn.innerHTML = 'Add Customer';
        customerLabel.innerHTML = 'Create Customer';
        customerBtn.setAttribute('type', 'submit');
        // customerBtn.onclick = addCustomer;
    }
};


// Toggles alert to inform customer a change has been made to the customer
const toggleAlert = (alertType) => {
    const alertDiv = document.getElementById('alert-update-div');
    const alertText = document.querySelector('#alert-update-div #alert-dialog h4');
    alertText.innerHTML = `${alertType} Updated`;

    if (alertDiv.style.display === 'none') {
        alertDiv.style.display = 'flex';
    } else {
        alertDiv.style.display = 'none';
    }
};

// Updates customer information
const updateCustomer = () => {
    
    // Populate an object with all necessary values from the update form 
    let updateObj = {};
    for (let id of idArray) {
        updateObj[id] = getElemValue(id);
    }
    updateObj['uid'] = document.getElementsByClassName('user-id-input')[0].value;

    // Submit update form through ajax and post without triggering reload
    $.ajax({
        type : "POST", 
        url  : "php/operations.php",  
        data: { 
            "update": true, 
            "uid": updateObj['uid'],
            "firstName": updateObj['first-name'],
            "lastName": updateObj['last-name'],
            "address": updateObj['address'],
            "zipCode": updateObj['zip-code'],
            "city": updateObj['city'],
            "state": updateObj['state'],
            "phone": updateObj['phone'],
            "email": updateObj['email'],
            "preferredContact": updateObj['preferred-contact']
        },
        success: function(res){ 
            console.log(res);
            toggleAlert('Customer'); 
        }   
    });
};

// Submits invoice to php/db without causing refresh
const submitInvoice = () => {
    let invoiceObj = {};
    invoiceObj['invoiceCustId'] = document.getElementsByClassName('user-id-input')[0].value;
    invoiceObj['invoiceDate'] = document.getElementById('invoice-due-date').value;
    invoiceObj['service'] = document.getElementById('service-select').value;
    invoiceObj['fullyPaid'] = document.getElementById('fully-paid').value;

    $.ajax({
        type : "POST", 
        url  : "php/operations.php",  
        data: { 
            "addInvoice": true,
            'invoiceCustId': invoiceObj['invoiceCustId'],
            'invoiceDate': invoiceObj['invoiceDate'],
            'service': invoiceObj['service'],
            'fullyPaid': invoiceObj['fullyPaid']
        },
        success: function(res){  
            toggleAlert('Invoice');
            toggleInvoice();
            refreshInvoiceTable(invoiceObj['invoiceCustId']);
        }
    });
};

const submitNote = () => {
    let noteObj = {};
    noteObj['note'] = document.getElementById('notepad').value;
    noteObj['noteCustId'] = document.getElementsByClassName('user-id-input')[0].value; 

    $.ajax({
        type : "POST", 
        url  : "php/operations.php",  
        data: { 
            "noteSubmitted": true,
            'notepad': noteObj['note'],
            'uid': noteObj['noteCustId']
        },
        success: function(res){  
            toggleAlert('Notes');
            toggleNote();
            refreshNotes(noteObj['noteCustId']);
        }
    });
};

// Fills customer information form with provided array of values
const fillCustomerForm = (valueArr) => {
    // Fill in the rest of the customer's information
    let i = 1;
    for (let id of idArray) {
        editElement(id, valueArr[i]);
        i++;
    }
};

const edit = (e) => {
    createNewCustomer();    // Shows the create customer form
    showEditForm();         // Shows the note button to add a note to a customer
    toggleCustomerBtn();    // Toggles what the customer button does/says

    let id = 0;
    const td = document.querySelectorAll("#table-body tr td");
    let textValue = [];

    // Get the appropriate customers's information with the corresponding data-id
    for (let val of td) {
        if (val.dataset.id == e) {
            textValue[id++] = val.textContent;
        }
    }
    
    // uidInputs are hidden inputs holding the customers id (needed for adding notes and updating user)
    let uidInputs = document.getElementsByClassName('user-id-input');
    for (let input of uidInputs) {
        input.value = textValue[0]; // Go through both hidden inputs and populate with customer id
    }

    // Takes the customer information in textValue and populates the customer update form
    fillCustomerForm(textValue);

    // refresh notes for customer
    refreshNotes(textValue[0]);

    // refresh invoice table for customer
    refreshInvoiceTable(textValue[0]);
};

