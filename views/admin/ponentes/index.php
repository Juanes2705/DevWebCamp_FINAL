<h2 class="dashboard__heading"><?php echo $titulo; ?></h2>

<div class="dashboard__contenedor-boton">
    <a class="dashboard__boton" href="/admin/ponentes/crear">
        <i class="fa-solid fa-circle-plus"></i>
        Añadir Ponente
    </a>
</div>

<div class="dashboard__contenedor">
    <table class="table">
        <thead class="table__thead">
            <tr>
                <th scope="col" class="table__th">Nombre</th>
                <th scope="col" class="table__th">Ubicación</th>
                <th scope="col" class="table__th"></th>
            </tr>
        </thead>

        <tbody class="table__tbody" id="tbody-ponentes">
            <tr><td colspan="3" class="text-center">Cargando...</td></tr>
        </tbody>
    </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", async () => {
    const tbody = document.getElementById("tbody-ponentes");
    const apiURL = "http://localhost:8080/api/ponenteAPI.php"; 

    try {
        const response = await fetch(apiURL);
        const ponentes = await response.json();

        if (!Array.isArray(ponentes) || ponentes.length === 0) {
            tbody.innerHTML = `<tr><td colspan="3" class="text-center">No hay ponentes aún</td></tr>`;
            return;
        }

        tbody.innerHTML = "";

        ponentes.forEach(p => {
            const row = document.createElement("tr");
            row.classList.add("table__tr");

            row.innerHTML = `
                <td class="table__td">${p.nombre} ${p.apellido}</td>
                <td class="table__td">${p.ciudad}, ${p.pais}</td>
                <td class="table__td--acciones">
                    <a class="table__accion table__accion--editar" href="/admin/ponentes/editar?id=${p.id}">
                        <i class="fa-solid fa-user-pen"></i> Editar
                    </a>
                    <form method="POST" action="/admin/ponentes/eliminar" class="table__formulario">
                        <input type="hidden" name="id" value="${p.id}">
                        <button class="table__accion table__accion--eliminar" type="submit">
                            <i class="fa-solid fa-circle-xmark"></i> Eliminar
                        </button>
                    </form>
                </td>
            `;

            tbody.appendChild(row);
        });
    } catch (error) {
    console.error("Error al cargar ponentes:", error);
    const tbody = document.getElementById("tbody-ponentes");
    tbody.innerHTML = `<tr><td colspan="3" class="text-center text-red">Error: ${error.message}</td></tr>`;
}
    const deleteForms = document.querySelectorAll(".table__formulario");
    deleteForms.forEach(form => {
        form.addEventListener("submit", (e) => {
            const confirmDelete = confirm("¿Estás seguro de que deseas eliminar este ponente?");
            if (!confirmDelete) {
                e.preventDefault();
            }
        });
    });
});
</script>
<?php 
    echo $paginacion;
?>
