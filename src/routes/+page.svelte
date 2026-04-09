<script lang="ts">
	import Map from '$lib/Map.svelte';
	import Geolocation from '$lib/Geolocation.svelte';
	import Raw from '$lib/Raw.svelte';
	import { currentPoint, isActive } from '$lib/telemetryStore.js';
</script>

<Geolocation />
<Raw />

<div class="dashboard-root">
	<div class="nav-bar">
		<div class="main-title">
			<h1>Telemetry Dashboard</h1>
			<span class="disclaimer">Data is fetched automatically every minute</span>
		</div>
		<div class="status">
			{$isActive ? 'Active' : 'Inactive'}
			<!-- <p class="pulse-dot"></p> -->
		</div>
	</div>
	<div class="data-container">
		<div class="data-map">
			<Map />
		</div>
		<div class="general-data">
			<div class="data-display">
				<h1><strong>Telemetry Data</strong></h1>
				<p><strong>Latitude:</strong> {$currentPoint.lat}</p>
				<p><strong>Longitude:</strong> {$currentPoint.lng}</p>
				<p><strong>Altitude:</strong> {$currentPoint.alt}m</p>
				<p><strong>Speed:</strong> {$currentPoint.vel}km/h</p>
				<p><strong>Accuracy:</strong> {$currentPoint.accu}m</p>
				<p><strong>Used Satellites:</strong> {$currentPoint.usat}</p>
				<p><strong>GPS Polled at:</strong> {$currentPoint.esp_time} UTC</p>
				<p><strong>Received on Server:</strong> {$currentPoint.server_time} UTC</p>
			</div>
			<div class="data-logs">
				<h1><strong><centre>Telemetry log</centre></strong></h1>
				<!-- some .js thingy to store the logs -->
				<p>ur ur ur</p>
			</div>
		</div>
	</div>
</div>

<style>
	:global(body) {
		margin: 0;
		background-color: #020617;
		color: #f8fafc;
		font-family: 'Segoe UI', Roboto, sans-serif;
		overflow: hidden;
		cursor: default;
		user-select: none;
	}

	.dashboard-root {
		display: flex;
		flex-direction: column;
		height: 100vh;
		padding: 1rem;
		box-sizing: border-box;
	}

	.nav-bar {
		background-color: #1e293b;
		padding: 1rem;
		border-radius: 0.5rem;
		margin-bottom: 1rem;
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.main-title {
		background-color: #242f40;
		color: white;
		border: none;
		font-size: 1.2rem;
		padding: 0.5rem 1rem;
		border-radius: 0.5rem;
		cursor: pointer;
		position: relative;
		display: inline-flex;
		align-items: center;
	}

	.main-title:hover {
		background-color: #3b4d68;
	}

	.main-title:hover .disclaimer {
		max-width: 400px;
		opacity: 1;
		margin-left: 10px;
	}

	.disclaimer {
		max-width: 0;
		opacity: 0;
		font-size: 1rem;
		overflow: hidden;
		white-space: nowrap;
		transition: all 0.4s ease;
		margin-left: 0;
		color: aliceblue;
	}

	.data-container {
		display: grid;
		grid-template-columns: 2fr 0.75fr;
		gap: 1.25rem;
		flex-grow: 1;
		margin-top: 1rem;
	}

	.general-data {
		display: grid;
		grid-template-rows: 0.5fr 1fr;
	}

	.data-display {
		background-color: #2d3a4b;
		border-radius: 0.5rem;
		padding: 2rem 0.75rem;
		width: 100%;
		height: 100%;
	}

	.data-display h1 {
		margin-top: 0;
		margin-bottom: 1rem;
		color: #f8fafc;
		font-size: 20px;
		text-align: center;
	}

	.data-display p {
		margin: 0.25rem 0;
		font-size: 1.1rem;
	}

	.status {
		background-color: #2d3a4b;
		color: white;
		border: none;
		padding: 0.5rem 1rem;
		border-radius: 0.5rem;
	}

	/* .data-map {	// update configs in file://./../lib/Leaflet.svelte#57 at line 57
		background-color: #2d3a4b;
		border-radius: 0.5rem;
		max-width: 100%;
		max-height: 100%;
	} */

	.data-container :focus {
		outline: none;
	}

	.data-logs {
		background-color: #2d3a4b;
		border-radius: 0.5rem;
		padding: 2rem 0.75rem;
		height: 96.5%;
		width: 100%;
		margin-top: 3.5%;
	}

	.data-logs h1 {
		margin-top: 0;
		margin-bottom: 1rem;
		color: #f8fafc;
		font-size: 20px;
		text-align: center;
	}

	.data-logs p {
		margin: 0.25rem 0;
		font-size: 1.1rem;
	}
</style>
