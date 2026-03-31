<script>
	/*
    This is an example of using Renderless components to integrate Svelte with Leaflet. Original blog post here: https://imfeld.dev/writing/domless_svelte_component
		
	For comparison, the original REPL that implemented this without Renderless components is here: https://svelte.dev/repl/62271e8fda854e828f26d75625286bc3?version=3.29.7
	
	You can also find a full application implementing these techniques at https://github.com/dimfeld/svelte-leaflet-demo
	*/

	import Leaflet from './Leaflet.svelte';
	import Marker from './Marker.svelte';
	import Polyline from './Polyline.svelte';
	import { scaleSequential } from 'd3-scale';
	import { interpolateRainbow } from 'd3-scale-chromatic';

	import pointerIcon from '$lib/assets/pointer.svg';

	let map;

	const markerLocations = [
		[29.8283, -96.5795],
		[37.8283, -90.5795],
		[43.8283, -102.5795],
		[48.4, -122.5795],
		[43.6, -79.5795],
		[36.8283, -100.5795],
		[38.4, -122.5795]
	];

	const colors = scaleSequential(interpolateRainbow).domain([0, markerLocations.length - 1]);
	const lines = markerLocations.slice(1).map((latLng, i) => {
		let prev = markerLocations[i];
		return {
			latLngs: [prev, latLng],
			color: colors(i)
		};
	});

	export let initialView;

	let eye = true;
	let showLines = true;

	function resizeMap() {
		if (map) {
			map.invalidateSize();
		}
	}

	$: if (map && initialView) {
		// This forces Leaflet to fly to the new coordinates
		map.setView(initialView, map.getZoom());
	}
</script>

<svelte:window on:resize={resizeMap} />

<!-- Can just use an import statement for this, when outside the REPL -->
<link
	rel="stylesheet"
	href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css"
	integrity="sha512-xwE/Az9zrjBIphAcBb3F6JVqxf46+CDLwfLMHloNu6KEQCAWi6HcDUbeOfBIptF7tcCzusKFjFw2yuvEpDL9wQ=="
	crossorigin=""
/>

<Leaflet bind:map view={initialView} zoom={10}>
	{#if eye}
		{#each markerLocations as latLng}
			<Marker {latLng} width={30} height={30}>
				<svg viewBox="0 0 24 24" style="width:30px; height:30px">
					<image href={pointerIcon} width="24" height="24" />
				</svg>
			</Marker>
		{/each}
	{/if}

	{#if showLines}
		{#each lines as { latLngs, color }}
			<Polyline {latLngs} {color} opacity={0.5} />
		{/each}
	{/if}
</Leaflet>
