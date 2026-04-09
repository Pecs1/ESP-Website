import { writable } from 'svelte/store';

// This holds the single "active" point currently being displayed
export const currentPoint = writable({
    lat: 0,
    lng: 0,
    vel: 0,
    alt: 0,
    accu: 0,
    usat: 0,
    esp_time: '00:00:00',
    extra_data: {},
    server_time: '00:00:00'
});

// This holds the history of points for your Leaflet trail
export const pathHistory = writable([]);
export const isActive = writable(false);
export const initialView = writable([0, 0]);
