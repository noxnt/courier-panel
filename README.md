# Courier Admin Panel

## Project Overview
The Courier Admin Panel is a lightweight management system that allows full CRUD operations for couriers, real-time tracking on a map, and simulation of requests from real courier devices.

Key features include:
- **Courier Management:** Create, read, update, and delete courier records via the admin interface.
- **Real-Time Location Tracking**: Courier positions are updated on the map using WebSockets.
- **Request Simulation**: A cron-based emulator runs every few seconds (default: 5s) and sends POST requests to the backend, simulating real devices activity.
- **Caching & Broadcast**: Incoming location data is temporarily stored in Redis. Each new location triggers an event, whose listener runs as a queued task and broadcasts the location to clients in real time via WebSockets, retrieving the data from Redis cache.
- **Database Storage**: A scheduled task runs at a dynamic frequency slightly higher than the cache TTL. During execution, all location points except the most recent one are transferred from the cache to the database. This ensures long-term storage of courier location history while maintaining high performance (write-back cache).
- **Configurable Behavior**: Cache TTL, request rate limits per IP, and cron frequency for database writes are fully adjustable via environment configuration.
- **Extensibility**: Location history is stored in a dedicated table, enabling future analytics and historical tracking features *(not used in this version)*.

**Note**: Authentication and authorization have been omitted, as they were not part of the test assignment requirements. For this reason only, a public WebSocket channel is used. In a production environment, in such cases, WebSocket communication should be implemented using private channels.


---
## Technologies Used
### Backend
- **PHP (PHP-FPM) 8.3** — core language
- **Laravel 12** — backend framework
- **PostgreSQL** + PostGIS — relational database with geospatial support, ideal for handling courier coordinates and OLTP workloads
- **Redis** — used for caching (write-back cache) and queue management for real-time WebSocket broadcasting

### WebSockets
- **Pusher** — real-time messaging service for broadcasting WebSocket events.

### Key Laravel Packages
- `clickbar/laravel-magellan` — geospatial calculations and coordinate handling
- `predis/predis` — Redis client for Laravel
- `pusher/pusher-php-server` — broadcasting events to WebSockets

### Web Server & Infrastructure
- **Nginx** — web server
- **Docker** — containerized environment for easy deployment

### Frontend / JS Libraries
- **JavaScript** — for map and UI interactivity
- **Leaflet + MarkerCluster** — map rendering and clustering of courier markers


---
## Installation & Setup
### Technical Requirements
- Docker Engine ≥ 27.x
- Docker Compose ≥ v2.31

### Steps
1. Clone the repository
```bash
git clone https://github.com/noxnt/courier-panel.git
cd courier-panel
```
2. Set up environment configuration
   
  *Copy or configure the provided .env file for your environment.*

3. Build Docker containers
```bash
docker compose build
```
4. Start the containers
```bash
docker compose up -d
```
5. Run initialization script (recommended)
```bash
docker compose exec php ./dev-init.sh
```
  A helper script is provided to run all required setup commands. **Done!** 
  
  After completion, open the application at: [http://localhost:8080/admin-panel](http://localhost:8080/admin-panel)
  
**OR perform the steps manually:**
  
6. Install dependencies via Composer
```bash
docker compose exec php composer install
```
7. Run database migrations
```bash
docker compose exec php artisan migrate
```
8. Run the main seeder to initialize system settings
```bash
docker compose exec php artisan db:seed --class=DatabaseSeeder
```
9. (Optional) Seed sample couriers (creates 5 couriers)
```bash
docker compose exec php artisan db:seed --class=CourierSeeder
```
10. Clear and refresh cache, config, and routes
```bash
docker compose exec php php artisan config:clear
docker compose exec php php artisan cache:clear
docker compose exec php php artisan route:clear
```
  Open the application at: [http://localhost:8080/admin-panel](http://localhost:8080/admin-panel)


---
## Postman Collection
[Download](https://drive.google.com/file/d/1RvyfPcwwYafsWnXHAGuet-4_cKgZnWCB/view?usp=sharing)
