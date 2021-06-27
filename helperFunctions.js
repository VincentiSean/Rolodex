// Changes a DOM element's value
const editElement = (elemId, value) => {
    document.getElementById(elemId).value = value;
};

// Returns a DOM element's value
const getElemValue = (elemId) => {
    return document.getElementById(elemId).value;
};