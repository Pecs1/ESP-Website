# ESP-Website

Project to get telemetry from a [ESP32 boards](https://github.com/Pecs1/ESP-telemetry#) for [Shell eco-marathon](https://www.shellecomarathon.com/) competetion

## Installing

```sh
git clone https://github.com/Pecs1/ESP-Website.git
cd ESP-Website

# Install Dependencies
pnpm i

# Start dev server 
pnpm run dev
# Or start the server and open in new browser tab
pnpm run dev --open


# Build for production version of the website
pnpm run build
# You will find it in /build directory
```

## Credits

- Icons are provided by [Flaticon](https://www.flaticon.com)
  - `favicon.svg`: [Remote sensing satelite](https://www.flaticon.com/free-icon/remote-sensing-satellite_14106352) icon made by **gravisio**
  - `favicon_old.svg`: [Remote sensing satelite](https://www.flaticon.com/free-icon/remote-sensing-satellite_14114645) icon made by **gravisio**
  - `pointer.svg`: [Placeholder](https://www.flaticon.com/free-icon/placeholder_684908) icon made by **Freepik**
- Map components (such as leaflet) i took from [Leaflet in Svelte with Renderless Components](https://svelte.dev/playground/36a84bbe2cf74c899ada6380e6e632d8?version=5.53.3)


