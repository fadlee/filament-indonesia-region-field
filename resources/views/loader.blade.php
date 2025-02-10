<script src="https://unpkg.com/fuzzysort@3.1.0/fuzzysort.min.js"></script>
<script src="https://unpkg.com/localforage@1.10.0/dist/localforage.min.js"></script>

<script>
const indonesiaRegionStore = localforage.createInstance({
    name: 'indonesia-region-field',
    storeName: 'data'
});

const dataTypes = {
    1: 'provinces',
    2: 'cities',
    3: 'districts',
    4: 'villages'
};

const initRegionField = async () => {
    try {
        // Load data for all region types
        for (const [level, type] of Object.entries(dataTypes)) {
            // Try to get data from storage
            let data = await indonesiaRegionStore.getItem(type);

            if (!data) {
                // If no data in storage, fetch from server
                const response = await fetch(`{{ route('indonesia-region-field.data', ['type' => '/']) }}/${type}`);
                data = await response.text();

                // Store in localForage
                await indonesiaRegionStore.setItem(type, data);
            }

            window[type] = data.split('\n');
        }

        window.dispatchEvent(new Event('region-data-ready'));
    } catch (error) {
        console.error('Error loading region data:', error);
        // Fallback to direct fetch if storage fails
        for (const [level, type] of Object.entries(dataTypes)) {
            const response = await fetch(`{{ route('indonesia-region-field.data', ['type' => '/']) }}/${type}`);
            const text = await response.text();
            window[type] = text.split('\n');
        }
        window.dispatchEvent(new Event('region-data-ready'));
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', initRegionField);

window.getRegionName = async (value, level) => {
    const type = dataTypes[level];

    // If data is not loaded yet, wait for it
    if (!window[type]) {
        await new Promise(resolve => {
            window.addEventListener('region-data-ready', resolve, { once: true });
        });
    }

    const results = fuzzysort.go(value + ';', window[type], {limit: 1, threshold: 0.5})
    return results[0]?.target.split(';')[1] ?? value;
}

window.searchRegion = async (search, level) => {
    const type = dataTypes[level];

    // If data is not loaded yet, wait for it
    if (!window[type]) {
        await new Promise(resolve => {
            window.addEventListener('region-data-ready', resolve, { once: true });
        });
    }

    const results = fuzzysort.go(search, window[type], {limit: 10, threshold: 0.5})
    const options = [];
    results.forEach((result) => {
        const [value, label] = result.target.split(';');
        options.push({
            value,
            label,
        });
    });
    return options;
}
</script>
