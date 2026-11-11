document.addEventListener('DOMContentLoaded', function () {
    const countryInput = document.getElementById('country');
    const resultDiv = document.getElementById('result');
    const lookupBtn = document.getElementById('lookup');
    const citiesBtn = document.getElementById('lookup-cities');

    function fetchData(lookupType) {
        const country = countryInput.value.trim();
        let url = 'world.php?country=' + encodeURIComponent(country);

        if (lookupType === 'cities') {
            url += '&lookup=cities';
        }

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Request failed with status ' + response.status);
                }
                return response.text();
            })
            .then(html => {
                resultDiv.innerHTML = html;
            })
            .catch(error => {
                resultDiv.textContent = 'Error: ' + error.message;
            });
    }

    lookupBtn.addEventListener('click', function () {
        fetchData('country');
    });

    citiesBtn.addEventListener('click', function () {
        fetchData('cities');
    });
});
