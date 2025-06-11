<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__grafica">
    <canvas id="regalos-grafica" width="400" height="400"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', async () => {
        const ctx = document.getElementById('regalos-grafica').getContext('2d');

        try {
            const response = await fetch('http://localhost:8080/api/regalosAPI.php');
            const data = await response.json();

            const nombres = data.map(regalo => regalo.nombre);
            const cantidades = data.map(regalo => regalo.cantidad);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombres,
                    datasets: [{
                        label: 'Regalos Seleccionados',
                        data: cantidades,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } catch (error) {
            console.error('Error al cargar los datos de regalos:', error);
        }
    });
</script>