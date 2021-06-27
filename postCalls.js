
// Send an ajax post call to show customer's invoices from invoices table without reload
const refreshInvoiceTable = (uid) => {
    $.ajax({
        type : "POST", 
        url  : "php/operations.php",  
        data: { "invoiceCust": uid },
        success: function(res){  
            if (res) {

                // If the invoice table exists already, remove it from the document to start over
                if (document.contains(document.getElementById('invoice-table-body'))) {
                    document.getElementById('invoice-table-body').remove();
                }

                // Get invoice container and display it
                const invoiceContainer = document.getElementById('invoice-container');
                const table = document.getElementById('invoice-table');     // Get invoice table element
                invoiceContainer.style.display = 'inherit';
                table.style.display = 'inherit';

                // Create and populate body of the Invoice table 
                const tbody = document.createElement('tbody');
                tbody.setAttribute('id', 'invoice-table-body');

                // Loop through db response and populate/display invoice table
                for (let row of JSON.parse(res)) {
                    let tableRow = document.createElement('tr');

                    // Populate CustomerID
                    let td = document.createElement('td');
                    let tdText = document.createTextNode(row.customer_id);
                    td.appendChild(tdText);
                    tableRow.appendChild(td);

                    // Populate Service
                    td = document.createElement('td');
                    tdText = document.createTextNode(row.service_name);
                    td.appendChild(tdText);
                    tableRow.appendChild(td);

                    // Populate Fully Paid 1 == 'no' : 2 == 'yes'
                    let paidText = '';
                    if (row.fully_paid === '1') {
                        paidText = 'No';
                    } else {
                        paidText = 'Yes';
                    }
                    td = document.createElement('td');
                    tdText = document.createTextNode(paidText);
                    td.appendChild(tdText);
                    tableRow.appendChild(td);

                    // Populate Amount Due
                    td = document.createElement('td');
                    tdText = document.createTextNode(row.amount_due);
                    td.appendChild(tdText);
                    tableRow.appendChild(td);

                    // Populate Invoice Date
                    td = document.createElement('td');
                    tdText = document.createTextNode(row.invoice_due_date);
                    td.appendChild(tdText);
                    tableRow.appendChild(td);

                    tbody.appendChild(tableRow);
                }

                table.appendChild(tbody);
            }
        },
        error: function(jqXHR, textStatus, errorThrown){
            console.log(errorThrown);
        }
    });
}


// Send an ajax post call to show customerNotes table without reload
const refreshNotes = (uid) => {
    $.ajax({
        type : "POST", 
        url  : "php/operations.php",  
        data: { "noteCust": uid },
        success: function(res){  

            if (res) {

                // If the notes are displayed already, remove it from the document to start over
                if (document.contains(document.getElementById('note-container-to-delete'))) {
                    document.getElementById('note-container-to-delete').remove();
                    document.getElementById('note-title').remove();
                }

                // Get note container and display it
                const noteContainer = document.getElementById('note-container');
                noteContainer.style.display = 'inherit';

                // Create a heading for the notes container and display it
                const noteTitle = document.createElement('h3');
                const noteTitleText = document.createTextNode('Customer Notes');
                noteTitle.appendChild(noteTitleText);
                noteTitle.setAttribute('id', 'note-title');
                noteContainer.appendChild(noteTitle);

                // Create a div for the notes
                const innerNoteContainer = document.createElement('div');
                innerNoteContainer.setAttribute('id', 'note-container-to-delete');

                // Loop through notes response and display each note
                for (let row of JSON.parse(res)) {
                    let note = document.createElement("p");
                    let node = document.createTextNode(row.note);
                    note.appendChild(node);
                    note.classList.add('note-text');
                    innerNoteContainer.appendChild(note);
                }

                noteContainer.appendChild(innerNoteContainer);
            }
        }
    });
}

