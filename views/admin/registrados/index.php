<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>


<div class="dashboard__contenedor">
    <?php if(!empty($registros)) { ?>
        <table class="table">
            <thead class="table__thead">
                <tr>
                    <th scope="col" class="table__th">Nombre</th>
                    <th scope="col" class="table__th">Email</th>
                    <th scope="col" class="table__th">Plan</th>
                </tr>
            </thead>

            <tbody class="table__tbody">
                <?php foreach($registros as $registro) { ?>
                    <tr class="table__tr">
                    <tr><td colspan="3" class="text-center">Cargando...</td></tr>    
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    <?php } else { ?>
        <p class="text-center">No Hay Registros Aún</p>
    <?php } ?>
</div>

<?php 
    echo $paginacion;
?>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.querySelector(".table__tbody");
    const apiURL = "http://localhost:8080/api/registradosAPI.php";

    try {
        const response = await fetch(apiURL);
        const registrados = await response.json();

        if (!Array.isArray(registrados) || registrados.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center">No hay registros aún</td></tr>`;
            return;
        }

        tbody.innerHTML = "";

        registrados.forEach(registro => {
            const row = document.createElement("tr");
            row.classList.add("table__tr");

            row.innerHTML = `
                <td class="table__td">${registro.nombre} ${registro.apellido}</td>
                <td class="table__td">${registro.email}</td>
                <td class="table__td">${registro.paquete}</td>
            `;

            tbody.appendChild(row);
        });
    } catch (error) {
        console.error("Error al cargar registros:", error);
        tbody.innerHTML = `<tr><td colspan="3" class="text-center text-red">Error: ${error.message}</td></tr>`;
    }
});
</script>