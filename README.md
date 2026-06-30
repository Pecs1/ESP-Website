# ESP-Website

Project to get telemetry from a [ESP32 boards](https://github.com/Pecs1/ESP-telemetry#) for [Shell eco-marathon](https://www.shellecomarathon.com/) competetion

> [!Important]
> I'll be slowly but surely doing a complete rewrite, so some things will be broken for some time ^^


## File Structure

Both local and remote directories live alongside already build website (that can be build manually or downloaded via github releases)

- [local](https://github.com/Pecs1/ESP-Website/local/README.md)
  - stores components that help decode the data and put them in a local database
  - can be configured if you would want to send data to a remote website 
- [remote](https://github.com/Pecs1/ESP-Website/remote/README.md)
  - accepts data via POST request from local
  - stores components that helps for this website be browsable to the public internet
- [website](https://github.com/Pecs1/ESP-Website/website/README.md)
  - stores website related components

## License

   Copyright © 2026 Pecs1

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at

       http://www.apache.org/licenses/LICENSE-2.0

   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License.
