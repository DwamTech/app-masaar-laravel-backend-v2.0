# Property Endpoints
This file documents all the API endpoints related to properties.
## Property Routes
- **Create Property:** `POST /api/properties`
- **Update Property:** `PUT /api/properties/{id}`
- **Delete Property:** `DELETE /api/properties/{id}`
- **Update The Best Property:** `POST /api/properties/updateTheBest`

- **Admin Index:** `GET /api/admin/properties`
- **Admin Delete Property:** `DELETE /api/admin/properties/{id}`
- **Toggle Featured Property:** `PATCH /api/admin/properties/{id}/feature`

- **Get Properties:** `GET /api/properties`
- **Search Properties:** `GET /api/properties/search`
- **Get Featured Properties:** `GET /api/properties/featured`
- **Get Property by ID:** `GET /api/properties/{id}`