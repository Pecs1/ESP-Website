<script>
    import { onMount } from 'svelte';
    import { currentPoint, pathHistory, isActive } from '$lib/telemetryStore.js';

    let playbackQueue = [];
    let lastId = 0;

    async function fetchNewData() {
        try {
            const res = await fetch(`/api.php?mode=buffer&last_id=${lastId}`);
            const data = await res.json();
            if (data.length > 0) {
                lastId = data[data.length - 1].id;
                playbackQueue = [...playbackQueue, ...data];
            }
        } catch (e) {
            console.error("Fetch error:", e);
            isActive.set(false); // Set inactive on network failure
        }
    }

    function moveNext() {
        if (playbackQueue.length > 0) {
            const next = playbackQueue.shift();
            
            // Broadcast the data
            currentPoint.set(next);
            pathHistory.update(h => [...h, [next.lat, next.lng]]);
            
            // Mark as active since we just processed a point
            isActive.set(true);
        } else {
            // If the buffer is empty, we are no longer "actively" moving
            isActive.set(false);
        }
    }

	onMount(() => {
		// Create an internal async function so onMount stays synchronous
		const initSync = async () => {
			try {
				const res = await fetch('/api.php?mode=log');
				const existingData = await res.json();
				if (existingData.length > 0) {
					// Set the lastId to the highest ID currently in the DB
					const latestEntry = existingData[existingData.length - 1];
					lastId = latestEntry.id || 0; 
				}
			} catch (e) {
				console.error("Initial sync failed:", e);
			}
		};

		initSync(); // Run the initial jump to the latest ID

		const fetchTimer = setInterval(fetchNewData, 60000);
		const moveTimer = setInterval(moveNext, 10000);
		
		return () => {
			clearInterval(fetchTimer);
			clearInterval(moveTimer);
		};
	});
</script>
