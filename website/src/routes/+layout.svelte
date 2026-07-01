<script lang="ts">
	import { favicon } from '$lib';

	let { children } = $props();
</script>

<svelte:head>
	<link rel="icon" href={favicon} />
</svelte:head>

<div class="nav-bar">
	<div class="title">
		<h>Telemetry Dashboard</h>
	</div>

	<div class="menu">
		<label for="menu-checkbox">
			<svg
				width="60"
				height="60"
				viewBox="0 0 24 24"
				fill="none"
				stroke="currentColor"
				stroke-width="2"
				stroke-linecap="round"
				stroke-linejoin="round"
			>
				<line x1="3" y1="12" x2="21" y2="12"></line>
				<line x1="3" y1="6" x2="21" y2="6"></line>
				<line x1="3" y1="18" x2="21" y2="18"></line>
			</svg>
		</label>
	</div>
</div>

<div class="menu-dropdown">
	<input type="checkbox" id="menu-checkbox" />

	<div class="menu-button">
		<a href="/" class="menu-options">Home</a>
		<a href="/about" class="menu-options">About</a>
		<a href="/changes" class="menu-options" style="text-decoration: none !important;">Changelog</a>
	</div>
</div>

{@render children()}

<div class="copyright">
	<a href="https://github.com/Pecs1/ESP-Website">Copyright &copy; 2026 Pecs1 ~ Apache 2.0</a>
</div>
