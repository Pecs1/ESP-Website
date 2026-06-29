<script lang="ts">
	import { favicon } from '$lib';

	let { children } = $props();
</script>

<svelte:head>
	<link rel="icon" href={favicon} />
</svelte:head>

{@render children()}

<div class="dashboard-root">
     <div class="nav-bar">
          <div class="title">
               <h>Telemetry Dashboard</h>
          </div>
          <div class="menu">
               <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
               </svg>
          </div>
     </div>
</div>
