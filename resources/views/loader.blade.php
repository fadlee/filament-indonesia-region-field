<script src="https://unpkg.com/fuzzysort@3.1.0/fuzzysort.min.js"></script>
<script src="https://unpkg.com/localforage@1.10.0/dist/localforage.min.js"></script>

<script>
const indonesiaRegionStore = localforage.createInstance({
    name: 'indonesia-region-field',
    storeName: 'data'
});

const initRegionField = async () => {
    try {
        // Try to get data from storage
        let villagesData = await indonesiaRegionStore.getItem('villages');

        if (!villagesData) {
            // If no data in storage, fetch from server
            const response = await fetch('{{ route('indonesia-region-field.data', ['type' => 'villages']) }}');
            villagesData = await response.text();

            // Store in localForage
            await indonesiaRegionStore.setItem('villages', villagesData);
        }

        window.villages = villagesData.split('\n');
        window.dispatchEvent(new Event('villages-data-ready'));
    } catch (error) {
        console.error('Error loading villages data:', error);
        // Fallback to direct fetch if storage fails
        const response = await fetch('{{ route('indonesia-region-field.data', ['type' => 'villages']) }}');
        const text = await response.text();
        window.villages = text.split('\n');
        window.dispatchEvent(new Event('villages-data-ready'));
    }
}

// Initialize when document is ready
document.addEventListener('DOMContentLoaded', initRegionField);

window.getVillageName = async (value) => {
    // If villages data is not loaded yet, wait for it
    if (!window.villages) {
        await new Promise(resolve => {
            window.addEventListener('villages-data-ready', resolve, { once: true });
        });
    }

    const results = fuzzysort.go(value + ';', window.villages, {limit: 1, treshold: 0.5})
    return results[0]?.target.split(';')[1] ?? value;
}

window.searchVillage = async (search) => {
    // If villages data is not loaded yet, wait for it
    if (!window.villages) {
        await new Promise(resolve => {
            window.addEventListener('villages-data-ready', resolve, { once: true });
        });
    }

    const results = fuzzysort.go(search, window.villages, {limit: 10, treshold: 1})
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
