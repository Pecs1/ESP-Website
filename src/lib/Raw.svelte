<script lang="ts">
	import { onMount } from 'svelte';

	export let latitude;
	export let longitude;
	export let speed;
	export let altitude;
	export let usedSats;
	export let accuracy;
	export let time;
	export let isActive;

	let telemetryBuffer = [];
	let currentIndex = 0;

	async function refreshBuffer() {
		// Adding ?t=[timestamp] ensures the browser asks the server for a fresh copy
		const res = await fetch(`/data.json?t=${Date.now()}`);
		const newData = await res.json();

		// Simple check: only reset if the data is actually different or exists
		if (newData && newData.length > 0) {
			telemetryBuffer = newData;
			currentIndex = 0;
			updateUI();
		}
	}

	function updateUI() {
		if (currentIndex >= telemetryBuffer.length) {
			console.log('Waiting for fresh data batch...');
			return; // Stop the function here so we don't crash
		}

		if (telemetryBuffer.length > 0 && currentIndex < telemetryBuffer.length) {
			const row = telemetryBuffer[currentIndex];

			latitude = row.lat;
			longitude = row.lng;
			speed = row.vel;
			altitude = row.alt;
			usedSats = row.usat;
			accuracy = row.accu;
			time = row.time;
			isActive = row.active;

			currentIndex++;
		}
	}

	onMount(() => {
		refreshBuffer(); // Get the first batch immediately

		// Timer 1: Every 60s, get a fresh batch from the server
		const batchInterval = setInterval(refreshBuffer, 60000);

		// Timer 2: Every 10s, move to the next row in the current batch
		const playInterval = setInterval(updateUI, 10000);

		return () => {
			clearInterval(batchInterval);
			clearInterval(playInterval);
		};
	});
</script>
