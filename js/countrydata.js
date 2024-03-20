var config = {
    cUrl: 'https://api.countrystatecity.in/v1/countries',
    ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
};

var countrySelect = document.querySelector('.country'),
    stateSelect = document.querySelector('.state');

    function loadCountries() {
        let apiEndPoint = config.cUrl;
    
        fetch(apiEndPoint, { headers: { "X-CSCAPI-KEY": config.ckey } })
            .then(response => response.json())
            .then(data => {
                // Sort the countries alphabetically by name
                data.sort((a, b) => a.name.localeCompare(b.name));
    
                // Clear existing options
                countrySelect.innerHTML = '';
    
                // Add the default option
                const defaultOption = document.createElement('option');
                defaultOption.value = '';
                defaultOption.textContent = 'Select Country';
                countrySelect.appendChild(defaultOption);
    
                // Append options to the country select dropdown
                data.forEach(country => {
                    const option = document.createElement('option');
                    option.value = country.iso2;
                    option.textContent = country.name;
                    countrySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading countries:', error));
    
        // Disable and reset state select
        stateSelect.disabled = true;
        stateSelect.innerHTML = '<option value="">Select State</option>'; // Clear existing state options
    }
    

function loadStates() {
    stateSelect.disabled = false;
    stateSelect.style.pointerEvents = 'auto';

    const selectedCountryCode = countrySelect.value;
    stateSelect.innerHTML = '<option value="">Select State</option>'; // Clear existing state options

    fetch(`${config.cUrl}/${selectedCountryCode}/states`, { headers: { "X-CSCAPI-KEY": config.ckey } })
        .then(response => response.json())
        .then(data => {
            // Sort the states alphabetically by name
            data.sort((a, b) => a.name.localeCompare(b.name));

            data.forEach(state => {
                const option = document.createElement('option');
                option.value = state.iso2;
                option.textContent = state.name;
                stateSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error loading states:', error));
}

window.onload = function() {
    loadCountries();
    countrySelect.addEventListener('change', loadStates);
};