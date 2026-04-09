<script>
	import { onMount } from 'svelte';
	import { initialView } from '$lib/telemetryStore.js';

	// center of the EU
	const fallback = [49.843, 9.902];
	let view = fallback;

	onMount(() => {
		console.log('Attempting geolocation...');

		if ('geolocation' in navigator) {
			navigator.geolocation.getCurrentPosition(
				(position) => {
					view = [position.coords.latitude, position.coords.longitude];
					console.log('Success:', view);
				},
				(error) => {
					console.warn(`OS/Hardware rejected geo: ${error.message}`);
					view = fallback;
				},
				{ timeout: 3000 }
			);
		} else {
			view = fallback;
		}

		initialView.set(view);
	});
</script>
