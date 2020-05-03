const name = document.getElementById('name');
const description = document.getElementById('description');
const price = document.getElementById('price');
const submit = document.getElementById('submit');

const validateName = name => {
    if (!name.value) return 'name is required';
    if (name.value.length < 3)  return 'name must contain at least 3 characters';
    return true;
}

const validateDescription = description => {
    if (!description.value) return 'description is required';
    if (description.value.length < 5)  return 'description must contain at least 5 characters';
    return true;
}

const validatePrice = price => {
    if (isNaN(price.value)) return 'price must be numeric';
    if (parseFloat(price.value) <= 0) return 'price must be above 0';
    return true;
}

submit.addEventListener('click', e => {
    if (validateName(name) !== true) {
        e.preventDefault();
        const nameError = document.getElementById('nameError') || document.createElement('span');
        nameError.setAttribute('class', 'error');
        nameError.setAttribute('id', 'nameError');
        nameError.textContent = validateName(name);
        name.parentNode.appendChild(nameError);
    }
    if (validateDescription(description) !== true) {
        e.preventDefault();
        const descError = document.getElementById('descriptionError') || document.createElement('span');
        descError.setAttribute('class', 'error');
        descError.setAttribute('id', 'descriptionError');
        descError.textContent = validateDescription(description);
        description.parentNode.appendChild(descError);
    }
    if (validatePrice(price) !== true) {
        e.preventDefault();
        const priceError = document.getElementById('priceError') || document.createElement('span');
        priceError.setAttribute('class', 'error');
        priceError.setAttribute('id', 'priceError');
        priceError.textContent = validatePrice(price);
        price.parentNode.appendChild(priceError);
    }
});
