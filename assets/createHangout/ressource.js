const id = 1

const fetchPlace =async(id)=>{
    try {
        const response = await fetch(`${window.location.origin}/api/places/${id}`)
        if (!response.ok) return
        return await response.json()
    }catch (e){
        console.log(e)
    }
}
console.log(window.location.origin)
const displayPlace =async (id)=>{
    const {street,latitude,longitude,city} = await fetchPlace(id)
    const {name,postcode} = city
    const cityNameDiv = document.getElementById('create-form-city')
    const streetDiv = document.getElementById('create-form-street')
    const postcodeDiv = document.getElementById('create-form-postcode')
    const latitudeDiv = document.getElementById('create-form-latitude')
    const longitudeDiv = document.getElementById('create-form-longitude')
    cityNameDiv.textContent = name
    streetDiv.textContent = street
    postcodeDiv.textContent = postcode
    latitudeDiv.textContent = latitude
    longitudeDiv.textContent = longitude
}

const select = document.getElementById('create_hangout_place')
displayPlace(select.value);
select.addEventListener("change",()=>{
    displayPlace(select.value)
})

const cancelButton = document.querySelector('.cancel-hangout');
cancelButton.addEventListener('click', () => {
    if(window.confirm('cancel hangout creation ?')){
        window.location.href = `${window.location.origin}/hangout/list`
    }
})

const placeButton = document.querySelector('.add-place-button');
const createPlaceForm = document.querySelectorAll('.place-form');
const autofillFields = document.querySelector('.autofill-site-fields');
const publishButton = document.querySelector('.publish-button');
const saveButton = document.querySelector('.save-button');
const placeRow = document.querySelector('.hangout-place-row');

createPlaceForm.forEach(c => c.style.display = 'none');
placeButton.addEventListener('click', event => {
    placeRow.style.display = placeRow.style.display === 'none'?'flex':'none';
    createPlaceForm.forEach(c=>c.style.display = c.style.display==='none' ? 'flex' : 'none');
    autofillFields.style.display = autofillFields.style.display==='none'?'flex':'none';
    [publishButton, saveButton].forEach(b=>b.disabled=b.disabled !== true);
    placeButton.textContent = placeButton.textContent === 'Add Place...'?'Cancel Place creation':'Add Place...';
})
