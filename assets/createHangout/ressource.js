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
select.addEventListener("change",()=>{
    displayPlace(select.value)
})
