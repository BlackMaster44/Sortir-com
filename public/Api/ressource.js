const id = 1

const fetchPlace =async(id)=>{
    try {
        const response = await fetch(`http://127.0.0.1:8000/api/places/${id}`)
        if (!response.ok) return
        const placeData = await response.json()
        return placeData

    }catch (e){
        console.log(e)
    }
}

const displayPlace =(id)=>{

    const {street,latitude,longitude,city} = fetchPlace(id)
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
displayPlace(1)