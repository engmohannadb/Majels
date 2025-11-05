        <section>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="copyright">
                                <p>جميع الحقوق © 2025 محفوظة لنظام مجلس مفتوج المصدر.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END PAGE CONTAINER-->
       
   
   </div>

    </div>

    <!-- Jquery JS-->
    <script src="js/vanilla-utils.js"></script>
    <!-- Bootstrap JS-->
    <script src="vendor/bootstrap-5.3.8.bundle.min.js"></script>
    <!-- Vendor JS       -->
    <script src="vendor/perfect-scrollbar/perfect-scrollbar-1.5.6.min.js"></script>
    <script src="vendor/chartjs/chart.umd.js-4.5.0.min.js"></script>
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Main JS-->
    <script src="js/bootstrap5-init.js"></script>
    <script src="js/main-vanilla.js"></script>
    <script src="js/swiper-bundle-11.2.10.min.js"></script>
    <script src="js/aos.js"></script>
    <script src="js/modern-plugins.js"></script>

    <!-- Initialize Leaflet Map for index2.html only -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Only initialize if this is index2.html and the vmap container exists
        const mapContainer = document.getElementById('vmap');
        const isIndex2 = window.location.pathname.includes('index2.html') || document.title.includes('Dashboard 2');
        
        if (mapContainer && typeof L !== 'undefined' && isIndex2 && !mapContainer._leaflet_id) {
            try {
                // Initialize the map
                const map = L.map('vmap', {
                    center: [20, 0], // Center of the world
                    zoom: 2,
                    zoomControl: true,
                    scrollWheelZoom: false,
                    doubleClickZoom: true,
                    attributionControl: false
                });

                // Add tile layer (OpenStreetMap)
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '',
                    maxZoom: 10,
                    minZoom: 1
                }).addTo(map);

                // Sample data points for major cities/regions
                const worldData = [
                    { name: 'United States', lat: 39.8283, lng: -98.5795, value: 45000, color: '#00b5e9' },
                    { name: 'United Kingdom', lat: 55.3781, lng: -3.4360, value: 32000, color: '#4caf50' },
                    { name: 'Germany', lat: 51.1657, lng: 10.4515, value: 28000, color: '#ff9800' },
                    { name: 'Japan', lat: 36.2048, lng: 138.2529, value: 35000, color: '#e91e63' },
                    { name: 'Australia', lat: -25.2744, lng: 133.7751, value: 22000, color: '#9c27b0' },
                    { name: 'Brazil', lat: -14.2350, lng: -51.9253, value: 18000, color: '#f44336' },
                    { name: 'India', lat: 20.5937, lng: 78.9629, value: 25000, color: '#3f51b5' },
                    { name: 'China', lat: 35.8617, lng: 104.1954, value: 40000, color: '#00bcd4' }
                ];

                // Add markers for each data point
                worldData.forEach(point => {
                    const marker = L.circleMarker([point.lat, point.lng], {
                        radius: Math.sqrt(point.value / 1000) + 5, // Size based on value
                        fillColor: point.color,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.7
                    }).addTo(map);

                    // Add popup with country info
                    marker.bindPopup(`
                        <div style="text-align: center; padding: 5px;">
                            <strong>${point.name}</strong><br>
                            <span style="color: ${point.color}; font-weight: bold;">$${point.value.toLocaleString()}</span>
                        </div>
                    `);

                    // Hover effects
                    marker.on('mouseover', function() {
                        this.setStyle({
                            fillOpacity: 0.9,
                            weight: 3
                        });
                    });

                    marker.on('mouseout', function() {
                        this.setStyle({
                            fillOpacity: 0.7,
                            weight: 2
                        });
                    });
                });

                // Add custom controls
                const info = L.control({position: 'topright'});
                info.onAdd = function() {
                    const div = L.DomUtil.create('div', 'info');
                    div.innerHTML = '<div style="background: rgba(255,255,255,0.9); padding: 8px; border-radius: 4px; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">Global Revenue Distribution</div>';
                    return div;
                };
                info.addTo(map);

                console.log('Leaflet world map initialized successfully');
            } catch (error) {
                console.error('Error initializing world map:', error);
                // Fallback content
                mapContainer.innerHTML = `
                    <div style="height: 100%; display: flex; align-items: center; justify-content: center; background: #f8f9fa; color: #666; border-radius: 8px;">
                        <div style="text-align: center;">
                            <i class="fas fa-globe-americas" style="font-size: 32px; margin-bottom: 10px;"></i>
                            <div>Map loading failed</div>
                        </div>
                    </div>
                `;
            }
        }
    });
    </script>

</body>

</html>
<!-- end document-->
