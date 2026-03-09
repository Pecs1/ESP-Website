<script lang="ts">
	import { onMount, tick } from 'svelte';

	export let latitude = 0;
	export let longitude = 0;
	export let speed = 0;
	export let altitude = 0;
	export let usedSats = 0;
	export let accuracy = 0;
	export let time = '00:00:00';
	export let isActive = 0;

	let telemetryBuffer = [];
	let currentIndex = 0;
	let lastBatchLatestTime = null;

	async function refreshBuffer() {
		// Cache busting fetch to ensure fresh data
		const res = await fetch(`/data.json?t=${Date.now()}`);
		const newData = await res.json();

		if (newData && newData.length > 0) {
			const latestTimeInNewData = newData[newData.length - 1].time;

			// Only process if the batch is new
			if (latestTimeInNewData !== lastBatchLatestTime) {
				telemetryBuffer = newData;
				lastBatchLatestTime = latestTimeInNewData;

				// Look for the "bookmark" in the newly fetched data
				const savedTime = localStorage.getItem('last_telemetry_time');
				if (savedTime) {
					const foundIndex = newData.findIndex((row) => row.time === savedTime);
					// Start at the next record after the one we last saw
					currentIndex = foundIndex !== -1 ? foundIndex + 1 : 0;
				} else {
					currentIndex = 0;
				}

				if (currentIndex < telemetryBuffer.length) {
					isActive = 1;
					await tick();
					updateUI();
				} else {
					isActive = 0;
				}
			}
		}
	}

	function updateUI() {
		// Check if we are out of data first
		if (currentIndex >= telemetryBuffer.length) {
			isActive = 0; // Explicitly set to inactive
			console.log('Waiting for fresh data batch...');
			return;
		}

		isActive = 1;
		const row = telemetryBuffer[currentIndex];

		// Update the component's exported props
		latitude = row.lat;
		longitude = row.lng;
		speed = row.vel;
		altitude = row.alt;
		usedSats = row.usat;
		accuracy = row.accu;
		if (row.time) {
			// format it directly to avoid undeclared variable errors
			time = row.time
				.split(':')
				.map((unit) => unit.padStart(2, '0'))
				.join(':');
		}

		// Save the timestamp of this row as the new bookmark
		localStorage.setItem('last_telemetry_time', row.time);

		currentIndex++;
	}

	onMount(() => {
		refreshBuffer();

		// Check for new server data every minute
		const batchInterval = setInterval(refreshBuffer, 60000);

		// Advance the UI every 10 seconds
		const playInterval = setInterval(updateUI, 10000);

		return () => {
			clearInterval(batchInterval);
			clearInterval(playInterval);
		};
	});
</script>
